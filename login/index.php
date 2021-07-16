<?php

	// if(preg_match('/^www/', $_SERVER['HTTP_HOST'])){

	// } else { header('Location: https://www.theroishop.com/login/'); }

	/** 
	*	Connect to the database
	**/
	
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/constants.php");
	require_once("$root/db/connection.php");
	
	if( !empty($_SESSION['LoggedIn']) && !empty($_SESSION['Username']) ) {
		header( "Location: /dashboard" );
	};
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<title>The ROI Shop | Login</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	
	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
	<link href="assets/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
	<link href="assets/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/font-awesome/5.3/css/all.min.css" rel="stylesheet" />
	<link href="assets/animate/animate.min.css" rel="stylesheet" />
	<link href="assets/css/style.css" rel="stylesheet" />
	<link href="assets/css/style-responsive.min.css" rel="stylesheet" />
	<link href="assets/css/default.css" rel="stylesheet" id="theme" />
	<!-- ================== END BASE CSS STYLE ================== -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="assets/js/pace.min.js"></script>
	<!-- ================== END BASE JS ================== -->
</head>
<body class="pace-top bg-white">
	
	<div id="page-loader" class="fade show"><span class="spinner"></span></div>
	
	<div id="page-container" class="fade">
		<div class="login login-with-news-feed">
			<div class="news-feed">
				<div class="news-image" style="background-image: url(assets/img/roi-login-bg.jpg)"></div>
				<div class="news-caption">
					<h4 class="caption-title"><img src="/assets/images/logo.png" alt="The ROI Shop"></h4>
					<p></p>
				</div>
			</div>
			<div class="right-content">
				<ul class="nav nav-tabs login-nav-tab">
					<li class="nav-item">
						<a class="nav-link active show" href="#login" data-toggle="tab">Log In</a>
					</li>
					<li>
						<a id="register-email" class="nav-link" href="#register" data-toggle="tab">Register Email</a>
					</li>
					<li>
						<a class="nav-link" href="#forgot" data-toggle="tab">Forgot Password</a>
					</li>
				</ul>
				<div class="tab-pane fade active show" id="login">
					<div class="login-header">
						<div class="brand">
							<span class="logo"></span> <strong>The ROI Shop</strong> Login
							<small>Enter your <strong>Username</strong> and <strong>Password</strong> to sign in!</small>
						</div>
						<div class="icon">
							<i class="fa fa-sign-in"></i>
						</div>
					</div>
					<div class="login-content">
						<form id="password-form" class="margin-bottom-0">
							<div class="form-group m-b-15">
								<input id="email-address" type="text" class="form-control form-control-lg" placeholder="Email Address" required />
							</div>
							<div class="form-group m-b-15">
								<input id="password" type="password" class="form-control form-control-lg" placeholder="Password" required />
							</div>
							<div class="alert-holder"></div>
							<div class="checkbox checkbox-css m-b-30">
								<input type="checkbox" id="remember-me" value="1" />
								<label for="remember-me">
								Remember Me
								</label>
							</div>
							<div class="login-buttons">
								<button type="submit" id="sign-in" class="btn btn-success btn-block btn-lg">Sign me in</button>
							</div>
							<div class="m-t-20 m-b-40 p-b-40 text-inverse">
								Have a login, but not yet registered? Click <a href="#" class="register-account">here</a> to register.
							</div>
							<hr />
							<p class="text-center text-grey-darker">
								&copy; The ROI Shop
							</p>
						</form>
					</div>
				</div>
				<div class="tab-pane fade" id="register">
					<div class="login-header">
						<div class="brand">
							<span class="logo"></span> <strong>Register</strong> your Account
							<small>Enter your <strong>Email</strong> to begin the registration process!</small>
						</div>
						<div class="icon">
							<i class="fa fa-sign-in"></i>
						</div>
					</div>
					<div class="login-content">
						<form id="register-account" class="margin-bottom-0">
							<div class="form-group m-b-15">
								<input id="email-register" type="text" class="form-control form-control-lg" placeholder="Email Address" required />
							</div>
							<div class="registration-alert-holder"></div>
							<div class="login-buttons">
								<button type="submit" class="btn btn-success btn-block btn-lg">Begin Registration</button>
							</div>
							<div class="m-t-20 m-b-40 p-b-40 text-inverse">
								An email will be sent to this email address. Follow those instructions to complete the registration.
							</div>
							<hr />
							<p class="text-center text-grey-darker">
								&copy; The ROI Shop
							</p>
						</form>
					</div>
				</div>
				<div class="tab-pane fade" id="forgot">
					<div class="login-header">
						<div class="brand">
							<span class="logo"></span> Forgot <strong>Password?</strong>
							<small>Enter your <strong>Username</strong> to reset your <strong>Password.</strong> An <strong>e-mail</strong> will be sent detailing how to complete the reset process.</small>
						</div>
					</div>
					<div class="login-content">
						<form id="password-reset" class="margin-bottom-0">
							<div class="form-group m-b-15">
								<input id="email-to-reset" type="text" class="form-control form-control-lg" placeholder="Email Address" required />
							</div>
							<div class="reset-alert-holder"></div>
							<div class="login-buttons">
								<button type="submit" class="btn btn-success btn-block btn-lg">Reset Password</button>
							</div>
							<div class="m-t-20 m-b-40 p-b-40 text-inverse">
								An email will be sent to this email address. Follow those instructions to reset your password.
							</div>
							<hr />
							<p class="text-center text-grey-darker">
								&copy; The ROI Shop
							</p>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<script src="assets/jquery/jquery-3.3.1.min.js"></script>
	<script src="assets/jquery-ui/jquery-ui.min.js"></script>
	<script src="assets/bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
	<script src="assets/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="assets/js-cookie/js.cookie.js"></script>
	<script src="assets/js/default.min.js"></script>
	<script src="assets/js/login.js"></script>

</body>
</html>