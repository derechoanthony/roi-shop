<?php

class CalculatorActions
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
	
	public function retrieveRoiHtml()
	{
		$sql = "SELECT roi_html FROM roi_html
				WHERE roi_item_id = :roi
				ORDER BY roi_html_id DESC LIMIT 1;";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		
		$roiHtml = $stmt->fetch();
		
		return $roiHtml['roi_html'];
	}

}	
	
?>