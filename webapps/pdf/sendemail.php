<?php

	session_start('pdfcreation');
	
	require_once( "../mpdf/mpdf.php" );
	require_once( "../../php/vendor/autoload.php" );						// Required for e-mailing
	require_once( "../../php/swiftmailer/lib/swift_required.php" );		// Required for e-mailing	

	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once( "$root/webapps/core/init.php" ); 									// Sets up connection to database
	
	

	$to = array( 'crudd@theroishop.com' => 'C3Solutions' );				

	$from = array('chrisrudd.home@gmail.com' => 'C3Solutions1');
	
	//$bcc = array('jachorn@theroishop.com' => 'Jacob Achorn');

	//Create the subject line.
	$subject = 'Your C3 Solutions Value Calculator Results Report';
				
	$text = "HTML Emails need to be enabled to see the email's contents.";

	//echo 'pdf created';
	



					
	// Login credentials
	//$username = 'azure_875a14c6e70db944ca4ffc08bbf38b44@azure.com';
	//$password = 'uK3aqHA359V72Xh';				
				
	// Setup Swift mailer parameters
	//$transport = Swift_SmtpTransport::newInstance('smtp.sendgrid.net', 587);
    $transport = Swift_AWSTransport::newInstance( 'AKIAIWQ3DPP7HAL33PIA', '7LtELaOSutm4jtPVOQI/Ucw/NuqKhCLctOiIxWVF' );
 
    $swift = Swift_Mailer::newInstance($transport);
                                                           			
	// Create a message (subject)
	$email = new Swift_Message($subject);
			
	// attach the body of the email
	$email->setFrom($from);
	$email->setBody($customer_email_HTML, 'text/html');
	$email->setTo($to);
	//$email->setBcc($bcc);
	$email->addPart($text, 'text/plain');
	//$email->attach(Swift_Attachment::fromPath('../assets/customwb/' . $wbroiID . '/pdf/C3SolutionsROI.pdf','F'));
		
	// send message 
	if ($recipients = $swift->send($email, $failures))
	{
			
	} else {
			
	}
	
	print_r($failures);  
	 echo 'done'; 
?>