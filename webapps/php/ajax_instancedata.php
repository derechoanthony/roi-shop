
<?php 

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once( "$root/webapps/core/init.php" ); 									// Sets up connection to database


foreach($_POST as $key => $value)
{
    if (strstr($key, 'instanceID'))
    {
        $instanceID = $value;
    }
	else 
	{
		//This is form data
		//See if the record exists for this field
		$value 				= trim($value);
		$value_formatted 	= trim($value);
		
		//Get the wbappID for this field
		$wbappID = $g->Dlookup('wbroiID','wb_roi_instance','instanceID=' . $instanceID);
		
		//Lookup the type of input that this it
		$fieldtype = $g->DLookup('inputType','wb_roi_fields','fieldID=' . $key);
		
		//If the field is a number field then remove all the special characters
		if($fieldtype==2){
			//Remove any commas
			if ( strstr( $value, ',' ) ) $value = str_replace( ',', '', $value );
			//Remove any dollars
			if ( strstr( $value, '$' ) ) $value = str_replace( '$', '', $value );
			//Remove any %
			if ( strstr( $value, '%' ) ) $value = str_replace( '%', '', $value );
			
			$value = preg_replace('/[^0-9]/','',$value);
		}
		
		//$cellnum = preg_replace("/[^A-Z]/", '', $key);
		//echo 'getting count field=' . $fieldID . ' ';
		//$fieldID = DLookup('fieldID','wb_roi_fields','wb_roi_ID=' . $wbappID . ' AND cell=' . $cellnum);
		//echo 'getting count field=' . $fieldID . ' ';
		$fieldcount = $g->DCount('valueID','wb_roi_instance_values','instanceID=' . $instanceID . ' AND field=' . $key);
		echo $fieldcount . ' ';
		if ($fieldcount==0) 
		{
			//This record does not yet exist
			//Create an Insert Statement
			$SQL = "INSERT INTO wb_roi_instance_values (instanceID,field,value)
					VALUES ($instanceID,$key,'$value');";
					echo $SQL . ' ';
			$stmt = $db->prepare($SQL);
			$stmt->execute();
			
			$SQL = "INSERT INTO wb_roi_instance_values_formatted (instanceID,field,value)
					VALUES ($instanceID,$key,'$value_formatted');";
					echo $SQL . ' ';
			$stmt = $db->prepare($SQL);
			$stmt->execute();
		}
		else 
		{
			//This record already exists
			//Create an Update Statement
			$SQL = "UPDATE wb_roi_instance_values
					SET value='$value', modified=NOW()
					WHERE   instanceID=$instanceID
						AND field=$key;";
			echo $SQL . ' ';	
			$stmt = $db->prepare($SQL);
			$stmt->execute();
			
			$SQL = "UPDATE wb_roi_instance_values_formatted
					SET value='$value_formatted', modified=NOW()
					WHERE   instanceID=$instanceID
						AND field=$key;";
			echo $SQL . ' ';	
			$stmt = $db->prepare($SQL);
			$stmt->execute();
		}	
	}
}




?>

