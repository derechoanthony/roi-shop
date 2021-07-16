<?php
	
	session_start();
	
	error_reporting(0);
	
	$host		= 'localhost';
	$username	= 'root';
	$password	= '';
	$database	= 'roit';

	try {
		
		$dsn = "mysql:host=".$host.";dbname=".$database.";charset=utf8";
		$db = new PDO($dsn, $username, $password);
	} catch (PDOException $e) {
		
		$host		= 'localhost';
		$username	= 'root';
		$password	= '';
		$database	= 'roi';
		
		try {
			
			$dsn = "mysql:host=".$host.";dbname=".$database.";charset=utf8";
			$db = new PDO($dsn, $username, $password);
		} catch (PDOException $e) {

			echo 'Connection failed: ' . $e->getMessage();
		}
	};
	
?>