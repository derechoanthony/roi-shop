<?php
	
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/constants.php");
	require_once("$root/db/connection.php");
	require_once("$root/email/email.actions.php");
	require_once("$root/email/swiftmailer/lib/swift_required.php");	

	require "$root/email/phpmailer/src/Exception.php";
	require "$root/email/phpmailer/src/PHPMailer.php";
	require "$root/email/phpmailer/src/SMTP.php";

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;
	
	if( $_POST['action'] == 'signInUser' ){
		
		$login_results = [];
		
		$sql = "SELECT user_id, username, registered FROM roi_users
				WHERE username = :user AND password = MD5(:pass);";
					
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':user', $_POST['username'], PDO::PARAM_STR);
		$stmt->bindParam(':pass', $_POST['password'], PDO::PARAM_STR);
		$stmt->execute();
		$user_info = $stmt->fetch(PDO::FETCH_ASSOC);
		
		if ($user_info){
			
			if ($user_info['registered'] == 0){
				$login_results['warnings']['not_registered'] = 'username not registered';
			}
			
			$login_results['user_data'] = $user_info;
			
			if ($user_info['registered'] == 1){
				$_SESSION['Username'] = $user_info['username'];
				$_SESSION['UserId'] = $user_info['user_id'];
				$_SESSION['LoggedIn'] = date("Y-m-d H:i:s");
				
				$session = hash( "sha256", time(). $user_info['user_id'] );
				$token = hash( "sha256", time() ."token". $user_info['user_id'] );

				$token_sha256 = hash( "sha256", $token);
				
				$sql = "INSERT INTO roi_login_tokens (session_id, token, ip_address, user_id)
						VALUES (:session, :token, :ipaddress, :user)
						ON DUPLICATE KEY UPDATE
						session_id = :session, token = :token, user_id = :user;";
						
				$stmt = $db->prepare($sql);
				$stmt->bindParam(':session', $session, PDO::PARAM_STR);
				$stmt->bindParam(':token', $token_sha256, PDO::PARAM_STR);
				$stmt->bindParam(':ipaddress', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
				$stmt->bindParam(':user', $user_info['user_id'], PDO::PARAM_INT);
				$stmt->execute();

				$_SESSION['Id'] = $session;
				
				if ($_POST['remember'] === 'true'){
					
					$login_results['session_id'] = $session;
					$login_results['token'] = $token;				
				}				
			}
			
		} else {
			
			$login_results['warnings']['no_user_found'] = 'no connection';
		}
		
		echo json_encode($login_results);
	}

	if( $_POST['action'] == 'rememberedSignIn' ){
		
		$login_results = [];
		
		$token = hash( "sha256", $_POST['token'] );
		
		$sql = "SELECT user_id FROM roi_login_tokens
				WHERE session_id = :session AND token = :token;";
					
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':session', $_POST['session'], PDO::PARAM_STR);
		$stmt->bindParam(':token', $token, PDO::PARAM_STR);
		$stmt->execute();
		$user_id = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($user_id){
			$sql = "SELECT user_id, username, registered FROM roi_users
					WHERE user_id = :userid;";
						
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':userid', $user_id['user_id'], PDO::PARAM_STR);
			$stmt->execute();
			$user_info = $stmt->fetch(PDO::FETCH_ASSOC);			
		}
		
		if ($user_info){
			
			$login_results['user_data'] = $user_info;
			
			$_SESSION['Username'] = $user_info['username'];
			$_SESSION['UserId'] = $user_info['user_id'];
			$_SESSION['LoggedIn'] = date("Y-m-d H:i:s");
			$_SESSION['Id'] = $_POST['session'];
		}

		echo json_encode($login_results);
	}

	if( $_POST['action'] == 'sendRegisterEmail' ){
		
		$login_results = [];
		$verification = hash( "sha256", time(). $_POST['username'] );
		
		$sql = "UPDATE roi_users SET verification_code = :verification
				WHERE username = :username;";
					
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
		$stmt->bindParam(':verification', $verification, PDO::PARAM_STR);
		$stmt->execute();
		
		$sql = "SELECT verification_code, registered, username FROM roi_users
				WHERE username = :username;";
					
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
		$stmt->execute();
		$user_info = $stmt->fetch(PDO::FETCH_ASSOC);
		
		if ($user_info){
			if ($user_info['registered'] == 1){
				$login_results['warnings']['account_registed'] = 'already registered';
			} elseif($user_info['registered'] == 0) {
				
				$transport = Swift_AWSTransport::newInstance( 'AKIAIWQ3DPP7HAL33PIA', '7LtELaOSutm4jtPVOQI/Ucw/NuqKhCLctOiIxWVF' );
				$swift = Swift_Mailer::newInstance($transport);
					
				$email_template = file_get_contents(realpath($_SERVER["DOCUMENT_ROOT"])."/email/templates/beginregistration.html");
				$email_template = str_replace("%registrationlink%", 'https://www.theroishop.com/login/registration?id=' . $user_info['verification_code'], $email_template);
					
				$email_subject = 'Welcome to The ROI Shop Registration';
				$email_text = 'HTML Emails need to be enabled to see the email\'s contents.';
				$email_from = array('noreply@theroishop.com' => 'The ROI Shop');
				$email_to = array($user_info['username'] => $user_info['username']);
				
				$registration_email_reset = new Swift_Message($email_subject);
				$registration_email_reset->setFrom($email_from);
				$registration_email_reset->setTo($email_to);
				$registration_email_reset->setBody($email_template, 'text/html');
				$registration_email_reset->addPart($email_text, 'text/plain');
			
				$swift->send($registration_email_reset, $failures);

				$login_results['email_sent'] = 'email sent';
			}			
		} else {
			$login_results['warnings']['not_in_system'] = 'username not in system';
		}


		echo json_encode($login_results);
	}
	
	if( $_POST['action'] == 'resetPassword' ){
		
		$login_results = [];
		$verification = hash( "sha256", time(). $_POST['username'] );
		
		$sql = "UPDATE roi_users SET verification_code = :verification, verified = 0
				WHERE username = :username;";
					
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
		$stmt->bindParam(':verification', $verification, PDO::PARAM_STR);
		$stmt->execute();
		
		$sql = "SELECT username, verification_code, first_name, last_name FROM roi_users
				WHERE username = :username
				LIMIT 1;";
					
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
		$stmt->execute();
		$user_info = $stmt->fetch(PDO::FETCH_ASSOC);
		
		if ($user_info){

			$email_template = file_get_contents(realpath($_SERVER["DOCUMENT_ROOT"])."/email/templates/resetpassword.html");
			$email_template = str_replace("%verification%", $user_info['verification_code'], $email_template);
			$email_template = str_replace("%email%", md5($user_info['username']), $email_template);

			$subject = 'The ROI Shop Request to Reset Your Password';
			$recipient = $user_info['username'];
			$bodyHtml = $email_template;
			$bodyText =  "HTML Emails need to be enabled to see the email's contents.";

			$sender = 'noreply@theroishop.com';
			$senderName = 'The ROI Shop';

			$usernameSmtp = 'AKIA4FBYV4FACMAOYEFG';
			$passwordSmtp = 'BG2j2KEcR5QOtTpDOY7YbJ5Is+tYcsfZzPofo7gxOR99';

			$host = 'email-smtp.us-east-1.amazonaws.com';
			$port = 587;

			$mail = new PHPMailer(true);

			try {
				// Specify the SMTP settings.
				$mail->isSMTP();
				$mail->setFrom($sender, $senderName);
				$mail->Username   = $usernameSmtp;
				$mail->Password   = $passwordSmtp;
				$mail->Host       = $host;
				$mail->Port       = $port;
				$mail->SMTPAuth   = true;
				$mail->SMTPSecure = 'tls';
				//$mail->addCustomHeader('X-SES-CONFIGURATION-SET', $configurationSet);
			
				// Specify the message recipients.
				$mail->addAddress($recipient);
				// You can also add CC, BCC, and additional To recipients here.
			
				// Specify the content of the message.
				$mail->isHTML(true);
				$mail->Subject    = $subject;
				$mail->Body       = $bodyHtml;
				$mail->AltBody    = $bodyText;
				$mail->Send();
			} catch (phpmailerException $e) {

			} catch (Exception $e) {

			}

			$login_results['email_sent'] = 'email sent';			
		} else {
			$login_results['warnings']['not_in_system'] = 'username not in system';
		}
		
		echo json_encode($login_results);
	}
	
	if( $_POST['action'] == 'completeregistration' ){
		
		$registration = [];
		
		$sql = "UPDATE roi_users SET password = MD5(:pass), first_name = :first, last_name = :last, phone = :phone, registered = 1
				WHERE verification_code = :verification;";
					
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':pass', $_POST['password'], PDO::PARAM_STR);
		$stmt->bindParam(':first', $_POST['firstname'], PDO::PARAM_STR);
		$stmt->bindParam(':last', $_POST['lastname'], PDO::PARAM_STR);
		$stmt->bindParam(':phone', $_POST['phone'], PDO::PARAM_STR);
		$stmt->bindParam(':verification', $_POST['verification'], PDO::PARAM_STR);
		$stmt->execute();

		$sql = "SELECT user_id, username, registered FROM roi_users
				WHERE verification_code = :verification;";
					
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':verification', $_POST['verification'], PDO::PARAM_STR);
		$stmt->execute();
		$user_info = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($user_info){			
			$_SESSION['Username'] = $user_info['username'];
			$_SESSION['UserId'] = $user_info['user_id'];
			$_SESSION['LoggedIn'] = date("Y-m-d H:i:s");
			
			$registration['user_info'] = $user_info;
		} else {
			$registration['warnings']['failed'] = 1;
		}
		
		echo json_encode($registration);
	}
?>