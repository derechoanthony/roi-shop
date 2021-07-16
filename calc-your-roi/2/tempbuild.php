<?php
	
	// Establish connection to the database
	
	include_once("db/constants.php");
	include_once("db/connection.php");
	
	$sql = 'UPDATE `roi_sections` SET `roi_section_title` = \'ROI Dashboard | {{Return Period}} Year Projection\' WHERE `roi_sections`.`roi_section_id` = 31;';
	
	$stmt = $db->prepare($sql);
	$stmt->execute();

?>