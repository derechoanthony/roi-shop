<?php

/**
 * Handles log in actions for The ROI Shop
 * 
 * PHP version 5
 **/
 
class DiscoveryActions
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
	
	public function retrieveDiscoveryQuestions()
	{
		$sql = "SELECT * FROM discovery_questions
				WHERE discovery_id IN (
					SELECT id FROM discovery_document
					WHERE company_id = (
						SELECT compStructure FROM list_items
						WHERE ListItemID=:roi
					)
				)
				ORDER BY position;";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;
	}
	public function retrieveDiscoveryQuestionsForSalesForse($roiId)
	{
		$sql = "SELECT * FROM discovery_questions
				WHERE discovery_id IN (
					SELECT id FROM discovery_document
					WHERE company_id = (
						SELECT compStructure FROM list_items
						WHERE ListItemID=:roi
					)
				)
				ORDER BY position;";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi',$roiId, PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;
	}
	public function retrieveDiscoveryQuestionsFromCompStructure($compid)
	{
		$sql = "SELECT * FROM discovery_questions
				WHERE discovery_id IN (
					SELECT id FROM discovery_document
					WHERE company_id = :roi
				)
				ORDER BY position;";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi', $compid, PDO::PARAM_INT);
		$stmt->execute();
	//	$stmt->debugDumpParams();
		$data = $stmt->fetchall();
		
		return $data;
	}
	public function retrieveDiscoveryDocuments()
	{
		$sql = "SELECT * FROM discovery_document
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
	public function retrieveDiscoveryDocumentsFromCompStructure($compid)
	{
		$sql = "SELECT * FROM discovery_document
				WHERE company_id = :roi";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi', $compid, PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;
	}

}	