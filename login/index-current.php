<?php
	
	if(preg_match('/www/', $_SERVER['HTTP_HOST'])){
		$host = $_SERVER['HTTP_HOST'];
	} else {
		$host = 'www.'.$_SERVER['HTTP_HOST'];
	}
	
	if($_SERVER["HTTPS"] != "on") {
		header("Location: https://" . $host . $_SERVER["REQUEST_URI"]);
		exit;
	};
	
	/** 
	*	Connect to the database
	**/
	
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/constants.php");
	require_once("$root/db/connection.php");
	
	require_once("$root/login/inc/login.actions.php");
	
	require_once("../common/header.php");

?>

		<!-- WRAPPER -->
		<div id="wrapper">

			<div id="shop">

				<!-- PAGE TITLE -->
				<header id="page-title">
					<div class="container">
						<h1>Log In</h1>

						<ul class="breadcrumb">
							<li><a href="index.html">Home</a></li>
							<li class="active">Log In</li>
						</ul>
					</div>
				</header>


				<section class="container">

					<div class="row">

						<!-- LOGIN -->
						<div class="col-md-6">

							<h2>Log <strong>In</strong></h2>

							<form class="white-row" action="#" method="post">

<?php
	
	if( $msg == "nomatch" ) {
?>
								<!-- alert failed -->
								<div class="animate_fade_in alert alert-danger" style="padding: 15px;">
									<i class="fa fa-frown-o"></i> 
									Wrong <strong>E-mail Address</strong> or <strong>Password</strong>!
								</div>
<?php
	} else {
?>
								<!-- alert failed -->
								<div class="<?= ( ( $msg == "passsent" || $msg=="noemail" ) ? '' : 'animate_fade_in' ) ?> alert alert-info" style="padding: 15px;">
									<i class="fa fa-sign-in"></i> 
									Enter your <strong>Username</strong> and <strong>Password</strong> to sign in!
								</div>
<?php
	}
?>
								<div class="row">
									<div class="form-group">
										<div class="col-md-12">
											<label>Username</label>
											<input name="username" type="text" value="" class="form-control" placeholder="Username" required />
										</div>
									</div>
								</div>
								<div class="row">
									<div class="form-group">
										<div class="col-md-12">
											<label>Password</label>
											<input name="password" type="password" value="" class="form-control" placeholder="Password" required />
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<input type="submit" value="Log In" class="btn btn-primary pull-right" data-loading-text="Loading...">
									</div>
								</div>
								<input type="hidden" name="token" value="<?= $_SESSION['token']; ?>" />								

							</form>

						</div>
						<!-- /LOGIN -->

						<!-- PASSWORD -->
						<div class="col-md-6">

							<h2>Forgot <strong>Password</strong>?</h2>

							<form class="white-row" method="post" action="#">

<?php
	if( $msg == "passsent" ) {
?>								
								<!-- alert success -->
								<div class="animate_fade_in alert alert-success" style="padding: 15px;">
									<i class="fa fa-check-circle"></i> 
									<strong>New Password Sent!</strong> Check your E-mail Address!
								</div>
<?php
	} elseif( $msg == "noemail" ) {
?>
								<!-- alert failed -->
								<div class="animate_fade_in alert alert-danger" style="padding: 15px;">
									<i class="fa fa-exclamation-circle"></i> 
									<strong>Error</strong> sending the password reset. Please double-check your e-mail address entered is correct.
								</div>
<?php
	} else {
?>
								<!-- alert failed -->
								<div class="<?= ( $msg == "nomatch" ? '' : 'animate_fade_in' ) ?> alert alert-info" style="padding: 15px;">
									<i class="fa fa-mail-forward"></i> 
									Enter your <strong>Username</strong> to reset your <strong>Password</strong>. An <strong>e-mail</strong> will be sent detailing how to complete the reset process.
								</div>
<?php
	}
?>
								<!-- password form -->
								
									<div class="row">
										<div class="form-group">
											<div class="col-md-12">
												<label>Type your E-mail Address</label>
												<input type="text" class="form-control" name="noemail" id="noemail" value="" placeholder="E-mail Address" required />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<input type="submit" value="Reset Password" class="btn btn-primary pull-right" data-loading-text="Loading...">
										</div>
									</div>									
								</form>

						</div>
						<!-- /PASSWORD -->

					</div>


					<p class="white-row">
						Not an ROI Shop member? <a href="/contact-us">Click here</a> to join our team!
					</p>

				</section>

			</div>
		</div>
		<!-- /WRAPPER -->
		
<?php
	
	include_once( "../common/close.php" );
	
?>