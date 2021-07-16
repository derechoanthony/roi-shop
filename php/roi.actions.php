<?php

/**
 * Handles log in actions for The ROI Shop
 * 
 * PHP version 5
 **/
 
class RoiActions
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
	
	public function retrieveRoiSpecs()
	{
		$sql = "SELECT * FROM comp_specs
				WHERE compID = (
					SELECT compStructure FROM list_items
					WHERE ListItemID = :roi
				)";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetch();
		return $data;
	}
	
	public function retrieveRoiDashboard()
	{
		$sql = "SELECT * FROM roi_dashboard
				WHERE company_id = (
					SELECT company_id FROM roi_items
					WHERE id = :roi
				)";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetch();
		return $data;
	}
	
	public function retrieveRoiSummary()
	{
		$sql = "SELECT * FROM roi_summary
				WHERE company_id = (
					SELECT company_id FROM roi_items
					WHERE id = :roi
				)";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetch();
		return $data;
	}
	
	public function retrieveRoiCosts()
	{
		$sql = "SELECT * FROM costs
				WHERE roiID = (
					SELECT compStructure FROM list_items
					WHERE ListItemID = :roi
				)";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetch();
		return $data;
	}
	
	public function retrieveRoiNotes()
	{
		$sql = "SELECT * FROM section_notes
				WHERE roiid = :roi;";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi',$_GET['roi'],PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;
	}

}	

?>