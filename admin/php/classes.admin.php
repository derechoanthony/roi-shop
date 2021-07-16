<?php

	class TheROIShopAdmin
	{

		private $_db;
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
		
		public function getAdmin() {
			
			$sql = "SELECT * FROM roi_companies
					WHERE company_id IN (
						SELECT CompID FROM user_comps
						WHERE UserID = (
							SELECT user_id FROM roi_users
							WHERE username = :user
						)
						AND permission = 1
					) ORDER BY company_name ASC";
					
			$stmt = $this->_db->prepare( $sql );
			$stmt->bindParam(':user', $_SESSION['Username'], PDO::PARAM_STR);
			$stmt->execute();
			
			return $stmt->fetchall();
		}
		
		public function getUsers()
		{
			$sql = "SELECT parent FROM comp_specs
					WHERE compID=:comp;";
					
			$stmt = $this->_db->prepare( $sql );
			$stmt->bindParam(':comp', $_SESSION['Admin'], PDO::PARAM_INT);
			$stmt->execute();

			$parent = $stmt->fetch();
			$users = ( $parent['parent'] == 0 ? $_SESSION['Admin'] : $parent['parent'] );
			
			$sql = "SELECT * FROM roi_users
					WHERE company_id = :comp
					ORDER BY username;";
					
			$stmt = $this->_db->prepare( $sql );
			$stmt->bindParam(':comp', $users, PDO::PARAM_INT);
			$stmt->execute();
			
			return $stmt->fetchall();
		}
		
		public function getCompanySpecs()
		{
			$sql = "SELECT parent
					FROM comp_specs
					WHERE compID=:comp;";
					
			$stmt = $this->_db->prepare( $sql );
			$stmt->bindParam(':comp', $_SESSION['Admin'], PDO::PARAM_INT);
			$stmt->execute();

			$parent = $stmt->fetch();
			$users = ( $parent['parent'] == 0 ? $_SESSION['Admin'] : $parent['parent'] );
			
			$sql = "SELECT *
					FROM comp_specs
					WHERE compID=:comp";
					
			$stmt = $this->_db->prepare( $sql );
			$stmt->bindParam(':comp', $users, PDO::PARAM_STR);
			$stmt->execute();
			
			return $stmt->fetch();
		}
		
		public function getChildren() {
			
			$sql = "SELECT * FROM roi_structure_versions
					WHERE structure_id IN (
						SELECT structure_id FROM roi_company_structures
						WHERE company_id = :company_id
					);";
			
			$stmt = $this->_db->prepare($sql);			
			$stmt->bindParam(':company_id', $_SESSION['Admin'], PDO::PARAM_INT);
			$stmt->execute();
			
			return $stmt->fetchall();
		}
		
		public function getRois()
		{
			$sql = "SELECT * FROM ep_created_rois
					LEFT JOIN roi_users
					ON ep_created_rois.user_id = roi_users.user_id
					WHERE roi_users.company_id=:comp";
					
			$stmt = $this->_db->prepare( $sql );
			$stmt->bindParam(':comp', $_SESSION['Admin'], PDO::PARAM_STR);
			$stmt->execute();
			
			return $stmt->fetchall();
		}
		
		public function getMasterList()
		{
			$sql = "SELECT * FROM roi_companies ORDER BY company_name ASC;";
					
			$stmt = $this->_db->prepare( $sql );
			$stmt->execute();
			
			return $stmt->fetchall();
		}
		
		public function getFirstSection( $roi )
		{
			$sql = "SELECT *
					FROM compsections
					WHERE compID = :comp";
					
			$stmt = $this->_db->prepare( $sql );
			$stmt->bindParam(':comp', $roi, PDO::PARAM_INT);
			$stmt->execute();
			
			return $stmt->fetchall();
		}
		
		public function getUserRoi()
		{
			$sql = "SELECT compName
					FROM users
					WHERE Username = :user";
					
			$stmt = $this->_db->prepare( $sql );
			$stmt->bindParam(':user', $_SESSION['Username'], PDO::PARAM_STR);
			$stmt->execute();
			$data = $stmt->fetch();
			
			return $data['compName'];
		}
		
		public function sendUsernameCreation( $user, $pass, $name )
		{
		
			$from = array('noreply@theroishop.com' => 'The ROI Shop');
			$to = array($user => $name);
			$bcc = array('jachorn@theroishop.com' => 'Jacob Achorn','mfarber@theroishop.com' => 'Mike Farber');
			$subject = "The ROI Shop Account Setup";
			
			$message = file_get_contents('../ajax/email/register.html');
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
		
			// Login credentials
			/*$username = 'azure_875a14c6e70db944ca4ffc08bbf38b44@azure.com';
			$password = 'uK3aqHA359V72Xh';		
			
			$from = array('noreply@theroishop.com' => 'The ROI Shop');
			$to = array($user => $name);
			$bcc = array('jachorn@theroishop.com' => 'Jacob Achorn', 'mfarber@theroishop.com' => 'Mike Farber');
			$subject = "The ROI Shop Account Setup";
			
			$message = file_get_contents('../ajax/email/register.html');
			$message = str_replace('%name%', ($name?$name:$user), $message);
			$message = str_replace('%username%', $user, $message);
			$message = str_replace('%password%', $pass, $message);
			
			// Setup Swift mailer parameters
			$transport = Swift_SmtpTransport::newInstance('smtp.sendgrid.net', 587);
			$transport->setUsername($username);
			$transport->setPassword($password);
			$swift = Swift_Mailer::newInstance($transport);
				
			// Create a message (subject)
			$email = new Swift_Message($subject);
					
			// attach the body of the email
			$email->setFrom($from);
			$email->setBody($message, 'text/html');
			$email->setTo($to);
			$email->setBcc($bcc);

			if ($recipients = $swift->send($email, $failures))
			{ } else { }*/
		
		}
		
		public function getSections()
		{			
			$sql = "SELECT *
					FROM compsections
					WHERE compID=:comp
					ORDER BY Position;";
					
			$stmt = $this->_db->prepare( $sql );
			$stmt->bindParam(':comp', $_SESSION['Admin'], PDO::PARAM_INT);
			$stmt->execute();
			
			return $stmt->fetchall();
		}
		
		public function getEntries()
		{			
			$sql = "SELECT *
					FROM entry_fields
					WHERE roiID=:comp
					ORDER BY Position;";
					
			$stmt = $this->_db->prepare( $sql );
			$stmt->bindParam(':comp', $_SESSION['Admin'], PDO::PARAM_INT);
			$stmt->execute();
			
			return $stmt->fetchall();
		}
		
	}
		
?>		