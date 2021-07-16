<?php

	//session_start('runmacro');
	$executionStartTime = microtime(true);

	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once( "$root/webapps/core/init.php" ); 									// Sets up connection to database	
	require "$root/webapps/macros/macros_functions-v10.php";						    // This is where the functions for each macro are stored
	//-old require "$root/webapps/core/functions/macros.php";
	require "$root/webapps/core/functions/marketo.php";								// This is where the connectivity for Marketo is stored
	$m = new MacroFunctions();	
	require '../php/swiftmailer/lib/swift_required.php';							//Required for emailing
	require_once( "../mpdf/mpdf.php" );												//Required for pdfs
	
	
	
	//**************** Required for Numeral formatting in php ***********************
	//$seconds = $executionEndTime - $executionStartTime;
	//echo "This macro script took $seconds to execute.";
	
	
	
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
	$aurgs = $g->getMacroAurguments($macroID);
	
	//Run the macro
	// The case corresponds to the standard macroID in the table wb_roi_reports_macros
	switch ($stdmacro) {
		
		case 1:	
			//This is the standard ROI Finish Macro that creates the pdf and sends the emails also sends data to marketo if needed.
			$nextaction = $m->FinishROI($macroID, $instanceID, $aurgs);
			$executionEndTime = microtime(true);
			echo $nextaction;
			break;
		case 2:
			$nextaction = $m->OpenModal($macroID, $instanceID, $aurgs);
			$executionEndTime = microtime(true);
			echo $nextaction;
			break;
		case 3:
			$nextaction = $m->CloseModal();
			$executionEndTime = microtime(true);
			echo $nextaction;
			break;
		
		default:
		
		
		
			//do nothing
	}

	

?>