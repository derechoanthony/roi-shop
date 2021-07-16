
<?php 

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once( "$root/webapps/core/init.php" ); 									// Sets up connection to database
require '../php/swiftmailer/lib/swift_required.php';							//Required for emailing

$reportid 		= $_POST['reportid'];

$g 			= new GeneralFunctions();
$emailbody 	= $g->DLookup('HTML','wb_roi_reports','wb_roi_report_ID=' . $reportid);
	
		//Create the Transport
		  $transport = Swift_AWSTransport::newInstance( 'AKIAIWQ3DPP7HAL33PIA', '7LtELaOSutm4jtPVOQI/Ucw/NuqKhCLctOiIxWVF' );
		
		  //Create the Mailer using your created Transport
		  $mailer = Swift_Mailer::newInstance($transport);
		 
		  //Create the message
		  $message = Swift_Message::newInstance()
		  ->setSubject('Email Report Test')
		  ->setFrom(array('EmailTest@theroishop.com'))
		  ->setTo(array('crudd@theroishop.com'))
		  ->setBody($emailbody, 'text/html');

		  $mailer->send( $message );

return 'email sent';

?>

