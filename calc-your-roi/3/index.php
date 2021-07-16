<?php
	
	// Establish connection to the database
	
	include_once("db/constants.php");
	include_once("db/connection.php");
	
	require_once("../../inc/vendor/autoload.php");
	require_once("../../php/swiftmailer/lib/swift_required.php");
	
	include_once("inc/login.actions.php");
	include_once("inc/verification.php");

?>

<!DOCTYPE html>
<html>

	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>Calculate Your ROI</title>

		<link href="css/demandware.css" rel="stylesheet">
		
		<link href="css/bootstrap/bootstrap.min.css" rel="stylesheet">
		<link href="css/calculator/style.css" rel="stylesheet">
		
		<link href="css/font-awesome/font-awesome.css" rel="stylesheet">
		
		<link href="css/tooltipster/tooltipster.css" rel="stylesheet">
		
		<link href="css/slider/jquery.nouislider.css" rel="stylesheet">
		<link href="css/chosen/chosen.css" rel="stylesheet">
		
		<link href="css/icheck/icheck-custom.css" rel="stylesheet">
		
		<link href="css/datatables/jquery.dataTables.min.css" rel="stylesheet">
		
		<link rel="shortcut icon" href="theroishop.ico" type="image/x-icon" />

	</head>

	<body class="pace-done fixed-sidebar fixed-nav fixed-nav-basic">
	
		<!--<a class="store-html-blob">Store ROI HTML</a>
		<a class="load-html-blob">Get ROI HTML</a>
		<a class="make-editable">Make editable</a>-->
		
		
		<div id="wrapper">

			<!-- Build Order:
			
				1. Left Navigation Panel
				2. Top Navigation
				3. Section Holder
				
			-->
			
			<!-- Left Navigation Panel Menu -->
			<nav class="navbar-default navbar-static-side" role="navigation">

			
			</nav>
			<!-- End Left Navigation Panel Menu -->
			
			<!-- Main ROI Holder -->
			<div id="page-wrapper" class="gray-bg dashbard-1">
			
				<!-- Main ROI Navigation Header --> 
				<div class="row bottom-border">
					
					<!-- Fixed Top Navbar -->
					<nav class="navbar navbar-fixed-top" role="navigation">
						
						<!-- ROI Title Holder -->
						<div class="navbar-header" style="padding: 15px 10px 15px 25px;">
							<h3 data-roi-title>New Nimble Storage ROI</h3>
						</div>
						<!-- End ROI Title Holder -->
						
						<!-- Navigation List Items -->
						<ul class="nav navbar-top-links navbar-right">
							
							<!-- The ROI Shop Link -->
							<li>
								<span class="m-r-sm text-muted welcome-message">Powered by <a href="https:\\www.theroishop.com" style="padding-left: 0;">The ROI Shop</a></span>
							</li>
							<!-- End The ROI Shop Link -->
							
							<!-- My Actions Dropdown -->
							<li class="dropdown">
								
								<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
									My Actions <i class="fa fa-caret-down"></i>
								</a>
								
								<ul class="dropdown-menu dropdown-alerts">
									
									<li>
										<a onclick="verificationModal()">Show Verification Link</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="../../dashboard/account.php"><i class="fa fa-user"></i> &nbsp; &nbsp;  View Your Profile</a>
									</li>
									<li>
										<a href="../../assets/logout.php"><i class="fa fa-power-off"></i> &nbsp; &nbsp; Log Out</a>
									</li>
					
								</ul>
							</li>
							<!-- End My Actions Dropdown -->
							
							<!-- Log Out -->
							<li>
								<a href="../../assets/logout.php">
									<i class="fa fa-sign-out"></i> Log Out
								</a>
							</li>
							<!-- End Log Out -->
							
						</ul>
						<!-- End Navigation List Items -->
					
					</nav>
					<!-- End Fixed Top Navbar -->
				
				</div>	
				<!-- End Main ROI Navigation Header -->
				
				<div id="verificationLevel" style="display: none;" data-verification="<?= $verification_lvl ?>"></div>
				<div id="roiContent">
				
				</div>
				
				<!-- Insert ROI Sections HERE -->

			</div>
			
		</div>
		
		<div class="modal inmodal" id="modal-shell" tabindex="-1" role="dialog" aria-hidden="true"></div>
		
		<script src="js/jquery/jquery-2.1.1.js"></script>
		<script src="js/bootstrap/bootstrap.min.js"></script>		
		<script src="js/metisMenu/jquery.metisMenu.js"></script>
		
		<script src="js/element_builder.js"></script>
		<script src="js/hardware.js"></script>
		
		<script src="js/charting/highcharts/modules/highcharts.js"></script>
		<script src="js/charting/highcharts/modules/highcharts-3d.js"></script>
		<script src="js/charting/highcharts/modules/highcharts-more.js"></script>		
		<script src="js/charting/highcharts/modules/highcharts-exporting.js"></script>
		<script src="js/charting/highcharts/modules/data.js"></script>
		
		<script src="js/datatables/jquery.dataTables.min.js"></script>
		
		<script src="js/calculator/numeral.js"></script>
		<script src="js/calculator/jquery-calx-2.1.1.js"></script>		
		<script src="js/tooltipster/jquery.tooltipster.min.js"></script>
		<script src="js/quovolver/jquery.quovolver.min.js"></script>
		<script src="js/icheck/icheck.min.js"></script>
		<script src="js/htmltopdf/xepOnline.jqPlugin.js"></script>
		
		<script src="js/slider/jquery.nouislider.all.min.js"></script>
		<script src="js/fitvids/fitvids.js"></script>
		<script src="js/chosen/chosen.jquery.js"></script>
		
		<script src="js/calculator/buildchart.js"></script>
		<script src="js/modal/modals.js"></script>
		
		<script src="js/calculator/theroishop.masterfunctions.js"></script>
		<script src="js/calculator/withinviewport.js"></script>
		<script src="js/calculator/jquery.withinviewport.js"></script>
		<script src="js/calculator/theroishop.sliders.js"></script>
		<script src="js/calculator/theroishop.toggles.js"></script>
		<script src="js/noty/jquery.noty.packaged.min.js"></script>
		
		<script src="js/calculator/languages/languages.js"></script>

	</body>
	
</html>