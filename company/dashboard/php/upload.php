<?php

	if(!is_dir('../company_specific_files/' . $_GET['companyid'] . '/')) {
		
		mkdir('../company_specific_files/' . $_GET['companyid'] . '/');
		mkdir('../company_specific_files/' . $_GET['companyid'] . '/logo/');
	}	
	
	$image = $_FILES['file']['tmp_name'];
	$destdir = '../company_specific_files/' . $_GET['companyid'] . '/users.csv';
	move_uploaded_file($image, $destdir);
	
?>