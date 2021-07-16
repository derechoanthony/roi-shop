<?php

	session_name("contactus");
	session_start();

	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/constants.php");
	require_once("$root/db/connection.php");
	require_once("$root/email/swiftmailer/lib/swift_required.php");

	$sql = "SELECT * FROM blacklist_ips
			WHERE ip_address = :ip";

	$stmt = $db->prepare($sql);
	$stmt->bindParam(':ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
	$stmt->execute();
	$data = $stmt->fetch();

	foreach($_POST as $k=>$v) {
		if(ini_get('magic_quotes_gpc'))
		$_POST[$k]=stripslashes($_POST[$k]);
		
		$_POST[$k]=htmlspecialchars(strip_tags($_POST[$k]));
	}

	if(!$_POST['g-recaptcha-response']){
		header('Location: /assets/contact?email=spammer');
		exit;

	} else {

		$msg=
		'<table cellpadding="0" cellspacing="0" border="0" width="560" style="border:0; border-collapse:collapse; background-color:#ffffff; border-radius:6px;">
			<tbody>
				<tr>
					<td style="border-collapse:collapse; vertical-align:middle; text-align center; padding:20px;">
	
						<!-- Headline Header -->
						<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
							<tbody>
	
								<tr><!-- logo -->
									<td width="100%" style="font-family: helvetica, Arial, sans-serif; font-size: 18px; letter-spacing: 0px; text-align: center;">	
										<a href="#" style="text-decoration: none;">
											<img src="/assets/images/logo.png" alt="The ROI Shop" border="0" width="166" height="auto" style="with: 166px; height: auto; border: 5px solid #ffffff;">
										</a>
									</td>
								</tr>
								<tr><!-- spacer before the line -->
									<td width="100%" height="20"></td>
								</tr>
								<tr><!-- line -->
									<td width="100%" height="1" bgcolor="#d9d9d9"></td>
								</tr>
								<tr><!-- spacer after the line -->
									<td width="100%" height="30"></td>
								</tr>
								<tr>
									<td width="100%" style=" font-size: 14px; line-height: 24px; font-family:helvetica, Arial, sans-serif; text-align: left; color:#87919F;">	
										<h1>Thank You!</h1>
										Thank You for your interest in The ROI Shop.
										A representative will contact your shortly.
									</td>
								</tr>
								<tr>
									<td width="100%" height="15"></td>
								</tr>
							</tbody>
						</table>
						<!-- /Headline Header -->
	
					</td>
				</tr>
			</tbody>
		</table>';	
	
		$from = array('noreply@theroishop.com' => 'The ROI Shop');
		$to = array($_POST['email'] => $_POST['name']);
		$subject = "Thanks for your interest in The ROI Shop!";
	
		// Setup Swift mailer parameters
		$transport = Swift_AWSTransport::newInstance( 'AKIAIWQ3DPP7HAL33PIA', '7LtELaOSutm4jtPVOQI/Ucw/NuqKhCLctOiIxWVF' );
		$swift = Swift_Mailer::newInstance($transport);
	
		// Create a message (subject)
		$email = new Swift_Message($subject);
	
		// attach the body of the email
		$email->setFrom($from);
		$email->setBody($msg, 'text/html');
		$email->setTo($to);
	
		if ($recipients = $swift->send($email, $failures))
		{ } else { }
	
		$msg=
		'Name:	'.$_POST['name'].'<br />
		Title: '.$_POST['title'].'<br />
		Company Name: '.$_POST['compname'].'<br />
		Email:	'.$_POST['email'].'<br />
		Sales Reps: '.$_POST['reps'].'<br />
		Phone Number: '.$_POST['phone'].'<br />
		IP:	'.$_SERVER['REMOTE_ADDR'].'<br /><br />
	
		Message:<br /><br />
	
		'.nl2br($_POST['message']).'
	
		';
	
		$from = array('noreply@theroishop.com' => 'The ROI Shop');
		$to = array('jachorn@theroishop.com' =>'Jacob Achorn', 'mfarber@theroishop.com' => 'Mike Farber');
		
		$subject = "A new email from ".$_POST['name']." | Contact Form Submittal";
	
		// Setup Swift mailer parameters
		$transport = Swift_AWSTransport::newInstance( 'AKIAIWQ3DPP7HAL33PIA', '7LtELaOSutm4jtPVOQI/Ucw/NuqKhCLctOiIxWVF' );
		$swift = Swift_Mailer::newInstance($transport);
	
		// Create a message (subject)
		$email = new Swift_Message($subject);
	
		// attach the body of the email
		$email->setFrom($from);
		$email->setBody($msg, 'text/html');
		$email->setTo($to);
	
		if ($recipients = $swift->send($email, $failures))
		{
			if($_SERVER['HTTP_REFERER'])
				header('Location: /assets/contact?email=sent');
			exit;	
	
		} else { }
	}

	function checkLen($str,$len=2)
	{
		return isset($_POST[$str]) && mb_strlen(strip_tags($_POST[$str]),"utf-8") > $len;
	}

	function checkEmail($str)
	{
		return preg_match("/^[\.A-z0-9_\-\+]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z]{1,4}$/", $str);
	}

?>
