<?php

	require_once("$root/login/inc/user.actions.php");
	$users = new RoiShopUsers($db);
	
	require_once("$root/email/email.actions.php");
	require_once("$root/email/swiftmailer/lib/swift_required.php");
	$email = new EmailActions($db);
	
	// Check if there is a session id and a session username. If so a user
	// is already logged in.
	
	if( !empty($_SESSION['LoggedIn']) && !empty($_SESSION['Username']) ) {
		
		// Send user to the dashboard as they are already logged in.
		header( "Location: /dashboard" );
	};
	
	// If a username and password have been posted the user has attempted
	// to log in. Check to see if the username and password pair exist
	// within the database and sign the user in if so.
	
	if( !empty($_POST['username']) && !empty($_POST['password']) ) {
		
		if( $users->accountLogin() ) {
			
			session_write_close();
			header( "Location: " . ( $_GET['ref'] ? $_GET['ref'] : '/dashboard' ) );
			exit();
		};
		
		$msg = 'nomatch';
	};
	
	if( !empty($_POST['noemail']) ) {
		
		$account_info = $users->resetPassword();
		if( $account_info ) {
			
			$password_sent = $email->sendResetEmail( $account_info['user_id'] );
			print_r($password_sent);
		};
	}
	
?>