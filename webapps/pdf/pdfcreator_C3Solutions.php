<?php

	session_start('pdfcreation');
	
	require_once( "../mpdf/mpdf.php" );
	//require_once("../../calc-your-roi/2/inc/vendor/autoload.php");
	//require_once("../../calc-your-roi/2/inc/swiftmailer/lib/swift_required.php");

	//require '../php/swiftmailer/lib/swift_required.php';
	//require_once("../php/vendor/autoload.php");

	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once( "$root/webapps/core/init.php" ); 									// Sets up connection to database
	
	//Get the contents of the report
	$instanceID = $_POST['instanceid'];
	//$instanceID=459;
	$reportID = $_POST['reportid'];
	
	//Lookup the wbroiID
	$wbroiID	= $g->Dlookup('wbroiID','wb_roi_instance','instanceID=' . $instanceID);
	//$wbroiID=5;
	$reportCSS 	= $g->Dlookup('CSS','wb_roi_reports','wb_roi_report_ID=' . $reportID);
	$reportHTML = $g->Dlookup('html','wb_roi_reports','wb_roi_report_ID=' . $reportID);
	
	//The report ID are hard coded here.
	//Need a way to get the report based on the main roiID
	$customer_email_HTML = $g->Dlookup('html','wb_roi_reports','wb_roi_report_ID=12');
	$C3_email_HTML = $g->Dlookup('html','wb_roi_reports','wb_roi_report_ID=13');
	
	
	//Loop through report and get contents of inputs and outputs
	
	//1. Get a list of the instance values for this instance
	 $SQL = "SELECT * 
    		FROM `wb_roi_instance_values_formatted`
    		WHERE instanceID=$instanceID;";
    
	//echo $SQL;
	$list = $g->returnarray($SQL);

	$numrows = count($list);
	$x=0;
	if($numrows>0){
	  foreach($list as $r){
		$x = $x + 1;	
		$reportHTML = str_replace('<tag>' . $r['field'] . '</tag>', $r['value'], $reportHTML);
		$customer_email_HTML = str_replace('<tag>' . $r['field'] . '</tag>', $r['value'], $customer_email_HTML);
		$C3_email_HTML = str_replace('<tag>' . $r['field'] . '</tag>', $r['value'], $C3_email_HTML);
	    //$search = "/[^<tag>](.*)[^<\/tag>]/";
	    //$replace = $r['value'];  //The given value for the field
	  }
	}
	
	
	//Need to loop through the standard values table and replace accodingly
	
		//2. Get a list of the instance values for this instance
	 $SQL = "SELECT * 
    		FROM `wb_roi_instance_values_standard`
    		WHERE instanceID=$instanceID;";
    
	//echo $SQL;
	$list = $g->returnarray($SQL);

	$numrows = count($list);
	$x=0;
	if($numrows>0){
	  foreach($list as $r){
		$x = $x + 1;	
		$reportHTML = str_replace('<tagstd>' . $r['stdfieldID'] . '</tagstd>', $r['value'], $reportHTML);
		$customer_email_HTML = str_replace('<tagstd>' . $r['stdfieldID'] . '</tagstd>', $r['value'], $customer_email_HTML);
		$C3_email_HTML = str_replace('<tagstd>' . $r['stdfieldID'] . '</tagstd>', $r['value'], $C3_email_HTML);
	    //$search = "/[^<tag>](.*)[^<\/tag>]/";
	    //$replace = $r['value'];  //The given value for the field
	  }
	}
	
	$report = '<html><head>' . $reportCSS . '</head><body>' . $reportHTML . '</body></html>';
	//echo $report;
		
	$stylesheet = file_get_contents('../assets/css/pdfstyle.css');
	$comp_stylesheet = file_get_contents('../assets/css/style.css');
	
	$mpdf = new mPDF('c', 'A4-L');
		
	$mpdf->WriteHTML($stylesheet,1);
	$mpdf->WriteHTML($comp_stylesheet,1);
		
	$mpdf->WriteHTML($report);
	
	$mpdf->Output('../assets/customwb/' . $wbroiID . '/pdf/' . $wbroiID . '-' . $instanceID . '.pdf','F');
	//$mpdf->Output('../assets/customwb/' . $wbroiID . '/pdf/C3SolutionsROI.pdf','F');

	
	require '../php/swiftmailer/lib/swift_required.php';

  // *******************Send Email to Customer **********************************

  //Lookup the right email address
  $customer_email = $g->Dlookup('value','wb_roi_instance_values','field=26 AND instanceID=' . $instanceID);

  //Create the Transport
  $transport = Swift_AWSTransport::newInstance( 'AKIAIWQ3DPP7HAL33PIA', '7LtELaOSutm4jtPVOQI/Ucw/NuqKhCLctOiIxWVF' );

  //Create the Mailer using your created Transport
  $mailer = Swift_Mailer::newInstance($transport);
 
  //Create the message
  $message = Swift_Message::newInstance()
  ->setSubject("Your C3 Solutions ROI Analysis Report")
  ->setFrom(array('noreply@theroishop.com'))
  ->setTo(array($customer_email))
  ->setBody($customer_email_HTML, 'text/html')
  ->attach(Swift_Attachment::fromPath('../assets/customwb/' . $wbroiID . '/pdf/' . $wbroiID . '-' . $instanceID . '.pdf')->setFilename('C3SolutionsROI.pdf'));

 
  $mailer->send( $message );

  // *******************Send Email to Client **********************************

  //Lookup the right email address
  $client_email = $g->Dlookup('fieldValue','wb_roi_custom_tags','fieldID=1');
  

  //Create the Transport
  $transport = Swift_AWSTransport::newInstance( 'AKIAIWQ3DPP7HAL33PIA', '7LtELaOSutm4jtPVOQI/Ucw/NuqKhCLctOiIxWVF' );

  //Create the Mailer using your created Transport
  $mailer = Swift_Mailer::newInstance($transport);
 
  //Create the message
  $message = Swift_Message::newInstance()
  ->setSubject("A New Lead Has Been Created")
  ->setFrom(array('noreply@theroishop.com'))
  ->setTo(array($client_email))
  ->setBody($C3_email_HTML, 'text/html')
  ->attach(Swift_Attachment::fromPath('../assets/customwb/' . $wbroiID . '/pdf/C3SolutionsROI.pdf'));

 
  //$mailer->send( $message );
  if ($recipients = $mailer->send($message, $failures))
	{
			
	} else {
			
	}
	
	/*
	

	$to = array( 'crudd@theroishop.com' => 'C3Solutions' );				

	$from = array('noreply@theroishop.com' => 'C3Solutions');
	
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
	$email->setBcc($bcc);
	$email->addPart($text, 'text/plain');
	$email->attach(Swift_Attachment::fromPath('../assets/customwb/' . $wbroiID . '/pdf/C3SolutionsROI.pdf','F'));
		
	// send message 
	if ($recipients = $swift->send($email, $failures))
	{
			
	} else {
			
	}
	
	$swift->send( $message );
	 
	echo implode(" ",$failures); 
	 /*
	
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