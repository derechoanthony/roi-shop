<?php

	require_once( "../inc/base.php" );
	require_once( "../php/login.actions.php" );
	
	$login = new LoginActions($db);
	$login->logoutUser();
	
	session_start();
    
    unset($_SESSION['LoggedIn']);
    unset($_SESSION['Username']);
	unset($_SESSION['FirstName']);
	unset($_SESSION['FullName']);
	unset($_SESSION['id']);
	unset($_SESSION['UserId']);

	setcookie('session', '', time()-3600, '/');
	setcookie('token', '', time()-3600, '/');

	
	if(isset($_GET['ref'])) {
		header("Location: ../login?ref=".$_GET['ref']);
	} else {
		header("Location: ../login");
	}

	die();

?>