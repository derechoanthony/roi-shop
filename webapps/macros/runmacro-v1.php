<?php

	//session_start('runmacro');

	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once( "$root/webapps/core/init.php" ); 									// Sets up connection to database	
	require "$root/webapps/macros/macros_functions-v1.php";						    // This is where the functions for each macro are stored
	//require "$root/webapps/core/functions/macros.php";
	require "$root/webapps/core/functions/marketo.php";								// This is where the connectivity for Marketo is stored
	$m = new MacroFunctions();	
	require '../php/swiftmailer/lib/swift_required.php';							//Required for emailing
	require_once( "../mpdf/mpdf.php" );												//Required for pdfs
	
	//**************** Required for Numeral formatting in php ***********************
	
	require "../php/numeral/vendor/autoload.php";												//Required for pdfs

	use Stillat\Numeral\Languages\LanguageManager;
	use Stillat\Numeral\Numeral;
	
	// Create the language manager instance.
	$languageManager = new LanguageManager;
	
	// Create the Numeral instance.
	$formatter = new Numeral;
	
	// Now we need to tell our formatter about the language manager.
	$formatter->setLanguageManager($languageManager);
	
	//Add global reference for $formatting in subsequent objects if needed
	// Ex: global $formatter;  At beginning of routine
	//**************** End Required for Numeral formatting in php ***********************
	
	//Get the contents of the report
	$instanceID = $_POST['instanceid'];
	//$reportID 	= $_POST['reportid'];
	$elementID	= $_POST['elementid'];
	
	//Lookup the wbroiID
	$wbroiID	= $g->Dlookup('wbroiID','wb_roi_instance','instanceID=' . $instanceID);
	
	//Lookup the reportID (assume the primary report for this wbroiID);
	//$reportID	= $g->DLookup('wb_roi_report_ID','wb_roi_reports','wb_roi_ID=' . $wbroiID . ' AND isprimary=1');
	
	//Lookup the macro for this elementID
	$macroID	= $g->DLookup('usedmacroID','wb_roi_reports_macros','elementID=' . $elementID);
	
	//Lookup which standard macro routine this macro is
	$stdmacro	= $g->DLookup('macroID','wb_roi_reports_macros','elementID=' . $elementID);
	
	//Get a list of the aurgurments for this macro
	 $SQL = "SELECT * 
    		FROM `wb_roi_reports_macros_aurguments`
    		WHERE usedMacroID=$macroID
    		ORDER BY varID ASC";
    //echo '0<;>' . $macroID;
	//echo $wbroiID;
	$list = $g->returnarray($SQL);
	//print_r ($list);
	//$liststring = 'start ';
	$numrows = count($list);
	$x=0;
	$vars = array();
	$varstring=$instanceID;
	if($numrows>0){
	  foreach($list as $r){
		$x = $x + 1;	
		  //$liststring = $liststring . implode("<;>",$r);
		//$vars[$r['varID']] = $r['varValue'];
		$varstring = $varstring  . '<;>' . $r['varValue'];
		//$liststring = $liststring . $x;
	  }
	}
	$varstring = $varstring  . '<;>' . $x;
	
		
	//$liststring = $liststring . implode("<;>",$vars);
	
	//$liststring = $liststring . ' end';
	
	$aurgs = $g->getMacroAurguments($macroID);
	
	//Run the macro
	// The case corresponds to the standard macroID in the table wb_roi_reports_macros
	switch ($stdmacro) {
		
		case 1:	
			//This is the standard ROI Finish Macro that creates the pdf and sends the emails also sends data to marketo if needed.
			$nextaction = $m->FinishROI($macroID, $instanceID, $aurgs);
			echo $nextaction;
			break;
		case 2:
			$nextaction = $m->OpenModal($varstring);
			echo $nextaction;
			break;
		case 3:
			$nextaction = $m->CloseModal();
			echo $nextaction;
			break;
		
		default:
		
		
		
			//do nothing
	}
	
	
	// *******************Pass Completion variables back to the page **********************************

		  
	
	
	//1 -  Name: Complete ROI
	/**
	 * 		Name: 			Complete ROI
	 * 		Description: 	This macro 
	 * 						1. checks that all required fields are completed, 
	 * 						2. creates the customer pdf, 
	 * 						3. sends an email to the customer, 
	 * 						4. sends an email to the ROI owner (or designee), and 
	 * 						5. redirects the page to a followup page.
	 */	
	/*
	$immedstop = 0;
	if($vars[1]==1){
		//Check that required fields are not null
								
		$emptycount = $g->CountRequiredNotCompleted($instanceID);	
		$emptycount1= $g->DCount('valueID','wb_roi_instance_values','instanceID=' . $instanceID);
		//$immedstop = 0;
		if($emptycount>0 || $emptycount1==0) {
			$immedstop = 1;
			$returnstrng 	= '1' . '<;>' . 'Please Complete All Required Fields.';	
		
		  echo $returnstrng;
		}
	}
	else {
		//Do Not check required
		//$immedstop = 0; //No immediate stop
	}
	
	if($immedstop==0){
		//Create the customer pdf	
		
		//Get the report to be pdfed from the aurgument of the macro
		$pdfreportID	= $vars[2];
		//retreive the css and html of the pdf report
		$reportCSS 		= $g->Dlookup('CSS','wb_roi_reports','wb_roi_report_ID=' . $pdfreportID);
		$reportHTML 	= $g->Dlookup('html','wb_roi_reports','wb_roi_report_ID=' . $pdfreportID);
		
		//Get the Customer Email Report
		$emailreportID		= $vars[3];  
		$cust_emailbody		= $g -> DLookup('HTML','wb_roi_reports','wb_roi_report_ID=' . $emailreportID);
		  
		//Get the ROI Owner Email Report
		$emailreportID		= $vars[5];  
		$client_emailbody	= $g -> DLookup('HTML','wb_roi_reports','wb_roi_report_ID=' . $emailreportID);
		
		
		//Get a list of the instance values for this instance
		 $SQL = "SELECT * 
	    		FROM `wb_roi_instance_values_formatted`
	    		WHERE instanceID=$instanceID;";
	    
		$list = $g->returnarray($SQL);
	
		$numrows = count($list);
		$x=0;
		if($numrows>0){
		  foreach($list as $r){
			$x = $x + 1;	
			$reportHTML 		= str_replace('<tag>' . $r['field'] . '</tag>', $r['value'], $reportHTML);
			$cust_emailbody 	= str_replace('<tag>' . $r['field'] . '</tag>', $r['value'], $cust_emailbody);
			$client_emailbody 	= str_replace('<tag>' . $r['field'] . '</tag>', $r['value'], $client_emailbody);
			//$customer_email_HTML = str_replace('<tag>' . $r['field'] . '</tag>', $r['value'], $customer_email_HTML);
			//$C3_email_HTML = str_replace('<tag>' . $r['field'] . '</tag>', $r['value'], $C3_email_HTML);
		    
		  }
		}
		
		//Need to loop through the standard values table and replace accodingly
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
			$cust_emailbody = str_replace('<tagstd>' . $r['stdfieldID'] . '</tagstd>', $r['value'], $cust_emailbody);
			$client_emailbody = str_replace('<tagstd>' . $r['stdfieldID'] . '</tagstd>', $r['value'], $client_emailbody);
		    //$search = "/[^<tag>](.*)[^<\/tag>]/";
		    //$replace = $r['value'];  //The given value for the field
		  }
		}
		
		
		
		//Finalize the report to be pdfed
		$report = '<html><head>' . $reportCSS . '</head><body>' . $reportHTML . '</body></html>';
			
		$stylesheet = file_get_contents('../assets/css/pdfstyle.css');
		$comp_stylesheet = file_get_contents('../assets/css/style.css');
		
		//Get the orientation of the page
		$orient = $g->DLookup('PDForientation','wb_roi_reports','wb_roi_report_ID=' . $pdfreportID);
		switch ($orient) {
		    case 0:
		        $page = 'A4';
		        break;
		    case 1:
		        $page = 'A4-L';
		        break;
		    
		    default:
		        $page = 'A4';
		}
		//Get the name of the file that should be created
		$filename = $g->DLookup('filename','wb_roi_reports','wb_roi_report_ID=' . $pdfreportID);
		
		$mpdf = new mPDF('c', $page);
			
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->WriteHTML($comp_stylesheet,1);
			
		$mpdf->WriteHTML($report);
		
		$mpdf->Output('../assets/customwb/' . $wbroiID . '/pdf/' . $wbroiID . '-' . $instanceID . '.pdf','F');
		$mpdf->Output('../assets/customwb/' . $wbroiID . '/pdf/' . $filename . '.pdf','F');
		
		
		  // *******************Send Email to ROI Owner **********************************

		  //Lookup the right email address
		  //Get the submital email from the aurguments of the macro
		  $client_email 	= $vars[7];
		  
		  //Get the message from the macro aurguments
		  $emailsubject 	= $vars[6];
		  
		  //The body of the email was already retrieved and tags replaced as needed
		
		  //Create the Transport
		  $transport = Swift_AWSTransport::newInstance( 'AKIAIWQ3DPP7HAL33PIA', '7LtELaOSutm4jtPVOQI/Ucw/NuqKhCLctOiIxWVF' );
		
		  //Create the Mailer using your created Transport
		  $mailer = Swift_Mailer::newInstance($transport);
		 
		  //Create the message
		  $message = Swift_Message::newInstance()
		  ->setSubject($emailsubject)
		  ->setFrom(array('ExpressROI@theroishop.com'))
		  ->setTo(array($client_email))
		  ->setBody($client_emailbody, 'text/html')
		  ->attach(Swift_Attachment::fromPath('../assets/customwb/' . $wbroiID . '/pdf/' . $filename . '.pdf'));

		  $mailer->send( $message );
			
		  // *******************Send Email to Customer **********************************

		  //Lookup the right email address
		  //Get the field that contains the email address
		  $emailfld			= $g->DLookup('fieldID','wb_roi_fields','InputType=100 AND wb_roi_ID=' . $wbroiID);
		  
		  //Now get the value of this field for this instance
		  $customer_email 	= $g->Dlookup('value','wb_roi_instance_values','field=' . $emailfld . ' AND instanceID=' . $instanceID);
		  
		  //Get the message from the macro aurguments
		  $emailsubject 	= $vars[4];
		  
		  //Get the from email address the client would prefer to use.
		  $emailfrom 	= $vars[10];
		  
		  //The body of the email was already retrieved and tags replaced as needed
		
		  //Create the Transport
		  $transport = Swift_AWSTransport::newInstance( 'AKIAIWQ3DPP7HAL33PIA', '7LtELaOSutm4jtPVOQI/Ucw/NuqKhCLctOiIxWVF' );
		
		  //Create the Mailer using your created Transport
		  $mailer = Swift_Mailer::newInstance($transport);
		 
		  //Create the message
		  $message = Swift_Message::newInstance()
		  ->setSubject($emailsubject)
		  ->setFrom(array($emailfrom . '@theroishop.com'))
		  ->setTo(array($customer_email))
		  ->setBody($cust_emailbody, 'text/html')
		  ->attach(Swift_Attachment::fromPath('../assets/customwb/' . $wbroiID . '/pdf/' . $filename . '.pdf'));

		  $mailer->send( $message );	
			
			
		  // *******************Pass Completion variables back to the page **********************************
		
		  $nextaction 	= $vars[8];
		  $nextarg		= $vars[9];
		
		  $returnstrng 	= $nextaction . '<;>' . $nextarg;	
		
		  echo $returnstrng;
		
	} //End if immedstop==0
	
	//End Macro ID=1
	
	
	
	//2 -  Name: Complete ROI
	/**
	 * 		Name: 			Complete ROI
	 * 		Description: 	This macro 
	 * 						1. checks that all required fields are completed, 
	 * 						2. creates the customer pdf, 
	 * 						3. sends an email to the customer, 
	 * 						4. sends an email to the ROI owner (or designee), and 
	 * 						5. redirects the page to a followup page.
	 */	
	
	
	/*
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
	$mpdf->Output('../assets/customwb/' . $wbroiID . '/pdf/C3SolutionsROI.pdf','F');

	
	

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
  ->attach(Swift_Attachment::fromPath('../assets/customwb/' . $wbroiID . '/pdf/C3SolutionsROI.pdf'));

 
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