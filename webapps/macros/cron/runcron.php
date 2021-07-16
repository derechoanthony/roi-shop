
<?php 

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once( "$root/webapps/core/init.php" ); 									// Sets up connection to database

require "$root/webapps/macros/cron/cron_functions-v1.php";						    // This is where the functions for each macro are stored
require '../../php/swiftmailer/lib/swift_required.php';							//Required for emailing
require_once( "../../mpdf/mpdf.php" );												//Required for pdfs
require "../../php/numeral/vendor/autoload.php";												//Required for pdfs

use Stillat\Numeral\Languages\LanguageManager;
use Stillat\Numeral\Numeral;

// Create the language manager instance.
$languageManager = new LanguageManager;

// Create the Numeral instance.
$formatter = new Numeral;

// Now we need to tell our formatter about the language manager.
$formatter->setLanguageManager($languageManager);


$m = new MacroFunctions();	
//Get all the jobs that need to be run

$crons 	= $g->GetCronJobs();
$len 	= count($crons);


for ($x=0; $x < $len; $x++) {
	$cron 	= $crons[$x];
	$args 	= json_decode($cron['aurgs'],true);
	$cronID = $cron['cronID'];
	
	
	switch ($cron['macro']) {
		case 1:
		//Send an email
		$recipient 		= $args['recipient'];
		$ccrecipient 	= $args['ccrecipient'];
		$bccrecipient 	= $args['bccrecipient'];
		$subject 		= $args['subject'];
		$body			= $args['body'];
		$from			= $args['emailFrom'];
		$attach			= $args['attachment'];
		$attachname		= $args['attachmentname'];

		$response = $m->sendEmail($recipient,$ccrecipient,$bccrecipient,$subject,$from,$body,$attach,$attachname);

		$g->cronStatus($cron['cronID'],10,$response);
		break;
				
		case 2:	
		//Create a PDF Report

		$instanceID = $cron['instanceID'];
		$reportID	= $args['reportID'];
		
		$response = $m->createPDF($instanceID,$reportID);
		$g->cronStatus($cron['cronID'],10,$response);
		break;
			
	}
	

	
};


?>

