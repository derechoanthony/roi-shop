<?php

	// Establish connections to the database
	
	require_once('db/constants.php');
	require_once('db/connection.php');

?>

<html>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<!-- Define title of the ROI -->
		<title>ROI Title</title>
		
		<!-- Include the ROI's CSS Files -->
		<link href="css/bootstrap/bootstrap.min.css" rel="stylesheet">
		<link href="css/loader/style.css" rel="stylesheet">
		<link href="css/calculator/style.css" rel="stylesheet">
		<link href="css/datatables/jquery.dataTables.min.css" rel="stylesheet">
		<link href="css/font-awesome/font-awesome.css" rel="stylesheet">
		<link href="css/tooltipster/tooltipster.css" rel="stylesheet">
		<link href="css/chosen/chosen.css" rel="stylesheet">
		
	</head>
	
	<body class="pace-done fixed-sidebar fixed-nav fixed-nav-basic">

		<header class="entry-header">

			<h1 class="entry-title">Please wait while your ROI is loaded</h1>
		</header>
		
		<div id="loader-wrapper">
			<div id="loader"></div>

			<div class="loader-section section-left"></div>
            <div class="loader-section section-right"></div>

		</div>
		
		<div id="wrapper">
		
			<!-- Wrapper contains the ROI content -->
			
			<nav class="navbar-default navbar-static-side" role="navigation">
			
				<!-- Left Sidebar Navigation -->
				
			</nav>
	
			<div id="page-wrapper" class="gray-bg dashboard-1">
			
				<!-- Main ROI Content Holder -->
				<div class="row bottom-border">
				
					<!-- Fixed Top Navbar -->
					<nav class="navbar navbar-fixed-top" role="navigation">
					
						<!-- ROI Title -->
						<div class="navbar-header" style="padding: 15px 10px 15px 25px;">
							<h3 data-roi-title>ROI Title</h3>
						</div>
						
						<ul class="nav navbar-top-links navbar-right">
							
							<li>
								<span class="m-r-sm text-muted welcome-message">Powered by <a href="https:\\www.theroishop.com" style="padding-left: 0;">The ROI Shop</a></span>
							</li>

							<li class="dropdown">
								
								<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
									My Actions <i class="fa fa-caret-down"></i>
								</a>
								
								<ul class="dropdown-menu dropdown-alerts">
									
									<li>
										<a onclick="verificationModal()">Show Verification Link</a>
									</li>
									<li>
										<a onclick="resetVerificationModal()">Reset Verification Link</a>
									</li>
									<li>
										<a class="showHideSections">Show/Hide Sections</a>
									</li>
									<li>
										<a class="change-currency">Change ROI Currency</a>
									</li>
									<li>
										<a onclick="contributorsModal()">Add Contributor</a>
									</li>
									<li>
										<a onclick="currentContributorsModal()">View Current Contributors</a>
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
						</ul>
						
					</nav>
					
				</div>
				
				<div id="roiContent">
					
				</div>
				
			</div>
		
		</div>
		
		<script src="js/jquery/jquery-2.1.1.js"></script>
		<script src="js/calculator/setup.plugins.js"></script>
		<script src="js/roi_builder_scripts/element-builder.js"></script>
		<script src="js/roi_builder_scripts/roi-builder.js"></script>
		<script src="js/bootstrap/bootstrap.min.js"></script>
		<script src="js/datatables/jquery.dataTables.min.js"></script>
		<script src="js/chosen/chosen.jquery.js"></script>
		<script src="js/calculator/video/video.functions.js"></script>
		<script src="js/calculator/calx/numeral.js"></script>
		<script src="js/calculator/calx/jquery-calx-2.1.1.js"></script>
		<script src="js/tooltipster/jquery.tooltipster.min.js"></script>
		<script src="js/highcharts/highcharts.js"></script>
		
	</body>
	
</html>