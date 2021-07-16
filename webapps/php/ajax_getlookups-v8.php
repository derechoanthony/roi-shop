
<?php 
//$executionStartTime = microtime(true);

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once( "$root/webapps/core/init.php" ); 									// Sets up connection to database
require_once( "$root/webapps/php/ajaxlookups.class.php");		// Set up a class for the weblet functions
$lookups = new lookups();

$routine		 = $_POST['routine'];


switch ($routine) {

case ('getlist'):

	$wbroiID 		= $_POST['roicalcID'];
	$tableID		= $_POST['tableid'];
	$valuecol		= $_POST['valuecol'];
	$optioncol		= $_POST['optioncol'];
	$ordercol		= $_POST['ordercol'];


	$optionvals = $lookups->getoptionvals($wbroiID, $tableID, $valuecol, $ordercol);

	$returnval = json_encode($optionvals);
	echo $returnval;		

	break;
	
case ('updatelookup1'):
	echo 'in routine';

	break;
	
case ('updatelookup'):
	$wbroiID 			= $_POST['roicalcID'];
	$tableID			= $_POST['lookuptableid'];
	$lookupvalue		= $_POST['lookupvalue'];
	$lookupcol			= $_POST['lookupcol'];
	$lookupvalcol		= $_POST['lookupvalcol'];
	
	$lookuprow = $lookups->getlookupval($wbroiID, $tableID, $lookupvalue, $lookupcol, $lookupvalcol);
	$returnval = json_encode($lookuprow);
	$returnstring = 'wbroiID: ' . $wbroiID . '; tableID: ' . $tableID . '; lookupvalue: ' . $lookupvalue . '; lookupcol: ' . $lookupcol . '; lookupvalcol: ' . $lookupvalcol; 
	echo $lookuprow;
	break;

}



?>

