<?php

	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/constants.php");
	require_once("$root/db/connection.php");
	
	$data = urldecode($_POST['imageData']);
	$content = file_get_contents($data);	
	
	/*if(!is_dir('company_specific_files/'.$_SESSION['roiStructure']['company_id'].'/pdfs/')) {
		
		mkdir('company_specific_files/'.$_SESSION['roiStructure']['company_id'].'/pdfs/');
	}
	
	file_put_contents('company_specific_files/'.$_SESSION['roiStructure']['company_id'].'/pdfs/'.$_GET['roi'].'chart'.$_GET['chrt'].'.png', $content);
	*/
	file_put_contents("$root/enterprise/company_specific_files/images/".$_GET['roi'].'chart'.$_GET['chrt'].'.png', $content);

?>