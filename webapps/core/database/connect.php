<?php
error_reporting(0);


$host='localhost';
$username='root';
$password='drUh7Mut';
$password='xU6athug';
$database='wrd1j72622l3g';

	define('DB_HOST',$host);
	define('DB_USER',$username);
	define('DB_PASS',$password);
	define('DB_NAME',$database);

//mysql_connect($host,$username,$password);
//mysql_select_db($database);

//$con = mysql_connect($host, $username, $password);
//$db_selected = mysql_select_db($database,$con);

//$db = new mysqli($host,$username,$password,$database);

//if($db->connect_errno){
	//This may be a local host environment
	//Try the username/password for the local host and not server
	
	//$host='localhost';
	//$username='root';
	//$password='rudd4396';
	//$database='wrd1j72622l3g';

	try {
		$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
		$db = new PDO($dsn, DB_USER, DB_PASS);
		
	} catch (PDOException $e) {
		
		$host='localhost';
		$username='root';
		$password='';
		$database='roi';
		
		define('DB_HOST',$host);
		define('DB_USER',$username);
		define('DB_PASS',$password);
		define('DB_NAME',$database);
		
		try {
		$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
		echo $dsn;
		$db = new PDO($dsn, DB_USER, DB_PASS);
			} catch (PDOException $e) {
				echo '<h3>We are currently experiencing technical difficulties. We apologize for the inconvenience, but we are currently working to get the site up and running as soon as possible.</h3>';
				exit;
			}

		exit;
	}
	
		
	//mysql_connect($host,$username,$password);
	//mysql_select_db($database);
	
	//$db = new mysqli($host,$username,$password,$database);
	
	//$con = mysql_connect($host, $username, $password);
	//$db_selected = mysql_select_db($database,$con);
	
	//if($db->connect_errno){
	//die('Sorry, we are having server issues1!');}
//}

	define('DB_HOST',$host);
	define('DB_USER',$username);
	define('DB_PASS',$password);
	define('DB_NAME',$database);



//$con = mysql_connect($host, $username, $database);
//if (!$con)

  //{
  //$con = mysql_connect($host, $username, $database);
 // }
?>