<?php

	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/constants.php");
	require_once("$root/db/connection.php");
	
	if($_POST['action'] =='addcontributor') {
		
		$sql = "INSERT INTO ep_roi_contributors (`roi_id`,`contributor_name`,`contributor_email`,`contributor_type`,`contributor_company`,`notes`,`created_dt`)
				VALUES (:roi,:name,:email,'owneradded',:company,:notes,:dt);";
				
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
		$stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
		$stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
		$stmt->bindParam(':company', $_POST['company'], PDO::PARAM_STR);
		$stmt->bindParam(':notes', $_POST['notes'], PDO::PARAM_STR);
		$stmt->bindParam(':dt', date("Y-m-d H:i:s"), PDO::PARAM_STR);
		$stmt->execute();
		
		echo $db->lastInsertId();	
	}
	
	if($_POST['action'] == 'removecontributor') {
		
		$sql = "DELETE FROM ep_roi_contributors
				WHERE contributor_id = :contributor;";
				
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':contributor', $_POST['contributor'], PDO::PARAM_INT);
		$stmt->execute();		
	}
	
	if($_POST['action'] =='changecurreny') {
		
		$sql = "INSERT INTO ep_roi_currency (roi_id, currency_name) VALUES (:roi, :symbol) ON DUPLICATE KEY UPDATE currency_name = :symbol;";
				
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
		$stmt->bindParam(':symbol', $_POST['symbol'], PDO::PARAM_STR);
		$stmt->execute();	
	}
	
	if($_POST['action'] == 'updatesflink' ) {
		
		$sql = "UPDATE ep_created_rois SET sfdc_link = :link, linked_title = :title, instance = 'opportunities'
				WHERE roi_id = :roi;";
				
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':link', $_POST['sfdclink'], PDO::PARAM_STR);
		$stmt->bindParam(':title', $_POST['sfdctitle'], PDO::PARAM_STR);
		$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
		$stmt->execute();		
	}
	
?>