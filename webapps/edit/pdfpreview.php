<?php

	session_start('pdfcreation');
	
	require_once( "../mpdf/mpdf.php" );
	//require_once("../../calc-your-roi/2/inc/vendor/autoload.php");
	//require_once("../../calc-your-roi/2/inc/swiftmailer/lib/swift_required.php");

	//require '../php/swiftmailer/lib/swift_required.php';
	//require_once("../php/vendor/autoload.php");

	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once( "$root/webapps/core/init.php" ); 									// Sets up connection to database
	
	//$instanceID=459;
	$reportID 	= $_POST['reportid'];
	$wbroiID 	= $g->Dlookup('wb_roi_ID','wb_roi_reports','wb_roi_report_ID=' . $reportID);
	$orient		= $g->Dlookup('PDForientation','wb_roi_reports','wb_roi_report_ID=' . $reportID);
	
	
	
	
	switch ($orient) {
		case 0:
			$orient = '';
			break;
		case 1:
			$orient = '-L';
			break;
		default:
			$orient = '';
			
	}
	
	//Lookup the wbroiID
	//$wbroiID	= $g->Dlookup('wbroiID','wb_roi_instance','instanceID=' . $instanceID);

	$reportCSS 	= $g->Dlookup('CSS','wb_roi_reports','wb_roi_report_ID=' . $reportID);
	$reportHTML = $g->Dlookup('html','wb_roi_reports','wb_roi_report_ID=' . $reportID);
	

	
	//Loop through report and get contents of inputs and outputs
	//*****************Get the demo values for each field used***********************(Add later)
	//1. Get a list of the instance values for this instance
	 $SQL = "SELECT * 
    		FROM `wb_roi_fields`
    		WHERE wb_roi_ID=$wbroiID;"; //Need to add field mapping so that multiple calculators may use same field list.
    
	//echo $SQL;
	$list = $g->returnarray($SQL);

	$numrows = count($list);
	if($numrows>0){
	  foreach($list as $r){
		$reportHTML = str_replace('<tag>' . $r['fieldID'] . '</tag>', $r['demovalue'], $reportHTML);
	  }
	}


	//Delete the existing file if it exists
	//delete('../assets/customwb/' . $wbroiID . '/pdf/preview-' . $reportID . '.pdf');
	
	
	$report = '<html><head>' . $reportCSS . '</head><body class="pdfbody">' . $reportHTML . '</body></html>';
	//echo $report;
	
	
		
	$stylesheet = file_get_contents('../assets/css/pdfstyle.css');
	$comp_stylesheet = file_get_contents('../assets/css/style.css');
	
	$mpdf->showImageErrors = true;	
	
	$mpdf = new mPDF('s', 'Letter' . $orient);
	
	$mpdf->WriteHTML($stylesheet,1);
	$mpdf->WriteHTML($comp_stylesheet,1);
		
	$mpdf->WriteHTML($report);
	
	$mpdf->Output('../assets/customwb/' . $wbroiID . '/pdf/preview-' . $reportID . '.pdf','F');

?>