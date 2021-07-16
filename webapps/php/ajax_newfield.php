
<?php 

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once( "$root/sandwebapp/core/init.php" ); 									// Sets up connection to database

$reportID 	= $_POST['reportid'];

//get next cell value
$cell = $g->DMax('cell','wb_roi_fields','wb_roi_ID=' . $reportID);




?>

