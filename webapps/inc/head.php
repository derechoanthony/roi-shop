<?php 

/******************************************
	Load all require files on page load
 ******************************************/
//$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once( "../core/init.php" ); 									// Sets up connection to database

//Verify user

session_start();
$_SESSION["userID"] = "1";


?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>THE ROI Shop | Web Apps</title>

	

    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/font-awesome.min.css" rel="stylesheet">

    <!-- Morris -->
    <!--<link href="css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">-->

    <link href="../assets/css/animate.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="../assets/css/inspinia.css" rel="stylesheet">
    
    <!-- Data Tables -->
    <link href="../assets/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="../assets/css/dataTables.responsive.css" rel="stylesheet">
    <link href="../assets/css/dataTables.tableTools.min.css" rel="stylesheet">
    
    <script src="../assets/js/jquery-2.1.1.js"></script>

	<!-- Code Mirror -->
    
	<link href="../assets/css/plugins/codemirror/codemirror.css" rel="stylesheet">
    <link href="../assets/css/plugins/codemirror/ambiance.css" rel="stylesheet">
	

    

</head>

<body>
    <div id="wrapper">
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                
                
                <?php include '../inc/menu.php'; ?>
                
                
            </ul>

        </div>
    </nav>

        <div id="page-wrapper" class="gray-bg">
        <?php include '../inc/header.php'; ?>

        <!--<div class="wrapper wrapper-content">-->