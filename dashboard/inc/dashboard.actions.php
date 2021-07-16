<?php

	class DashboardActions {
		
		private $_db;

		public function __construct($db=NULL) {
			
			if(is_object($db))
			{
				$this->_db = $db;
			}
			else
			{
				$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
				$this->_db = new PDO($dsn, DB_USER, DB_PASS);
			}
		}
		
		public function getUserCompVersions() {
			
			$sql = "SELECT * FROM roi_structure_versions
					WHERE version_stage = 1 AND version_id IN (
						SELECT structure_id FROM roi_user_companies
						WHERE user_id = :user AND create_active_rois = 1
					) OR version_stage = 1 AND structure_id IN (
						SELECT structure_id FROM roi_company_structures
						WHERE company_id IN (
							SELECT company_id FROM roi_user_companies
							WHERE user_id = :user AND create_active_rois = 1
						)
					) OR version_id IN (
						SELECT structure_id FROM roi_user_companies
						WHERE user_id = :user AND create_beta_rois = 1
					) OR structure_id IN (
						SELECT structure_id FROM roi_company_structures
						WHERE company_id IN (
							SELECT company_id FROM roi_user_companies
							WHERE user_id = :user AND create_beta_rois = 1
						)
					) ORDER BY version_name;";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':user', $_SESSION['UserId'], PDO::PARAM_INT);
			$stmt->execute();
			$user_comps = $stmt->fetchall();
			
			return $user_comps;
			
		}
		
		public function getUserCreatedRois() {

			$sql = "SELECT * FROM ep_created_rois
					LEFT JOIN roi_structure_versions
						ON ep_created_rois.roi_version_id = roi_structure_versions.version_id
						WHERE user_id = :user
					ORDER BY roi_position;";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':user', $_SESSION['UserId'], PDO::PARAM_INT);
			$stmt->execute();
			$user_rois = $stmt->fetchall();
			
			return $user_rois;			
		}
		
		public function getUserFolders() {
		
			$sql = "SELECT * FROM roi_folders
					WHERE global = 1 OR userid = :user;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':user',$_SESSION['UserId'],PDO::PARAM_INT);
			$stmt->execute();
			$folders = $stmt->fetchall();
			return $folders;		
		}
		
		public function getVisibleFolders() {
			
			$sql = "SELECT * FROM roi_visible_folders
					WHERE user_id = :user;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':user',$_SESSION['UserId'],PDO::PARAM_INT);
			$stmt->execute();
			$visible_folders = $stmt->fetchall();
			return $visible_folders;				
		}
		
		public function getVersionLevels() {
			
			$sql = "SELECT * FROM roi_version_levels;";

			$stmt = $this->_db->prepare($sql);
			$stmt->execute();
			$version_levels = $stmt->fetchall();
			return $version_levels;			
		}
		
		public function getUserSpecs() {
			
			$sql = "SELECT * FROM roi_users
					WHERE user_id = :user;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':user',$_SESSION['UserId'],PDO::PARAM_INT);
			$stmt->execute();
			$user_specs = $stmt->fetch();
			return $user_specs;
		}
		
		public function salesforceAccess() {
			
			$sql = "SELECT * FROM roi_company_integration
					WHERE company_id = (
						SELECT company_id FROM roi_users
						WHERE user_id = :user
					) AND integration_id = 'sfdc'";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':user',$_SESSION['UserId'],PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetch();			
		}
		
		public function sfconnected() {
			
			$sql = "SELECT * FROM integration
					WHERE userid = :user;";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':user', $_SESSION['UserId'], PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetch();			
		}
	}

?>