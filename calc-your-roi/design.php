<?php


		
	/******************************************
		Load all require files on page load
	 ******************************************/
		
	require_once( "../inc/base.php" ); 									// Sets up connection to database
	require_once( "../inc/config.php" ); 								// Defines APP_BASE, APP_URL, $directory and other common file locations
	
?>

<!DOCTYPE html>
<html>

	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>The ROI Shop | Design Your ROI</title>
	
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/font-awesome.css" rel="stylesheet">
		<link href="css/jquery.contextMenu.css" rel="stylesheet">

		<!-- Morris -->
		<link href="../assets/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">

		<!-- Gritter -->
		<link href="../assets/plugins/gritter/jquery.gritter.css" rel="stylesheet">

		<link href="../assets/css/roi_calculator/animate.css" rel="stylesheet">
		<link href="../assets/css/roi_calculator/style.css" rel="stylesheet">
		<link href="../assets/css/roi_calculator/design.css" rel="stylesheet">
		
		<!-- Magnificant Popup -->
		<link href="../assets/plugins/magnific-popup/magnific-popup.css" rel="stylesheet">
		
		<link href="../assets/plugins/nouislider/jquery.nouislider.css" rel="stylesheet">
		
		<link href="../assets/plugins/chosen/chosen.css" rel="stylesheet">
		<link href="../assets/plugins/tooltipster/tooltipster.css" rel="stylesheet">
		<link href="css/plugins/dropzone/basic.css" rel="stylesheet">
		<link href="css/plugins/dropzone/dropzone.css" rel="stylesheet">
		
		<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">
		
		<link href="css/plugins/iCheck/custom.css" rel="stylesheet">
		
		<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

		<link href="css/plugins/summernote/summernote.css" rel="stylesheet">

	</head>

	<body class="fixed-sidebar">
		
		<div id="wrapper">
		
<?php
	
	if(!$_GET['comp']) {
		
		if($_SESSION['Username'] == 'mfarber@theroishop.com') {
			
			require_once( "../php/design.actions.php" );						// Defines all PHP processes for building design
		
			// Initialize design and login actions
			$design = new DesignActions($db);
			
			$adminPrivleges = $design->adminPrivleges();

?>
		<div id="page-wrapper" class="gray-bg dashbard-1">
			<div class="row border-bottom">
				<nav class="navbar navbar-static-top" role="navigation" style="margin: 0;">
					<ul class="nav navbar-top-links navbar-right">
						<li>
							<span class="m-r-sm text-muted welcome-message">Powered by <a href="https:\\www.theroishop.com" style="padding-left: 0;">The ROI Shop</a></span>
						</li>
					</ul>
				</nav>
			</div>
<?php
			foreach($adminPrivleges as $admin) {
?>
				<div class="well admin-link" data-admin-id="<?= $admin['compID'] ?>">
					<h1 class="txt-color-red"><?= $admin['compName'] ?></h1>
				</div>
<?php
			}
			
			if($_SESSION['Username'] == 'mfarber@theroishop.com') {
?>
			<button class="btn btn-primary btn-block create-new-roi"><i class="fa fa-plus-square"></i> Create New ROI</button>
<?php
			}
?>
		</div>
<?php	
			
		} else {
			
			header("Location: /login?ref=".$_SERVER["REQUEST_URI"]);
		}
	} else {
		
		/***********************************
			Set up the design actions
		 ***********************************/	
		
		require_once( "../php/design.actions.php" );						// Defines all PHP processes for building design
		
		// Initialize design and login actions
		$design = new DesignActions($db);
		
		$verification_lvl = $design->checkAdminPermissions();
		
		if($verification_lvl['permission']<1) {
			
			// If the ROI is unverified redirect them to the login screen and append
			// the current ROI to the url. User will be returned after logging in.
				
			header("Location: /login?ref=".$_SERVER["REQUEST_URI"]);
		}

		$designSpecs = $design->retrieveRoiSpecs();
		
?>
			
			<!-- Build the left navigation bar -->
			
			<nav class="navbar-default navbar-static-side" role="navigation">
				<div class="sidebar-collapse">
					<ul class="nav" id="side-menu">
						<li class="nav-header">
							<div class="dropdown profile-element">
								<span>
									<img action="upload.php?comp=<?= $_GET['comp'] ?>" id="company_logo" class="some-button company_logo" alt="Click or Drop an Image Here" style="min-height: 0px; padding: 0px;" src="../company_specific_files/<?= $designSpecs['compID'] ?>/logo/logo.png" />
								</span>
							</div>
						</li>
<?php

		$discoveries = $design->retrieveDiscoveryDocuments();
		
		if($discoveries) {			
?>
						<li id="discovery" class="smooth-scroll">
							<a href="#"><i class="fa fa-calculator"></i> <span class="nav-label">Discovery Documents</span><span class="fa arrow"></span></a>
							<ul class="nav nav-second-level collapse in nav-sections">

<?php

			foreach($discoveries as $discovery) {
?>				
								<li data-discovery-id="<?= $discovery['id'] ?>">
									<a href="#discovery<?= $discovery['id'] ?>" class="section-navigator" data-section-type="discovery"><?= $discovery['title'] ?></a>
								</li>
<?php			
			}
?>
								<li class="pin">
									<a class="add-new-section"> <i class="fa fa-plus-square"></i> Add New Discovery Document</a>
								</li>
							</ul>
						</li>							
<?php
		}
?>		
						<li id="sections" class="smooth-scroll">
							<a href="#"><i class="fa fa-calculator"></i> <span class="nav-label">ROI Sections</span><span class="fa arrow"></span></a>
							<ul class="nav nav-second-level collapse in nav-sections">
<?php

		$sections = $design->retrieveRoiSections();
		
		foreach($sections as $section){
			
			if(!$section['inactive']) {
?>
					
								<li data-section-id="<?= $section['ID'] ?>">
									<a href="#section<?= $section['ID'] ?>" class="section-navigator" data-section-type="section"><?= $section['Title'] ?></a>
								</li>
<?php
			}
		}
?>
								<li class="pin">
									<a class="add-new-section"> <i class="fa fa-plus-square"></i> Add New Section</a>
								</li>
							</ul>
						</li>
<?php

		if($_SESSION['Username'] == 'mfarber@theroishop.com') {
			$pdfPages = $design->retrievePdfPages();
		
			if($pdfPages) {
			
?>
						<li id="pdfs" class="smooth-scroll">
							<a href="#"><i class="fa fa-file-pdf-o"></i> <span class="nav-label">PDF</span><span class="fa arrow"></span></a>
							<ul class="nav nav-second-level collapse in nav-sections">
<?php
				$maxPages = $design->maxPdfPages();
				for( $i=1; $i<=$maxPages['MaxPages']; $i++ ) {
?>				
								<li data-page-num="<?= $i ?>">
									<a href="#pdfpage<?= $i ?>" class="section-navigator" data-section-type="pdf">PDF Page <?= $i ?></a>
								</li>
<?php
				}
?>
							</ul>
						</li>
<?php
			}
		}
?>
						<li id="dashboard">
							<a href="../dashboard"><i class="fa fa-tags"></i> Return to Dashboard</a>
						</li>
					</ul>

				</div>
			</nav>
		
			<div id="page-wrapper" class="gray-bg dashbard-1">				
				<div class="row border-bottom">
					<nav class="navbar navbar-static-top" role="navigation" style="margin: 0;">
						<ul class="nav navbar-top-links navbar-right">
							<li>
								<span class="m-r-sm text-muted welcome-message">Powered by <a href="https:\\www.theroishop.com" style="padding-left: 0;">The ROI Shop</a></span>
							</li>
							<li class="dropdown">
								<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#" aria-expanded="true">My Actions <i class="fa fa-caret-down"></i></a>
								<ul class="dropdown-menu dropdown-alerts">
									<li>
										<a href="../assets/logout.php">
											<i class="fa fa-power-off"></i> Log Out
										</a>
									</li>
								</ul>
							</li>
						</ul>
					</nav>
				</div>
				
<?php
		foreach($discoveries as $discovery) {
?>
				<div data-show-with="discovery" data-discovery-holder-id="<?= $discovery['id'] ?>">
					<div id="discovery<?= $discovery['id'] ?>" class="row border-bottom white-bg dashboard-header">		
						<div class="col-lg-10 col-md-8 col-sm-6 col-xs-12">
							<h1 class="section-title" style="margin-bottom: 20px;"><?= $discovery['title'] ?></h1>
						</div>
						<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
							<button class="btn btn-primary pull-right salesforce-link" style="margin-top: 20px;">Link to Salesforce</button>
						</div>
					</div>
					<div class="row border-bottom gray-bg dashboard-header">
						<div class="col-lg-12">
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="ibox float-e-margins">
										<div class="ibox-title">
											<h5><?= $discovery['title'] ?></h5>
										</div>
										<div class="ibox-content">
											<div class="feed-activity-list">
<?php
			$discoveryQuestions = $design->retrieveDiscoveryQuestions($discovery['id']);
			foreach($discoveryQuestions as $discoveryQuestion) {
?>
												<div class="feed-element" data-discovery-question-id="<?= $discoveryQuestion['ID'] ?>" data-sfdc-link="<?= $designSpecs['sf_integration'] ? '1' : '0' ?>">
													<div>
														<div class="ibox-tools">
															<a class="dropdown-toggle edit-discovery-question">
																<i class="fa fa-pencil"></i>
															</a>
															<a>
																<i class="fa fa-times delete-discovery-question"></i>
															</a>
														</div>
														<strong class="discovery-question-title"><?= $discoveryQuestion['Title'] ?></strong>
													</div>
													<div class="discovery-question-specs" style="display:none;"
														data-discovery-question-title="<?= $discoveryQuestion['Title'] ?>"
														data-discovery-question-type="<?= $discoveryQuestion['Type'] ?>"
														data-discovery-question-format="<?= $discoveryQuestion['Format'] ?>"
														data-discovery-question-choices="<?= $discoveryQuestion['choices'] ?>"
														data-discovery-question-growl="<?= $discoveryQuestion['growl'] ?>"
														data-discovery-question-position="<?= $discoveryQuestion['position'] ?>"
														data-discovery-question-append="<?= $discoveryQuestion['append'] ?>"
														data-discovery-question-placeholder="<?= $discoveryQuestion['Placeholder'] ?>"
														data-discovery-question-tip="<?= $discoveryQuestion['Tip'] ?>"
														data-discovery-question-link="<?= $discoveryQuestion['link'] ?>"
														data-discovery-question-equation="<?= $discoveryQuestion['eq'] ?>"
														data-discovery-question-sfdc="<?= $discoveryQuestion['sfdc_element'] ?>"
													>
													</div>
												</div>												
<?php
			}
?>
												<div class="feed-element pin">
													<div>
														<strong class="discovery-question-title"><a class="add-new-discovery-question"> <i class="fa fa-plus-square"></i> Add a New Discovery Question </a></strong>
													</div>
												</div>												
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>					
				</div>
<?php

		}
?>

				<div data-show-with="section" data-section-holder-id="#dash">
					<div id="dash" class="row border-bottom white-bg dashboard-header">		
						<div class="col-lg-12">
							<h1 style="margin-bottom: 20px;">ROI Dashboard | <?= $designSpecs['retPeriod'] ?> Year Projection 
								<span class="pull-right pod-total section-total grand-total" data-format="($0,0)" data-formula="GRANDTOTAL(\'true\')"</span>
							</h1>
						</div>
					</div>
					<div class="row border-bottom gray-bg dashboard-header">
						<div class="col-lg-12">
							<div class="ibox-content">
								<h3 style="font-size: 18px; font-weight: 700;">Select a section below to review your ROI</h3>
								<p style="font-size: 16px;">
									To calculate your return on investment, begin with the first section below. The information 
									entered therein will automatically populate corresponding fields in the other sections. You 
									will be able to move from section to section to add and/or adjust values to best reflect your 
									organization and process. To return to this screen, click the ROI Dashboard button to the left.
								</p>
							</div>
						</div>
						<div class="sortable-list" style="list-style: none; padding: 0;">
						
<?php
	
		foreach($sections as $section) {
		
			if(!$section['inactive']) {
?>
							<div class="col-lg-3">
								<div class="widget white-bg">
									<div class="p-m row">
										<div class="row">
											<h2 class="col-lg-10 section-title" data-section-id="<?= $section['ID'] ?>"><?= $section['Title'] ?></h2>
											<div class="col-lg-2 ibox-tools no-padding" style="margin-top: 10px;">
												<a class="dropdown-toggle" href="#" data-toggle="dropdown">
													<i class="fa fa-wrench"></i>
												</a>
												<ul class="dropdown-menu dropdown-user">
													<li>
														<a class="change-section-title" data-section-id="<?= $section['ID'] ?>">Change Section Title</a>
													</li>
												</ul>
												<a class="close-link section-inactive" data-section-id="<?= $section['ID'] ?>">
													<i class="fa fa-times"></i>
												</a>
											</div>
										</div>
									</div>
								</div>
							</div>
					
<?php
			}
		}
?>					
							<div class="col-lg-3 pin">
								<div class="widget white-bg">
									<div class="p-m row">
										<div class="row">
											<h2 class="col-lg-12"><a class="add-new-section"> <i class="fa fa-plus-square"></i> Add a New Section </a></h2>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row border-bottom gray-bg dashboard-header testimonials">
						<div class="col-lg-12">
<?php
	
		// Retrieve all testimonials that are associate with the current ROI
		$testimonials = $design->retrieveTestimonials();
		foreach($testimonials as $testimonial) {
?>													
							<div class="row" data-testimonial-id="<?= $testimonial['id'] ?>">
								<div class="col-lg-11">
									<p class="testimonial"><?= $testimonial['testimonial'] ?></p>
									<p class="author"><?= $testimonial['author'] ?></p>
								</div>
								<div class="col-lg-1 ibox-tools no-padding" style="margin-top: 10px;">
									<a class="dropdown-toggle" href="#" data-toggle="dropdown">
										<i class="fa fa-wrench"></i>
									</a>
									<ul class="dropdown-menu dropdown-user">
										<li>
											<a class="change-testimonial">Change Testimonial</a>
										</li>
									</ul>
									<a class="close-link section-inactive">
										<i class="fa fa-times"></i>
									</a>
								</div>
							</div>
							<hr/>
<?php

	}
?>
							<button class="btn btn-primary btn-block add-testimonial"><i class="fa fa-plus-square"></i>   Add New Testimonial</button>
						</div>
					</div>
				</div>
<?php
	
		foreach($sections as $section) {
		
			if(!$section['inactive']) {
?>
				<div data-show-with="section" data-section-holder-id="<?= $section['ID'] ?>">
					<div id="section<?= $section['ID'] ?>" class="row border-bottom white-bg dashboard-header">		
						<div class="col-lg-12">
							<h1 class="section-title" style="margin-bottom: 20px;"><?= $section['Title'] ?></h1>
						</div>
					</div>
					<div class="row border-bottom gray-bg dashboard-header">
						<div class="col-lg-12">
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="ibox float-e-margins">
										<div class="ibox-title">
											<h5><?= $section['Title'] ?></h5>
											<div class="ibox-tools">
												<a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
													<i class="fa fa-wrench"></i>
												</a>
												<ul class="dropdown-menu dropdown-user">
													<li><a class="change-section-writeup">Change the Section Writeup</a></li>
												</ul>
												<a class="close-link">
													<i class="fa fa-times delete-section-video"></i>
												</a>
											</div>
										</div>
										<div class="ibox-content" style="padding-left: 30px;">
											<div class="row">
												<div class="col-md-7 section-writeup" role="alert">
													<p class="caption-text"><?= $section['Caption'] ?></p>
												</div>
												<div class="col-lg-5 video-holder" data-section-id="<?= $section['ID'] ?>">
													<div class="ibox float-e-margins">
														<div class="ibox-title" style="border: none;">
															<h5>Section Video</h5>
															<div class="ibox-tools">
																<a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
																	<i class="fa fa-wrench"></i>
																</a>
																<ul class="dropdown-menu dropdown-user">
																	<li><a class="change-video-src">Change the Video Source</a></li>
																</ul>
																<a class="close-link">
																	<i class="fa fa-times delete-section-video"></i>
																</a>
															</div>
														</div>
<?php
		if($section['Video']) {
?>
														<div class="ibox-content">
															<div class="col-md-12 player">
																<a class="popup-iframe" href="<?= $section['Video'] ?>"></a>
																<iframe width="425" height="239" style="margin-left: 5px;" src="<?= $section['Video'] ?>?rel=0&wmode=transparent' .'" frameborder="0" allowfullscreen></iframe>
															</div>
														</div>
<?php
	}
?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="ibox float-e-margins">
										<div class="ibox-title">
											<h5><?= $section['Title'] ?></h5>
										</div>
										<div class="ibox-content">
											<div class="feed-activity-list">
<?php
		$entries = $design->retrieveEntries($section['ID']);
		foreach($entries as $entry){
?>
												<div class="feed-element" data-entry-id="<?= $entry['ID'] ?>">
													<div>
														<div class="ibox-tools">
															<a class="dropdown-toggle edit-entry">
																<i class="fa fa-pencil"></i>
															</a>
															<a>
																<i class="fa fa-times delete-entry"></i>
															</a>
														</div>
														<strong class="entry-title"><?= $entry['Title'] ?></strong>
													</div>
												</div>												
<?php
	}
?>
												<div class="feed-element pin">
													<div>
														<strong class="entry-title"><a class="add-new-entry"> <i class="fa fa-plus-square"></i> Add a New Entry </a></strong>
													</div>
												</div>												
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>					
				</div>
<?php

			}
		}
?>

<?php
	
		for( $i=1; $i<=$maxPages['MaxPages']; $i++) {
?>
				<div data-show-with="section" data-section-holder-id="<?= $section['ID'] ?>">
					<div id="pdfpage<?= $i ?>" class="row border-bottom white-bg dashboard-header">		
						<div class="col-lg-12">
							<h1 class="section-title" style="margin-bottom: 20px;">PDF Page <?= $i ?></h1>
						</div>
					</div>
					<div class="row border-bottom gray-bg dashboard-header">
						<div class="col-lg-12">
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="ibox float-e-margins">
										<div class="ibox-title">
											<h5>PDF Line Items for Page <?= $i ?></h5>
										</div>
										<div class="ibox-content">
											<div class="feed-activity-list">
<?php
			foreach($pdfPages as $pdfLineItem){
				
				if($pdfLineItem['pageno'] == $i) {
?>
												<div class="feed-element" data-pdf-item-id="<?= $pdfLineItem['id'] ?>">
													<div>
														<div class="ibox-tools">
															<a class="dropdown-toggle edit-pdf-line-item">
																<i class="fa fa-pencil"></i>
															</a>
															<a>
																<i class="fa fa-times delete-pdf-item"></i>
															</a>
														</div>
														<textarea class="form-control pdf-html" rows="3" style="resize: vertical;"><?= $pdfLineItem['html'] ?></textarea>
													</div>
												</div>												
<?php
				}
			}
?>
												<div class="feed-element pin">
													<div>
														<strong class="entry-title"><a class="add-new-pdf-item"> <i class="fa fa-plus-square"></i> Add a PDF Item </a></strong>
													</div>
												</div>												
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>	
				</div>
<?php
		}
	}
?>				
			<div class="modal inmodal" id="modal-shell" tabindex="-1" role="dialog" aria-hidden="true"></div>
			</div>
		
    <div class="modal inmodal salesforce-entries-spinner" id="stakeholders" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="ibox pdf-progress">
				<div class="well">
					<h3>Please wait while we retrieve your salesforce objects</h3>
					This may take a few minutes depending on the amount of objects you have...
				</div>
				<div class="ibox-content" style="padding-bottom: 35px;">
					<div id="cssload-contain">
						<div class="cssload-wrap" id="cssload-wrap1">
							<div class="cssload-ball" id="cssload-ball1"></div>
						</div>
						<div class="cssload-wrap" id="cssload-wrap2">
							<div class="cssload-ball" id="cssload-ball2"></div>
						</div>
						<div class="cssload-wrap" id="cssload-wrap3">
							<div class="cssload-ball" id="cssload-ball3"></div>
						</div>
						<div class="cssload-wrap" id="cssload-wrap4">
							<div class="cssload-ball" id="cssload-ball4"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
	
		<!-- Mainly scripts -->
		<script src="js/jquery-2.1.1.js"></script>
		<script src="js/bootstrap.js"></script>
		<script src="js/plugins/noty/js/noty/packaged/jquery.noty.packaged.min.js"></script>
		<script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
		<script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
		<script src="js/modals.js"></script>
		<script src="js/jquery.contextMenu.js"></script>
		<script src="js/jquery.ui.position.js"></script>

		<!-- Peity -->
		<script src="js/plugins/peity/jquery.peity.min.js"></script>
		<script src="js/demo/peity-demo.js"></script>

		<!-- Custom and plugin javascript -->
		<script src="js/inspinia.js"></script> 
		<script src="js/plugins/pace/pace.min.js"></script>

		<!-- jQuery UI -->
		<script src="js/plugins/jquery-ui/jquery-ui.min.js"></script>

		<!-- GITTER -->
		<script src="js/plugins/gritter/jquery.gritter.min.js"></script>
		
		<!-- EayPIE -->
		<script src="js/plugins/easypiechart/jquery.easypiechart.js"></script>

		<!-- Sparkline -->
		<script src="js/plugins/sparkline/jquery.sparkline.min.js"></script>

		<!-- Sparkline demo data  -->
		<script src="js/demo/sparkline-demo.js"></script>

		<!-- ChartJS-->
		<script src="js/plugins/chartJs/Chart.min.js"></script>
		
		<!-- FitVids -->
		<script src="js/plugins/fitvids/fitvids.js"></script>
		
		<!-- Magnificant Popup -->
		<script src="js/plugins/magnificant-popup/jquery.magnific-popup.min.js"></script>
		
	   <!-- NouSlider -->
	   <script src="js/plugins/nouslider/jquery.nouislider.all.min.js"></script>
	   
	   <script src="js/plugins/summernote/summernote.min.js"></script>
	   
		<!-- Chosen -->
		<script src="js/plugins/chosen/chosen.jquery.js"></script>
		
		<!-- Highcharts -->
		<script src="js/plugins/highcharts/highcharts.js"></script>
		<script src="js/plugins/highcharts/highcharts-3d.js"></script>
		<script src="js/plugins/highcharts/highcharts-more.js"></script>		
		<script src="js/plugins/highcharts/exporting.js"></script>

		<!-- Tooltip Functions -->
		<script src="js/plugins/tooltipster/jquery.tooltipster.min.js"></script>
		
		<!-- Toastr -->
		<script src="js/plugins/toastr/toastr.min.js"></script>
		<script src="js/plugins/quovolver/jquery.quovolver.min.js"></script>
		
		<script src="js/plugins/iCheck/icheck.min.js"></script>
		
		<!-- DROPZONE -->
		<script src="js/plugins/dropzone/dropzone.js"></script>
		
		<!-- Load Languages -->
		<script src="../languages/fr.js"></script>
		<script src="../languages/en-gb.js"></script>
		
		<script src="js/plugins/cleanhtml/jquery.htmlClean.min.js"></script>
		
		<script>
		
<?php
	
	if(isset($_GET['comp'])) {
?>
			$(function() {

				if ($(".smooth-scroll").length>0) {
					if($(".header.fixed").length>0) {
						$('.smooth-scroll a[href*=#]:not([href=#]), a[href*=#]:not([href=#]).smooth-scroll').click(function() {
							if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
								var target = $(this.hash);
								target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
								if (target.length) {
									$('html,body').animate({
										scrollTop: target.offset().top-85
									}, 1000);
									return false;
								}
							}
						});
					} else {
						$('.smooth-scroll a[href*=#]:not([href=#]), a[href*=#]:not([href=#]).smooth-scroll').click(function() {
							if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
								var target = $(this.hash);
								target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
								if (target.length) {
									$('html,body').animate({
										scrollTop: target.offset().top
									}, 1000);
									return false;
								}
							}
						});
					}
				}
				
				$(".player").fitVids();
				
				$('.chosen-selector').chosen({
					width: '100%',
					disable_search_threshold: 10
				});			
				
				var Dropzones = new Dropzone("#company_logo");
				Dropzones.on("success", function(file) {
					$('.company_logo').attr('src', '../company_specific_files/<?= $designSpecs['compID'] ?>/logo/logo.png?timestamp=' + new Date().getTime());
				});
				
				// Set Max Widget Height to 0
				var maxHeight = 0;	 
				 
				// Loop through all widgets to determine max height
				$('.widget').each(function(){
					if( $(this).height() > maxHeight ) { maxHeight = $(this).height(); } 
				});
				
				// Set all widget heights to match max height
				$('.widget').each(function(){
					$(this).height( maxHeight );		
				});
				
				$('.add-testimonial').on('click', function(){
					
					// Build the modal
					var modal = {
							
						size		:	'modal-lg',
						animation	:	'fadeIn',
						header		:	{
							title		:	'Add a New Testimonial'
						},
						body		:	{
							content		:	'<form class="form-horizontal">\
												<div class="form-group">\
													<label class="control-label col-lg-3 col-md-3 col-sm-12">Testimonial: </label>\
													<div class="col-lg-8 col-md-8 col-sm-11">\
														<textarea class="form-control testimonial-writeup" rows="3" style="resize: vertical;"></textarea>\
													</div>\
												</div>\
												<div class="form-group">\
													<label class="control-label col-lg-3 col-md-3 col-sm-12">Author: </label>\
													<div class="col-lg-8 col-md-8 col-sm-12">\
														<input class="form-control testimonial-author"/>\
													</div>\
													<div class="infont col-md-1 col-sm-1 input-advice" style="margin-top: 5px;">\
														<div class="col-md-12 input-tooltip" style="padding: 0;">\
															<i class="fa fa-question-circle tooltipstered" data-toggle="tooltip" data-placement="left" title="Enter the name or company to attribute the quote to"></i>\
														</div>\
													</div>\
												</div>\
											</form>'
						},
						footer		:	{
							content		:	'<button type="button" class="btn btn-primary add-testimonial">Add Testimonial</button>\
											<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>'
						}
					};
									
					displayModal(modal);					
					
													
				});
				
				$('.feed-activity-list').on('click', '.delete-entry', function() {
					
					var entryId = $(this).closest('.feed-element').data('entry-id');
					var entryTitle = $(this).closest('.feed-element').find('.entry-title').html();
					
					// Build the modal
					var modal = {
							
						size		:	'modal-lg',
						animation	:	'fadeIn',
						header		:	{
							title		:	'Delete ' + entryTitle + '?'
						},
						body		:	{
							content		:	'<form class="form-horizontal">\
												<div class="form-group">\
													<label class="control-label col-lg-12 col-md-12 col-sm-12">Would you like to delete this entry? Once done so this action cannot be undone\
													and any calculations that use this entry will no longer work until the equation is changed.</label>\
												</div>\
											</form>'
						},
						footer		:	{
							content		:	'<button type="button" class="btn btn-primary delete-entry" data-entry-id="' + entryId + '">Yes, Delete Entry</button>\
											<button type="button" class="btn btn-white" data-dismiss="modal">No, Keep Entry</button>'
						}
					};
									
					displayModal(modal);
				});
	
				$('.add-new-entry').on('click', function() {
					
					currentSection = $(this).closest('[data-section-holder-id]').data('section-holder-id');
					
					$.ajax({
										
						type: 'GET',
						url: '../../php/database.manipulation.php',
						data: 'action=getcompsections&comp=' + <?= $_GET['comp'] ?>,
						success: function(sections) {					
					
							var compSections = $.parseJSON(sections);
							var sectionsDropdown = '<select class="entry-section chosen-select">';
							
							$.each(compSections, function(index, value) {
								
								if(value.inactive != 1){
									sectionsDropdown += '<option value="'+ value.ID +'" ' + ( currentSection == value.ID ? 'selected="selected"' : '' ) + '>' + value.Title + '</option>';
								}
							});
							
							sectionsDropdown += '</select>';
							
							// Build the modal
							var modal = {
									
								size		:	'modal-lg',
								animation	:	'fadeIn',
								header		:	{
									icon		:	'fa-file-text',
									title		:	'Add a New Entry'
								},
								body		:	{
									content		:	'<form class="form-horizontal">\
														<div class="form-group">\
															<label class="control-label col-lg-5 col-md-5 col-sm-12">Entry Name</label>\
															<div class="col-lg-7 col-md-7 col-sm-12">\
																<input class="form-control new-entry-title" />\
															</div>\
														</div>\
														<div class="form-group">\
															<label class="control-label col-lg-5 col-md-5 col-sm-12">Add to Section: </label>\
															<div class="col-lg-7 col-md-7 col-sm-12">' + sectionsDropdown +
															'</div>\
														</div>\
													</form>'
								},
								footer		:	{
									content		:	'<button type="button" class="btn btn-primary create-new-entry">Create Entry</button>\
													<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>'
								}
							};
									
							displayModal(modal);
							
							$('.chosen-select').chosen({
								width: '100%',
								disable_search_threshold: 10
							});
							
						}
						
					});
					
				});
				
				$('.sortable-list').on('click', '.section-inactive', function() {
					
					var sectionId = $(this).data('section-id');
					
					// Build the modal
					var modal = {
								
						animation	:	'fadeIn',
						header		:	{
							title		:	'Delete this Section?'
						},
						body		:	{
							content		:	'Are you sure you\'d like to delete this section? This action cannot be undone.'
						},
						footer		:	{
							content		:	'<button type="button" class="btn btn-primary delete-section" data-section-id="' + sectionId + '">Yes, Delete</button>\
											<button type="button" class="btn btn-white" data-dismiss="modal">No, Keep</button>'
						}
					};
						
					displayModal(modal);
					
				});
				
				$('.change-video-src').on('click', function() {
					
					var sectionId = $(this).closest('.video-holder').data('section-id');
					
					// Build the modal
					var modal = {
								
						animation	:	'fadeIn',
						header		:	{
							title		:	'Embed Video'
						},
						body		:	{
							content		:	'<div class="row">\
												<label class="control-label col-lg-5 col-md-5 col-sm-12">Video Link</label>\
												<div class="col-lg-7 col-md-7 col-sm-12">\
													<input class="form-control video-link" />\
												</div>\
											</div>'
						},
						footer		:	{
							content		:	'<button type="button" class="btn btn-primary embed-video" data-section-id="' + sectionId + '">Embed Video</button>\
											<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>'
						}
					};
						
					displayModal(modal);
					
				});				
				
				$('.sortable-list').on('click', '.change-section-title', function() {
					
					var sectionId = $(this).data('section-id');
					var sectionTitle = $(this).closest('.row').find('h2').html();
					
					// Build the modal
					var modal = {
								
						animation	:	'fadeIn',
						header		:	{
							title		:	'Change ' + sectionTitle
						},
						body		:	{
							content		:	'<div class="row">\
												<label class="control-label col-lg-5 col-md-5 col-sm-12">Section Name</label>\
												<div class="col-lg-7 col-md-7 col-sm-12">\
													<input class="form-control change-section-name" />\
												</div>\
											</div>'
						},
						footer		:	{
							content		:	'<button type="button" class="btn btn-primary change-section" data-section-id="' + sectionId + '">Change Name</button>\
											<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>'
						}
					};
						
					displayModal(modal);
					
				});
				
				$('.feed-activity-list').on('click', '.edit-entry', function() {
					
					var entryId = $(this).closest('.feed-element').data('entry-id');
					
					$.ajax({
										
						type: 'GET',
						url: '../../php/database.manipulation.php',
						data: 'action=getinputspecs&id=' + entryId,
						success: function(entry) {
							
							var entrySpecs = $.parseJSON(entry);
							
							var sections = '<select class="entry-questions chosen-select">';

							var entryTitle = entrySpecs.Title;
							
							$('h1.section-title').each(function(){
								
								var selectoptions = '';
								$(this).closest('[data-section-holder-id]').find('.feed-activity-list').children('.feed-element:not(.pin)').each(function(){
									
									selectoptions += '<option value="' + $(this).data('entry-id') + '">' + $(this).find('.entry-title').html() + '</option>';
								})
								sections += '<optgroup label="' + $(this).html() + '">' + selectoptions + '</optgroup>';								
							});
							
							sections += '</select>';
							
							var currentChoiceList = '';
							
							if(entrySpecs.choices) {
								
								var currentChoices = $.parseJSON(entrySpecs.choices);
								
								$.each(currentChoices, function(index, value) {
									
									currentChoiceList +=	'<li>\
																<span class="m-l-xs">' + value + '</span>\
																<i class="fa fa-times remove-choice pull-right"></i>\
															</li>';
								});
							}
							
							modalContent = '<form class="form-horizontal">\
														<div class="form-group">\
															<label class="control-label col-lg-3 col-md-3 col-sm-12">Title: </label>\
															<div class="col-lg-8 col-md-8 col-sm-11">\
																<textarea class="form-control change-entry-name" rows="1" style="resize: vertical;">' + entryTitle + '</textarea>\
															</div>\
														</div>\
														<div class="form-group">\
															<label class="control-label col-lg-3 col-md-3 col-sm-12">Type: </label>\
															<div class="col-lg-8 col-md-8 col-sm-11">\
																<select class="entry-type chosen-select">\
																	<option value="input">Input</option>\
																	<option value="output">Output</option>\
																	<option value="textarea">Textbox</option>\
																	<option value="dropdown">Dropdown</option>\
																	<option value="slider">Slider</option>\
																	<option value="13">Header</option>\
																	<option value="text">Plain or HTML text</option>\
																</select>\
															</div>\
															<div class="infont col-md-1 col-sm-1 input-advice" style="margin-top: 5px;">\
																<div class="col-md-12 input-tooltip" style="padding: 0;">\
																	<i class="fa fa-question-circle tooltipstered" data-toggle="tooltip" data-placement="left" title="Define this as an <strong>output</strong>, <strong>input</strong>, <strong>slider</strong> or <strong>header</strong>. Right now a slider is in beta mode and can only be used for 0-100 in a percentage field. More functionality will be added in the future. If this represents a header and not an input feild then select header."></i>\
																</div>\
															</div>\
														</div>\
														<div class="choice-block">\
															<div class="form-group">\
																<label class="control-label col-lg-3 col-md-3 col-sm-12">Choices: </label>\
																<div class="col-lg-8 col-md-8 col-sm-12">\
																	<div class="input-group">\
																		<input class="form-control entry-new-choice" placeholder="Enter a New Choice"/>\
																		<span class="input-group-btn">\
																			<button type="button" class="btn btn-primary save-new-choice">Save Choice</button>\
																		</span>\
																	</div>\
																</div>\
															</div>\
															<div class="form-group">\
																<label class="control-label col-lg-3 col-md-3 col-sm-12"></label>\
																<div class="col-lg-8 col-md-8 col-sm-12">\
																	<ul class="todo-list m-t ui-sortable">'
																	+ currentChoiceList +
																	'</ul>\
																</div>\
															</div>\
														</div>\
														<div class="form-group">\
															<label class="control-label col-lg-3 col-md-3 col-sm-12">Format: </label>\
															<div class="col-lg-8 col-md-8 col-sm-12">\
																<select class="entry-format chosen-select">\
																	<option value="0">Text</option>\
																	<option value="1">Currency</option>\
																	<option value="2">Percent</option>\
																</select>\
															</div>\
														</div>\
														<div class="form-group">\
															<label class="control-label col-lg-3 col-md-3 col-sm-12">Helpful Tip: </label>\
															<div class="col-lg-8 col-md-8 col-sm-12">\
																<textarea class="form-control entry-tip" rows="2" style="resize: vertical;">' + entrySpecs.Tip + '</textarea>\
															</div>\
															<div class="infont col-md-1 col-sm-1 input-advice" style="margin-top: 5px;">\
																<div class="col-md-12 input-tooltip" style="padding: 0;">\
																	<i class="fa fa-question-circle tooltipstered" data-toggle="tooltip" data-placement="left" title="Enter a popup for this entry. This is where you can tell your prospect anything that may be useful for them to enter a value. Typical values for other customers is also something helpful to include."></i>\
																</div>\
															</div>\
														</div>\
														<div class="form-group">\
															<label class="control-label col-lg-3 col-md-3 col-sm-12">Prefilled Value: </label>\
															<div class="col-lg-8 col-md-8 col-sm-12">\
																<input class="form-control entry-placeholder" value="' + entrySpecs.Placeholder + '"/>\
															</div>\
															<div class="infont col-md-1 col-sm-1 input-advice" style="margin-top: 5px;">\
																<div class="col-md-12 input-tooltip" style="padding: 0;">\
																	<i class="fa fa-question-circle tooltipstered" data-toggle="tooltip" data-placement="left" title="Enter a pre-defined value for this input. This will only work if this entry is defined as an input as any output will be overwritten by its equation. This can even be text, the text will evaluate as 0 if the input is part of an output\'s equation and will reset to 0 once the user enters the input."></i>\
																</div>\
															</div>\
														</div>\
														<div class="form-group">\
															<label class="control-label col-lg-3 col-md-3 col-sm-12">Appended Value: </label>\
															<div class="col-lg-8 col-md-8 col-sm-12">\
																<input class="form-control entry-append" value="' + entrySpecs.append + '"/>\
															</div>\
															<div class="infont col-md-1 input-advice" style="margin-top: 5px;">\
																<div class="col-md-12 input-tooltip" style="padding: 0;">\
																	<i class="fa fa-question-circle tooltipstered" data-toggle="tooltip" data-placement="left" title="This value will be appended to the input or output. This helps identify exactly what the user is entering, for example if the user is entering hours the appended value should read <strong><em>hours</em></strong> to further clarify for the prospect."></i>\
																</div>\
															</div>\
														</div>';
														
<?php
	if($_SESSION['Username'] == 'mfarber@theroishop.com') {
?>
							modalContent += '			<hr>\
														<div class="form-group">\
															<label class="control-label col-lg-3 col-md-3 col-sm-12">Formula: </label>\
															<div class="col-lg-8 col-md-8 col-sm-12">\
																<textarea class="form-control entry-formula" rows="3" style="resize: vertical;">' + entrySpecs.formula + '</textarea>\
															</div>\
															<div class="infont col-md-1 input-advice" style="margin-top: 5px;">\
																<div class="col-md-12 input-tooltip" style="padding: 0;">\
																	<i class="fa fa-question-circle tooltipstered" data-toggle="tooltip" data-placement="left" title="This value will be appended to the input or output. This helps identify exactly what the user is entering, for example if the user is entering hours the appended value should read <strong><em>hours</em></strong> to further clarify for the prospect."></i>\
																</div>\
															</div>\
														</div>\
														<div class="form-group">\
															<label class="control-label col-lg-3 col-md-3 col-sm-12"></label>\
															<div class="col-lg-8 col-md-8 col-sm-12">'
																+ sections +
															'</div>\
														</div>';
<?php
	}
?>
							modalContent += '</form>';
							
<?php
	if($_SESSION['Username'] == 'mfarber@theroishop.com') {
?>
							modalFooter = '<button type="button" class="btn btn-primary save-formula pull-left" data-entry-id="' + entryId + '">Save Formula</button>\
											<button type="button" class="btn btn-primary save-entry" data-entry-id="' + entryId + '">Save Entry</button>\
											<button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>';
<?php	
	} else {
?>
							modalFooter = '<button type="button" class="btn btn-primary save-entry" data-entry-id="' + entryId + '">Save Entry</button>\
											<button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>';	
<?php	
	}
?>							
							// Build the modal
							var modal = {
										
								size		:	'modal-lg',
								animation	:	'fadeIn',
								header		:	{
									title		:	'Edit ' + entryTitle
								},
								body		:	{
									content		:	modalContent
								},
								footer		:	{
									content		:	modalFooter
								}
							};
								
							displayModal(modal);
							
							var field_entry_type = entrySpecs.Type;
							switch(entrySpecs.Type){
								case '0': field_entry_type = 'input'; break;
								case '1': field_entry_type = 'output'; break;
								case '2': field_entry_type = 'textarea'; break;
								case '3': field_entry_type = 'dropdown'; break;
								case '11': field_entry_type = 'slider'; break;
							}
							$('.entry-type').val(field_entry_type);
							$('.entry-format').val(entrySpecs.Format);
							
							
							if( entrySpecs.Type != 3 ) {
								
								$('.choice-block').hide();
							}
							
							$('.fa-question-circle').tooltipster({
								theme: 'tooltipster-light',
								maxWidth: 300,
								animation: 'grow',
								position: 'right',
								arrow: false,
								interactive: true,
								contentAsHTML: true
							});
							
							$('.chosen-select').chosen({
								width: '100%',
								disable_search_threshold: 10
							});
							
							$(".ui-sortable").sortable({
								items: '> li',
							});
							
							$('.entry-type').on('change', function() {
								
								if( $(this).val() == 3 ) {
									
									$('.choice-block').fadeIn();
								} else {
									
									$('.choice-block').fadeOut();
								}
							});
							
						}
					});
					
				});
				
				$('.modal').on('click', '.delete-section', function(){

					var sectionId = $(this).data('section-id');
					
					$.ajax({
									
						type: 'POST',
						url: '../../php/database.manipulation.php',
						data: 'action=deletesection&sectionid=' + sectionId,
						success: function() {
							
							$('.section-title[data-section-id="' + sectionId + '"]').closest('.col-lg-3').fadeOut();
							$('li[data-section-id="' + sectionId + '"]').fadeOut();
							
							noty({
								text: 'Section successfully deleted',
								type: 'success',
								timeout: 2000
							});
							
							$('.modal').modal('hide');
						}
					});
				});
				
				$('.feed-activity-list').on('click', '.edit-discovery-question', function() {
					
					var id = $(this).closest('.feed-element').data('discovery-question-id');
					var title = $(this).closest('.feed-element').find('.discovery-question-title').html();
					var type = $(this).closest('.feed-element').find('.discovery-question-specs').data('discovery-question-type');
					var format = $(this).closest('.feed-element').find('.discovery-question-specs').data('discovery-question-format');
					var choices = $(this).closest('.feed-element').find('.discovery-question-specs').data('discovery-question-choices');
					var growl = $(this).closest('.feed-element').find('.discovery-question-specs').data('discovery-question-growl');
					var position = $(this).closest('.feed-element').find('.discovery-question-specs').data('discovery-question-position');
					var append = $(this).closest('.feed-element').find('.discovery-question-specs').data('discovery-question-append');
					var placeholder = $(this).closest('.feed-element').find('.discovery-question-specs').data('discovery-question-placeholder');
					var tip = $(this).closest('.feed-element').find('.discovery-question-specs').data('discovery-question-tip');
					var link = $(this).closest('.feed-element').find('.discovery-question-specs').data('discovery-question-link');
					var eq = $(this).closest('.feed-element').find('.discovery-question-specs').data('discovery-question-eq');
					
					var linked = $(this).closest('.feed-element').data('sfdc-link');
					var sfdc_element = $(this).closest('.feed-element').find('.discovery-question-specs').data('discovery-question-sfdc');
					
					var currentChoiceList = '';
					
					if(choices) {
								
						var currentChoices = $.parseJSON(choices);
						
						$.each(currentChoices, function(index, value) {
							
							currentChoiceList +=	'<li>\
														<span class="m-l-xs">' + value + '</span>\
														<i class="fa fa-times remove-choice pull-right"></i>\
													</li>';
						});
					}
					
					var modalContent = '<form class="form-horizontal">\
												<div class="form-group">\
													<label class="control-label col-lg-3 col-md-3 col-sm-12">Title: </label>\
													<div class="col-lg-8 col-md-8 col-sm-11">\
														<textarea class="form-control change-discovery-question-name" rows="1" style="resize: vertical;">' + title + '</textarea>\
													</div>\
												</div>\
												<div class="form-group">\
													<label class="control-label col-lg-3 col-md-3 col-sm-12">Type: </label>\
													<div class="col-lg-8 col-md-8 col-sm-11">\
														<select class="discovery-question-type chosen-select">\
															<option value="0">Input</option>\
															<option value="1">Output</option>\
															<option value="3">Dropdown</option>\
															<option value="11">Slider</option>\
															<option value="13">Header</option>\
														</select>\
													</div>\
													<div class="infont col-md-1 col-sm-1 input-advice" style="margin-top: 5px;">\
														<div class="col-md-12 input-tooltip" style="padding: 0;">\
															<i class="fa fa-question-circle tooltipstered" data-toggle="tooltip" data-placement="left" title="Define this as an <strong>output</strong>, <strong>input</strong>, <strong>slider</strong> or <strong>header</strong>. Right now a slider is in beta mode and can only be used for 0-100 in a percentage field. More functionality will be added in the future. If this represents a header and not an input feild then select header."></i>\
														</div>\
													</div>\
												</div>\
												<div class="choice-block">\
													<div class="form-group">\
														<label class="control-label col-lg-3 col-md-3 col-sm-12">Choices: </label>\
														<div class="col-lg-8 col-md-8 col-sm-12">\
															<div class="input-group">\
																<input class="form-control entry-new-choice" placeholder="Enter a New Choice"/>\
																<span class="input-group-btn">\
																	<button type="button" class="btn btn-primary save-new-choice">Save Choice</button>\
																</span>\
															</div>\
														</div>\
													</div>\
													<div class="form-group">\
														<label class="control-label col-lg-3 col-md-3 col-sm-12"></label>\
														<div class="col-lg-8 col-md-8 col-sm-12">\
															<ul class="todo-list m-t ui-sortable">'
																+ currentChoiceList +
															'</ul>\
														</div>\
													</div>\
												</div>\
												<div class="form-group">\
													<label class="control-label col-lg-3 col-md-3 col-sm-12">Format: </label>\
													<div class="col-lg-8 col-md-8 col-sm-12">\
														<select class="discovery-question-format chosen-select">\
															<option value="0">Text</option>\
															<option value="1">Currency</option>\
															<option value="2">Percent</option>\
														</select>\
													</div>\
												</div>\
												<div class="form-group">\
													<label class="control-label col-lg-3 col-md-3 col-sm-12">Helpful Tip: </label>\
													<div class="col-lg-8 col-md-8 col-sm-12">\
														<textarea class="form-control discovery-question-tip" rows="2" style="resize: vertical;">' + tip + '</textarea>\
													</div>\
													<div class="infont col-md-1 col-sm-1 input-advice" style="margin-top: 5px;">\
														<div class="col-md-12 input-tooltip" style="padding: 0;">\
															<i class="fa fa-question-circle tooltipstered" data-toggle="tooltip" data-placement="left" title="Enter a popup for this entry. This is where you can tell your prospect anything that may be useful for them to enter a value. Typical values for other customers is also something helpful to include."></i>\
														</div>\
													</div>\
												</div>\
												<div class="form-group">\
													<label class="control-label col-lg-3 col-md-3 col-sm-12">Prefilled Value: </label>\
													<div class="col-lg-8 col-md-8 col-sm-12">\
														<input class="form-control entry-placeholder" value="' + placeholder + '"/>\
													</div>\
													<div class="infont col-md-1 col-sm-1 input-advice" style="margin-top: 5px;">\
														<div class="col-md-12 input-tooltip" style="padding: 0;">\
															<i class="fa fa-question-circle tooltipstered" data-toggle="tooltip" data-placement="left" title="Enter a pre-defined value for this input. This will only work if this entry is defined as an input as any output will be overwritten by its equation. This can even be text, the text will evaluate as 0 if the input is part of an output\'s equation and will reset to 0 once the user enters the input."></i>\
														</div>\
													</div>\
												</div>\
												<div class="form-group">\
													<label class="control-label col-lg-3 col-md-3 col-sm-12">Appended Value: </label>\
													<div class="col-lg-8 col-md-8 col-sm-12">\
														<input class="form-control discovery-question-append" value="' + append + '"/>\
													</div>\
													<div class="infont col-md-1 input-advice" style="margin-top: 5px;">\
														<div class="col-md-12 input-tooltip" style="padding: 0;">\
															<i class="fa fa-question-circle tooltipstered" data-toggle="tooltip" data-placement="left" title="This value will be appended to the input or output. This helps identify exactly what the user is entering, for example if the user is entering hours the appended value should read <strong><em>hours</em></strong> to further clarify for the prospect."></i>\
														</div>\
													</div>\
												</div>\
												<div class="form-group">\
													<label class="control-label col-lg-3 col-md-3 col-sm-12">Linked ROI Value: </label>\
													<div class="col-lg-8 col-md-8 col-sm-12">\
														<input class="form-control discovery-question-append" value="' + link + '"/>\
													</div>\
													<div class="infont col-md-1 input-advice" style="margin-top: 5px;">\
														<div class="col-md-12 input-tooltip" style="padding: 0;">\
															<i class="fa fa-question-circle tooltipstered" data-toggle="tooltip" data-placement="left" title="This value will be appended to the input or output. This helps identify exactly what the user is entering, for example if the user is entering hours the appended value should read <strong><em>hours</em></strong> to further clarify for the prospect."></i>\
														</div>\
													</div>\
												</div>';
												
					if(linked == '1') {
						
						modalContent += '<div class="form-group">\
											<label class="control-label col-lg-3 col-md-3 col-sm-12">Salesforce Link: </label>\
											<div class="col-lg-8 col-md-8 col-sm-12">\
												<input class="form-control discovery-question-sfdc-element" value="' + sfdc_element + '"/>\
											</div>\
											<div class="infont col-md-1 input-advice" style="margin-top: 5px;">\
												<div class="col-md-12 input-tooltip" style="padding: 0;">\
													<i class="fa fa-question-circle tooltipstered" data-toggle="tooltip" data-placement="left" title="This value will be appended to the input or output. This helps identify exactly what the user is entering, for example if the user is entering hours the appended value should read <strong><em>hours</em></strong> to further clarify for the prospect."></i>\
												</div>\
											</div>\
										</div>';
					}
					
					modalContent += '<div class="form-group">\
										<label class="control-label col-lg-3 col-md-3 col-sm-12">Formula: </label>\
										<div class="col-lg-8 col-md-8 col-sm-12">\
											<textarea class="form-control discovery-question-formula" rows="3" style="resize: vertical;">' + eq + '</textarea>\
										</div>\
										<div class="infont col-md-1 input-advice" style="margin-top: 5px;">\
											<div class="col-md-12 input-tooltip" style="padding: 0;">\
											<i class="fa fa-question-circle tooltipstered" data-toggle="tooltip" data-placement="left" title="This value will be appended to the input or output. This helps identify exactly what the user is entering, for example if the user is entering hours the appended value should read <strong><em>hours</em></strong> to further clarify for the prospect."></i>\
										</div>\
										</div>\
									</div>\
								</form>';
					
					
					// Build the modal
					var modal = {
								
						size		:	'modal-lg',
						animation	:	'fadeIn',
						header		:	{
							title		:	'Edit ' + title
						},
						body		:	{
							content		:	modalContent
						},
						footer		:	{
							content		:	'<button type="button" class="btn btn-primary save-discovery-question-formula pull-left" data-discovery-question-id="' + id + '">Save Formula</button>\
											<button type="button" class="btn btn-primary save-discovery-question" data-discovery-question-id="' + id + '">Save Discovery Question</button>\
											<button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>'
						}
					};
								
					displayModal(modal);
					
					$('.discovery-question-type').val(type);
					$('.discovery-question-type').val(format);
					
					$('.fa-question-circle').tooltipster({
						theme: 'tooltipster-light',
						maxWidth: 300,
						animation: 'grow',
						position: 'right',
						arrow: false,
						interactive: true,
						contentAsHTML: true
					});
							
					$('.chosen-select').chosen({
						width: '100%',
						disable_search_threshold: 10
					});
					
				});
				
				$('.salesforce-link').on('click', function() {
					
					var discovery_id = $(this).closest('[data-discovery-holder-id]').data('discovery-holder-id');
					
					$('.salesforce-entries-spinner').modal({
						backdrop: 'static',
						keyboard: false
					}).modal('show');					
					
					$.ajax({
						
						type: 'GET',
						url: '../../php/database.manipulation.php',
						data: 'action=getsfdcverlink&comp=<?= $_GET['comp'] ?>',
						success: function(sfdcverlink) {
							
							$.ajax({
											
								type: 'GET',
								url: '../../php/database.manipulation.php',
								data: 'action=getdiscoveryquestions&discoveryid=' + discovery_id,
								success: function(discovery) {
									
									var discoveryQuestions = $.parseJSON(discovery);
									var discoveryEntries = '<div class="col-lg-8">\
																<div class="ibox float-e-margins">\
																	<div class="ibox-content ibox-heading">\
																		<h3>ROI Discovery Entry Name <span class="pull-right">Linked Salesforce ID</span></h3>\
																	</div>\
																	<div class="ibox-content">\
																		<div class="sfdc-list">';
									
									$.each(discoveryQuestions, function(index, value) {
										
										if(value.Type != 13) {
											discoveryEntries += '<div class="dd-handle droppable discovery-id" data-discovery-id="' + value.ID + '">'
																	+ value.Title +
																	'<span class="sfdc-link pull-right" data-sfdc-link-id="0">' + value.sfdc_element + '</span>\
																</div>';
										}
															
									});
									
									discoveryEntries += '<div class="dd-handle droppable discovery-id" data-discovery-id="sfdc_link">\
															Verification Link to this ROI\
															<span class="sfdc-link pull-right" data-sfdc-link-id="0">' + sfdcverlink + '</span>\
														</div>';
									
									discoveryEntries += '</div></div></div></div>';

									$.ajax({
													
										type: 'GET',
										url: '../../php/cloudelement.php',
										data: 'action=getaccountobjects',
										success: function(returnedData) {
											
											var returnedObject = $.parseJSON(returnedData)
											
											var accountObjects = $.parseJSON(returnedObject.Account);
											var opportunityObjects = $.parseJSON(returnedObject.Opportunity);
											var leadObjects = $.parseJSON(returnedObject.Lead);

											var sfdc_list = '<div class="ibox float-e-margins">\
																	<div class="ibox-content ibox-heading">\
																		<h3>Available Salesforce Objects</h3>\
																	</div>\
																	<div class="ibox-content">\
																		<select class="object-dropdown">\
																			<option value="account">Account Objects</option>\
																			<option value="opportunity">Opportunity Objects</option>\
																			<option value="lead">Lead Objects</option>\
																		</select>\
																		<div style="margin-top: 10px;">\
																			<div class="dd sfdc-list" id="nestable">\
																				<ol class="dd-list">';
											
											sfdc_list += '<ul class="objectList" style="padding-left: 0;" data-object="account">';
											
											for( var i=0; i<accountObjects['fields'].length; i++ ) {
												
												sfdc_list += '<li class="dd-item draggable" id="' + i + '"><div class="dd-handle">' + accountObjects['fields'][i].vendorPath + '</div></li>';
											}
											
											sfdc_list += '</ul>\
															<ul class="objectList" style="padding: 0; display: none;" data-object="opportunity">';
											
											for( var i=0; i<opportunityObjects['fields'].length; i++ ) {
												
												sfdc_list += '<li class="dd-item draggable" id="' + i + '"><div class="dd-handle">' + opportunityObjects['fields'][i].vendorPath + '</div></li>';
											}
											
											sfdc_list += '</ul>\
															<ul class="objectList" style="padding-left: 0; display: none;" data-object="lead">';
											
											for( var i=0; i<leadObjects['fields'].length; i++ ) {
												
												sfdc_list += '<li class="dd-item draggable" id="' + i + '"><div class="dd-handle">' + leadObjects['fields'][i].vendorPath + '</div></li>';
											}
											
											sfdc_list += '</ul></ol></div></div></div></div>';
											
											var modalContent = '<div class="ibox-content">\
																	<div class="row">'
																		+ discoveryEntries +
																		'<div class="form-group col-lg-4">'
																			+ sfdc_list +
																		'</div>\
																	</div>\
																</div>';
											
											// Build the modal
											var modal = {
														
												size		:	'modal-xlg',
												animation	:	'fadeIn',
												header		:	{
													title		:	'Link Discovery Questions to Salesforce IDs'
												},
												body		:	{
													content		:	modalContent
												},
												footer		:	{
													content		:	'<button type="button" class="btn btn-primary save-sfdc-link">Save Salesforce Links</button>\
																	<button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>'
												}
											};
											
											$('.salesforce-entries-spinner').modal('hide');
											
											displayModal(modal);
											
											$('.dd-item.draggable').draggable({
												cursor: 'move',
												helper: 'clone',
												appendTo: '.modal-content'
											});
											
											$('.droppable').droppable({
												
												hoverClass: 'hovered',
												drop: function(event, ui){
													var draggable = ui.draggable;
													$(this).find('.sfdc-link').html(draggable.find('.dd-handle').html() + ' <i class="fa fa-times remove-sfdc-link"></i>');
													$(this).find('.sfdc-link').data('sfdc-link-id', draggable.attr('id'));
													draggable.hide();
												}
											});
											
											$('.sfdc-list').slimScroll({
												height: $(window).height() * 0.65 + 'px',
												width: '100%'
											});

											$('.sfdc-link').each(function(){
												 
												var sfdcLink = $(this);
												
												$('.dd-list li').each(function(){
													if( sfdcLink.html() == $(this).find('.dd-handle').html() ) {
														
														sfdcLink.html(sfdcLink.html() + ' <i class="fa fa-times remove-sfdc-link"></i>');
														sfdcLink.data('sfdc-link-id', $(this).attr('id'));
														$(this).hide();
													}
												});
												
											});
											
											$('.object-dropdown').chosen({
												width: '100%',
												disable_search_threshold: 10
											});
											
											$('.object-dropdown').on('change', function(){
												
												var selectedObjectList = $(this).val();
												
												$('[data-object]').each(function(){
													
													$(this).hide();
													if($(this).data('object') == selectedObjectList) { $(this).show(); }
												});
											});
											
											$('.objectList').each(function(){
												
												var items = $(this).find('li').get();
												items.sort(function(a, b){
													var keyA = $(a).text().toLowerCase();
													var keyB = $(b).text().toLowerCase();

													if (keyA < keyB) return -1;
													if (keyA > keyB) return 1;
													return 0;
												});
												
												var ul = $(this);
												$.each(items, function(i,li){
													ul.append(li);
												});												
												
											});

										}
									});	
								}
							});
						
						}
					});
				
				});
				
				$('.modal').on('click', '.remove-sfdc-link', function(){
					$('#' + $(this).parent().data('sfdc-link-id')).fadeIn();
					$(this).parent().html('');
				});
				
				$('.modal').on('click', '.save-sfdc-link', function(){
					
					$('[data-sfdc-link-id]').each(function(){
						
						var sfdcLink = $(this).html().replace(' <i class="fa fa-times remove-sfdc-link"></i>','');
						var discoveryid = $(this).closest('.discovery-id').data('discovery-id');
						
						var accountObject = 0;
						var opportunityObject = 0;
						var leadObject = 0;						
						
						if(sfdcLink) {
							
							$('.dd-list').find('li').each(function(){
								
								if($(this).find('.dd-handle').html() == sfdcLink) {
									
									if( $(this).closest('.objectList').data('object') == 'account' ) {
										accountObject = 1;
									};
									if( $(this).closest('.objectList').data('object') == 'opportunity' ) {
										opportunityObject = 1;
									};								
									if( $(this).closest('.objectList').data('object') == 'lead' ) {
										leadObject = 1;
									};								
								};
							});
							
							if(discoveryid == 'sfdc_link') {
								
								$.ajax({
												
									type: 'POST',
									url: '../../php/database.manipulation.php',
									data: 'action=savesfdcverlink&sfdclink=' + sfdcLink + '&comp=<?= $_GET['comp'] ?>' + '&account=' + accountObject + '&opportunity=' + opportunityObject + '&lead=' + leadObject
								});
							} else {

								$.ajax({
												
									type: 'POST',
									url: '../../php/database.manipulation.php',
									data: 'action=savesfdclink&sfdclink=' + sfdcLink + '&id=' + discoveryid + '&account=' + accountObject + '&opportunity=' + opportunityObject + '&lead=' + leadObject
								});
							}
						}
					});
					
					noty({
						text: 'Salesforce Links successfully updated',
						type: 'success',
						timeout: 2000
					});
							
					$('.modal').modal('hide');
				
				});
				
				$('.modal').on('click', '.add-testimonial', function(){

					var writeup = $('.testimonial-writeup').val();
					var author = $('.testimonial-author').val();
					
					$.ajax({
									
						type: 'POST',
						url: '../../php/database.manipulation.php',
						data: 'action=addnewtestimonial&test=' + writeup + '&author=' + author + '&comp=' + <?= $_GET['comp'] ?>,
						success: function(newId) {
							
							$('button.add-testimonial').before(
								'<div class="row" data-testimonial-id="' + newId + '">\
									<div class="col-lg-11">\
										<p>' + writeup + '</p>\
										<p>' + author + '</p>\
									</div>\
									<div class="col-lg-1 ibox-tools no-padding" style="margin-top: 10px;">\
										<a class="dropdown-toggle" href="#" data-toggle="dropdown">\
											<i class="fa fa-wrench"></i>\
										</a>\
										<ul class="dropdown-menu dropdown-user">\
											<li>\
												<a class="change-testimonial">Change Testimonial</a>\
											</li>\
										</ul>\
										<a class="close-link section-inactive">\
											<i class="fa fa-times delete-testimonial"></i>\
										</a>\
									</div>\
								</div>\
								<hr/>' );
								
							$('[data-testimonial-id="' + newId + '"]').hide().fadeIn('slow');
								
							noty({
								text: 'Testimonial successfully added',
								type: 'success',
								timeout: 2000
							});
							
							$('.modal').modal('hide');
						}
					});
				});
				
				$('.modal').on('click', '.change-section', function(){

					var sectionId = $(this).data('section-id');
					var newsectionname = $('input.change-section-name').val();
					
					if(newsectionname) {
						
						$.ajax({
										
							type: 'POST',
							url: '../../php/database.manipulation.php',
							data: 'action=updatesectionname&sectionid=' + sectionId + '&sectionname=' + newsectionname,
							success: function() {
								
								$('.section-title[data-section-id="' + sectionId + '"]').html(newsectionname);
								$('li[data-section-id="' + sectionId + '"] > a').html(newsectionname);
								
								noty({
									text: 'Section name successfully changed',
									type: 'success',
									timeout: 2000
								});
								
								$('.modal').modal('hide');
							}
						});
					} else {
						
						noty({
							text: 'No name was entered. If you\'d like to delete this section please click the x button',
							type: 'warning',
							timeout: 2000
						});						
					}
				});
				
				$('.modal').on('click', '.delete-entry', function(){

					var entryId = $(this).data('entry-id');
						
					$.ajax({
										
						type: 'POST',
						url: '../../php/database.manipulation.php',
						data: 'action=deleteentry&entryid=' + entryId,
						success: function() {
							
							$('.feed-element[data-entry-id="' + entryId + '"]').hide("slow");
						
							noty({
								text: 'Entry successfully deleted',
								type: 'success',
								timeout: 2000
							});
							
							$('.modal').modal('hide');
						}
					});
				});				
				
				$('.modal').on('click', '.create-new-entry', function(){

					var sectionId = $('.entry-section').val();
					var newentryname = $('input.new-entry-title').val();
					
					if(newentryname) {
						
						$.ajax({
										
							type: 'POST',
							url: '../../php/database.manipulation.php',
							data: 'action=addnewentry&sectionid=' + sectionId + '&entryname=' + newentryname + '&comp=' + <?= $_GET['comp'] ?> + '&title=' + newentryname,
							success: function(newestId) {
								
								$('[data-section-holder-id="' + sectionId + '"]').find('.feed-activity-list').find('.pin').before(
									
									'<div class="feed-element" data-entry-id="' + newestId + '">\
										<div>\
											<div class="ibox-tools">\
												<a class="dropdown-toggle edit-entry">\
													<i class="fa fa-pencil"></i>\
												</a>\
												<a class="close-link">\
													<i class="fa fa-times delete-entry"></i>\
												</a>\
											</div>\
											<strong class="entry-title">' + newentryname + '</strong>\
										</div>\
									</div>'
								);
								
								$('[data-entry-id="' + newestId + '"]').hide().fadeIn();
								
								noty({
									text: 'Entry succesfully added',
									type: 'success',
									timeout: 2000
								});
								
								$('.modal').modal('hide');
							}
						});
					} else {
						
						noty({
							text: 'No name was entered. In order to add a new input please specify a name.',
							type: 'warning',
							timeout: 2000
						});						
					}
				});
				
				$('.modal').on('click', '.embed-video', function(){

					var videoLink = $('.video-link').val();
					var sectionId = $(this).data('section-id');
					
					if(videoLink) {
						
						var pattern1 = /(?:http?s?:\/\/)?(?:www\.)?(?:vimeo\.com)\/?(.+)/g;
						var pattern2 = /(?:http?s?:\/\/)?(?:www\.)?(?:youtube\.com|youtu\.be)\/(?:watch\?v=)?(.+)/g;
						var pattern3 = /([-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?(?:jpg|jpeg|gif|png))/gi;
						
						if(pattern1.test(videoLink)){
						   var replacement = '//player.vimeo.com/video/$1';
						   
						   var videoLink = videoLink.replace(pattern1, replacement);
						}
						   
						
						if(pattern2.test(videoLink)){
							  var replacement = '//www.youtube.com/embed/$1';
							  var videoLink = videoLink.replace(pattern2, replacement);
						} 
						
						
						if(pattern3.test(videoLink)){
							var replacement = '<a href="$1" target="_blank"><img class="sml" src="$1" /></a><br />';
							var videoLink = videoLink.replace(pattern3, replacement);
						} 						
						
						$.ajax({
										
							type: 'POST',
							url: '../../php/database.manipulation.php',
							data: 'action=updatevideosrc&sectionid=' + sectionId + '&src=' + videoLink,
							success: function() {
								
								noty({
									text: 'Video successfully changed',
									type: 'success',
									timeout: 2000
								});
								
								$('.modal').modal('hide');
							}
						});
					} else {
						
						noty({
							text: 'No video was entered. If you\'d like to delete this video please click the x button',
							type: 'warning',
							timeout: 2000
						});						
					}
				});
				
				$('.modal').on('click', '.save-entry', function(){
					
					var choiceArray = "[";
					var choicesAdded = 0;
					$('.todo-list > li').each(function(){
						
						choiceArray += ( choicesAdded == 0 ? '' : ',' );
						choiceArray += '"' + $(this).find('.m-l-xs').html() + '"';
						choicesAdded++;
					});
					
					choiceArray += "]";

					var name = encodeURIComponent( $('.change-entry-name').val() );
					var type = $('.entry-type').val();
					var format = $('.entry-format').val();
					var tip = encodeURIComponent( $('.entry-tip').val() );
					var placeholder = encodeURIComponent( $('.entry-placeholder').val() );
					var append = encodeURIComponent( $('.entry-append').val() );
					var id = $(this).data('entry-id');
					var choices = encodeURIComponent(choiceArray);
					
					// If the type of input is a header then the format must be set to 0.
					if(type==13) { format = 0; }
					console.log(type);
					if(name) {
						
						$.ajax({
										
							type: 'POST',
							url: '../../php/database.manipulation.php',
							data: 'action=changeentry&entryid=' + id + '&title=' + name + '&type=' + type + '&format=' + format + '&tip=' + tip + '&placeholder=' + placeholder + '&append=' + append + '&choices=' + choices,
							success: function(returned) {
								noty({
									text: 'Entry succesfully added',
									type: 'success',
									timeout: 2000
								});
								
								$('.modal').modal('hide');
							}
						});
					} else {
						
						noty({
							text: 'No name was entered. In order to add a new input please specify a name.',
							type: 'warning',
							timeout: 2000
						});						
					}
					
				});

				$('.modal').on('click', '.save-formula', function(){
					
					var formula = encodeURIComponent( $('.entry-formula').val() );
					var entryid = $(this).data('entry-id');
					
					$.ajax({
						
						type: 'POST',
						url: '../../php/database.manipulation.php',
						data: 'action=changeformula&formula=' + formula + '&entryid=' + entryid,
						success: function() {

							noty({
								text: 'Entry formula succesfully changed',
								type: 'success',
								timeout: 2000
							});
							
						}
					});
					
				});
				
				$(".sortable-list").sortable({
					items: '> div:not(.pin)',
					stop: function() {
						var pos = 1;
						
						$('.sortable-list > div:not(.pin)').each(function(){
							
							var sectionId = $(this).find('.section-title').data('section-id');
							$.ajax({
									
								type: 'POST',
								url: '../../php/database.manipulation.php',
								data: 'action=updatesectionpos&pos=' + pos + '&sectionid=' + sectionId
							});	
							
							pos += 1;
						});
					}
				});
				
				$(".nav-sections").sortable({
					items: '> li:not(.pin)',
					stop: function() {
						
						var pos = 1;
						
						$('.nav-sections > li:not(.pin)').each(function(){
							
							var sectionId = $(this).data('section-id');
							$.ajax({
									
								type: 'POST',
								url: '../../php/database.manipulation.php',
								data: 'action=updatesectionpos&pos=' + pos + '&sectionid=' + sectionId
							});	
							
							pos += 1;
						});
					}
				});
				
				$(".feed-activity-list").sortable({
					start: function(event, ui) {
						ui.item.css('border', '1px dashed black');
						ui.item.css('padding', '5px');
						ui.item.css('background-color', '#f5f5dc');
						ui.item.css({ opacity: 0.75 });
					},
					items: '> .feed-element:not(.pin)',
					stop: function(event, ui) {
						ui.item.removeAttr('style');
						var pos = 1;
						
						$(this).find('.feed-element').each(function(){
							var entryId = $(this).data('entry-id');
							
							$.ajax({
									
								type: 'POST',
								url: '../../php/database.manipulation.php',
								data: 'action=updateentrypos&pos=' + pos + '&entryid=' + entryId
							});	
							
							pos += 1;
						});
					}

				});

				$('.testimonials').on('click', '.fa-times', function() {
					
					var testId = $(this).closest('[data-testimonial-id]').data('testimonial-id');
					
					// Build the modal
					var modal = {
								
						animation	:	'fadeIn',
						header		:	{
							title		:	'Delete this Testimonial?'
						},
						body		:	{
							content		:	'Are you sure you\'d like to delete this testimonial. This action cannot be undone.'
						},
						footer		:	{
							content		:	'<button type="button" class="btn btn-primary delete-testimonial" data-testimonial-id="' + testId + '">Yes, Delete</button>\
											<button type="button" class="btn btn-white" data-dismiss="modal">No, Keep</button>'
						}
					};
							
					displayModal(modal);				
				});
				
				$('.modal').on('click', '.delete-testimonial', function(){
						
					var testId = $(this).data('testimonial-id');
					
					$.ajax({
											
						type: 'POST',
						url: '../../php/database.manipulation.php',
						data: 'action=deletetestimonial&testid=' + testId,
						success: function() {

							$('[data-testimonial-id="' + testId + '"]').fadeOut();
							
							noty({
								text: 'Testimonial successfully deleted',
								type: 'success',
								timeout: 2000
							});
							
							$('.modal').modal('hide');
						}
					});
						
				});
				
				$('.testimonials').on('click', '.change-testimonial', function(){
					
					var testimonial = $(this).closest('.row').find('.col-lg-11').find('.testimonial').html();
					var author = $(this).closest('.row').find('.col-lg-11').find('.author').html();
					var testId =  $(this).closest('.row').data('testimonial-id');
						
					// Build the modal
					var modal = {
								
						size		:	'modal-lg',
						animation	:	'fadeIn',
						header		:	{
							title		:	'Change Testimonial'
						},
						body		:	{
							content		:	'<form class="form-horizontal">\
												<div class="form-group">\
													<label class="control-label col-lg-3 col-md-3 col-sm-12">Testimonial: </label>\
													<div class="col-lg-8 col-md-8 col-sm-11">\
														<textarea class="form-control testimonial-writeup" rows="3" style="resize: vertical;">' + testimonial + '</textarea>\
													</div>\
												</div>\
												<div class="form-group">\
													<label class="control-label col-lg-3 col-md-3 col-sm-12">Author: </label>\
													<div class="col-lg-8 col-md-8 col-sm-12">\
														<input class="form-control testimonial-author" value="' + author + '"/>\
													</div>\
													<div class="infont col-md-1 col-sm-1 input-advice" style="margin-top: 5px;">\
														<div class="col-md-12 input-tooltip" style="padding: 0;">\
															<i class="fa fa-question-circle tooltipstered" data-toggle="tooltip" data-placement="left" title="Enter the name or company to attribute the quote to"></i>\
														</div>\
													</div>\
												</div>\
											</form>'
						},
						footer		:	{
							content		:	'<button type="button" class="btn btn-primary change-testimonial" data-testimonial-id="' + testId + '">Change Testimonial</button>\
											<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>'
						}
					}
										
					displayModal(modal);
					
				});
				
				$('.modal').on('click', '.change-testimonial', function(){
						
					var testimonial = $('.testimonial-writeup').val();
					var author = $('.testimonial-author').val();
					var testId = $(this).data('testimonial-id');
					
					$.ajax({
											
						type: 'POST',
						url: '../../php/database.manipulation.php',
						data: 'action=changetestimonial&testid=' + testId + '&test=' + testimonial + '&author=' + author,
						success: function() {

							$('[data-testimonial-id="' + testId + '"]').find('.testimonial').html(testimonial);
							$('[data-testimonial-id="' + testId + '"]').find('.author').html(author);
							
							noty({
								text: 'Testimonial successfully changed',
								type: 'success',
								timeout: 2000
							});
							
							$('.modal').modal('hide');
						}
					});
						
				});
				
				$('.change-section-writeup').on('click', function() {
					
					var sectionWriteup = $(this).closest('.ibox').find('.caption-text').html();
					var sectionId = $(this).closest('[data-section-holder-id]').data('section-holder-id');
					var sectionTitle = $(this).closest('[data-section-holder-id]').find('.section-title').html();
					
					// Build the modal
					var modal = {
								
						size		:	'modal-lg',
						animation	:	'fadeIn',
						header		:	{
							title		:	'Change ' + sectionTitle + ' Writeup'
						},
						body		:	{
							content		:	'<form class="form-horizontal">\
												<div class="form-group">\
													<label class="control-label col-lg-3 col-md-3 col-sm-12">Section Writeup: </label>\
													<div class="col-lg-9 col-md-9 col-sm-12">\
														<textarea class="form-control section-changed-writeup" rows="5" style="resize: vertical;">' + sectionWriteup + '</textarea>\
													</div>\
												</div>\
											</form>'
						},
						footer		:	{
							content		:	'<button type="button" class="btn btn-primary change-section-writeup" data-section-id="' + sectionId + '">Change Writeup</button>\
											<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>'
						}
					}
										
					displayModal(modal);
				});
				
				$('.modal').on('click', '.change-section-writeup', function(){
						
					var writeup = $('.section-changed-writeup').val();
					var sectionId = $(this).data('section-id');
					
					$.ajax({

						type: 'POST',
						url: '../../php/database.manipulation.php',
						data: 'action=changesectionwriteup&writeup=' + writeup + '&sectionid=' + sectionId,
						success: function() {

							$('[data-section-holder-id="' + sectionId + '"]').find('.caption-text').html(writeup);
							
							noty({
								text: 'Section writeup successfully changed',
								type: 'success',
								timeout: 2000
							});
							
							$('.modal').modal('hide');
						}
					});
						
				});
				
			
				$('.modal').on('click', '.save-new-choice', function(){
					
					$('.todo-list').append('<li>\
												<span class="m-l-xs">' + $('.entry-new-choice').val() + '</span>\
												<i class="fa fa-times remove-choice pull-right"></i>\
											</li>');
				});
				
				$('.modal').on('click', '.remove-choice', function(){
					
					$(this).closest('li').fadeOut().remove();
				});
				
				$('.modal').on('chosen:hiding_dropdown', '.entry-questions', function(){
					
					console.log('clicked');
					var lastChar = $('.entry-formula').val().substr(-1);
					
					$('.entry-formula').val( $('.entry-formula').val() + ( lastChar == ' ' ? '' : ' ' ) + 'A' + $(this).val() );
					//console.log($(this).val());
					//console.log($(this).find(':selected').text());	
				});
				
				$('.feed-activity-list').on('click', '.edit-pdf-line-item', function(){
					
					var pdfLineId = $(this).closest('.feed-element').data('pdf-item-id');
					
					$.ajax({

						type: 'GET',
						url: '../../php/database.manipulation.php',
						data: 'action=getpdflinespecs&pdfitem=' + pdfLineId,
						success: function(pdfItem) {

							var pdfItem = $.parseJSON(pdfItem);
							
							// Build the modal
							var modal = {
										
								size		:	'modal-lg',
								animation	:	'fadeIn',
								header		:	{
									title		:	'Change Testimonial'
								},
								body		:	{
									content		:	'<form class="form-horizontal">\
														<div class="form-group">\
															<label class="control-label col-lg-3 col-md-3 col-sm-12">PDF Line HTML: </label>\
															<div class="col-lg-9 col-md-9 col-sm-12">\
																<textarea class="form-control pdf-item-html" rows="3" style="resize: vertical;">' + pdfItem.html + '</textarea>\
															</div>\
														</div>\
														<div class="form-group">\
															<label class="control-label col-lg-3 col-md-3 col-sm-12">Position X: </label>\
															<div class="col-lg-9 col-md-9 col-sm-12">\
																<input class="form-control pdf-item-pos-x" value="' + pdfItem.pos_x + '"/>\
															</div>\
														</div>\
														<div class="form-group">\
															<label class="control-label col-lg-3 col-md-3 col-sm-12">Position Y: </label>\
															<div class="col-lg-9 col-md-9 col-sm-12">\
																<input class="form-control pdf-item-pos-y" value="' + pdfItem.pos_y + '"/>\
															</div>\
														</div>\
													</form>'
								},
								footer		:	{
									content		:	'<button type="button" class="btn btn-primary change-pdf-item" data-pdf-item-id="' + pdfItem.id + '">Change PDF Item</button>\
													<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>'
								}
							}
												
							displayModal(modal);
						}
					});
					
				});

				$('.modal').on('click', '.change-pdf-item', function(){
						
					var newhtml = $('.pdf-item-html').val()
					var pdfhtml = encodeURIComponent( newhtml );
					var posx = $('.pdf-item-pos-x').val();
					var posy = $('.pdf-item-pos-y').val();
					var pdfItemId = $(this).data('pdf-item-id');
					
					$.ajax({

						type: 'POST',
						url: '../../php/database.manipulation.php',
						data: 'action=changepdfitem&pdfhtml=' + pdfhtml + '&posx=' + posx + '&posy=' + posy + '&pdfitemid=' + pdfItemId,
						success: function() {

							$('[data-pdf-item-id="' + pdfItemId + '"]').find('.pdf-html').val(newhtml);
							
							noty({
								text: 'PDF Item successfully changed',
								type: 'success',
								timeout: 2000
							});
							
							$('.modal').modal('hide');
						}
					});
						
				});				
			
			});

			function getUrlVars() {
					
				var vars = [], hash;
				var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
				for(var i = 0; i < hashes.length; i++)
				{
					hash = hashes[i].split('=');
					vars.push(hash[0]);
					vars[hash[0]] = hash[1];
				}
				return vars;
				
			}

<?php
	
	} else {
?>
		$(function() {
			
			$('.admin-link').on('click', function() {
					
				var browserhref = window.location.href;
				browserhref = browserhref.replace('#','');
				
				window.location.href = browserhref+"?comp="+$(this).data('admin-id');
			});
			
			$('.create-new-roi').on('click', function() {
				
				// Build the modal
				var modal = {
						
					size		:	'modal-lg',
					animation	:	'fadeIn',
					header		:	{
						icon		:	'fa-file-text',
						title		:	'Create a New ROI'
					},
					body		:	{
					content		:	'<form class="form-horizontal">\
										<div class="form-group">\
											<label class="control-label col-lg-5 col-md-5 col-sm-12">ROI Name:</label>\
											<div class="col-lg-7 col-md-7 col-sm-12">\
												<input class="form-control new-roi-name" />\
											</div>\
										</div>\
										<div class="form-group">\
											<label class="control-label col-lg-5 col-md-5 col-sm-12">ROI Return Period: </label>\
											<div class="col-lg-7 col-md-7 col-sm-12">\
												<select class="return-period chosen-select">\
													<option value="1">1 Year</option>\
													<option value="2">2 Years</option>\
													<option value="3">3 Years</option>\
													<option value="4">4 Years</option>\
													<option value="5">5 Years</option>\
												</select>\
											</div>\
										</div>\
									</form>'
								},
					footer		:	{
						content		:	'<button type="button" class="btn btn-primary create-new-roi">Create ROI</button>\
										<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>'
					}
				};

				displayModal(modal);

				$('.chosen-select').chosen({
					width: '100%',
					disable_search_threshold: 10
				});				
				
			});
			
			$('.modal').on('click', '.create-new-roi', function(){
					
				var roiname = $('.new-roi-name').val();
				var returnperiod = $('.return-period').val();
					
				if(roiname) {
						
					$.ajax({

						type: 'POST',
						url: '../../php/database.manipulation.php',
						data: 'action=addroi&name=' + roiname + '&return=' + returnperiod,
						success: function(returned) {

							window.location.href = window.location.href+"?comp="+returned;
						}
					});
				} else {
						
					noty({
						text: 'No name was entered. In order to add an ROI please specify a name.',
						type: 'warning',
						timeout: 2000
					});						
				}
					
			});
				
		});
<?php

	}
?>
		</script>
		
	</body>
	
</html>