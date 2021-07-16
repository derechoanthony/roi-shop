<?php

	// Establish connection to the database
	
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/db_connection.php");

	if( $_POST['action'] == 'updatestatus' ) {

		$sql = "UPDATE ep_created_rois SET status = ? WHERE roi_id = ?";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(1, $_POST['status'], PDO::PARAM_INT);
		$stmt->bindParam(2, $_POST['roi'], PDO::PARAM_INT);
		$stmt->execute();			
	}

	if( $_POST['action'] == 'updateimport' ) {

		$sql = "UPDATE ep_created_rois SET importance = ? WHERE roi_id = ?";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(1, $_POST['importance'], PDO::PARAM_INT);
		$stmt->bindParam(2, $_POST['roi'], PDO::PARAM_INT);
		$stmt->execute();			
	}
	
	if( $_POST['action'] == 'renameroi' ) {	
			
		$sql = "UPDATE ep_created_rois SET roi_title = ? WHERE roi_id = ?";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(1, $_POST['name'], PDO::PARAM_STR);
		$stmt->bindParam(2, $_POST['roi'], PDO::PARAM_INT);
		$stmt->execute();	
	}
	
	if( $_POST['action'] == 'deleteroi' ) {		
			
		$sql = "DELETE FROM ep_created_rois WHERE roi_id = ?";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(1, $_POST['roi'], PDO::PARAM_INT);
		$stmt->execute();
	}
	
	if( $_POST['action'] == 'createroi' ) {		
			
		$verificaiton_code = sha1(uniqid(mt_rand(), true));
		$sql = "INSERT INTO ep_created_rois ( user_id, roi_title, roi_version_id, verification_code, currency )
				VALUES ( ?, ?, ?, ?, ? )";
		
		$stmt = $db->prepare($sql);
		$stmt->bindParam(1, $_SESSION['UserId'], PDO::PARAM_INT);
		$stmt->bindParam(2, $_POST['roiName'], PDO::PARAM_STR);
		$stmt->bindParam(3, $_POST['template'], PDO::PARAM_INT);
		$stmt->bindParam(4, $verificaiton_code, PDO::PARAM_STR);
		$stmt->bindParam(5, $_POST['currency'], PDO::PARAM_STR);
		$stmt->execute();
		
		echo $db->lastInsertId();
	}

?>