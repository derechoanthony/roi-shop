<?php

	// Establish connection to the database
	
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/db_connection.php");
	
	require_once("$root/php/classes/roishop.classes.php");

	$ep_created_rois = new ep_created_rois($db);
	$roi_users = new roi_users($db);
	$roi_user_companies = new roi_user_companies($db);
	
	$roi_data   = $ep_created_rois->get_data($_GET['roi'])[0];
	$roi_owner  = $roi_users->get_data($roi_data['user_id'])[0];
	$manager    = $roi_users->get_data($roi_owner['manager'])[0];
	
	require_once("$root/enterprise/includes/verification.php");
	require_once("$root/php/email/swiftmailer/lib/swift_required.php");

?>

<html>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<!-- Define title of the ROI -->
		<title><?= $roi_data['roi_title'] ?></title>
		
		<!-- Include the ROI's CSS Files -->
		<link href="/assets/css/bootstrap/bootstrap.min.css" rel="stylesheet">
		<link href="/assets/css/calculator/e_style.css" rel="stylesheet">
		<link href="/assets/css/datatables/jquery.dataTables.min.css" rel="stylesheet">
		<link href="/assets/css/slider/nouislider.css" rel="stylesheet">
		<link href="/assets/css/chosen/chosen.css" rel="stylesheet">
		<link href="/assets/css/tooltipster/tooltipster.css" rel="stylesheet">
		<link href="/assets/fonts/font-awesome.css" rel="stylesheet">
		<link href="company_specific_files/<?= $roi_data['roi_version_id'] ?>/css/style.css" rel="stylesheet">
	</head>