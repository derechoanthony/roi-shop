<?php
	
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/constants.php");
	require_once("$root/db/connection.php");	
	
?>

<!DOCTYPE html>
<html>

	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>The ROI Shop | Complete Registration</title>

		<link href="../../calc-your-roi/css/bootstrap.min.css" rel="stylesheet">
		<link href="../../calc-your-roi/css/font-awesome.css" rel="stylesheet">

		<!-- Morris -->
		<link href="../../assets/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">

		<!-- Gritter -->
		<link href="../../assets/plugins/gritter/jquery.gritter.css" rel="stylesheet">

		<link href="../../assets/css/roi_calculator/animate.css" rel="stylesheet">
		<link href="../../assets/css/roi_calculator/style.css" rel="stylesheet">
			
		<link href="../../assets/css/roi_calculator/summernote.css" rel="stylesheet">
		<link href="../../assets/css/roi_calculator/summernote-bs3.css" rel="stylesheet">
		
		<!-- Magnificant Popup -->
		<link href="../../assets/plugins/magnific-popup/magnific-popup.css" rel="stylesheet">
		
		<link href="../../assets/plugins/nouislider/jquery.nouislider.css" rel="stylesheet">
		
		<link href="../../assets/plugins/chosen/chosen.css" rel="stylesheet">
		<link href="../../assets/plugins/tooltipster/tooltipster.css" rel="stylesheet">

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
									<img id="company_logo" class="some-button" alt="image" src="../../company_specific_files/1/logo/logo.png" />
								</span>
							</div>
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
				
				<div class="row wrapper border-bottom white-bg page-heading" style="border: none;">
					<div class="col-lg-10">
						<h2>Complete your Registration</h2>
					</div>
					<div class="col-lg-2">

					</div>
				</div>
				<br/>
				<div class="row">
					<div class="col-lg-12">
						<div class="ibox float-e-margins">
							<div class="ibox-title">
								<h5>Password and Personal Information</h5>
							</div>
							<div class="ibox-content">
								<form id="personalform" class="form-horizontal">
									<h3>Create Your Password</h3>
									<div class="hr-line-dashed"></div>
									<div class="form-group">
										<label class="col-sm-2 control-label">New Password</label>
										<div class="col-sm-10">
											<input class="form-control newpassword" type="password" id="newpassword" required>
										</div>
									</div>
									<div class="hr-line-dashed"></div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Confirm Password</label>
										<div class="col-sm-10">
											<input class="form-control confirmpassword" type="password" id="confirm" required>
										</div>
									</div>
									<div class="password-alert"></div>
									<br/>
									<h3>Personal Information</h3>
									<div class="hr-line-dashed"></div>									
									<div class="form-group">
										<label class="col-sm-2 control-label">First Name</label>
										<div class="col-sm-10">
											<input class="form-control" type="text" id="firstname" required>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Last Name</label>
										<div class="col-sm-10">
											<input class="form-control" type="text" id="lastname" required>
										</div>
									</div>
									<div class="hr-line-dashed"></div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Phone Number</label>
										<div class="col-sm-10">
											<input class="form-control" type="text" id="phone" required>
										</div>
									</div>
									
									<div class="hr-line-dashed"></div>
									<div class="form-group">
										<div class="col-sm-12 pull-right">
											<button class="btn btn-primary" type="submit">Register Account</button>
										</div>
									</div>
									
								</form>
							</div>
						</div>
					</div>
				</div>
				
			</div>
			
		</div>
		
	</body>
	
	<!-- Mainly scripts -->
	<script src="../../calc-your-roi/js/jquery-2.1.1.js"></script>
	<script src="../../calc-your-roi/js/bootstrap.js"></script>
	
<script type="text/javascript">
	function getQueryVariable(variable) {
		
		var query = window.location.search.substring(1),
			vars = query.split("&");

		for (var i=0;i<vars.length;i++) {
			
			var pair = vars[i].split("=");
			if(pair[0] == variable){ return pair[1]; }
		}
		
		return(false);
	};		
	
	$('#personalform').on('submit', function(e){
		e.preventDefault();
		
		var password = $('#newpassword').val();
		var confirm = $('#confirm').val();
		var firstname = $('#firstname').val();
		var lastname = $('#lastname').val();
		var phone = $('#phone').val();
		
		if(password !== confirm){
			var alert = '<div class="alert alert-danger">Passwords do not match. Please re-enter your password.</div>',

			$alert_holder = $('.password-alert');		
			$alert_holder.empty();
					
			$(alert).appendTo($alert_holder).hide().fadeIn(1000);			
		} else {
			$.ajax({
				type: "POST",
				url: "../assets/ajax/login.actions.php",
				data: {
					action: "completeregistration",
					password: password,
					firstname: firstname,
					lastname: lastname,
					phone: phone,
					verification: getQueryVariable('id')
				},
				success: function(registerStatus){
					var registration = JSON.parse(registerStatus);

					if (!registration.warnings){
						window.location.replace("/dashboard");
					}
				}
			})			
		}
		
	});
</script>