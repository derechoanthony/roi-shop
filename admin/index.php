<?php

include_once '../common/base.php';
	
if( isset( $_SESSION['LoggedIn'] ) && isset( $_SESSION['Username'] ) )
{
	if( isset( $_SESSION['Admin'] ) )
	{
	} else {
		header("Location: /admin/login.php");
	}
} else {
	header("Location: /login?ref=".$_SERVER["REQUEST_URI"]);
}

include_once 'php/classes.admin.php';

$admin = new TheROIShopAdmin($db);

$getComp = $admin->getCompanySpecs();
$getUsers = $admin->getUsers();
$getRois = $admin->getRois();
	
$totalViews = 0;
for( $i=0; $i<count($getRois); $i++ )
{
	$totalViews += $getRois[$i]['visits'];
}

//initilize the page
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC. */



/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">
<?= $_SERVER['HTTPS'] ?>
	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
	//include footer
	include("inc/footer.php"); 
?>