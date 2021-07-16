<?php

	// Establish connection to the database
	
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/db_connection.php");
	
	$roiRetrieval = new RoiRetrieval($db);
	if( $_GET['action'] == 'setupdashboard' ) {
		
		$user_dashboard = array();
		
		$structure_versions = $roiRetrieval->getStructureVersions();
		
		$version_levels = $roiRetrieval->getVersionLevels();
		
		$user_dashboard['rois'] = $roiRetrieval->getUserCreatedRois();
		// $user_dashboard['tags'] = array_filter($tags);

		$user_templates = $roiRetrieval->getTemplatesAvailable();
		$template_count = 0;
		foreach($user_templates as $template){
			$roi_link = array_keys(array_column($version_levels,'version_level_id'), $template['ep_version_level']);
			$user_templates[$template_count]['template_path'] = $version_levels[$roi_link[0]]['version_path'];
			$template_count++;
		}
		
		$user_dashboard['permissions'] = $roiRetrieval->retrieveCompanyPermissions();
		$user_dashboard['templates'] = $user_templates;
		$user_dashboard['userinfo'] = $roiRetrieval->getUserInfo();
		$user_dashboard['companyusers'] = $roiRetrieval->getAllCompanyUsers();
		
		echo json_encode($user_dashboard);
	}

	if( $_GET['action'] == 'userCreatedRois' ) {

		echo json_encode($roiRetrieval->getUserCreatedRois());
	}
	
	class RoiRetrieval {
		
		private $_db;
		
		public function __construct($db=NULL) {
			
			if(is_object($db)) {
				$this->_db = $db;
			} else {
				$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
				$this->_db = new PDO($dsn, DB_USER, DB_PASS);
			}
		}

		public function retrieveCompanyPermissions(){

			$sql = "SELECT * FROM roi_companies ORDER BY company_name ASC";

			$stmt = $this->_db->prepare($sql);
			$stmt->execute();
			$companies = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			$permissions = $this->retrieveUserPermissions();
			$rs_permissions = 0;
			foreach($permissions as $permission){
				$rs_permission = max($permission['rs_access'], $rs_permission);
			}
			
			$count = 0;
			foreach($companies as $company){
				$key = array_keys(array_column($permissions, 'company_id'), $company['company_id']);
				$permission = 0;
				if($key){
					$permission = $permissions[$key[0]]['company_access'];
				}
				$companies[$count]['permission'] = max($permission, $rs_permission);
				$count++;
			}

			return $companies;
		}

		public function retrieveUserPermissions(){

			$sql = "SELECT * FROM roi_user_permissions
					WHERE user_id = :user_id;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':user_id', $_SESSION['UserId'], PDO::PARAM_INT);
			$stmt->execute();
			$permissions = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $permissions;			
		}

		public function getTemplatesAvailable() {
			
			$sql = "SELECT * FROM roi_structure_versions WHERE version_stage = 1 AND version_id IN (
						SELECT structure_id FROM roi_user_companies WHERE user_id = :user AND create_active_rois = 1
					) OR version_stage = 1 AND structure_id IN (
						SELECT structure_id FROM roi_company_structures WHERE company_id IN (
							SELECT company_id FROM roi_user_companies WHERE user_id = :user AND create_active_rois = 1
						)
					) OR version_id IN (
						SELECT structure_id FROM roi_user_companies WHERE user_id = :user AND create_beta_rois = 1
					) OR structure_id IN (
						SELECT structure_id FROM roi_company_structures WHERE company_id IN (
							SELECT company_id FROM roi_user_companies WHERE user_id = :user AND create_beta_rois = 1
						)
					) ORDER BY version_name;";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':user', $_SESSION['UserId'], PDO::PARAM_INT);
			$stmt->execute();
			$user_templates = $stmt->fetchall(PDO::FETCH_ASSOC);
				
			return $user_templates;					
		}
		
		public function getUserInfo() {
			
			$sql = "SELECT username, first_name, last_name, status, currency FROM roi_users WHERE user_id = ?";
					
			$stmt = $this->_db->prepare( $sql );
			$stmt->bindParam(1, $_SESSION['UserId'], PDO::PARAM_INT);
			$stmt->execute();
			$user_info = $stmt->fetch(PDO::FETCH_ASSOC);

			return $user_info;
		}
		
		public function getUserCreatedRois() {

			$structure_versions = $this->getStructureVersions();
			$version_levels = $this->getVersionLevels();

			$sql = "SELECT * FROM ep_created_rois LEFT JOIN roi_structure_versions
						ON ep_created_rois.roi_version_id = roi_structure_versions.version_id
						WHERE user_id = ?
					ORDER BY roi_id DESC;";
						
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $_SESSION['UserId'], PDO::PARAM_INT);
			$stmt->execute();
			$user_rois = $stmt->fetchall(PDO::FETCH_ASSOC);
				
			$roi_count = 0;
			$tags = [];
			foreach($user_rois as $roi){
				$version_level = array_keys(array_column($structure_versions,'version_id'), $user_rois[$roi_count]['version_id']);
				$roi_link = array_keys(array_column($version_levels,'version_level_id'), $structure_versions[$version_level[0]]['ep_version_level']);
				$user_rois[$roi_count]['roi_full_path'] = '../' . $version_levels[$roi_link[0]]['version_path'] . '?roi=' . $user_rois[$roi_count]['roi_id'];
				
				$user_rois[$roi_count]['formatted_date'] = date('M j Y g:i A', strtotime($user_rois[$roi_count]['dt']));
				$tags = array_unique(array_merge($tags, explode(",",$user_rois[$roi_count]['tags'])));
				$roi_count++;
			}
			
			return $user_rois;
		}
		
		public function getAllCompanyUsers() {
			
			$sql = "SELECT count(ep_created_rois.roi_id) AS created_rois, roi_users.username, roi_users.user_id, roi_users.first_name, roi_users.last_name FROM ep_created_rois
					INNER JOIN roi_users ON roi_users.user_id = ep_created_rois.user_id 
					WHERE roi_users.user_id IN ( 
						SELECT user_id FROM roi_users 
						WHERE company_id = ( 
							SELECT company_id FROM roi_users 
							WHERE user_id = ?
						)
					)
					GROUP BY roi_users.username";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $_SESSION['UserId'], PDO::PARAM_INT);
			$stmt->execute();
			$all_company_users = $stmt->fetchall(PDO::FETCH_ASSOC);
				
			return $all_company_users;
		}
		
		public function getVersionLevels() {

			$sql = "SELECT * FROM roi_version_levels;";
						
			$stmt = $this->_db->prepare($sql);
			$stmt->execute();
			$version_levels = $stmt->fetchall(PDO::FETCH_ASSOC);
				
			return $version_levels;		
		}
		
		public function getStructureVersions() {

			$sql = "SELECT * FROM roi_structure_versions;";
						
			$stmt = $this->_db->prepare($sql);
			$stmt->execute();
			$structure_versions = $stmt->fetchall(PDO::FETCH_ASSOC);
				
			return $structure_versions;		
		}
	}

?>