<?php

	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/constants.php");
	require_once("$root/db/connection.php");
	
	if($_GET['action'] == 'resetverification') {
		
		$verification_code = sha1(uniqid(mt_rand(), true));
		$sql = "UPDATE ep_created_rois SET verification_code = :verification_code
				WHERE roi_id = :roi";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':verification_code', $verification_code, PDO::PARAM_STR);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		echo $verification_code;		
	}
	
	if($_GET['action'] == 'getcurrency') {
		
		$sql = "SELECT * FROM roi_existing_currencies
				WHERE currency_id = :currency";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':currency', $_GET['currencyid'], PDO::PARAM_INT);
		$stmt->execute();
		$currencies = $stmt->fetch();
		print_r($currencies);
		//echo json_encode($stmt->fetch(), JSON_UNESCAPED_UNICODE);	
	}
	
	if($_GET['action'] == 'getversion') {
		
		$sql = "SELECT roi_version_id FROM ep_created_rois
				WHERE roi_id = :roi;";
					
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$version = $stmt->fetch();
		echo $version['roi_version_id'];
	}
	
	if($_GET['action'] == 'getworkfronttotal') {
		
		$sql = "SELECT * FROM roi_values
				WHERE roiid = :roi AND entryid = 'GT1'
				ORDER BY dt DESC LIMIT 1;";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$summarytotal = $stmt->fetch();
		echo $summarytotal['value'];
	}	
	
?>