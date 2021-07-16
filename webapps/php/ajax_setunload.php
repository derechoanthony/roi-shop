
<?php 

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once( "$root/webapps/core/init.php" ); 									// Sets up connection to database


$instanceID = $_POST['instanceID'];
$fieldedits = $_POST['fieldedits'];

//This record does not yet exist
//Create an Insert Statement

$rec_count = $g->DCount('valueID','wb_roi_instance_values_standard','instanceID=' . $instanceID . ' AND stdfieldID=9');
	if ($rec_count > 0) {
		$SQL = "UPDATE wb_roi_instance_values_standard
				SET value=NOW()
				WHERE instanceID=$instanceID AND stdfieldID=9;";
		
	} else {
		$SQL = "INSERT INTO wb_roi_instance_values_standard
		(`instanceID`,`stdfieldID`,`value`)
		VALUES
		($instanceID,9,NOW());";
	}


$stmt = $db->prepare($SQL);
$stmt->execute();

$starttime 	= $g->DMax('value','wb_roi_instance_values_standard','instanceID=' . $instanceID . ' AND stdfieldID=5');
$endtime 	= $g->DMax('value','wb_roi_instance_values_standard','instanceID=' . $instanceID . ' AND stdfieldID=9');

$endtime = strtotime($endtime);
$starttime = strtotime($starttime);
$difmin = round(abs($endtime - $starttime) / 60,2);

//$difference	= $starttime->diff($endtime);

//$difmin		=$difference->i;

//$difmin = 3;

$rec_count = $g->DCount('valueID','wb_roi_instance_values_standard','instanceID=' . $instanceID . ' AND stdfieldID=11');

	if ($rec_count > 0) {
			$SQL = "UPDATE wb_roi_instance_values_standard
					SET value=$difmin
					WHERE instanceID=$instanceID AND stdfieldID=11;";
			
		} else {
	
			$SQL = "INSERT INTO wb_roi_instance_values_standard
				(`instanceID`,`stdfieldID`,`value`)
				VALUES
				($instanceID,11,$difmin);";
		}
		
$stmt = $db->prepare($SQL);
$stmt->execute();

//$difsec		=$difference->s;
$difsec = $difmin * 60;

$rec_count = $g->DCount('valueID','wb_roi_instance_values_standard','instanceID=' . $instanceID . ' AND stdfieldID=12');
	if ($rec_count > 0) {
				$SQL = "UPDATE wb_roi_instance_values_standard
						SET value=$difsec
						WHERE instanceID=$instanceID AND stdfieldID=12;";
				
			} else {
				$SQL = "INSERT INTO wb_roi_instance_values_standard
					(`instanceID`,`stdfieldID`,`value`)
					VALUES
					($instanceID,12,$difsec);";
			}
$stmt = $db->prepare($SQL);
$stmt->execute();


$rec_count = $g->DCount('valueID','wb_roi_instance_values_standard','instanceID=' . $instanceID . ' AND stdfieldID=18');
	if ($rec_count > 0) {
				$SQL = "UPDATE wb_roi_instance_values_standard
						SET value=$fieldedits
						WHERE instanceID=$instanceID AND stdfieldID=18;";
				
			} else {

				$SQL = "INSERT INTO wb_roi_instance_values_standard
					(`instanceID`,`stdfieldID`,`value`)
					VALUES
					($instanceID,18,$fieldedits);";
			}
$stmt = $db->prepare($SQL);
$stmt->execute();


	$SQL = "UPDATE wb_roi_instance_values_standard t1 INNER JOIN wb_roi_instance t2
				ON t1.instanceID = t2.instanceID
			SET t1.wb_roi_ID = t2.wbroiID
			WHERE t1.instanceID=$instanceID;"
	$stmt = $db->prepare($SQL);
	$stmt->execute();

?>

