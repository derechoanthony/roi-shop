<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once( "$root/webapps/core/init.php" ); 									// Sets up connection to database

$fielddetails   = array(); 

$fieldID 		= $_POST['fieldid'];
$fielddetails	= $g->GetFieldDetails($fieldID);



echo json_encode($fielddetails);

?>