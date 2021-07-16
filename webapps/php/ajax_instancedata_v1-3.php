
<?php 

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once( "$root/webapps/core/init.php" ); 									// Sets up connection to database


$instanceID 		= $_POST['instanceID'];
$roicalcstatus		= $_POST['roicalcstatus'];
$roicalcID	 		= $_POST['roicalcID'];
$data_formatted		= $_POST['data_formatted'];
$data_unformatted	= $_POST['data_unformatted'];

$returntext = 'instanceID: ' . $instanceID . '; roicalcID: ' . $roicalcID . '; roistatus: ' . $roicalcstatus;

$formatted = array();
parse_str($data_formatted, $formatted);
$unformatted = array();
parse_str($data_unformatted, $unformatted);

//Get the wbappID for this field
$wbappID = $g->Dlookup('wbroiID','wb_roi_instance','instanceID=' . $instanceID);				

foreach($formatted as $key => $value)
{
		$unformattedval = $unformatted[$key];
		$formmatedval = $formatted[$key];
		//echo $key . '=' . $formmatedval . '  ';
		$fieldcount = $g->DCount('valueID','wb_roi_instance_values','instanceID=' . $instanceID . ' AND field=' . $key);
		//echo 'fieldcount: ' . $fieldcount . ' ';
		if ($fieldcount==0) 
		{
			//This record does not yet exist
			//Create an Insert Statement
			$SQL = "INSERT INTO wb_roi_instance_values (instanceID,wb_roi_ID,wb_roi_status,field,value,formatted_value)
					VALUES (:instanceID,:wbroiID,:wbroistatus,:key,:value,:value_formatted);";
					//echo $SQL . ' ';
			$stmt = $db->prepare($SQL);
			$stmt->bindParam(':instanceID', $instanceID, PDO::PARAM_INT);
			$stmt->bindParam(':wbroiID', $roicalcID, PDO::PARAM_INT);
			$stmt->bindParam(':wbroistatus', $roicalcstatus, PDO::PARAM_INT);
			$stmt->bindParam(':key', $key, PDO::PARAM_INT);
			$stmt->bindParam(':value', $unformattedval, PDO::PARAM_STR);
			$stmt->bindParam(':value_formatted', $formmatedval, PDO::PARAM_STR);
			$stmt->execute();
			
			
		}
		else 
		{
			//This record already exists
			//Create an Update Statement
			$SQL = "UPDATE wb_roi_instance_values
					SET value=:value, formatted_value=:value_formatted, modified=NOW()
					WHERE   instanceID=:instanceID
						AND field=:key;";
			//echo $SQL . ' ';	
			$stmt = $db->prepare($SQL);
			$stmt->bindParam(':key', $key, PDO::PARAM_INT);
			$stmt->bindParam(':instanceID', $instanceID, PDO::PARAM_INT);
			$stmt->bindParam(':value', $unformattedval, PDO::PARAM_STR);
			$stmt->bindParam(':value_formatted', $formmatedval, PDO::PARAM_STR);
			$stmt->execute();
	

		}	
	
}

echo $returntext;


?>

