<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once( "$root/webapps/core/init.php" ); 									// Sets up connection to database

$reportid 		= $_POST['reportid'];
$codetype 		= $_POST['codetype'];
$returnhtml 	= '';

$returnhtml 	= $g->Dlookup($codetype,'wb_roi_reports','wb_roi_report_ID=' . $reportid);

echo $returnhtml;

?>