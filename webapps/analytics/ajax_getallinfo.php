<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once( "$root/webapps/core/init.php" ); 									// Sets up connection to database

$roiID 			= $_POST['roiid'];

$calcinfo = array();

$views = $g->analytics_getviews($roiID);
$stdvalues = $g->analytics_getstdvalues($roiID); 	
$values = $g->analytics_getvalues($roiID);
$tablecolumns = $g->analytics_gettablecols($roiID);
$calcfields = $g->analytics_getcalcfields($roiID);


$calcinfo['instances'] = $views;
$calcinfo['instancevalues'] = $values;
$calcinfo['instancestdvalues'] = $stdvalues;
$calcinfo['tablecolumns'] = $tablecolumns;
$calcinfo['calcfields'] = $calcfields;

$calcinfo['Testing'] = 'Testing';

echo json_encode($calcinfo);
	
	//echo $roiID;
	
?>