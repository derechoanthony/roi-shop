<?php

/**
 * Handles log in actions for The ROI Shop
 * 
 * PHP version 5
 **/
 
class DesignActions
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
	
	public function checkAdminPermissions() {
		
		$sql = "SELECT * FROM user_comps
				WHERE CompID = :comp AND UserID = (
					SELECT UserID FROM users
					WHERE Username = :user
				)";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':comp', $_GET['comp'], PDO::PARAM_INT);
		$stmt->bindParam(':user', $_SESSION['Username'], PDO::PARAM_STR);
		$stmt->execute();
		$data = $stmt->fetch();
		return $data;
	}
	
	public function retrieveRoiSpecs() {
		
		$sql = "SELECT * FROM comp_specs
				WHERE compID = :comp";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':comp', $_GET['comp'], PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetch();
		return $data;
	}
	
	public function retrieveRoiSections() {
		
		$sql = "SELECT * FROM compsections
				WHERE compID = :comp
				ORDER BY Position";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':comp', $_GET['comp'], PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;
	}
	
	public function retrieveTestimonials() {
		
		$sql = "SELECT * FROM testimonials
				WHERE company_id = :comp";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':comp', $_GET['comp'], PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;
	}
	
	public function retrieveEntries($sectionid) {
		
		$sql = "SELECT * FROM entry_fields
				WHERE sectionName = :section AND roiID = :comp
				ORDER BY position;";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':comp', $_GET['comp'], PDO::PARAM_INT);
		$stmt->bindParam(':section', $sectionid, PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;
	}
	
	public function adminPrivleges() {
		
		$sql = "SELECT * FROM comp_specs
				WHERE compID IN (
					SELECT compID FROM user_comps
					WHERE UserID = (
						SELECT UserID FROM users
						WHERE Username=:user
					) AND permission = 1
				)
				ORDER BY compName ASC";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':user', $_SESSION['Username'], PDO::PARAM_STR);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;		
	}
	
	public function retrievePdfPages() {
		
		$sql = "SELECT * FROM pdf_specs
				WHERE roi = :comp;";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':comp', $_GET['comp'], PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;
	}
	
	public function maxPdfPages() {
		
		$sql = "SELECT MAX(pageno) AS MaxPages FROM pdf_specs
				WHERE roi = :comp;";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':comp', $_GET['comp'], PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetch();
		return $data;
	}
	
	public function retrieveDiscoveryDocuments() {
		
		$sql = "SELECT * FROM discovery_document
				WHERE company_id = :comp";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':comp', $_GET['comp'], PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;
	}
	
	public function retrieveDiscoveryQuestions($discoveryid) {
		
		$sql = "SELECT * FROM discovery_questions
				WHERE discovery_id = :discovery
				ORDER BY position;";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':discovery', $discoveryid, PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;
	}

}	