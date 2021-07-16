<?php

	class EmailActions {
		
		private $_db;

		public function __construct($db=NULL) {
			
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
		
		public function sendResetEmail( $user_id ) {
		
			$sql = "SELECT * FROM roi_users
					WHERE user_id = :user
					LIMIT 1;";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':user', $user_id, PDO::PARAM_INT);
			$stmt->execute();
			$account_info = $stmt->fetch();
			
			$transport = Swift_AWSTransport::newInstance( 'AKIAIWQ3DPP7HAL33PIA', '7LtELaOSutm4jtPVOQI/Ucw/NuqKhCLctOiIxWVF' );
			$swift = Swift_Mailer::newInstance($transport);
			
			$email_template = file_get_contents(realpath($_SERVER["DOCUMENT_ROOT"])."/email/templates/resetpassword.html");
			$email_template = str_replace("%verification%", $account_info['verification_code'], $email_template);
			$email_template = str_replace("%email%", md5($account_info['username']), $email_template);
			
			$email_subject = 'The ROI Shop Request to Reset Your Password';
			$email_text = 'HTML Emails need to be enabled to see the email\'s contents.';
			$email_from = array('noreply@theroishop.com' => 'The ROI Shop');
			$email_to = array($account_info['username'] => $account_info['first_name'] . $account_info['last_name']);
			
			$password_email_reset = new Swift_Message($email_subject);
			$password_email_reset->setFrom($email_from);
			$password_email_reset->setTo($email_to);
			$password_email_reset->setBody($email_template, 'text/html');
			$password_email_reset->addPart($email_text, 'text/plain');
	
			$swift->send($password_email_reset, $failures);
		}	
		
	}
	
?>