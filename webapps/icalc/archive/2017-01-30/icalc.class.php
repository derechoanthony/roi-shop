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
				('$wbroiID','$ip','$status')";
								
		$stmt = $this->_db->prepare($sql);
		$stmt->execute();
		
	}
	
	//Function that adds an IP address to the wb_roi_instance table
	public function update_ip($instanceID,$country,$state,$city,$lat,$long){

		$sql = "UPDATE wb_roi_instance
				SET `country`='$country',
					`stateprov`='$state',
					`city`='$city',
					`lat`=$lat,
					`long`=$long
				WHERE `instanceID`=$instanceID;";
								
		$stmt = $this->_db->prepare($sql);
		$stmt->execute();
		
		return $sql;
	}
	
	//Function that adds an IP address to the wb_roi_instance table
	public function update_stdvalues($instanceID,$ip,$country,$state,$city,$lat,$long){

		$sql = "INSERT INTO wb_roi_instance_values_standard
				(`instanceID`,`stdfieldID`,`value`)
				VALUES
				($instanceID,1,'$ip')";
								
		$stmt = $this->_db->prepare($sql);
		$stmt->execute();
		
		$sql = "INSERT INTO wb_roi_instance_values_standard
				(`instanceID`,`stdfieldID`,`value`)
				VALUES
				($instanceID,2,'$country')";
								
		$stmt = $this->_db->prepare($sql);
		$stmt->execute();
		
		$sql = "INSERT INTO wb_roi_instance_values_standard
				(`instanceID`,`stdfieldID`,`value`)
				VALUES
				($instanceID,3,'$state')";
								
		$stmt = $this->_db->prepare($sql);
		$stmt->execute();
		
		$sql = "INSERT INTO wb_roi_instance_values_standard
				(`instanceID`,`stdfieldID`,`value`)
				VALUES
				($instanceID,4,'$city')";
								
		$stmt = $this->_db->prepare($sql);
		$stmt->execute();
		
		$sql = "INSERT INTO wb_roi_instance_values_standard
				(`instanceID`,`stdfieldID`,`value`)
				VALUES
				($instanceID,5,NOW())";
								
		$stmt = $this->_db->prepare($sql);
		$stmt->execute();
		
		$sql = "INSERT INTO wb_roi_instance_values_standard
				(`instanceID`,`stdfieldID`,`value`)
				VALUES
				($instanceID,6,NOW())";
								
		$stmt = $this->_db->prepare($sql);
		$stmt->execute();
		
		$sql = "INSERT INTO wb_roi_instance_values_standard
				(`instanceID`,`stdfieldID`,`value`)
				VALUES
				($instanceID,7,$lat)";
								
		$stmt = $this->_db->prepare($sql);
		$stmt->execute();
		
		$sql = "INSERT INTO wb_roi_instance_values_standard
				(`instanceID`,`stdfieldID`,`value`)
				VALUES
				($instanceID,8,$long)";
								
		$stmt = $this->_db->prepare($sql);
		$stmt->execute();
		return $sql;
	}
	
	//Function that gets the ID of the instance that was just added with a particular IP
	public function get_id($wbroiID,$ip){

		$sql = "SELECT instanceID 
				FROM wb_roi_instance
				WHERE `IP` = '$ip'
					AND `wbroiID` = '$wbroiID'
				ORDER BY dateCreated DESC
				LIMIT 1";
								
		$stmt = $this->_db->prepare($sql);
		$stmt->execute();
		$instanceID = $stmt->fetch();
		return $instanceID;
		
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
	
	public function retrieveCompleteRoiStructure() {
		
		$sql = "SELECT * 
				FROM tbl_roi_pages t1 
				FULL OUTER JOIN tbl_roi_sections t2
				ON t1.roiVersionID = t2.roiVersionID
				WHERE roiVersionID = :roiVersionID
				ORDER BY t1.pagePosition, t2.sectionPosition;";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roiVersionID', $_GET['roiVersionID'], PDO::PARAM_INT);
		$stmt->execute();
		$roiSectionPages = $stmt->fetchall();
		return $roiSectionPages;				
	}
	
	
	public function retrieveRoiSectionPages() {
		
		$sql = "SELECT roiPageID, roiPageName 
				FROM tbl_roi_pages
				WHERE roiVersionID = :roiVersionID
				ORDER BY pagePosition;";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roiVersionID', $_GET['roiVersionID'], PDO::PARAM_INT);
		$stmt->execute();
		$roiSectionPages = $stmt->fetchall();
		return $roiSectionPages;				
	}
	public function retrieveRoiSections($roiPageID){
		
		$sql = "SELECT * 
				FROM tbl_roi_sections
				WHERE roiPageID = :roiPageID
				ORDER BY sectionPosition;";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roiPageID', $roiPageID, PDO::PARAM_INT);
		$stmt->execute();
		$roiPageSections = $stmt->fetchall();
		return $roiPageSections;		
	}

	public function retrieveRoiSectionsElements($sectionID){
		
		$sql = "SELECT * 
				FROM tbl_roi_section_elements
				WHERE sectionID = :sectionID AND parent_elementID=0
				ORDER BY elementOrder;";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':sectionID', $sectionID, PDO::PARAM_INT);
		$stmt->execute();
		$SectionElements = $stmt->fetchall();
		return $SectionElements;		
	}
	
	public function retrieveRoiSubSectionsElements($parentID){
		
		$sql = "SELECT * 
				FROM tbl_roi_section_elements
				WHERE parent_elementID = :parentID
				ORDER BY elementOrder;";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':parentID', $parentID, PDO::PARAM_INT);
		$stmt->execute();
		$SubSectionElements = $stmt->fetchall();
		return $SubSectionElements;		
	}
		
		
	public function retrieveRoiColumnProps($refID){
		
		$sql = "SELECT * 
				FROM tbl_elem_column
				WHERE elementID = :elementID
				ORDER BY columnPropID;";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':elementID', $refID, PDO::PARAM_INT);
		$stmt->execute();
		$ColumnProps = $stmt->fetchall();
		return $ColumnProps;		
	}
			
		
	public function retrieveRoiSectionRows($sectionID){
		
		$sql = "SELECT * 
				FROM tbl_roi_section_rows
				WHERE sectionID = :sectionID
				ORDER BY rowPosition;";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':sectionID', $sectionID, PDO::PARAM_INT);
		$stmt->execute();
		$roiSectionRows = $stmt->fetchall();
		return $roiSectionRows;		
	}
	
	public function retrieveRoiRowColumns($rowID){
		
		$sql = "SELECT * 
				FROM tbl_roi_section_row_columns
				WHERE rowID = :rowID
				ORDER BY columnOrder;";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':rowID', $rowID, PDO::PARAM_INT);
		$stmt->execute();
		$roiRowColumns = $stmt->fetchall();
		return $roiRowColumns;		
	}
	
	public function retrieveRoiSectionTitle($sectionId){
		
		$sql = "SELECT title, formula, format FROM section_title
				WHERE roi_section_id = :roi_section_id
				LIMIT 1;";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi_section_id', $sectionId, PDO::PARAM_INT);
		$stmt->execute();
		$roiSectionTitle = $stmt->fetch();
		return $roiSectionTitle;		
	}
	
	public function retrieveSectionElements($sectionId){
		
		$sql = "SELECT * FROM roi_section_elements
				WHERE roi_section_id = :roi_section_id AND parent = 0
				ORDER BY position;";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi_section_id', $sectionId, PDO::PARAM_INT);
		$stmt->execute();
		$roiSectionElements = $stmt->fetchall();
		return $roiSectionElements;		
	}
	
	public function retrieveSectionSubElements($sectionId){
		
		$sql = "SELECT * FROM roi_section_elements
				WHERE parent = :parent
				ORDER BY position;";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':parent', $sectionId, PDO::PARAM_INT);
		$stmt->execute();
		$roiSectionSubElements = $stmt->fetchall();
		return $roiSectionSubElements;		
	}
	
	public function retrieveInfoPanel($refId){
		
		$sql = "SELECT * FROM info_panel
				WHERE element_id = :reference_id
				LIMIT 1;";

		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':reference_id', $refId, PDO::PARAM_INT);
		$stmt->execute();
		$infoPanel = $stmt->fetch();
		return $infoPanel;				
	}
	
	public function retrieveVideoSpecs($refId){
		
		$sql = "SELECT source FROM videos
				WHERE element_id = :reference_id
				LIMIT 1;";

		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':reference_id', $refId, PDO::PARAM_INT);
		$stmt->execute();
		$videoSource = $stmt->fetch();
		return $videoSource;		
	}
	
	public function retrieveCalculationElementSpecs($refId){
		
		$sql = "SELECT * FROM calculation_elements
				WHERE element_id = :reference_id
				LIMIT 1;";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':reference_id', $refId, PDO::PARAM_INT);
		$stmt->execute();
		$inputSpecs = $stmt->fetch();
		return $inputSpecs;
	}
	
	public function retrieveTextElementSpecs($refId){
		
		$sql = "SELECT * FROM text_elements
				WHERE element_id = :reference_id
				LIMIT 1;";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':reference_id', $refId, PDO::PARAM_INT);
		$stmt->execute();
		$textSpecs = $stmt->fetch();
		return $textSpecs;
	}
	
	public function retrieveProgressSpecs($refId){
		
		$sql = "SELECT * FROM progress_element
				WHERE element_id = :reference_id
				LIMIT 1;";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':reference_id', $refId, PDO::PARAM_INT);
		$stmt->execute();
		$progressSpecs = $stmt->fetch();
		return $progressSpecs;				
	}
	
	public function retrieveElementOptions($refId){
		
		$sql = "SELECT * FROM calculation_element_options
				WHERE element_id = :reference_id
				ORDER BY position;";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':reference_id', $refId, PDO::PARAM_INT);
		$stmt->execute();
		$elementOptions = $stmt->fetchall();
		return $elementOptions;
	}
	
	public function retrieveToggleOptions($refId){
		
		$sql = "SELECT * FROM toggle_options
				WHERE element_id = :reference_id
				LIMIT 1;";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':reference_id', $refId, PDO::PARAM_INT);
		$stmt->execute();
		$elementOptions = $stmt->fetch();
		return $elementOptions;
	}	
	
	public function retrieveUnits($refId) {
		
		$sql = "SELECT * FROM element_units
				WHERE element_id = :reference_id;";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':reference_id', $refId, PDO::PARAM_INT);
		$stmt->execute();
		$units = $stmt->fetchall();
		return $units;			
	}

}	