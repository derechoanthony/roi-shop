<?php

	session_start('pdfcreation');
	
	require_once( "../mpdf/mpdf.php" );
	//require_once( "../php/vendor/autoload.php" );						// Required for e-mailing
	//require_once( "../php/swiftmailer/lib/swift_required.php" );		// Required for e-mailing	
	
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once( "$root/sandwebapp/core/init.php" ); 									// Sets up connection to database
	
	//Get the contents of the report
	$reportID = $_POST['reportid'];
	$reportCSS = $g->Dlookup('CSS','wb_roi_reports','wb_roi_report_ID=' . $reportID);
	$reportHTML = $g->Dlookup('html','wb_roi_reports','wb_roi_report_ID=' . $reportID);
	
	$report = '<html><head>' . $reportCSS . '</head><body>' . $reportHTML . '</body></html>';
		
	$stylesheet = file_get_contents('../assets/css/pdfstyle.css');
	$comp_stylesheet = file_get_contents('../assets/css/style.css');
	
	$mpdf = new mPDF('c', 'A4-L');
		
	$mpdf->WriteHTML($stylesheet,1);
	$mpdf->WriteHTML($comp_stylesheet,1);
		
	$mpdf->WriteHTML($report);
	unlink('../assets/customwb/6/pdf/Lead1.pdf');
	$mpdf->Output('../assets/customwb/6/pdf/Lead1.pdf','F');
	
	$handle = curl_init();
		
	curl_setopt_array(
		$handle,
		array(
			CURLOPT_URL => 'http://api.db-ip.com/addrinfo?addr=' . $_SERVER["REMOTE_ADDR"] . '&api_key=b4a21f9ee4ded936cacbe174c2798372cf70b91b',
			CURLOPT_RETURNTRANSFER => true
		)
	);

	$ipInfo = json_decode(curl_exec($handle));
	curl_close($handle);

	$to = array( 'crudd@theroishop.com' => 'MineralTree' );				

	$from = array('crudd@theroishop.com' => 'MineralTree');
	
	//$bcc = array('jachorn@theroishop.com' => 'Jacob Achorn');

	//Create the subject line.
	$subject = 'Your Vindicia Value Calculator Results Report';
				
	$text = "HTML Emails need to be enabled to see the email's contents.";

/*

	$message = file_get_contents('../email/viewed.html');
	$message = str_replace('%ipaddress%', $_SERVER["REMOTE_ADDR"], $message);
	$message = str_replace('%firstname%', $_POST['first'], $message);
	$message = str_replace('%lastname%', $_POST['last'], $message);
	$message = str_replace('%email%', $_POST['email'], $message);
					
	foreach ($ipInfo as $k => $v) {
		switch($k) {
			case 'country':
				$message = str_replace('%country%', $v, $message);
				break;
			case 'stateprov':
				$message = str_replace('%state%', $v, $message);
				break;	
			case 'city':
				$message = str_replace('%city%', $v, $message);
				break;
		}
	}
					
	// Login credentials
	$username = 'azure_875a14c6e70db944ca4ffc08bbf38b44@azure.com';
	$password = 'uK3aqHA359V72Xh';				
				
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
	$email->addPart($text, 'text/plain');
	$email->attach(Swift_Attachment::fromPath('../../calc-your-roi/2/company_specific_files/pdfs/MineralTree ROI Calculation.pdf','F'));
		
	// send message 
	if ($recipients = $swift->send($email, $failures))
	{
			
	} else {
			
	}
	
	
	$to = array( $_POST['email'] => $_POST['firstname'].$_POST['lastname'] );				

	$from = array('bdr@mineraltree.com' => 'MineralTree');

	//Create the subject line.
	$subject = 'Your MineralTree Value Calculator Results Report';
				
	$text = "HTML Emails need to be enabled to see the email's contents.";

	$message = file_get_contents('../email/customer.html');
	$message = str_replace('%ipaddress%', $_SERVER["REMOTE_ADDR"], $message);
	$message = str_replace('%firstname%', $_POST['first'], $message);
	$message = str_replace('%lastname%', $_POST['last'], $message);
	$message = str_replace('%email%', $_POST['email'], $message);	
					
	foreach ($ipInfo as $k => $v) {
		switch($k) {
			case 'country':
				$message = str_replace('%country%', $v, $message);
				break;
			case 'stateprov':
				$message = str_replace('%state%', $v, $message);
				break;	
			case 'city':
				$message = str_replace('%city%', $v, $message);
				break;
		}
	}
					
	// Login credentials
	$username = 'azure_875a14c6e70db944ca4ffc08bbf38b44@azure.com';
	$password = 'uK3aqHA359V72Xh';				
				
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
	$email->addPart($text, 'text/plain');
	$email->attach(Swift_Attachment::fromPath('../../calc-your-roi/2/company_specific_files/pdfs/MineralTree ROI Calculation.pdf','F'));
		
	// send message 
	if ($recipients = $swift->send($email, $failures))
	{
			
	} else {
			
	}
	
	exit;
//*/	
?>