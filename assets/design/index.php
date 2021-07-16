<?php

    // Check permissions (for now only mfarber@theroishop is allowed access)

	/* Setup the root of the document */
	$root = realpath($_SERVER['DOCUMENT_ROOT']);
	
	/* Database connection */
	require_once("$root/db/db_connection.php");
	require_once("$root/db/db_interaction.php");
	
	$user_information = new db_interaction($db);
	$user_specs = $user_information->get_user_info_by_id($_SESSION['UserId']);
	
	if( $_SESSION['Username'] == "mfarber@theroishop.com" )  {
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
		<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />
		
		<link href="/assets/roishop/rs.bootstrap.css" rel="stylesheet"/>
		<link href="/assets/enterprise/nestable/css/jquery.nestable.css" rel="stylesheet">
		<link href="/assets/enterprise/summernote/css/summernote.css" rel="stylesheet">
		<link href="/assets/enterprise/tribute/tribute.css" rel="stylesheet">
	</head>
	
	<body class="canvas-menu pace-done">
		
		<div id="wrapper">
			<?php include('nav/header.php'); ?>
		</div>
		
	</body>
	
	<!-- Mainly scripts -->
	<script src="/assets/enterprise/jquery/js/jquery-2.1.1.js"></script>
	<script src="/assets/enterprise/bootstrap/js/bootstrap.bundle.js"></script>
	<script src="/assets/enterprise/bootstrap-table/js/bootstrap-table.js"></script>
	<script src="/assets/enterprise/bootstrap-table/extensions/editable/bootstrap-table-editable.js"></script>
	<script src="/assets/enterprise/bootstrap-table/extensions/editable/bootstrap-editable.js"></script>
	<script src="/assets/enterprise/bootstrap-table/extensions/export/bootstrap-table-export.js"></script>
	<script src="/assets/enterprise/bootstrap-table/extensions/export/tableExport.js"></script>	
	<script src="/assets/enterprise/calc/js/calc.functions.js"></script>
	<script src="/assets/enterprise/chosen/js/chosen.jquery.js"></script>
	<script src="/assets/enterprise/highcharts/js/highcharts.js"></script>
	<script src="/assets/enterprise/highcharts/js/highcharts-3d.js"></script>
	<script src="/assets/enterprise/highcharts/js/highcharts-more.js"></script>		
	<script src="/assets/enterprise/highcharts/js/exporting.js"></script>
	<script src="/assets/enterprise/jexcel/js/roishop.jexcel.js"></script>
	<script src="/assets/enterprise/jexcel/js/jsuites.js"></script>
	<script src="/assets/enterprise/quovolver/js/jquery.quovolver.min.js"></script>
	<script src="/assets/enterprise/roishop/js/rs.bootstrap.js"></script>
	<script src="/assets/enterprise/roishop/js/numeral.js"></script>
	<script src="/assets/enterprise/roishop/js/languages.js"></script>
	<script src="/assets/enterprise/slider/js/nouislider.js"></script>
	<script src="/assets/enterprise/tooltipster/js/jquery.tooltipster.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
	<script src="/assets/enterprise/nestable/js/jquery.nestable.js"></script>
	<script src="/assets/enterprise/summernote/js/summernote.js"></script>
	<script src="/assets/enterprise/tribute/tribute.js"></script>

	<script src="assets/js/select2/select2.min.js"></script>
	<script src="assets/js/bootstrap-select/bootstrap-select.js"></script>
	<script src="assets/js/design/design.initialize.js"></script>
</html>

<?php

	} else {
		
		header("Location: /login?ref=".$_SERVER["REQUEST_URI"]);	
	}
	
?>

