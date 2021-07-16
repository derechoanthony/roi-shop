
<?php 
$executionStartTime = microtime(true);

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once( "$root/webapps/core/init.php" ); 									// Sets up connection to database


$instanceID 		= $_POST['instanceID'];
$data_formatted		= $_POST['data_formatted'];
$data_unformatted	= $_POST['data_unformatted'];

if( isset($_POST['roicalcstatus']) ){$roicalcstatus = $_POST['roicalcstatus'];} else {$roicalcstatus=0;}
if( isset($_POST['roicalcID']) ){$roicalcID = $_POST['roicalcID'];} else {$roicalcID=$g->Dlookup('wbroiID','wb_roi_instance','instanceID=' . $instanceID);}


$formatted = array();
parse_str($data_formatted, $formatted);
$unformatted = array();
parse_str($data_unformatted, $unformatted);


//1. Delete * Where InstanceID=$instanceID
			$SQL = "DELETE FROM wb_roi_instance_values WHERE instanceID=:instanceid";
			$stmt = $db->prepare($SQL);
			$stmt->bindParam(':instanceid', $instanceID, PDO::PARAM_INT);
			$stmt->execute();

//2. Insert All values


$insertdata = array();
$x = 0;
foreach($formatted as $key => $value)
{
		$unformattedval = $unformatted[$key];
		$formmatedval = $formatted[$key];
		
		$insertdata[$x]['instanceID']=$instanceID;
		$insertdata[$x]['wb_roi_ID']=$roicalcID;
		$insertdata[$x]['wb_roi_status']=$roicalcstatus;
		$insertdata[$x]['field']=$key;
		$insertdata[$x]['value']=$unformattedval;
		$insertdata[$x]['formatted_value']=$formmatedval;
		
		$x = $x + 1;
		
		
}


$g->pdoMultiInsert('wb_roi_instance_values',$insertdata);

$executionEndTime = microtime(true);
$seconds = $executionEndTime - $executionStartTime;
	echo "This script took $seconds to execute.";
	//echo '<pre>'; print_r($insertdata); echo '</pre>';

?>

