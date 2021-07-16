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
	
		<link href="/assets/enterprise/bootstrap/css/old/bootstrap.min.css" rel="stylesheet">
		<link href="/assets/enterprise/fonts/font-awesome/css/font-awesome.css" rel="stylesheet">
		<link href="/assets/enterprise/nouislider/css/nouislider.css" rel="stylesheet">
		<link href="/assets/enterprise/tooltipster/css/tooltipster.css" rel="stylesheet">
		<link href="/assets/enterprise/toastr/css/toastr.css" rel="stylesheet">
		<link href="/assets/enterprise/jexcel/css/jexcel.datatables.css" rel="stylesheet">
		<link href="/assets/enterprise/roishop/css/jquery.dataTables.min.css" rel="stylesheet">
		
		<link href="assets/enterprise/css/enterprise.css" rel="stylesheet">
		<link href="assets/enterprise/css/style.css" rel="stylesheet">
		<link href="/assets/enterprise/chosen/css/chosen.css" rel="stylesheet">
		<link href='//fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	</head>

	<body class="pace-done fixed-sidebar fixed-nav">
		<div id="wrapper"></div>
		
		<script src="/assets/enterprise/jquery/js/jquery-2.1.1.js"></script>
		<script src="/enterprise/9/assets/bootstrap/js/bootstrap.js"></script>
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
		<script src="/assets/enterprise/noty/js/jquery.noty.packaged.min.js"></script>
		<script src="/assets/enterprise/roishop/js/rs.bootstrap.js"></script>
		<script src="/assets/enterprise/roishop/js/numeral.js"></script>
		<script src="/assets/enterprise/roishop/js/languages.js"></script>
		<script src="/assets/enterprise/waiting/js/waitingfor.js"></script>
		<script src="/assets/enterprise/slider/js/nouislider.js"></script>
		<script src="/assets/enterprise/tooltipster/js/jquery.tooltipster.min.js"></script>
		<script src="/assets/enterprise/toastr/js/toastr.js"></script>
		
		<script src="assets/enterprise/js/enterprise.functions.js"></script>
	</body>
</html>


<?php

	} else {
		
		header("Location: /login?ref=".$_SERVER["REQUEST_URI"]);	
	}
	
?>