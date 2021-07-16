<?php

	// Establish connection to the database
	
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/constants.php");
	require_once("$root/db/connection.php");
	
	class DashboardActions {
		
		private $_db;
			
		public function __construct($db=NULL) {
				
			if(is_object($db)) {
				$this->_db = $db;
			} else {
				$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
				$this->_db = new PDO($dsn, DB_USER, DB_PASS);
			}
		}
		
		public function userPermissions() {
			
			$sql = "SELECT * FROM ep_admin_permissions
					WHERE user_id = :user AND company_id = :company;";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':user', $_SESSION['UserId'], PDO::PARAM_INT);
			$stmt->bindParam(':company', $_GET['companyid'], PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetch();
		}

		public function companySpecs() {
			
			$sql = "SELECT * FROM roi_companies
					WHERE company_id = :company;";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':company', $_GET['companyid'], PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetch();
		}
		
		public function companyUsers() {
			
			$sql = "SELECT * FROM roi_users
					WHERE company_id = :company
					AND status <> 99";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':company', $_GET['companyid'], PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetchall();
		}

		public function companyStructures() {
			
			$sql = "SELECT * FROM roi_company_structures
					WHERE company_id = :company;";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':company', $_GET['companyid'], PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetchall();
		}

		public function companyRois() {
			
			$sql = "SELECT * FROM ep_created_rois
					WHERE user_id IN (
						SELECT user_id FROM roi_users
						WHERE company_id = :company
					)";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':company', $_GET['companyid'], PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetchall();
		}
		
		public function companyVersions() {
			
			$sql = "SELECT * FROM roi_structure_versions
					WHERE structure_id IN (
						SELECT structure_id FROM roi_company_structures
						WHERE company_id = :company
					)";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':company', $_GET['companyid'], PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetchall();
		}
		
		public function versionPaths() {
			
			$sql = "SELECT * FROM roi_version_levels";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->execute();
			return $stmt->fetchall();			
		}
		
		public function sendWelcomeEmail( $user, $pass, $name ) {	
			
			$from = array('noreply@theroishop.com' => 'The ROI Shop');
			$to = array($user => $name);
			$bcc = array('jachorn@theroishop.com' => 'Jacob Achorn','mfarber@theroishop.com' => 'Mike Farber');
			$subject = "The ROI Shop Account Setup";
			
			$root = realpath($_SERVER["DOCUMENT_ROOT"]);
			
			$message = file_get_contents("$root/email/templates/register.html");
			$message = str_replace('%name%', ($name?$name:$user), $message);
			$message = str_replace('%username%', $user, $message);
			$message = str_replace('%password%', $pass, $message);
			
			// Setup Swift mailer parameters
			$transport = Swift_AWSTransport::newInstance( 'AKIAIWQ3DPP7HAL33PIA', '7LtELaOSutm4jtPVOQI/Ucw/NuqKhCLctOiIxWVF' );

			$swift = Swift_Mailer::newInstance($transport);
				
			// Create a message (subject)
			$email = new Swift_Message($subject);
					
			// attach the body of the email
			$email->setFrom($from);
			$email->setBody($message, 'text/html');
			$email->setTo($to);
			$email->setBcc($bcc);
			$email->addPart($text, 'text/plain');

			if ($recipients = $swift->send($email, $failures))
			{ } else { }			
		}
	}
	
?>