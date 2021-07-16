<?php

/**
 * Handles log in actions for The ROI Shop
 * 
 * PHP version 5
 **/
 
class icalc
{
	
	private $data = array();
	
	//function that allows for getting and setting variables
	//in this class by calling
	//getVariableName(); or setVariableName($variable);
    public function __call($name, $arguments){
        switch(substr($name, 0, 3)){
            case 'get':
                if(isset($this->data[substr($name, 3)])){
                    return $this->data[substr($name, 3)];
                }else{
                    die('Unknown variable1.');
                }
            break;
            case 'set':
                $this->data[substr($name, 3)] = $arguments[0];
                return $this;
            break;
            default: 
                die('Unknown method1.');
        }
    }
	
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
	
	//Function that adds an IP address to the wb_roi_instance table
	public function add_ip($wbroiID,$ip,$status){

		$sql = "INSERT INTO wb_roi_instance
				(`wbroiID`,`IP`,`status`)
				VALUES 
				(:wbroiID,:ip,:status)";
								
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':wbroiID', $wbroiID, PDO::PARAM_INT);
		$stmt->bindParam(':ip', $ip, PDO::PARAM_STR);
		$stmt->bindParam(':status', $status, PDO::PARAM_INT);
		$stmt->execute();
		
		
		//Return the value of the ID
		$last_id = $this->_db->lastInsertId();
		return $last_id;
		
		
	}
	
	
	public function get_roi_fields($wbroiID){
		$sql = "SELECT * , (SELECT format FROM wb_formats_fields t2 WHERE t2.fieldID=t1.fieldID) formatstring
    		FROM `wb_roi_fields` t1
    		WHERE wb_roi_ID=:wbroiID;";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':wbroiID', $wbroiID, PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetchall();
	
		return $data;
		
	}
	
	//Function that adds an IP address to the wb_roi_instance table
	public function update_ip($instanceID,$country,$state,$city,$lat,$long){

		$sql = "UPDATE wb_roi_instance
				SET `country`=':country',
					`stateprov`=':state',
					`city`=':city',
					`lat`=:lat,
					`long`=:long
				WHERE `instanceID`=:instanceID;";
								
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':instanceID', $instanceID, 	PDO::PARAM_INT);
		$stmt->bindParam(':country', 	$country, 		PDO::PARAM_STR);
		$stmt->bindParam(':state', 		$state, 		PDO::PARAM_STR);
		$stmt->bindParam(':city', 		$city, 			PDO::PARAM_STR);
		$stmt->bindParam(':lat', 		$lat, 			PDO::PARAM_STR);
		$stmt->bindParam(':long', 		$long, 			PDO::PARAM_STR);
		$stmt->execute();
		
		return $sql;
	}
	
	//Function that adds an IP address to the wb_roi_instance table
	public function update_stdvalues($instanceID,$ip,$country,$state,$city,$lat,$long){

		$sql = "INSERT INTO wb_roi_instance_values_standard
				(`instanceID`,`stdfieldID`,`value`)
				VALUES
				(:instanceID,1,:ip)";
								
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':instanceID', $instanceID, 	PDO::PARAM_INT);
		$stmt->bindParam(':ip', 		$ip, 			PDO::PARAM_STR);
		$stmt->execute();
		
		$sql = "INSERT INTO wb_roi_instance_values_standard
				(`instanceID`,`stdfieldID`,`value`)
				VALUES
				(:instanceID,2,:country)";
								
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':instanceID', 	$instanceID, 		PDO::PARAM_INT);
		$stmt->bindParam(':country', 		$country, 			PDO::PARAM_STR);
		$stmt->execute();
		
		$sql = "INSERT INTO wb_roi_instance_values_standard
				(`instanceID`,`stdfieldID`,`value`)
				VALUES
				(:instanceID,3,:state)";
								
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':instanceID', 	$instanceID, 		PDO::PARAM_INT);
		$stmt->bindParam(':state', 			$state, 			PDO::PARAM_STR);
		$stmt->execute();
		
		$sql = "INSERT INTO wb_roi_instance_values_standard
				(`instanceID`,`stdfieldID`,`value`)
				VALUES
				(:instanceID,4,:city)";
								
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':instanceID', 	$instanceID, 		PDO::PARAM_INT);
		$stmt->bindParam(':city', 			$city, 				PDO::PARAM_STR);
		$stmt->execute();
		
		$sql = "INSERT INTO wb_roi_instance_values_standard
				(`instanceID`,`stdfieldID`,`value`)
				VALUES
				(:instanceID,5,NOW())";
								
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':instanceID', 	$instanceID, 		PDO::PARAM_INT);
		$stmt->execute();
		
		$sql = "INSERT INTO wb_roi_instance_values_standard
				(`instanceID`,`stdfieldID`,`value`)
				VALUES
				(:instanceID,6,NOW())";
								
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':instanceID', 	$instanceID, 		PDO::PARAM_INT);
		$stmt->execute();
		
		$sql = "INSERT INTO wb_roi_instance_values_standard
				(`instanceID`,`stdfieldID`,`value`)
				VALUES
				(:instanceID,7,:lat)";
								
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':instanceID', 	$instanceID, 		PDO::PARAM_INT);
		$stmt->bindParam(':lat', 			$lat, 				PDO::PARAM_STR);
		$stmt->execute();
		
		$sql = "INSERT INTO wb_roi_instance_values_standard
				(`instanceID`,`stdfieldID`,`value`)
				VALUES
				(:instanceID,8,:long)";
								
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':instanceID', 	$instanceID, 		PDO::PARAM_INT);
		$stmt->bindParam(':long', 			$long, 				PDO::PARAM_STR);
		$stmt->execute();
		return $sql;
	}
	

	
	// Function to get the client IP address
	function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';

   return $ipaddress;
}
	

	public function retrieveRoiSpecificReport($wbroiID,$reportID){
		
		$sql = "SELECT *
				FROM wb_roi_list t1
				JOIN wb_roi_reports t2 ON t1.wb_roi_ID = t2.wb_roi_ID 
				WHERE t1.wb_roi_ID = :wbroiID
					AND t2.wb_roi_report_ID= :reportID
				ORDER BY t2.dateCreated DESC
				LIMIT 1;";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':wbroiID', $wbroiID, PDO::PARAM_INT);
		$stmt->bindParam(':reportID', $reportID, PDO::PARAM_INT);
		$stmt->execute();
		$roi = $stmt->fetch();
		return $roi;
		
		//setroiName($roi['roiName']);
		//echo $this->$roiName;
				
	}
	
		
	public function retrieveRoi($wbroiID){
		
		$sql = "SELECT *
				FROM wb_roi_list t1
				JOIN wb_roi_reports t2 ON t1.wb_roi_ID = t2.wb_roi_ID 
				WHERE t1.wb_roi_ID = :wbroiID
					AND t2.roiReportType=0
					AND t2.isprimary=1
				ORDER BY t2.dateCreated DESC
				LIMIT 1;";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':wbroiID', $wbroiID, PDO::PARAM_INT);
		$stmt->execute();
		$roi = $stmt->fetch();
		return $roi;
		
		//setroiName($roi['roiName']);
		//echo $this->$roiName;
				
	}
	


}	