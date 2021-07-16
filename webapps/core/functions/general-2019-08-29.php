<?php

class GeneralFunctions
{
	
	//Create database object
	private $_db;
		
	/**
	 * Checks for a database object and creates one if none is found
	 **/
	public function __construct($db=NULL)
	{
		if(is_object($db))
		{
			$this->_db = $db;
		}
		else
		{
			$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
			$this->_db = new PDO($dsn, DB_USER, DB_PASS);
		}
	}
	

function gotit(){
	return 'got it';
}

function sanitize($data){
	
	return mysqli_real_escape_string($this,$data);
}

function output_erros($errors){
	$output=array();
	foreach($errors as $error){
		$output[] = '<li>'. $error. '</li>';
	}
	return '<ul>'. implode('',$output) .'</ul>';
}


function DUpdate($fld, $table, $val, $where)
{
	
	$sql = "Update `$table` t1 SET t1.`$fld` = :val where $where";
				
	$stmt = $this->_db->prepare($sql);
	//$stmt->bindParam(':fld', $fld, PDO::PARAM_STR);
	$stmt->bindParam(':val', $val, PDO::PARAM_STR);
	$stmt->execute();
	
	//return $sql;
}

function InsertArchive($reportID, $archivename, $archivedesc, $html, $css, $script)
{
$sql = "INSERT INTO `wb_roi_reports_archives` 
		(wb_roi_report_ID, archiveName, archiveDescription, HTML, CSS, Scripts)
		VALUES 
		(:reportID, :archivename, :archivedesc, :html, :css, :script);";

//echo $reportID;
			
	$stmt = $this->_db->prepare($sql);
	$stmt->bindParam(':archivename', $archivename, PDO::PARAM_STR);
	$stmt->bindParam(':archivedesc', $archivedesc, PDO::PARAM_STR);
	$stmt->bindParam(':html',$html, PDO::PARAM_STR);
	$stmt->bindParam(':css', $css, PDO::PARAM_STR);
	$stmt->bindParam(':script', $script, PDO::PARAM_STR);
	$stmt->bindParam(':reportID',$reportID,	PDO::PARAM_INT);
	$stmt->execute();
	
	return $sql;
}

function InsertMacro($wbappID, $macroID, $macroname)
{
$sql = "INSERT INTO `wb_roi_reports_macros` 
		(wbappID, elementID, macroID, givenName)
		VALUES 
		(:wbappid, 200026, :macroid, :macroname);";		
	$stmt = $this->_db->prepare($sql);
	$stmt->bindParam(':wbappid', $wbappID, PDO::PARAM_INT);
	$stmt->bindParam(':macroid', $macroID, PDO::PARAM_INT);
	$stmt->bindParam(':macroname', $macroname, PDO::PARAM_STR);
	$stmt->execute();
	
	
	$sql = "SELECT MAX(usedmacroID)
    		FROM `wb_roi_reports_macros`
    		WHERE wbappID=:wbappid";
	$stmt = $this->_db->prepare($sql);
	$stmt->bindParam(':wbappid', $wbappID, PDO::PARAM_INT);
	$stmt->execute();
	$usedmacroID = $stmt->fetch();
	$maxid = $usedmacroID[0];
	
	
	$sql = "INSERT INTO `wb_roi_reports_macros_aurguments` (usedMacroID, varID, varValue)
			SELECT $maxid, varID, varDefault FROM `wb_roi_macros_aurguments`
			WHERE `wb_roi_macros_aurguments`.macroID=$macroID";
	$stmt = $this->_db->prepare($sql);
	$stmt->execute();
	
}


function InsertMacroResponse($wbappID, $instanceID, $macroID, $response)
{
$sql = "INSERT INTO `wb_roi_instance_macro_responses` 
		(wbappID, instanceID, macroID, response)
		VALUES 
		(:wbappid, :instanceid, :macroid, :response);";		
	$stmt = $this->_db->prepare($sql);
	$stmt->bindParam(':wbappid', $wbappID, PDO::PARAM_INT);
	$stmt->bindParam(':instanceid', $instanceID, PDO::PARAM_INT);
	$stmt->bindParam(':macroid', $macroID, PDO::PARAM_INT);
	$stmt->bindParam(':response', $response, PDO::PARAM_STR);
	$stmt->execute();
	
	
	
}



function UpdateMacroAurgValue($aurgid, $newval)
{
	
	$sql = "Update `wb_roi_reports_macros_aurguments` t1 SET t1.`varValue` = :newval where aurgID=:aurgid";
				
	$stmt = $this->_db->prepare($sql);
	$stmt->bindParam(':aurgid', $aurgid, PDO::PARAM_INT);
	$stmt->bindParam(':newval', $newval, PDO::PARAM_STR);
	$stmt->execute();

}


function DUpdatecss($val, $reportid)
{
	
	$sql = "Update `wb_roi_reports` t1 SET t1.`CSS` = :val where wb_roi_report_ID=:reportid";
				
	$stmt = $this->_db->prepare($sql);
	//$stmt->bindParam(':fld', $fld, PDO::PARAM_STR);
	$stmt->bindParam(':val', $val, PDO::PARAM_STR);
	$stmt->bindParam(':reportid', $reportid, PDO::PARAM_INT);
	$stmt->execute();
	
	return $sql;
}
function DUpdatehtml($val, $reportid)
{
	
	$sql = "Update `wb_roi_reports` t1 SET t1.`HTML` = :val where wb_roi_report_ID=:reportid";
				
	$stmt = $this->_db->prepare($sql);
	//$stmt->bindParam(':fld', $fld, PDO::PARAM_STR);
	$stmt->bindParam(':val', $val, PDO::PARAM_STR);
	$stmt->bindParam(':reportid', $reportid, PDO::PARAM_INT);
	$stmt->execute();
	
	return $sql;
}
function DUpdatescript($val, $reportid)
{
	
	$sql = "Update `wb_roi_reports` t1 SET t1.`Scripts` = :val where wb_roi_report_ID=:reportid";
				
	$stmt = $this->_db->prepare($sql);
	//$stmt->bindParam(':fld', $fld, PDO::PARAM_STR);
	$stmt->bindParam(':val', $val, PDO::PARAM_STR);
	$stmt->bindParam(':reportid', $reportid, PDO::PARAM_INT);
	$stmt->execute();
	
	return $sql;
}


function DLookup($fld, $tab, $whr)
{

	$sql = "Select $fld from $tab where $whr Limit 1";
				
	$stmt = $this->_db->prepare($sql);	
	//$stmt->bindParam(':fld', $fld, PDO::PARAM_STR);
	//$stmt->bindParam(':tab', $tab, PDO::PARAM_STR);
	//$stmt->bindParam(':whr', $whr, PDO::PARAM_STR);
	$stmt->execute();
	$data = $stmt->fetch();

	return $data[0];


}


function pdoMultiInsert($tableName, $data){
    
    //Will contain SQL snippets.
    $rowsSQL = array();
 
    //Will contain the values that we need to bind.
    $toBind = array();
    
    //Get a list of column names to use in the SQL statement.
    $columnNames = array_keys($data[0]);
 
    //Loop through our $data array.
    foreach($data as $arrayIndex => $row){
        $params = array();
        foreach($row as $columnName => $columnValue){
            $param = ":" . $columnName . $arrayIndex;
            $params[] = $param;
            $toBind[$param] = $columnValue; 
        }
        $rowsSQL[] = "(" . implode(", ", $params) . ")";
    }
 
    //Construct our SQL statement
    $sql = "INSERT INTO `$tableName` (" . implode(", ", $columnNames) . ") VALUES " . implode(", ", $rowsSQL);
 
    //Prepare our PDO statement.
    $pdoStatement = $this->_db->prepare($sql);
 
    //Bind our values.
    foreach($toBind as $param => $val){
        $pdoStatement->bindValue($param, $val);
    }
    
    //Execute our statement (i.e. insert the data).
    return $pdoStatement->execute();
}



function GetCronJobs()
{
	$sql = "SELECT *
    		FROM `wb_cron_jobs`
    		WHERE jobstatus=0";
	$stmt = $this->_db->prepare($sql);
	$stmt->execute();
	$data = $stmt->fetchAll();
	return $data;

	
}

function deleteCron($cronID)
{
	$sql = "DELETE 
    		FROM `wb_cron_jobs`
    		WHERE cronID=:cronid";
	$stmt = $this->_db->prepare($sql);
	$stmt->bindParam(':cronid', $cronID, PDO::PARAM_INT);
	$stmt->execute();	
	
}

function cronStatus($cronID,$jobstatus,$response)
{
	$sql = "UPDATE `wb_cron_jobs`
			SET jobstatus=:jobstatus, response=:response
    		WHERE cronID=:cronid";
	$stmt = $this->_db->prepare($sql);
	$stmt->bindParam(':cronid', $cronID, PDO::PARAM_INT);
	$stmt->bindParam(':jobstatus', $jobstatus, PDO::PARAM_INT);
	$stmt->bindParam(':response', $response, PDO::PARAM_STR);
	$stmt->execute();	
	
}

/* To Delete after cron is done
 * 
 */

 function testsaveargs($macro,$args){
	$sql = "INSERT INTO wb_cron_jobs 
			(jobstatus,macro,aurgs)
			VALUES (0,:macro,:args)";
				
	$stmt = $this->_db->prepare($sql);
	//$stmt->bindParam(':fld', $fld, PDO::PARAM_STR);
	$stmt->bindParam(':macro', $macro, PDO::PARAM_INT);
	$stmt->bindParam(':args', $args, PDO::PARAM_STR);
	$stmt->execute();

}
 
 
 /* To delete adter cron is done
  * 
  */

function GetFieldDetails($fieldid)
{
	$sql = "SELECT *
    		FROM `wb_roi_fields`
    		WHERE fieldID=:fieldid";
	$stmt = $this->_db->prepare($sql);
	$stmt->bindParam(':fieldid', $fieldid, PDO::PARAM_INT);
	$stmt->execute();
	$data = $stmt->fetch();
	return $data;
	
	
}

 
// ex: lookup a customer name based on $customerid
//$name = DLookup("Name", "Customer", "Id=$customerid");
function getMacroAurguments($macroid){
	
	$sql = "SELECT varID, varValue
    		FROM `wb_roi_reports_macros_aurguments`
    		WHERE usedMacroID=:macroid
    		ORDER BY varID ASC";
	$stmt = $this->_db->prepare($sql);
	$stmt->bindParam(':macroid', $macroid, PDO::PARAM_INT);
	$stmt->execute();
	$data = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
	return $data;
}

function getMarketoMapping($instanceID,$mkID){
	
	$sql = "SELECT *, 
				(SELECT value FROM wb_roi_instance_values t2 WHERE t2.field=t1.fieldID AND t2.instanceID=:instanceID LIMIT 1) nonformattedvalue,
				(SELECT formatted_value FROM wb_roi_instance_values t2 WHERE t2.field=t1.fieldID AND t2.instanceID=:instanceID LIMIT 1) formattedvalue,
				(SELECT CASE (t1.standard)
					WHEN 0 THEN ((SELECT CASE (t1.formatted)
									WHEN 0 THEN (SELECT value FROM wb_roi_instance_values t2 WHERE t2.field=t1.fieldID AND t2.instanceID=:instanceID LIMIT 1)
									ELSE (SELECT formatted_value FROM wb_roi_instance_values t2 WHERE t2.field=t1.fieldID AND t2.instanceID=:instanceID LIMIT 1) 
									END
									FROM wb_roi_instance_values LIMIT 1))
					ELSE (SELECT value FROM wb_roi_instance_values_standard t3 WHERE t3.stdfieldID=t1.fieldID AND t3.instanceID=:instanceID LIMIT 1)
					END
					FROM wb_roi_instance_values LIMIT 1) selectedvalue				
			FROM wb_marketo_fieldmapping t1
			WHERE mkID=:mkID";
	$stmt = $this->_db->prepare($sql);
	$stmt->bindParam(':mkID', $mkID, PDO::PARAM_INT);
	$stmt->bindParam(':instanceID', $instanceID, PDO::PARAM_INT);
	$stmt->execute();
	$data = $stmt->fetchAll();
	return $data;
}

function getMarketoConnection($mkID){
	$sql = "SELECT * 
			FROM wb_marketo_connections
			WHERE mkID=:mkID
			LIMIT 1";
	$stmt = $this->_db->prepare($sql);
	$stmt->bindParam(':mkID', $mkID, PDO::PARAM_INT);
	$stmt->execute();
	//$data = $stmt->fetchAll(PDO::FETCH_GROUP);
	$data = $stmt->fetch();
	return $data;
}

function StorePDFNameValue($instanceID,$filename){
	$sql = "INSERT INTO wb_roi_instance_values_standard 
			(instanceID,stdfieldID,value)
			VALUES (:instanceid,17,:reportname)";
				
	$stmt = $this->_db->prepare($sql);
	//$stmt->bindParam(':fld', $fld, PDO::PARAM_STR);
	$stmt->bindParam(':instanceid', $instanceID, PDO::PARAM_INT);
	$stmt->bindParam(':reportname', $filename, PDO::PARAM_STR);
	$stmt->execute();
	
	return $filename;
}

function returnarray($sql){
	$stmt = $this->_db->prepare($sql);
	$stmt->execute();
	$data = $stmt->fetchall();

	return $data;
}

function DMax($fld, $tab)
{
	$func_num_args = func_num_args();
	$func_get_args = func_get_args();
	if($func_num_args>2){
		$whr = $func_get_args[2];
		$whr = " where " . $whr;
	}else {$whr="";}
	
    $q = "Select MAX($fld) as maxfld from $tab" . $whr;
    $stmt = $this->_db->prepare($q);
	$stmt->execute();
	$data = $stmt->fetch();

	return $data[0];
}

function DMin($fld, $tab)
{
	$func_num_args = func_num_args();
	$func_get_args = func_get_args();
	if($func_num_args>2){
		$whr = $func_get_args[2];
		$whr = " where " . $whr;
	}else {$whr="";}
	
    $q = "Select MIN($fld) as minfld from $tab" . $whr;
    $stmt = $this->_db->prepare($q);
	
	$stmt->execute();
	$data = $stmt->fetch();

	return $data[0];
}


function DCount($fld, $tab)
{
	$func_num_args = func_num_args();
	$func_get_args = func_get_args();
	if($func_num_args>2){
		$whr = $func_get_args[2];
		$whr = " where " . $whr;
	}else {$whr="";}
	
    $q = "Select COUNT($fld) as countfld from $tab" . $whr;
    $stmt = $this->_db->prepare($q);
	
	$stmt->execute();
	$data = $stmt->fetch();

	return $data[0];
}

function DCountDistinct($fld, $tab)
{
	$func_num_args = func_num_args();
	$func_get_args = func_get_args();
	if($func_num_args>2){
		$whr = $func_get_args[2];
		$whr = " where " . $whr;
	}else {$whr="";}
	
    $q = "Select COUNT(DISTINCT $fld) as countfld from $tab" . $whr;
    $stmt = $this->_db->prepare($q);
	
	$stmt->execute();
	$data = $stmt->fetch();

	return $data[0];
}




function GetWbroiIDFromInstanceID($instanceID){
	$sql = "SELECT wbroiID 
			FROM wb_roi_instance 
			WHERE instanceID=:instanceID LIMIT 1";
	$stmt = $this->_db->prepare($sql);
	$stmt->bindParam(':instanceID', $instanceID, PDO::PARAM_INT);
	$stmt->execute();
	$data = $stmt->fetch();
	return $data[0];
}

function CountRequiredNotCompleted($macroID,$instanceID){
	
	//Determine if there are any records for this specific macro in the wb_roi_reports_macros_requiredfield table
	//If not any field marked required is checked for completion
	
	$sql = "SELECT COUNT(1)
			FROM `wb_roi_reports_macros_requiredfields`
			WHERE macroID=:macroID";
	$stmt = $this->_db->prepare($sql);
	$stmt->bindParam(':macroID', $macroID, PDO::PARAM_INT);
	$stmt->execute();
	$data = $stmt->fetchColumn();
	$specified_count = $data[0];
	
	if ($specified_count==0) {
	
	$sql = "SELECT COUNT(1) 
			FROM wb_roi_instance_values t1 
			WHERE t1.instanceID=:instanceID 
				AND t1.field IN(SELECT t2.fieldID 
								FROM wb_roi_fields t2 
								WHERE wb_roi_ID=(SELECT wbroiID 
												FROM wb_roi_instance 
												WHERE instanceID=:instanceID 
												LIMIT 1) 
								AND required=1) 
				AND (t1.formatted_value='' 
					OR (t1.formatted_value IS NULL))";
	$stmt = $this->_db->prepare($sql);
	$stmt->bindParam(':instanceID', $instanceID, PDO::PARAM_INT);
	$stmt->execute();
	$data = $stmt->fetchColumn();
	$resultdata = $data[0];
	}
	else 
		
	{
		$sql = "SELECT COUNT(1)
			FROM wb_roi_reports_macros_requiredfields t1
			WHERE t1.macroID =:macroID
			AND ((SELECT t3.formatted_value 
					FROM wb_roi_instance_values t3 
					WHERE t3.instanceID=:instanceID
			AND t1.fieldID=t3.field)='' 
				OR (SELECT formatted_value 
					FROM wb_roi_instance_values t2 
					WHERE t2.instanceID=:instanceID
					AND t1.fieldID=t2.field) IS NULL)";
	$stmt = $this->_db->prepare($sql);
	$stmt->bindParam(':macroID', $macroID, PDO::PARAM_INT);
	$stmt->bindParam(':instanceID', $instanceID, PDO::PARAM_INT);
	$stmt->execute();
	$data = $stmt->fetchColumn();
	$resultdata = $data[0];
	}
	
	return $resultdata;
}


function dateDiff($start, $end) {
  $start_ts = strtotime($start);
  $end_ts = strtotime($end);
  $diff = $end_ts - $start_ts;
  return round($diff / 86400);
}

function mscalc($date){
	$msdate = strtotime();
}


function IsNullDate($date){
	$isnull=false;
	if($date=='0000-00-00'){$isnull=true;}
	if($date=='0000-00-00 00:00:00'){$isnull=true;} 
	if(strlen($date)==0){$isnull=true;} 
	return ($isnull) ? true : false;
}

function nextauto($tablename){
	$result = mysql_query("
    SHOW TABLE STATUS LIKE '$tablename'
		");
	$data = mysql_fetch_assoc($result);
	$next_increment = $data['Auto_increment'];
	return $next_increment;
}

function shortdate($date){
	if($date=='0000-00-00'||$date==''){return '';} else {
	$date = strtotime($date);
	return date("m/d/y", $date);}
}



function insertdate($data,$year='20',$add=0){
	$date=explode('/',$data);
	$xplode=explode('/',$data);
	$xplode = preg_split('/[-]|[\s]/',$data);
	$string = "$xplode[2]-$xplode[0]-$xplode[1]";
	//if($date[2]<50){$date[2]=="20$date[2]";} else {$date[2]=="19$date[2]";}
	if (strlen($date[2])==2){$date[2]=$year . $date[2];}
	if(strlen($date[1])<2){$date[1] = '0'.$date[1];}
	if(strlen($date[0])<2){$date[0] = '0'.$date[0];}
	$date[1] = $date[1]+$add;
	$insertdate = "$date[2]-$date[0]-$date[1]";
	return $insertdate;
}



function getselectboxvalues($SQL1,$field,$rptfield,$showblank=true){
	
	$records99=array();
	$rowcount=0;
	$box = array();
	$box[$rowcount][0] = '<option value="0">';
	$box[$rowcount][1] = '</option>';
	$box[$rowcount][2] = '';
	
	$result = mysql_query($SQL1);
	
	if($showblank!=true){$rowcount=-1;}
	
	while ($row = mysql_fetch_object($result))
	  {
	  	$rowcount = $rowcount + 1/1;
		$box[$rowcount][0] = '<option value="' . $row->$field . '"';
		$box[$rowcount][1] = '>' . $row->$rptfield . '</option>';
		$box[$rowcount][2] = $row->$field;
		//print_r($box);
	  }

	return $box;
	
}

function popselectbox($box,$value){
		$strng = '';
	for ($i=0; $i<count($box);$i++){
		$strng = $strng . $box[$i][0];
		if($box[$i][2]==$value){$strng = $strng .' selected';}
		$strng = $strng . $box[$i][1];
	}
	
	return $strng;
	
}

}

?>