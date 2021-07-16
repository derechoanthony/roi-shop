<?php

	// Establish connection to the database
	
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/constants.php");
	require_once("$root/db/connection.php");

	class RoiRetrieval {
		
		private $_db;
			
		public function __construct($db=NULL) {
				
			if(is_object($db)) {
				$this->_db = $db;
			} else {
				$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
				$this->_db = new PDO($dsn, DB_USER, DB_PASS);
			}
		}
		
		public function roiSpecifics() {
			
			$sql = "SELECT * FROM ep_created_rois
					WHERE roi_id = :roi;";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetch();
		}
		
		public function roiContributors() {
			
			$sql = "SELECT * FROM ep_roi_contributors
					WHERE roi_id = :roi;";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetchall();
		}
		
		public function roiOwner() {
			
			$sql = "SELECT * FROM roi_users
					WHERE user_id = (
						SELECT user_id FROM ep_created_rois
						WHERE roi_id = :roi
					)";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetch();			
		}
		
		public function roiCurrency() {
			
			$sql = "SELECT * FROM ep_roi_currency
					WHERE roi_id = :roi;";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetch();			
		}
		
		public function roiCurrencies() {
			
			$sql = "SELECT * FROM roi_existing_currencies
					ORDER BY currency_name;";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->execute();
			return $stmt->fetchall();			
		}
		
		public function roiSFIntegration() {
			
			$sql = "SELECT * FROM integration
					WHERE userid = :user;";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':user', $_SESSION['UserId'], PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetch();			
		}
		
		public function verifyUser() {
			
			$sql = "SELECT verification_code, roi_title FROM ep_created_rois
					WHERE roi_id = :roi AND verification_code = :verification;";
					
			$ver_code = ( isset($_GET['v']) ? $_GET['v'] : ( isset($_GET['amp;v']) ? $_GET['amp;v'] : '' ) );
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_STR);
			$stmt->bindParam(':verification', $ver_code, PDO::PARAM_STR);
			$stmt->execute();
			return $stmt->fetch();
		}
		
		public function roiSalesforceIntegrations() {
			
			$sql = "SELECT * FROM roi_company_integration
					WHERE company_id = (
						SELECT company_id FROM roi_company_structures
						WHERE structure_id = (
							SELECT structure_id FROM roi_structure_versions
							WHERE version_id = (
								SELECT roi_version_id FROM ep_created_rois
								WHERE roi_id = :roi
							)
						)
					) AND active = 1 AND integration_id = 'sfdc';";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_STR);
			$stmt->execute();
			return $stmt->fetch();			
		}
		
		public function roiPDFElements() {
			
			$sql = "SELECT * FROM ep_pdf_elements
					WHERE roi_version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					) ORDER BY position";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetchall();
		}		
	}

?>	