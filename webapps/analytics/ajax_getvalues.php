<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once( "$root/webapps/core/init.php" ); 									// Sets up connection to database

$roiID 			= $_POST['roiid'];
$startdatestng  = $_POST['starting'];
$currentdate    = $_POST['ending'];

$views = $g->analytics_getvalues($roiID);
 	
	echo json_encode($views);
session_write_close();
sleep(5);	
	
?>