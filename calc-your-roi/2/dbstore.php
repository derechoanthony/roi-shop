<?php
	
	// Establish connection to the database
	
	include_once("db/constants.php");
	include_once("db/connection.php");
	

	$sql = "INSERT INTO `entry_choices` (`entryid`, `show_map`, `value`, `position`) VALUES ('24468', '#A24469, #A24470, #A24471, #A24472, #A24473, #A24474, #A24475, #A24476', 'Yes', '1'), ('24468', '', 'No', '2');";
	
	$stmt = $db->prepare($sql);
	$stmt->execute();	
	
?>
