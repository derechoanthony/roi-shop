<?php
	
	include_once '../common/base.php';
	include_once '../php/vendor/autoload.php';
	include_once '../php/swiftmailer/lib/swift_required.php';
	
	$pg_title = "Log In";
	
	if( !empty($_SESSION['LoggedIn']) && !empty($_SESSION['Username']) ) {
		
		// User is already logged in succesfully, send them to their dashboard
		header("Location: /dashboard");
	
	} else if (	!empty($_POST['username']) && !empty($_POST['password']) ) {
	
		include_once '../php/classes/class.users.inc.php';
		
		// Initiate the User object now that user has entered a username and password
		$users = new TheROIShopUsers($db);
		
		if( $users->accountLogin()===TRUE ) {
			
			if( isset($_GET['ref']) ) {
				
				// If ref is set then user was sent to log in page from another page,
				// send user back to that page once they've successfully logged in.
				header("Location: ".$_GET['ref']);
			
			} else {
				
				// Send them to their dashboard otherwise.
				header("Location: /dashboard");
			}
		} else {
			
			// If the password does not match the username's password in the database then
			// show message informing them the log in was unsuccessful.
			
			$msg = "nomatch";
			include_once '../common/header.php';
		}
		
	} else if ( !empty( $_POST['noemail'] ) ) {
	
		include_once '../php/classes/class.users.inc.php';
		$users = new TheROIShopUsers($db);
		
		$passSent = $users->resetPassword();
		if($passSent) {
			
			// Password was reset, inform user that new password was sent.
			$msg = "passsent";
		} else {
			
			// Email does not exist, inform user that the email they entered was wrong.
			$msg = "noemail";
		}
		include_once '../common/header.php';		
	
	} else {
		
		include_once '../common/header.php';
	}

?>

		<!-- WRAPPER -->
		<div id="wrapper">

			<div id="shop">

				<!-- PAGE TITLE -->
				<header id="page-title">
					<div class="container">
						<h1>Log In</h1>

						<ul class="breadcrumb">
							<li><a href="/">Home</a></li>
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