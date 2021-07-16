<?php
session_start();
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require "$root/webapps/core/database/connect.php";
require "$root/webapps/core/functions/general.php";
require "$root/webapps/core/functions/weblets.php";

$g = new GeneralFunctions();

//require 'core/functions/calculation.functions.php';
//require 'core/functions/roi.builder.RUDD.php';
//require '/core/functions/calculation.builder.RUDD.php';

//$roiBuilder = new CalculatorBuilder();

//$roiBuilder = new roiBuilder();
//require 'core/functions/projects.php';
//require 'core/functions/security.php';
/*
if (logged_in() === true){
	$session_userID = $_SESSION['userID'];
	$user_data = user_data($_SESSION['userID'],'first_name','last_name','userID','username','password','pic','email','admin');
	} else {
		header ('Location: login.php');
	}

if (project_selected() === true){
	$session_projectID = $_GET['projectID'];
	$project_data = project_data($_GET['projectID'],'PINum','ProjectNum','county','description','PositionNumber','GroupAssignment','PM','NextDeadlineID','GDOTDeadlineStatus','RDDeadlineStatus','ProjectStatus','ScheduleStatus');
	}


 //*/ 
 
$errors = array();
//print_r ($errors);

?>