<?php

/**
 * Handles log in actions for The ROI Shop
 * 
 * PHP version 5
 **/
 
class DashboardActions
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
	
	public function retrieveUserCompanySpecs()
	{
		$sql = "SELECT * FROM comp_specs
				WHERE compID IN (
					SELECT compID FROM user_comps
					WHERE UserID = (
						SELECT UserID FROM users
						WHERE Username=:user
					)
				)
				ORDER BY compName ASC";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':user', $_SESSION['Username'], PDO::PARAM_STR);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;
	}
	
	public function retrieveUserCompanyPermissions()
	{
		$sql = "SELECT * FROM user_comps
				INNER JOIN comp_specs
				ON user_comps.CompID=comp_specs.compID
				WHERE user_comps.UserID = (
					SELECT UserID FROM users
					WHERE Username=:user
				)";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':user', $_SESSION['Username'], PDO::PARAM_STR);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;
	}
	
	public function retrieveUsersSavedRois()
	{
		$sql = "SELECT * FROM list_items
				INNER JOIN comp_specs
				ON list_items.compStructure=comp_specs.compID
				WHERE list_items.ListID = (
					SELECT UserID FROM users
					WHERE Username=:user
				)
				ORDER BY ListItemPosition";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':user', $_SESSION['Username'], PDO::PARAM_STR);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;
	}
	
	public function retrieveCompanyUsers()
	{
		$sql = "SELECT * FROM users
				WHERE compName = (
					SELECT compName FROM users
					WHERE Username = :user
				)";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':user', $_SESSION['Username'], PDO::PARAM_STR);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;
	}
	
	public function retrieveUserSpecs()
	{
		$sql = "SELECT * FROM users
				WHERE Username = :user";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':user', $_SESSION['Username'], PDO::PARAM_STR);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;
	}

}	