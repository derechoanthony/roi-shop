<?php

/**
 * Handles log in actions for The ROI Shop
 * 
 * PHP version 5
 **/
 
class RoiStyles
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
	
	public function retrieveRoiStyles()
	{
		$sql = "SELECT * FROM roi_styles
				WHERE company_id = (
					SELECT compStructure FROM list_items
					WHERE ListItemID=:roi
				)";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;
	}

}	