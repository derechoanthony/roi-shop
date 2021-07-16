<?php
	
	include_once( "../common/base.php" );

	if( isset( $_POST['v'] ) ) {
		
		include_once( "../inc/class.users.inc.php" );
		$users = new ColoredListsUsers($db);
		$status = $users->updatePassword();
		
		if( $status=="changed" ) {
			
			header("Location: /dashboard");
			exit;
		}
	} 
	
	if( isset( $_GET['v'] ) && isset( $_GET['e'] ) ) {
		
		include_once( "../inc/class.users.inc.php" );
		$users = new ColoredListsUsers($db);
		$ret = $users->verifyAccount();
	
	} else {
		
		header("Location: /dashboard/login.php");
		exit;
	}

    $pg_title = "Reset Pending";
    include_once( "../common/header.php" );
?>

		<!-- WRAPPER -->
		<div id="wrapper">

			<div id="shop">

				<!-- PAGE TITLE -->
				<header id="page-title">
					<div class="container">
						<h1>Reset Password</h1>

						<ul class="breadcrumb">
							<li><a href="index.html">Home</a></li>
							<li class="active">Reset Password</li>
						</ul>
					</div>
				</header>


				<section class="container">

					<div class="row">

						<!-- LOGIN -->
						<div class="col-md-12">

							<h2>Reset <strong>Password</strong></h2>

							<form class="white-row" method="post" action="#">

<?php
							if(	isset($ret) ){
								
								if($ret=='verified') {
									
?>
								<div class="animate_fade_in alert alert-info" style="padding: 15px;">
									<i class="fa fa-sign-in"></i> 
									Password was already <strong>verified</strong> with this link. If you've forgotten your password please reset it <a href="/login">here</a>
								</div>
<?php								
								} elseif ($ret=='not exist') {
?>
								<div class="animate_fade_in alert alert-info" style="padding: 15px;">
									<i class="fa fa-sign-in"></i> 
									The link used is <strong>not valid</strong>. Please double check you've used the latest reset link sent from The ROI Shop. If you've forgotten your password please reset it <a href="/login">here</a>
								</div>									
<?php									
								}
							}
														
							if( ( isset($ret) && $ret=='no error' ) || $status=='no match' ) {
?>
								<div class="animate_fade_in alert alert-info" style="padding: 15px;">
<?php
								if( $ret=='no error' && $status != 'no match' ) {
?>
									<i class="fa fa-sign-in"></i> 
									Reset Your <strong>Password</strong>
<?php
								} elseif( $status== 'no match' ) {
?>
									<i class="fa fa-sign-in"></i> 
									<strong>Passwords</strong> entered do not match.
<?php	
								}
?>
								</div>

								<div class="row">
									<div class="form-group">
										<div class="col-md-12">
											<label>Password</label>
											<input name="p" type="password" value="" class="form-control" placeholder="Password" required />
										</div>
									</div>
								</div>
								<div class="row">
									<div class="form-group">
										<div class="col-md-12">
											<label>Password</label>
											<input name="r" type="password" value="" class="form-control" placeholder="Confirm Password" required />
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<input type="submit" value="Change Password" class="btn btn-primary pull-right" data-loading-text="Loading...">
									</div>
								</div>
								<input type="hidden" name="v" value="<?php echo $_GET['v'] ?>" />
								
<?php

							}
?>

							</form>

						</div>
						<!-- /LOGIN -->

				</section>

			</div>
		</div>
		<!-- /WRAPPER -->
		
<?php
	
	include_once( "../common/close.php" );
	
?>