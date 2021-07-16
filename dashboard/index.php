<?php
	
	/* Setup the root of the document */
	$root = realpath($_SERVER['DOCUMENT_ROOT']);
	
	/* Database connection */
	require_once("$root/db/db_connection.php");
	require_once("$root/db/db_interaction.php");
	
	$user_information = new db_interaction($db);
	$user_specs = $user_information->get_user_info_by_id($_SESSION['UserId']);
	
	if( $_SESSION['Username'] )  {
?>

<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>The ROI Shop | Dashboard</title>

		<link href="assets/css/bootstrap/bootstrap.min.css" rel="stylesheet">
		<link href="assets/css/theme/style.css" rel="stylesheet">
		<link href="assets/css/font-awesome/font-awesome.css" rel="stylesheet">
		<link href="assets/css/chosen/chosen.css" rel="stylesheet">
		<link href="assets/css/theme/roishop.css" rel="stylesheet">
		<link href="assets/css/iCheck/custom.css" rel="stylesheet">
		<link href="assets/css/bootstrap-select/bootstrap-select.css" rel="stylesheet">
		<link href="assets/css/tagsinput/bootstrap-tagsinput.css" rel="stylesheet">
		<link href="assets/css/select2/select2.min.css" rel="stylesheet">
		<link href="assets/css/starrr/starrr.css" rel="stylesheet">
		<link href="/assets/roishop/rs.bootstrap.css" rel="stylesheet"/>
	</head>
	
	<body class="canvas-menu pace-done">
		
		<div id="wrapper"></div>
		
	</body>
	
	<!-- Mainly scripts -->
	<script src="assets/js/jquery/jquery-2.1.1.js"></script>
	<script src="assets/js/bootstrap/bootstrap.js"></script>
	<script src="assets/js/chosen/chosen.jquery.js"></script>
	<script src="assets/js/iCheck/icheck.min.js"></script>
	<script src="assets/js/bootstrap-select/bootstrap-select.js"></script>
	<script src="assets/js/highcharts/highcharts.js"></script>
	<script src="assets/js/tagsinput/bootstrap-tagsinput.js"></script>
	<script src="assets/js/select2/select2.min.js"></script>
	<script src="assets/js/starrr/starrr.js"></script>
	<script src="/assets/roishop/rs.bootstrap.js"></script>
	
	<script src="assets/js/dashboard/dashboard.initialize.js"></script>
	<script src="assets/js/dashboard/dashboard.builder.js"></script>
</html>

<?php

	} else {
		
		header("Location: /login?ref=".$_SERVER["REQUEST_URI"]);	
	}
	
?>