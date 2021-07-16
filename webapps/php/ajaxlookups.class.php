<?php

/**
 * Handles log in actions for The ROI Shop
 * 
 * PHP version 5
 **/
 
class lookups
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
	

	
	public function getoptionvals ($wbroiID, $tableID, $col, $ordercol) {
	
	$ordercol = (isset($ordercol) ? 'ORDER BY cellvalue ASC' : '');
	
	
	$sql = "SELECT `cellvalue` 
    		FROM `wb_roi_table_values` t1
    		WHERE wbroiID=:wbroiID AND tableID=:tableID AND col=:col
			" . $ordercol . ";";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':wbroiID', $wbroiID, PDO::PARAM_INT);
		$stmt->bindParam(':tableID', $tableID, PDO::PARAM_INT);
		$stmt->bindParam(':col', $col, PDO::PARAM_STR);
		$stmt->execute();
		$data = $stmt->fetchall();
	
		return $data;
		
		
	}
	
	public function getlookupval ($wbroiID, $tableID, $lookupvalue,$lookupcol, $lookupvalcol) {
	

	$sql = "SELECT `row` 
    		FROM `wb_roi_table_values` t1
    		WHERE t1.wbroiID=:wbroiID AND t1.tableID=:tableID AND t1.col=:lookupcol AND t1.cellvalue=:lookupvalue
			LIMIT 1;";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':wbroiID', $wbroiID, PDO::PARAM_INT);
		$stmt->bindParam(':tableID', $tableID, PDO::PARAM_INT);
		$stmt->bindParam(':lookupcol', $lookupcol, PDO::PARAM_STR);
		$stmt->bindParam(':lookupvalue', $lookupvalue, PDO::PARAM_STR);
		$stmt->execute();
		$data = $stmt->fetch();
	
		$row = $data[0];
	
	$sql = "SELECT `cellvalue` 
    		FROM `wb_roi_table_values` t1
    		WHERE t1.wbroiID=:wbroiID AND t1.tableID=:tableID AND t1.col=:lookupvalcol AND t1.row=:row
			LIMIT 1;";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':wbroiID', $wbroiID, PDO::PARAM_INT);
		$stmt->bindParam(':tableID', $tableID, PDO::PARAM_INT);
		$stmt->bindParam(':lookupvalcol', $lookupvalcol, PDO::PARAM_STR);
		$stmt->bindParam(':row', $row, PDO::PARAM_STR);
		$stmt->execute();
		$data = $stmt->fetch();
	
		$returnval = $data[0];
		
		return $returnval;
		
	}
	


}	