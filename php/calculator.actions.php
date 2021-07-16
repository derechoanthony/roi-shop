<?php

/**
 * Handles log in actions for The ROI Shop
 * 
 * PHP version 5
 **/
 
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
	
	public function retrieveRoiSections()
	{
		$sql = "SELECT * FROM compsections
				WHERE compID = (
					SELECT roi_version_id FROM ep_created_rois
					WHERE roi_id = :roi
				)
				ORDER BY Position";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;
	}
	
	public function retrieveRoiGraphs()
	{
		$sql = "SELECT * FROM graphs
				WHERE roiid = (
					SELECT roi_version_id FROM ep_created_rois
					WHERE roi_id = :roi
				)
				ORDER BY position";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;
	}
	
	public function retrieveRoiEntries()
	{
		$sql = "SELECT * FROM entry_fields
				WHERE roiID = (
					SELECT roi_version_id FROM ep_created_rois
					WHERE roi_id = :roi
				)
				ORDER BY position";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;
	}
	
	public function retrieveDiscoveryDocuments()
	{
		$sql = "SELECT * FROM discovery_document
				WHERE company_id = (
					SELECT roi_version_id FROM ep_created_rois
					WHERE roi_id = :roi
				)";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;	
	}
	
	public function retrieveRoiPreferences()
	{
		$sql = "SELECT * FROM ep_created_rois
				WHERE roi_id = :roi";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetch();
		return $data;	
	}
	
	public function retrieveRoiContributors()
	{
		$sql = "SELECT * FROM createdwith
				WHERE roi=:roi";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;	
	}
	
	public function retrieveRoiOwner()
	{
		$sql = "SELECT * FROM users
				WHERE UserID = (
					SELECT ListID FROM list_items
					WHERE roi_id = :roi
				)";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetch();
		return $data;
	}
	
	public function retrieveTestimonials()
	{
		$sql = "SELECT * FROM testimonials
				WHERE company_id = (
					SELECT roi_version_id FROM ep_created_rois
					WHERE roi_id = :roi
				)";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;
	}
	
	public function retrievePDFSpecifics()
	{
		$sql = "SELECT *
	            FROM pdfspecs
	            WHERE roiid = (
					SELECT compStructure
					FROM list_items
					WHERE ListItemID=:comp
				)
				ORDER BY position";

		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':comp', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;	
	}
	
	public function retrieveRoiValues()
	{
		$sql = "SELECT roi_bl FROM list_items
				WHERE roi_id = :roi";

		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetch();

		return $data['roi_bl'];	
	}
	
	public function addUserROIs()
	{
		$sql = "SELECT * FROM user_comps WHERE CompID = 26";

		$stmt = $this->_db->prepare($sql);
		$stmt->execute();
		$data = $stmt->fetchall();
		
		foreach($data as $newdata)
		{
			$sql = "INSERT INTO user_comps (`UserID`,`CompID`,`permission`) VALUES (:user,'62','0')";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':user', $newdata['UserID'], PDO::PARAM_INT);			
			$stmt->execute();			
		}
		
	}
	
	public function retrieveRoiStakeholders()
	{
		$sql = "SELECT * FROM stakeholder WHERE roiid = 
					(SELECT roi_version_id FROM ep_created_rois WHERE roi_id = :roi )";

		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetchall();

		return $data;
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
				) AND active = 1
				ORDER BY compName ASC";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':user', $_SESSION['Username'], PDO::PARAM_STR);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;
	}
	
	public function retrieveUserNewVersionCompanySpecs()
	{
		$sql = "SELECT * FROM roi_structure_version
				WHERE roi_structure_id IN (
					SELECT compID FROM user_comps
					WHERE UserID = (
						SELECT UserID FROM users
						WHERE Username=:user
					)
				) AND version_stage = 2
				ORDER BY version_name ASC";
		
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
				)
				ORDER BY comp_specs.compName";
		
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
		$data = $stmt->fetch();
		return $data;
	}
	
	public function retrieveVersionId()
	{
		$sql = "SELECT roi_version_id FROM ep_created_rois
				WHERE roi_id = :roi";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;
	}
	
	public function retrieveDiscoveryQuestions()
	{
		$sql = "SELECT * FROM discovery_questions
				WHERE discovery_id IN (
					SELECT id FROM discovery_document
					WHERE company_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
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
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
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
	
	public function retrieveRoiSpecs()
	{
		$sql = "SELECT * FROM comp_specs
				WHERE compID = (
					SELECT roi_version_id FROM ep_created_rois
					WHERE roi_id = :roi
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
					SELECT roi_version_id FROM ep_created_rois
					WHERE roi_id = :roi
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
	
	public function retrievePdfSetup()
	{
		$sql = "SELECT * FROM pdf_specs
				WHERE roi = (
					SELECT roi_version_id FROM ep_created_rois
					WHERE roi_id = :roi
				);";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi',$_GET['roi'],PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;
	}
	
	public function retrievePagesForSetup()
	{
		$sql = "SELECT MAX(pageno) FROM pdf_specs
				WHERE roi = (
					SELECT roi_version_id FROM ep_created_rois
					WHERE roi_id = :roi
				);";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi',$_GET['roi'],PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetch();
		return $data;
	}	
	
	public function retrievePdfBuilder()
	{
		$sql = "SELECT * FROM pdf_builder
				WHERE roi = :roi;";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi',$_GET['roi'],PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;
	}
	
	public function retrieveTotalPages()
	{
		$sql = "SELECT MAX(page) FROM pdf_builder
				WHERE roi = :roi;";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi',$_GET['roi'],PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetch();
		return $data;
	}
	
	public function retrieveSFCode()
	{
		$sql = "SELECT * FROM integration
				WHERE userid = (
					SELECT user_id FROM roi_users
					WHERE username = :user
				);";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':user',$_SESSION['Username'],PDO::PARAM_STR);
		$stmt->execute();
		$data = $stmt->fetch();
		return $data;
	}
	
	public function retrieveUserFolders()
	{
		$sql = "SELECT * FROM roi_folders
				WHERE global = 1 OR userid = (
					SELECT user_id FROM roi_users
					WHERE username = :user				
				);";

		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':user',$_SESSION['Username'],PDO::PARAM_STR);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;		
	}
	
	public function retrieveVisibleFolders()
	{
		$sql = "SELECT * FROM visible_folders
				WHERE userid = (
					SELECT UserID FROM users
					WHERE Username = :user
				);";

		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':user',$_SESSION['Username'],PDO::PARAM_STR);
		$stmt->execute();
		$data = $stmt->fetchall();
		return $data;				
	}
	
	public function storeSessionValues()
	{
		
		$sql = "SELECT * FROM roi_values
				WHERE roiid=:roi AND sessionid=:session;";
						
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->bindParam(':session', $_SESSION['id'], PDO::PARAM_INT);
		$stmt->execute();
		$stmt->fetchall();
		
		if( $stmt->rowCount() == 0 ) {
			
			$sql = "SELECT MAX(sessionid) FROM roi_values
					WHERE roiid=:roi";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch();

			$sql = "INSERT INTO roi_values (`roiid`,`value`,`sessionid`,`entryid`)
					SELECT `roiid`,`value`, :currentsession, `entryid` FROM roi_values
					WHERE roiid=:roi AND sessionid=:session";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->bindParam(':currentsession', $_SESSION['id'], PDO::PARAM_INT);
			$stmt->bindParam(':session', $data['MAX(sessionid)'], PDO::PARAM_INT);
			$stmt->execute();
		}
	}
	
	public function retrieveAvailableCurrencies()
	{
		
		$sql = "SELECT * FROM exchange_rates
				ORDER BY full_name";
						
		$stmt = $this->_db->prepare($sql);
		$stmt->execute();
		$data = $stmt->fetchall();
		
		return $data;
	}
	
	public function retrieveCurrencies()
	{
		
		$sql = "SELECT roi_currency.currency, exchange_rates.rate, exchange_rates.dt, exchange_rates.full_name FROM roi_currency 
				JOIN exchange_rates
				ON roi_currency.currency = exchange_rates.currency
				WHERE roiid = :roi;";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetch();
		
		return $data;
	}
	
	public function retrieveExcludedSections() {
		
		$sql = "SELECT * FROM hidden_entities
				WHERE type = 'section' AND roi = :roi;";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetchall();
		
		return $data;
	}
	
	public function retrieveEntryChoices($entry) {
		
		$sql = "SELECT show_map, value FROM entry_choices
				WHERE entryid = :entry
				ORDER BY position;";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':entry', $entry, PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetchall();
		
		return $data;		
	}

}	