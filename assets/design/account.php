<?php
	
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/constants.php");
	require_once("$root/db/connection.php");	
	
	require_once("$root/dashboard/inc/dashboard.actions.php");
	
	$dashboard = new DashboardActions($db);
	
	$roi_versions = $dashboard->getUserCompVersions();
	$user_rois = $dashboard->getUserCreatedRois();
	$user_folders = $dashboard->getUserFolders();
	$visible_folders = $dashboard->getVisibleFolders();
	$version_levels = $dashboard->getVersionLevels();
	$user_specs = $dashboard->getUserSpecs();
	$user_salesforce = $dashboard->salesforceAccess();
	$salesforce_connected = $dashboard->sfconnected();
	
?>

<!DOCTYPE html>
<html>

	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>The ROI Shop | Dashboard</title>

		<link href="../calc-your-roi/css/bootstrap.min.css" rel="stylesheet">
		<link href="../calc-your-roi/css/font-awesome.css" rel="stylesheet">

		<!-- Morris -->
		<link href="../assets/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">

		<!-- Gritter -->
		<link href="../assets/plugins/gritter/jquery.gritter.css" rel="stylesheet">

		<link href="../assets/css/roi_calculator/animate.css" rel="stylesheet">
		<link href="../assets/css/roi_calculator/style.css" rel="stylesheet">
			
		<link href="../assets/css/roi_calculator/summernote.css" rel="stylesheet">
		<link href="../assets/css/roi_calculator/summernote-bs3.css" rel="stylesheet">
		
		<!-- Magnificant Popup -->
		<link href="../assets/plugins/magnific-popup/magnific-popup.css" rel="stylesheet">
		
		<link href="../assets/plugins/nouislider/jquery.nouislider.css" rel="stylesheet">
		
		<link href="../assets/plugins/chosen/chosen.css" rel="stylesheet">
		<link href="../assets/plugins/tooltipster/tooltipster.css" rel="stylesheet">
		
		<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">
		
		<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

	</head>
	
	<body>
		
		<div id="wrapper">
			
			<!-- Build the left navigation bar -->
			
			<nav class="navbar-default navbar-static-side" role="navigation">
				<div class="sidebar-collapse">
					<ul class="nav" id="side-menu">
						<li class="nav-header">
							<div class="dropdown profile-element">
								<span>
									<img id="company_logo" class="some-button" alt="image" src="../company_specific_files/<?= $user_specs['company_id'] ?>/logo/logo.png" />
								</span>
								<div class="login-info">
									<span> <!-- User image size is adjusted inside CSS, it should stay as it --> 
										
										<a <?= $_SESSION['Username'] ? 'href="account.php"' : 'href="javascript:void(0);"' ?>>
											<span>
												Welcome <?= $_SESSION['UserId'] ? $user_specs['first_name'] . ' ' . $user_specs['last_name'] : 'Guest' ?>
											</span>
										</a> 
										
									</span>
								</div>
							</div>
						</li>
						<li>
							<a href="../dashboard">
								<i class="fa fa-globe"></i> <span class="nav-label">My Dashboard</span>
							</a>
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
										<a href="/assets/logout.php">
											<i class="fa fa-power-off"></i> Log Out
										</a>
									</li>
								</ul>
							</li>
						</ul>
					</nav>
				</div>
				
				<div class="row">
					<div class="col-lg-12">
						<div class="ibox float-e-margins">
							<div class="ibox-title">
								<h5>Personal Information</h5>
							</div>
							<div class="ibox-content">
								<form id="personalform" action="" class="form-horizontal">

									<div class="form-group">
										<label class="col-sm-2 control-label">Email Address</label>
										<div class="col-sm-10">
											<input class="col-sm-10 form-control" type="text" name="email" value="<?= $user_specs['username'] ?>">
											<span class="help-block m-b-none">Your email address will also be your username and is the email address that all notifications will be sent to.</span>
										</div>
									</div>
									<div class="hr-line-dashed"></div>
									<div class="form-group">
										<label class="col-sm-2 control-label">First Name</label>
										<div class="col-sm-10">
											<input class="form-control" type="text" name="firstname" value="<?= $user_specs['first_name'] ?>">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Last Name</label>
										<div class="col-sm-10">
											<input class="form-control" type="text" name="lastname" value="<?= $user_specs['last_name'] ?>">
										</div>
									</div>
									<div class="hr-line-dashed"></div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Phone Number</label>
										<div class="col-sm-10">
											<input class="form-control" type="text" name="phone" value="<?= $user_specs['phone'] ?>">
										</div>
									</div>
									
									<div class="hr-line-dashed"></div>
									<div class="form-group">
										<div class="col-sm-12 pull-right">
											<button class="updatepersonal btn btn-primary">Update Account Info</button>
										</div>
									</div>
									
								</form>
							</div>
						</div>
						
						<div class="ibox float-e-margins">
							<div class="ibox-title">
								<h5>Change Your Password</h5>
							</div>
							<div class="ibox-content">
								<form id="passwordform" action="" class="form-horizontal">

									<div class="form-group">
										<label class="col-sm-2 control-label">New Password</label>
										<div class="col-sm-10">
											<input class="form-control newpassword" type="password" name="newpassword">
										</div>
									</div>
									<div class="hr-line-dashed"></div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Confirm Password</label>
										<div class="col-sm-10">
											<input class="form-control confirmpassword" type="password" name="confirm">
										</div>
									</div>
						
									<div class="hr-line-dashed"></div>
									<div class="form-group">
										<div class="col-sm-12 pull-right">
											<button class="updatepassword btn btn-primary">Update Password</button>
										</div>
									</div>
									
								</form>
							</div>
						</div>

<?php
	if($user_salesforce){
?>
						<div class="ibox float-e-margins">
							<div class="ibox-title">
								<h5>Salesforce Integration</h5>
							</div>
							<div class="ibox-content">
<?php
			if( $salesforce_connected['code'] ) {
?>				
								<h3>You are successfully connected to Salesforce.</h3>					
<?php							
			} else {
?>
								<center>
									<button type="button" class="btn btn-primary" onclick="setupSalesforceConnection()">Connect to Salesforce</button>
								</center>
<?php
			}
?>
							</div>
						</div>
<?php
	
	}
?>
					</div>
				</div>
				
			</div>
			
		</div>
		
	</body>
	
	<!-- Mainly scripts -->
	<script src="../calc-your-roi/js/jquery-2.1.1.js"></script>
	<script src="../calc-your-roi/js/bootstrap.js"></script>
	<script src="../calc-your-roi/js/plugins/noty/js/noty/packaged/jquery.noty.packaged.min.js"></script>
	<script src="../calc-your-roi/js/plugins/metisMenu/jquery.metisMenu.js"></script>
	<script src="../calc-your-roi/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="../calc-your-roi/js/modals.js"></script>

	<!-- Peity -->
	<script src="../calc-your-roi/js/plugins/peity/jquery.peity.min.js"></script>
	<script src="../calc-your-roi/js/demo/peity-demo.js"></script>

	<!-- Custom and plugin javascript -->
	<script src="../calc-your-roi/js/inspinia.js"></script> 
	<script src="../calc-your-roi/js/plugins/pace/pace.min.js"></script>

	<!-- jQuery UI -->
	<script src="../calc-your-roi/js/plugins/jquery-ui/jquery-ui.min.js"></script>

	<!-- GITTER -->
	<script src="../calc-your-roi/js/plugins/gritter/jquery.gritter.min.js"></script>
		
	<!-- EayPIE -->
	<script src="../calc-your-roi/js/plugins/easypiechart/jquery.easypiechart.js"></script>

	<!-- Sparkline -->
	<script src="../calc-your-roi/js/plugins/sparkline/jquery.sparkline.min.js"></script>

	<!-- Sparkline demo data  -->
	<script src="../calc-your-roi/js/demo/sparkline-demo.js"></script>

	<!-- ChartJS-->
	<script src="../calc-your-roi/js/plugins/chartJs/Chart.min.js"></script>
		
	<!-- FitVids -->
	<script src="../calc-your-roi/js/plugins/fitvids/fitvids.js"></script>
		
	<!-- Magnificant Popup -->
	<script src="../calc-your-roi/js/plugins/magnificant-popup/jquery.magnific-popup.min.js"></script>
		
	<!-- NouSlider -->
	<script src="../calc-your-roi/js/plugins/nouslider/jquery.nouislider.all.min.js"></script>
	   
	<script src="../calc-your-roi/js/plugins/summernote/summernote.min.js"></script>
	   
	<!-- Chosen -->
	<script src="../calc-your-roi/js/plugins/chosen/chosen.jquery.js"></script>
		
	<!-- ROI Shop Functions -->
	<script src="../calc-your-roi/js/numeral.js"></script>
	<script src="../calc-your-roi/js/jquery-calx-2.1.1.js"></script>
		
	<!-- Highcharts -->
	<script src="../calc-your-roi/js/plugins/highcharts/highcharts.js"></script>
	<script src="../calc-your-roi/js/plugins/highcharts/highcharts-3d.js"></script>
	<script src="../calc-your-roi/js/plugins/highcharts/highcharts-more.js"></script>		
	<script src="../calc-your-roi/js/plugins/highcharts/exporting.js"></script>

	<!-- Tooltip Functions -->
	<script src="../calc-your-roi/js/plugins/tooltipster/jquery.tooltipster.min.js"></script>
	
	<!-- Toastr -->
	<script src="../calc-your-roi/js/plugins/toastr/toastr.min.js"></script>
	<script src="../calc-your-roi/js/plugins/quovolver/jquery.quovolver.min.js"></script>
		
	<!-- Load Languages -->
	<script src="../languages/fr.js"></script>
	<script src="../languages/en-gb.js"></script>
		
	<script src="../calc-your-roi/js/plugins/cleanhtml/jquery.htmlClean.min.js"></script>	
	
<script type="text/javascript">
	
	function setupSalesforceConnection(){

		window.open('https://www.theroishop.com/salesforceintegration','The ROI Shop','scrollbars=yes,width=650,height=450');
	}
	
	$('.updatepersonal').on( 'click', function(e) {
		
		e.preventDefault();
		$.ajax({
			
			type:  "POST",
			url: "ajax/dashboard.post.php",
			data: 'action=updatepersonal&'+$('#personalform').serialize(),
			success:	function(values) {
				if( values == 'updated' ) {
					
					noty({
						text: 'Personal information successfully updated!',
						type: 'success',
						timeout: 2000
					});
				}
			}
		});
	});
	
	$('.updatepassword').on( 'click', function(e) {
		
		e.preventDefault();
		if( $('input[name="newpassword"]').val() != $('input[name="confirm"]').val() ) {
			console.log('no match');
			noty({
				text: 'The passwords entered did not match, please re-type your password and re-submit.',
				type: 'danger',
				timeout: 2000
			});			
		
		} else {
			console.log('match');
			$.ajax({
				type: "POST",
				url: "ajax/dashboard.post.php",
				data: 'action=updatepassword&'+$('#passwordform').serialize(),
				success: function(values) {
					if( values == 'updated' ) {
						
						noty({
							text: 'Password successfully updated!',
							type: 'success',
							timeout: 2000
						});	
					}
				}
			});
		}
		
		$('.newpassword').val('');
		$('.confirmpassword').val('');
	});
	
</script>