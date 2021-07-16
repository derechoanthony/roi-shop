<?php

	require_once( "../inc/base.php" );
	require_once( "../inc/config.php" );
	require_once( "../php/calculator.actions.php" );
	
	$calculator = new CalculatorActions($db);
	
	$calculatorSpecs = $calculator->retrieveRoiSpecs();
	
	$data = urldecode($_POST['imageData']);
	$content = file_get_contents($data);
	
	if(!is_dir('../company_specific_files/'.$calculatorSpecs['compID'].'/pdfs/')) {
		
		mkdir('../company_specific_files/'.$calculatorSpecs['compID'].'/pdfs/');
	}
	
	file_put_contents('../company_specific_files/'.$calculatorSpecs['compID'].'/pdfs/'.$_GET['chart'].$_GET['roi'].'.png', $content);

?>