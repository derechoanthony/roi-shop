<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once( "$root/webapps/core/init.php" ); 									// Sets up connection to database

$roiID 			= $_POST['roiid'];

$fields = $g->analytics_getcalcfields($roiID);
 	
	echo json_encode($fields);
	
	
?>