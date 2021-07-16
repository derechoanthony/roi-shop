<?php

	/* Setup the root of the document */
	$root = realpath($_SERVER['DOCUMENT_ROOT']);
	
	/* Database connection */
	require_once("$root/db/db_connection.php");
	require_once("$root/php/swiftmailer/lib/swift_required.php");

?>

<!DOCTYPE html>
<html>

	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>The ROI Shop | Dashboard</title>
	
		<link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
		<link href="assets/dashboard/css/style.css" rel="stylesheet">
		<link href="assets/dashboard/css/dashboard.css" rel="stylesheet">
		<link href="assets/dashboard/css/roishop.css" rel="stylesheet">
		<link href="assets/nouislider/css/nouislider.css" rel="stylesheet">
		<link href="assets/magnific-popup/css/magnific-popup.css" rel="stylesheet">
		<link href="assets/iCheck/css/custom.css" rel="stylesheet">
		<link href="assets/chosen/css/chosen.css" rel="stylesheet">
		<link href="assets/starrr/css/starrr.css" rel="stylesheet">
		<link href="assets/tooltipster/css/tooltipster.css" rel="stylesheet">
		<link href="assets/table/css/jexcel.datatables.css" rel="stylesheet">
		<link href="/assets/roishop/rs.bootstrap.css" rel="stylesheet"/>
		<link href="/assets/toastr/css/toastr.css" rel="stylesheet"/>
		<link href="/assets/bootstrap-table/css/bootstrap-table.css" rel="stylesheet"/>
		<link href="/assets/bootstrap-table/css/bootstrap-editable.css" rel="stylesheet"/>
		<link href="/assets/chosen/css/chosen.css" rel="stylesheet">

	</head>

	<body class="pace-done fixed-sidebar fixed-nav">

		<div id="wrapper"></div>

		<script src="/assets/jquery/js/jquery-3.5.1.min.js"></script>
		<script src="/assets/roishop/rs.bootstrap.js"></script>
		<script src="assets/bootstrap/js/bootstrap.js"></script>
		<script src="/assets/bootstrap-table/js/bootstrap-table.js"></script>
		<script src="/assets/bootstrap-table/extensions/editable/bootstrap-table-editable.js"></script>
		<script src="/assets/bootstrap-table/extensions/editable/bootstrap-editable.js"></script>
		<script src="/assets/toastr/js/toastr.js"></script>
		<script src="/assets/chosen/js/chosen.jquery.js"></script>
		<script src="/assets/bootstrap-table/extensions/export/bootstrap-table-export.js"></script>
		<script src="/assets/bootstrap-table/extensions/export/tableExport.js"></script>
		<script src="assets/dashboard/js/dashboard.functions.js?v=1.0"></script>
	</body>
</html>