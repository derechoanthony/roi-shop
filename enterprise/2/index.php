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
		<link href="assets/css/bootstrap-table/bootstrap-table.css" rel="stylesheet">
		<link href="assets/css/loader/style.css" rel="stylesheet">
		<link href="assets/css/calculator/style.css" rel="stylesheet">
		<link href="assets/css/datatables/jquery.dataTables.min.css" rel="stylesheet">
		<link href="assets/css/font-awesome/font-awesome.css" rel="stylesheet">
		<link href="assets/css/tooltipster/tooltipster.css" rel="stylesheet">
		<link href="assets/css/chosen/chosen.css" rel="stylesheet">
		<link href="assets/css/checkbox/checkbox.min.css" rel="stylesheet">
		<link href="assets/css/slider/jquery.nouislider.css" rel="stylesheet">
		<link href="assets/css/magnific-popup/magnific-popup.css" rel="stylesheet">
		<link href="company_specific_files/<?= $roi_information->get_version_id(); ?>/css/style.css" rel="stylesheet">
		
	</head>
	
	<body class="pace-done fixed-sidebar fixed-nav fixed-nav-basic">
	
		<div id="wrapper">
			<nav class="navbar-default navbar-static-side" role="navigation">
				<div class="sidebar-collapse sidebar-navigation">
				
					<ul class="nav" id="side-menu">
						<li class="nav-header">
							<div class="dropdown profile-element">
								<span>
									<img id="company_logo" class="some-button" alt="image" src="company_specific_files/<?= $roi_information->get_version_id(); ?>/logo/logo.png">
								</span>
							</div>
						</li>		
					</ul>
				</div>
			</nav>
			<div id="page-wrapper">
				<div class="row bottom-border">
					<nav class="navbar navbar-fixed-top" role="navigation" style="margin-bottom: 0">
						<div class="navbar-header" style="padding: 15px 10px 15px 25px;">
							<h3><?= $roi_information->get_roi_name(); ?></h3>
						</div>						
						<ul class="nav navbar-top-links navbar-right">
							<li>
								<span class="m-r-sm text-muted welcome-message">Powered by <a href="https:\\www.theroishop.com" style="padding-left: 0;">The ROI Shop</a></span>
							</li>
							<li class="dropdown">
								<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
									My Actions <i class="fa fa-caret-down"></i>
								</a>
<?php
	if($verification_lvl > 1) {
?>
								<ul class="dropdown-menu dropdown-alerts">
									<li>
										<a onclick="verificationModal()">Show Verification Link</a>
									</li>
									<li>
										<a class="change-currency">Change ROI Currency</a>
									</li>									
									<li class="divider"></li>
									<li>
										<a href="../../dashboard/account.php"><i class="fa fa-user"></i> &nbsp; &nbsp;  View Your Profile</a>
									</li>
									<li>
										<a href="../../assets/logout.php"><i class="fa fa-power-off"></i> &nbsp; &nbsp; Log Out</a>
									</li>
								</ul>
<?php
	}
?>
							</li>
<?php
	if($verification_lvl > 1) {
?>
							<li>
								<a href="../../assets/logout.php">
									<i class="fa fa-sign-out"></i> Log Out
								</a>
							</li>
<?php
	}
?>
						</ul>
					</nav>
				</div>
				<div id="roiContent"></div>
				
			</div>
		</div>
		
		<script src="assets/js/jquery/jquery-2.1.1.js"></script>
		<script src="assets/js/jquery/jquery-ui.min.js"></script>
		<script src="assets/js/bootstrap/bootstrap.min.js"></script>
		<script src="assets/js/tooltipster/jquery.tooltipster.min.js"></script>
		<script src="assets/js/chosen/chosen.jquery.js"></script>
		<script src="assets/js/slider/nouislider.js"></script>
		<script src="assets/js/highcharts/highcharts.js"></script>
		<script src="assets/js/calx/numeral.js"></script>
		<script src="assets/js/calx/jquery-calx-2.1.1.js"></script>
		<script src="assets/js/bootstrap-table/roishop-bs-table.js"></script>
		<script src="assets/js/x-editable/bootstrap-editable.js"></script>		
		<script src="assets/js/sticky/jquery.sticky.js"></script>
		<script src="assets/js/video/video.functions.js"></script>
		<script src="assets/js/metis-menu/jquery.metisMenu.js"></script>
		<script src="assets/js/magnific-popup/jquery.magnific-popup.min.js"></script>
		<script src="assets/js/languages/languages.js"></script>
		<script src="assets/js/modal/modals.js"></script>
		
		<script src="assets/js/enterprise/roishop.initialize.js"></script>
		<script src="assets/js/enterprise/jquery.roibuilder.js"></script>
		<script src="assets/js/enterprise/jquery.roifunctions.js"></script>
		<script src="assets/js/roishop/jquery.roishop.js"></script>
		<script src="assets/js/enterprise/setup.plugins.js"></script>
		
		<div id="test-form" class="mfp-hide white-popup-block bs-modal"></div>
		<div class="modal inmodal fade" id="roishop-modal" role="dialog" tabindex="-1" style="display: none;"></div>

		<div class="modal inmodal" id="change-currency" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content animated bounceInRight">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<h4 class="modal-title">Change ROI Currency</h4>
					</div>
					<div class="modal-body">
						<div class="form-horizontal">
							<div class="form-group">
								<label class="control-label col-lg-8 col-md-8 col-sm-12">Currency Symbols for ROI (thousand separators, decimal points and currency symbol): </label>
								<div class="col-lg-4 col-md-4 col-sm-12">
									<select class="current-language chosen-select" data-placeholder="Select Your Language/Country">
										<option></option>
										<optgroup data-label="currency" label="Choose by currency">
											<option class="language-option" data-currency-option="AUD" value="aud">Australian Dollar</option>
											<option class="language-option" data-currency-option="EUR" value="eur">Euro</option>
											<option class="language-option" data-currency-option="GBP" value="gbp">Pound Sterling</option>
											<option class="language-option" data-currency-option="RUB" value="ru">Ruble</option>
											<option class="language-option" data-currency-option="INR" value="inr">Indian Rupee</option>
											<option class="language-option" data-currency-option="USD" value="usd">United States Dollar</option>
										</optgroup>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary update-currency">Update Currency</button>
						<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>		
		
	</body>
	
</html>