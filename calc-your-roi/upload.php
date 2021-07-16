<?php

	if(!is_dir('../company_specific_files/' . $_GET['comp'] . '/')) {
		
		mkdir('../company_specific_files/' . $_GET['comp'] . '/');
		mkdir('../company_specific_files/' . $_GET['comp'] . '/logo/');
	}	
	
	$image = $_FILES['file']['tmp_name'];
	$destdir = '../company_specific_files/' . $_GET['comp'] . '/logo/logo.png';
	move_uploaded_file($image, $destdir);
	
?>