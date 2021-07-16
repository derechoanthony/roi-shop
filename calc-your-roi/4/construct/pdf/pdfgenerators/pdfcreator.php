<?php

	require_once("../../../db/constants.php");
	require_once("../../../db/connection.php");	
	require_once("../../../inc/mpdf/mpdf.php");
	
	$stylesheet = file_get_contents('style.css');
	$comp_stylesheet = file_get_contents('../../../company_specific_files/73/css/style.css');
	
	$sql = "SELECT * FROM pdf_builder
			WHERE roi = :roi;";

	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$roiHtml = $stmt->fetchall();
	
	$sql = "SELECT * FROM list_items
			WHERE ListItemID = :roi
			LIMIT 1;";

	$stmt = $db->prepare($sql);
	$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
	$stmt->execute();
	$roiName = $stmt->fetch();
	
	$mpdf = new mPDF('c', 'A4');
	$mpdf->WriteHTML($stylesheet,1);
	$mpdf->WriteHTML($comp_stylesheet,1);
	
	for($i=1; $i<=2; $i++) {
		$pdfPageKeys = array_keys(array_column($roiHtml, 'page'), $i );
			
		foreach($pdfPageKeys as $key) {
				
			$mpdf->WriteFixedPosHTML($roiHtml[$key]['html'], $roiHtml[$key]['pos_x'], $roiHtml[$key]['pos_y'], ( $roiHtml[$key]['width'] ? $roiHtml[$key]['width'] : 297 ), 100);
		}
		
		if($i < 2) {
			
			$mpdf->AddPage();
		}
	}

	$mpdf->Output( $roiName['ListText'].'.pdf', 'D' );
	
?>


