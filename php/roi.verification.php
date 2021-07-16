<?php

	require_once( "../inc/base.php" );
	require_once( "../php/login.actions.php" );
	
	$login = new LogInActions($db);
	//Define verification
	
	$verification = 0;
	$msg = '';

	//Is there a verification string?
	if( isset( $_GET['v'] ) && isset( $_GET['roi'] ) )
	{
		//Does the verification string match the roi?
		$ver = $login->verifyUser();
		if( $ver ){ $verification = 1; }
	}
	
	//Check to see if the ROI has been password protected
	if( isset( $_GET['roi'] ) && $ver )
	{
		$password = $login->password();
		if( $password ){
			//Password has been setup, must be confirmed
			$verification = 2;
		}
	}
	
	//Was a password and username entered
	if( isset( $_POST['username'] ) && isset( $_POST['password'] ) )
	{
		$passprotected = $login->roiLogin();
		if( $passprotected ){
			//Password has been setup, must be confirmed
			$verification = 1;
		} else {
			$msg='Username and password do not match. Please try again.';
		}
	}
	
	//Is a user signed in, if so, is this their ROI?
	if( isset( $_SESSION['Username'] ) && isset( $_GET['roi'] ) )
	{
		//A User is signed in, check to see if they are the owner of the ROI.
		$roiOwner = $login->roiOwner();
		if( rtrim( strtolower( $roiOwner ) ) === rtrim( strtolower( $_SESSION['Username'] ) ) || $_SESSION['Username'] == 'mfarber@theroishop.com' )
		{
			$verification = 3;
		}
	}
	
?>