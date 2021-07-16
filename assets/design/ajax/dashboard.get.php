<?php

	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/constants.php");
	require_once("$root/db/connection.php");
	
	if( $_GET['action'] == 'companyusers' ) {			
			
		$sql = "SELECT * FROM roi_users
				WHERE company_id = (
					SELECT company_id FROM roi_users
					WHERE user_id = :user
				);";
				
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':user', $_SESSION['UserId'], PDO::PARAM_INT);
		$stmt->execute();
		$company_users = $stmt->fetchall();

		echo json_encode($company_users);
	}
	
?>