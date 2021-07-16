
<?php 

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once( "$root/webapps/core/init.php" ); 									// Sets up connection to database


$instanceID = $_POST['instanceID'];

//This record does not yet exist
//Create an Insert Statement
$SQL = "INSERT INTO wb_roi_instance_values_standard
	(`instanceID`,`stdfieldID`,`value`)
	VALUES
	($instanceID,9,NOW());";

$stmt = $db->prepare($SQL);
$stmt->execute();

$starttime 	= new DateTime($g->Dlookup('value','wb_roi_instance_values_standard','instanceID=' . $instanceID . ' AND stfieldID=5'));
$endtime 	= new DateTime($g->Dlookup('value','wb_roi_instance_values_standard','instanceID=' . $instanceID . ' AND stfieldID=9'));

$difference	= $starttime->diff($endtime);

$difmin		=$difference->i;

$SQL = "INSERT INTO wb_roi_instance_values_standard
	(`instanceID`,`stdfieldID`,`value`)
	VALUES
	($instanceID,11,$difmin);";

$stmt = $db->prepare($SQL);
$stmt->execute();

$difsec		=$difference->s;

$SQL = "INSERT INTO wb_roi_instance_values_standard
	(`instanceID`,`stdfieldID`,`value`)
	VALUES
	($instanceID,12,$difsec);";

$stmt = $db->prepare($SQL);
$stmt->execute();

?>

