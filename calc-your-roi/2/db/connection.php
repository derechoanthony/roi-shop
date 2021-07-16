<?php
	// Start a PHP session
	session_start();

	// Include site constants
	// $root = realpath($_SERVER["DOCUMENT_ROOT"]);
	// include_once "$root/inc/new-constants.inc.php";
	
	// Create a database object
	try {
		$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
		$db = new PDO($dsn, DB_USER, DB_PASS);
	} catch (PDOException $e) {
		echo '<h3>We are currently experiencing technical difficulties. We apologize for the inconvenience, but we are currently working to get the site up and running as soon as possible.</h3>';
		exit;
	}
?>