<?php

	require_once( "../../../webapps/mpdf/mpdf.php" );

	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/constants.php");
	require_once("$root/db/connection.php");
	require "$root/webapps/core/functions/general.php";
	require "$root/webapps/core/functions/weblets.php";
	
	$g = new GeneralFunctions();
	
	$reportID 	= 164;
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

    $stylesheet = file_get_contents('../../../webapps/assets/css/pdfstyle.css');
    $comp_stylesheet = file_get_contents('../../../webapps/assets/css/style.css');
	
	$roiPreferences = $g->Dlookup('roi_title','ep_created_rois','roi_id=' . $_GET['roi']);
		
	$report = '<html><head>' . $reportCSS . '</head><body class="pdfbody">' . $reportHTML . '</body></html>';

    $sql = "SELECT * FROM hidden_entities
            WHERE type = 'section' AND roi = ?";

    $stmt = $db->prepare($sql);	
    $stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
    $stmt->execute();
	$hidden_sections = $stmt->fetchall();
	
	$hidden = '';
	foreach($hidden_sections as $hidden){
		$report = str_replace("section" . $hidden['entity_id'], '', $report);
	}

	$mpdf->showImageErrors = true;	
	
    $mpdf = new mPDF('s', 'A4' . $orient);
    
    $mpdf->WriteHTML($stylesheet, 1);
    $mpdf->WriteHTML($comp_stylesheet, 1);
	$mpdf->WriteHTML($report);
	
	$mpdf->Output( $roiPreferences. '.pdf', 'D' );

?>