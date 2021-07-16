<?php

	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/db_connection.php");
	require_once("../../../inc/mpdf/mpdf.php");
	
	$stylesheet = file_get_contents('style.css');
	$comp_stylesheet = file_get_contents('../../../company_specific_files/'.$_SESSION['roiStructure']['company_id'].'/css/style.css');
	
	$sql = "SELECT * FROM pdf_builder
			WHERE roi = :roi;";

	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$roiHtml = $stmt->fetchall();
	
	$sql = "SELECT * FROM ep_created_rois
			WHERE roi_id = :roi
			LIMIT 1;";

	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$roiName = $stmt->fetch();
	
	$mpdf = new mPDF('c', 'A4-L');
	$mpdf->WriteHTML($stylesheet,1);
	$mpdf->WriteHTML($comp_stylesheet,1);
	
	for($i=1; $i<=$_SESSION['pdfMaxPages']['MAX(pageno)']; $i++) {
		$pdfPageKeys = array_keys(array_column($roiHtml, 'page'), $i );
			
		foreach($pdfPageKeys as $key) {
				
			$mpdf->WriteFixedPosHTML($roiHtml[$key]['html'], $roiHtml[$key]['pos_x'], $roiHtml[$key]['pos_y'], ( $roiHtml[$key]['width'] ? $roiHtml[$key]['width'] : 297 ), 100);
		}
		
		if( $i > 1 ){ $GLOBALS['mpdf']->SetFooter('Link to the ROI: https://www.theroishop.com/calc-your-roi/2/?roi='.$_GET['roi'].'&v='.$roiName['verification_code']); }
		
		if($i < $_SESSION['pdfMaxPages']['MAX(pageno)']) {
			
			$mpdf->AddPage();
		}
	}

	$mpdf->Output( $roiName['roi_title'].'.pdf', 'D' );
	
?>


