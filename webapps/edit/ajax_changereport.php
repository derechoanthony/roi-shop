<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once( "$root/webapps/core/init.php" ); 									// Sets up connection to database

$reportid 		= $_POST['reportid'];
$reportname		= $g->Dlookup('roiReportName','wb_roi_reports','wb_roi_report_ID=' . $reportid);

$returnhtml 	= '';

$reportType 	= $g->Dlookup('roiReportType','wb_roi_reports','wb_roi_report_ID=' . $reportid);


$returnhtml		=               '<h4>Editing <strong>' . $reportname . '</strong></h4>'; 
$returnhtml		= $returnhtml . '<input type="hidden" id="selectedreport" value="' . $reportid . '">';
$returnhtml		= $returnhtml . '<input type="hidden" id="reportType" value="' . $reportType . '">';

echo $returnhtml;

?>