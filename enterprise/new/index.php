<?php

	/* Setup the root of the document */
	$root = realpath($_SERVER['DOCUMENT_ROOT']);
	
	/* Database connection */
	require_once("$root/db/db_connection.php");
	require_once("$root/enterprise/7/db/db_interaction.php");

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
	
		<link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
		<link href="assets/enterprise/css/style.css" rel="stylesheet">
		<link href="assets/enterprise/css/enterprise.css" rel="stylesheet">
		<link href="assets/nouislider/css/jquery.nouislider.css" rel="stylesheet">
		<link href="assets/magnific-popup/css/magnific-popup.css" rel="stylesheet">
		<link href="assets/iCheck/css/custom.css" rel="stylesheet">
		<link href="assets/chosen/css/chosen.css" rel="stylesheet">
		<link href="assets/tooltipster/css/tooltipster.css" rel="stylesheet">
		<link href="assets/toastr/css/toastr.css" rel="stylesheet">

	</head>

	<body class="pace-done fixed-sidebar fixed-nav fixed-nav-basic">

<?php 
	if ($verification_level != 2){
?>

		<div id="wrapper"></div>

		<script src="assets/jquery/js/jquery-2.1.1.js"></script>
		<script src="assets/bootstrap/js/bootstrap.js"></script>
		<script src="assets/calx/js/numeral.js"></script>
		<script src="assets/calx/js/languages.js"></script>
		<script src="assets/calx/js/jquery-calx-2.1.1.js"></script>
		<script src="assets/nouislider/js/jquery.nouislider.all.min.js"></script>
		<script src="assets/magnific-popup/js/jquery.magnific-popup.min.js"></script>
		<script src="assets/fitvids/js/fitvids.js"></script>
		<script src="assets/quovolver/js/jquery.quovolver.min.js"></script>
		<script src="assets/noty/js/jquery.noty.packaged.min.js"></script>
		<script src="assets/iCheck/js/icheck.min.js"></script>
		<script src="assets/tooltipster/js/jquery.tooltipster.min.js"></script>
		<script src="assets/chosen/js/chosen.jquery.js"></script>
		<script src="assets/highcharts/js/highcharts.js"></script>
		<script src="assets/highcharts/js/highcharts-3d.js"></script>
		<script src="assets/highcharts/js/highcharts-more.js"></script>		
		<script src="assets/highcharts/js/exporting.js"></script>
		<script src="assets/toastr/js/toastr.js"></script>
		<script src="assets/enterprise/js/theroishop.functions.js"></script>
	</body>
</html>

<?php
	}
?>