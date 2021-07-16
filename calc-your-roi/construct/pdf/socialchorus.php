<?php

	require_once( "../../../webapps/mpdf/mpdf.php" );

	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/constants.php");
	require_once("$root/db/connection.php");
	require "$root/webapps/core/functions/general.php";
	require "$root/webapps/core/functions/weblets.php";
	
	$g = new GeneralFunctions();

	$reportID 	= 128;
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

	$reportCSS 	= $g->Dlookup('CSS','wb_roi_reports','wb_roi_report_ID=' . $reportID);
	$reportHTML = $g->Dlookup('html','pdf_builder','roi=' . $_GET['roi']);
	
	$roiPreferences = $g->Dlookup('roi_title','ep_created_rois','roi_id=' . $_GET['roi']);
	
	$report = '<html><head>' . $reportCSS . '</head><body class="pdfbody">' . $reportHTML . '</body></html>';
	
	$mpdf->showImageErrors = true;	
	
	$mpdf = new mPDF('s', 'A4' . $orient);
		
	$mpdf->WriteHTML($report);
	
	$mpdf->Output( $roiPreferences. '.pdf', 'D' );

?>