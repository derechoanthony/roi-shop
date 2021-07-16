<?php

	/* Setup the root of the document */
	$root = realpath($_SERVER['DOCUMENT_ROOT']);
	
	/* Database connection */
	require_once("$root/db/db_connection.php");
	require_once("db/db_interaction.php");

	require_once("$root/php/swiftmailer/lib/swift_required.php");

	$enterprise = new db_interaction($db);

	$verification_level = 0;
	if( isset($_GET['v']) && isset($_GET['roi']) ){
		$ver_user = $enterprise->verifyUser();
		if($ver_user){ 
			$email_protected = $enterprise->isEmailProtected();
			if( $email_protected['email_protected'] == 1 ){
				$verification_level = 2;
			} else {
				$verification_level = 1; 
			}
		}
	}

	if ( isset($_POST['email']) ){
		$grant_access = $enterprise->checkEmailVerification();

		if (count($grant_access) > 0){
			$verification_level = 1;
		}
	}

	if( isset( $_SESSION['Username'] ) && isset( $_GET['roi'] ) ) {
		$calculatorOwner = $enterprise->roiOwner();
		if( rtrim(strtolower($calculatorOwner['username'])) === rtrim(strtolower($_SESSION['Username'])) || $_SESSION['Username'] == 'mfarber@theroishop.com' ){
			$verification_level = 3;
		}
		
		$calculatorManager = $enterprise->roiManager();
		if( rtrim(strtolower($calculatorManager['username'])) === rtrim(strtolower($_SESSION['Username'])) ){
			$verification_level = 3;
		}
	}

	if( isset( $_SESSION['Username'] ) && isset( $_GET['roi'] ) ) {
		$calculatorAdmin = $enterprise->userAdmin();
		if($calculatorAdmin['permission']>0) {
			$verification_level = 4;
		}
	}

	switch ($verification_level) {
		case 0:
			header("Location: /login?ref=".$_SERVER["REQUEST_URI"]);
			break;
		case 1:
			if( isset($_GET['email']) && $_GET['email']=='false' ){ } else {
				$enterprise->addHit();
			}
			break;
		case 2:
?>
			<title>The ROI Shop | Additional verification needed</title>
			<link rel="stylesheet" type="text/css" href="../../css/style.css" />
			<div class="slider" style="height:400px;">	
				<form id="login" action="#" method="post" style="height:300px;">
					<h3>Additional verification needed!</h3>
					<p>Please enter your email address to gain access to this ROI.</p>
					<p class="failed"><?=$msg?></p>
					<input type="hidden" name="ref" value ="<?php echo $_GET['roi']?>" />
					<fieldset id="inputs">
						<input name="email" id="email" type="text" placeholder="Email" autofocus required>
					</fieldset>
					<fieldset id="actions">
						<input type="submit" name="submit" id="submit" value="Access ROI">
						<a href="../contact-us">Questions? Contact Us</a>
					</fieldset>
				</form>
				<div class="clr"></div>
			</div>
<?php
			break;
		case 3:
			break;
	}
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