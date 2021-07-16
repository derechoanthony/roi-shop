<?php

	// Establish connection to the database
	
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/db_connection.php");
	
	$roiActions = new AdminActions($db);
	
	switch($_GET['action']){
		case 'RetrieveUsers':
			echo json_encode($roiActions->retrieveUsers());
		break;

		case 'GetUserRois':
			echo json_encode($roiActions->getUserRois());
		break;
	}

	class AdminActions {
		private $_db;

		public function __construct($db=NULL) {
			
			if(is_object($db)) {
				$this->_db = $db;
			} else {
				$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
				$this->_db = new PDO($dsn, DB_USER, DB_PASS);
			}			
		}

		public function retrieveUsers(){

			$sql = "SELECT roi_users.username, roi_users.user_id, roi_users.manager, COUNT(ep_created_rois.user_id) AS user_rois FROM roi_users
					LEFT JOIN ep_created_rois ON ep_created_rois.user_id = roi_users.user_id
					WHERE roi_users.user_id IN (
						SELECT user_id FROM roi_users 
						WHERE company_id = :company
					) 
					GROUP BY roi_users.user_id
					ORDER BY roi_users.username";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':company',$_GET['company'],PDO::PARAM_INT);
			$stmt->execute();
			$users = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $users;					
		}

		public function getUserRois(){

			$sql = "SELECT roi_id, roi_title FROM ep_created_rois
					WHERE user_id = :user";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':user',$_GET['user'],PDO::PARAM_INT);
			$stmt->execute();
			$rois = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $rois;				
		}
	}

?>