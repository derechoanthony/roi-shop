<?php
	
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/constants.php");
	require_once("$root/db/connection.php");
	
	if( $_GET['action'] == 'getUsers' ) {

		$sql = "SELECT user_id, username FROM roi_users
				WHERE company_id = :companyid";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':companyid', $_GET['companyid'], PDO::PARAM_INT);
		$stmt->execute();
		
		echo json_encode($stmt->fetchall());
	}

?>