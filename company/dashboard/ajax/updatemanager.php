<?php

	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/constants.php");
	require_once("$root/db/connection.php");
	
	$sql = "UPDATE roi_users SET manager = :manager
			WHERE user_id = :userid";

	$stmt = $db->prepare($sql);
	$stmt->bindParam(':userid', $_POST['name'], PDO::PARAM_STR);
	$stmt->bindParam(':manager', $_POST['value'], PDO::PARAM_STR);
	$stmt->execute();
	
?>