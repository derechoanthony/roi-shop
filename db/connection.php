<?php
	
	// Start a PHP session
	session_start();
	
	error_reporting(E_ERROR);
	ini_set("display_errors", 1);
	
	// Create a database object
	try {
		$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
		$db = new PDO($dsn, DB_USER, DB_PASS);
	} catch (PDOException $e) {
		echo '<h3>We are currently experiencing technical difficulties. We apologize for the inconvenience, but we are currently working to get the site up and running as soon as possible.</h3>';
		exit;
	}
	
?>