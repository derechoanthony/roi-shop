<?php

	// Establish connection to the database
	
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/db_connection.php");
	
	if( $_POST['action'] == 'changecurrency' ) {
		
		$roiBuilder = new RoiBuilder($db);
		$roiBuilder->changeRoiCurrency();
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
		
		public function changeRoiCurrency(){
			
			$sql = "UPDATE ep_created_rois SET currency = :currency WHERE roi_id = :roi;";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':currency', $_POST['currency'], PDO::PARAM_STR);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->execute();			
		}
	}
?>