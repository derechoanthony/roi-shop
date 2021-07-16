<?php
	
	session_start('pdfcreation');
	
	require_once( "../mpdf/mpdf.php" );	
		
	$stylesheet = file_get_contents('../css/pdfstyle.css');
	$comp_stylesheet = file_get_contents('../css/style.css');
		
	$mpdf = new mPDF('c', 'A4');
		
	$mpdf->WriteHTML($stylesheet,1);
	$mpdf->WriteHTML($comp_stylesheet,1);
		
	$mpdf->WriteHTML($_SESSION['pdfhtml']);
	$mpdf->Output('DiscoverOrg ROI Calculation.pdf','D');
	
?>


