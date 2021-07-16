<?php
		
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/db_connection.php");
	
	if( $_GET['action'] == 'getverification' ) {
		
		$roiBuilder = new RoiBuilder($db);
		$roiStructure = array();
		
		$roiInfo = $roiBuilder->getRoiInfo();
		$structure_versions = $roiBuilder->getStructureVersions();
		$version_levels = $roiBuilder->getVersionLevels();

		$version_level = array_keys(array_column($structure_versions,'version_id'), $roiInfo['version_id']);
		$roi_link = array_keys(array_column($version_levels,'version_level_id'), $structure_versions[$version_level[0]]['ep_version_level']);
		$roiInfo['roi_full_path'] = '../' . $version_levels[$roi_link[0]]['version_path'] . '?roi=' . $roiInfo['roi_id'];
			
		$roiInfo['formatted_date'] = date('M j Y g:i A', strtotime($roiInfo['dt']));

		$roiStructure['roiInfo'] = $roiInfo;
		echo json_encode($roiStructure);		
	}

	class RoiBuilder {
		
		private $_db;
			
		public function __construct($db=NULL) {
				
			if(is_object($db)) {
				$this->_db = $db;
			} else {
				$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
				$this->_db = new PDO($dsn, DB_USER, DB_PASS);
			}
		}
		
		public function getRoiInfo() {

			$sql = "SELECT * FROM ep_created_rois 
					LEFT JOIN roi_structure_versions
					ON ep_created_rois.roi_version_id = roi_structure_versions.version_id
					WHERE roi_id = :roi;";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiInfo = $stmt->fetch(PDO::FETCH_ASSOC);
			
			return $RoiInfo;			
		}
		
		public function getStructureVersions() {

			$sql = "SELECT * FROM roi_structure_versions;";
						
			$stmt = $this->_db->prepare($sql);
			$stmt->execute();
			$structure_versions = $stmt->fetchall(PDO::FETCH_ASSOC);
				
			return $structure_versions;		
		}
		
		public function getVersionLevels() {

			$sql = "SELECT * FROM roi_version_levels;";
						
			$stmt = $this->_db->prepare($sql);
			$stmt->execute();
			$version_levels = $stmt->fetchall(PDO::FETCH_ASSOC);
				
			return $version_levels;		
		}
	}
?>