<?php

	require_once( "../../../inc/base.php" );
	require_once( "../../../inc/config.php" );
	require_once( "../../../assets/plugins/mpdf/mpdf.php" );
	require_once( "../../../php/calculator.actions.php" );
	
	$calculator = new CalculatorActions($db);
	$roiSpecs = $calculator->retrieveRoiSpecs();
	$roiPreferences = $calculator->retrieveRoiPreferences();
	$roiOwner = $calculator->retrieveRoiOwner();
	$roiSections = $calculator->retrieveRoiSections();
	
	$pdfBuilder = $calculator->retrievePdfBuilder();
	$pdfTotalPages = $calculator->retrieveTotalPages();
	
	$stylesheet = file_get_contents('style.css');
	$comp_stylesheet = file_get_contents('../../../company_specific_files/'.$roiSpecs['compID'].'/css/style.css');
	$playfair = file_get_contents('https://fonts.googleapis.com/css?family=Playfair+Display');
	$monserrat = file_get_contents('https://fonts.googleapis.com/css?family=Montserrat');
	
	$mpdf = new mPDF('c', 'A4-L');
	$mpdf->WriteHTML($stylesheet,1);
	$mpdf->WriteHTML($comp_stylesheet,1);
	$mpdf->WriteHTML($playfair,1);
	$mpdf->WriteHTML($monserrat,1);
	
	for ($i=1; $i <= $pdfTotalPages['MAX(page)']; $i++ ) {
	
		foreach($pdfBuilder as $pdfLine) {
			
			if( $pdfLine['page'] == $i ) {
				
				$GLOBALS['mpdf']->WriteFixedPosHTML($pdfLine['html'], $pdfLine['pos_x'], $pdfLine['pos_y'], ( $pdfLine['width'] ? $pdfLine['width'] : 297 ), 100);
			}
		}
		
		if( $i > 1 ){ $GLOBALS['mpdf']->SetFooter('Link to the ROI: https://www.theroishop.com/calc-your-roi/?roi='.$_GET['roi'].'&v='.$roiPreferences['verification_code']); }
		
		if($i < $pdfTotalPages['MAX(page)']) {
			
			$GLOBALS['mpdf']->AddPage();
		}
	}

	$mpdf->Output( $roiPreferences['roi_title'].'.pdf', 'D' );
	
?>


