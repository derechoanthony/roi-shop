<?php

	/* Setup the root of the document */
	$root = realpath($_SERVER['DOCUMENT_ROOT']);
	
	/* Database connection */
	require_once("$root/db/db_connection.php");
	require_once("$root/db/db_interaction.php");
	
	$roi_information = new db_interaction($db);

	require_once("$root/php/email/swiftmailer/lib/swift_required.php");
	require_once("$root/php/verification/enterprise/verification.php");
?>

<html>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<!-- Define title of the ROI -->
		<title><?= $roi_information->get_roi_name(); ?></title>
		
		<!-- Include the ROI's CSS Files -->
		<link href="assets/css/bootstrap/bootstrap.min.css" rel="stylesheet">
		<link href="assets/css/calculator/style.css" rel="stylesheet">
		<link href="assets/css/iCheck/custom.css" rel="stylesheet">
		<link href="assets/css/font-awesome/font-awesome.css" rel="stylesheet">
		<link href="assets/css/tooltipster/tooltipster.css" rel="stylesheet">
		<link href="assets/css/chosen/chosen.css" rel="stylesheet">
		<link href="assets/css/slider/slider.css" rel="stylesheet">
		<link href="assets/css/table/bootstrap-table.css" rel="stylesheet">
		<link href="assets/css/x-editable/bootstrap-editable.css" rel="stylesheet">
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		
	</head>
	
	<body class="pace-done fixed-sidebar fixed-nav fixed-nav-basic">
	
		<div id="wrapper">
		
		</div>
		
		<script src="assets/js/jquery/jquery-2.1.1.js"></script>
		<script src="assets/js/jquery/jquery-ui.min.js"></script>
		<script src="assets/js/bootstrap/bootstrap.js"></script>
		<script src="assets/js/tooltipster/jquery.tooltipster.min.js"></script>
		<script src="assets/js/highcharts/highcharts.js"></script>
		<script src="assets/js/highcharts/highcharts-3d.js"></script>
		<script src="assets/js/highcharts/highcharts-more.js"></script>
		<script src="assets/js/highcharts/exporting.js"></script>		
		<script src="assets/js/calx/numeral.js"></script>
		<script src="assets/js/calx/jquery-calx-2.1.1.js"></script>
		<script src="assets/js/video/video.functions.js"></script>
		<script src="assets/js/metis-menu/jquery.metisMenu.js"></script>
		<script src="assets/js/iCheck/iCheck.min.js"></script>
		<script src="assets/js/chosen/chosen.jquery.js"></script>
		<script src="assets/js/slider/slider.js"></script>
		<script src="assets/js/table/roishop-bs-table.js"></script>
		<script src="assets/js/x-editable/bootstrap-editable.js"></script>
		
		<script src="assets/js/calculator/jquery.roifunctions.js"></script>
		<script src="assets/js/roishop/jquery.roishop.js"></script>
		<script src="assets/js/calculator/setup.plugins.js"></script>
		<script src="assets/js/calculator/roishop.initialize.js"></script>
		
	</body>
	
</html>