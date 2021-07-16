<?php

/**
 * Handles log in actions for The ROI Shop
 * 
 * PHP version 5
 **/
 
class ChartFunctions
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
	
	//Get the Fonts that are available for general use
	public function retrieveFonts()
	{
		$sql = "SELECT * FROM tbl_ref_fonts
				ORDER BY FontName";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;
	}

	//Get the currently stored chart options
	//Requires a chartID variable in the URL
	public function retrieveChartOpts()
	{
		
		$sql = "SELECT * FROM `tbl_charts_list` t1 
				JOIN `tbl_charts_options_general` t2 
				ON t1.chartID = t2.chartID 
				WHERE t1.chartID = :chartID;";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':chartID', $_GET['chartID'] , PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetch();
		
		return $data;
	}
	
	//Get a list of companies 
	//Can be removed once companies are no longer used to reference ROIs
		public function retrieveCompanies()
	{
		$sql = "SELECT * FROM comp_specs
				ORDER BY compName";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;
	}
	
	public function retrieveNoSeries()
	{
		$sql = "SELECT COUNT(seriesID) 
				FROM tbl_charts_options_series t1
				WHERE t1.chartID=:chartID";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':chartID', $_GET['chartID'] , PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetch();
		return $data;
	}
	
	public function retrieveSeriesOptions()
	{
		$sql = "SELECT * 
				FROM tbl_charts_options_series t1
				WHERE t1.chartID=:chartID
				ORDER BY t1.seriesID;";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':chartID', $_GET['chartID'] , PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;
	}
	
}	




