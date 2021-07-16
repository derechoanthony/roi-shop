<?php

include_once '../common/base.php';
	
if( isset( $_SESSION['LoggedIn'] ) && isset( $_SESSION['Username'] ) )
{
	if( $_POST['company'] )
	{
		$_SESSION['Admin'] = $_POST['company'];
		header( "Location: /admin/#ajax/users.php" );
	}
} else {
	header("Location: /login?ref=".$_SERVER["REQUEST_URI"]);
}

include_once 'php/classes.admin.php';

$admin = new TheROIShopAdmin($db);

$YourRois = $admin->getAdmin();
$AllRois = $admin->getMasterList();

//initilize the page
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Login";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
$no_main_header = true;
$page_body_prop = array("id"=>"login", "class"=>"animated fadeInDown");
include("inc/header.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- possible classes: minified, no-right-panel, fixed-ribbon, fixed-header, fixed-width-->
<header id="header">
	<!--<span id="logo"></span>-->

	<div id="logo-group">
		<span id="logo"> <img src="<?php echo ASSETS_URL; ?>/img/logo.png" alt="SmartAdmin"> </span>

		<!-- END AJAX-DROPDOWN -->
	</div>

</header>

<div id="main" role="main">

	<!-- MAIN CONTENT -->
	<div id="content" class="container">

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-7 col-lg-8 hidden-xs hidden-sm">
				<h1 class="txt-color-red login-header-big">The ROI Shop Admin</h1>
				<div class="hero">

					<div class="pull-left login-desc-box-l">
						<h4 class="paragraph-header">Sign In on the right and select the company ROI admin you'd like to access.</h4>
					</div>
					
					<img src="<?php echo ASSETS_URL; ?>/img/screenshot.png" class="pull-right display-image" alt="" style="width:350px">

				</div>

			</div>
			<div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
				
				<div class="well no-padding">
					<form action="#" id="login-form" class="smart-form client-form" method="post">
						<header>
							Choose Your ROI
						</header>

						<fieldset>
							
							<section>
								<label class="label">Your Available ROIs</label>
								<label class="select">
									<select name="company" min="1">
										<option value="0">Choose name</option>
<?php
	if( $_SESSION['Username'] != 'mfarber@theroishop.com' )
	{	
		if( count( $YourRois ) )
		{			
			for( $i=0; $i<count( $YourRois ); $i++ )
			{
?>										
										<option value="<?= $YourRois[$i]['company_id']; ?>"><?= $YourRois[$i]['company_name']; ?></option>
<?php
			}
		}	
	} else {
		for( $i=0; $i<count( $AllRois ); $i++ )
		{	
?>
										<option value="<?= $AllRois[$i]['company_id']; ?>"><?= $AllRois[$i]['company_name'] ?></option>
<?php
		}
	}
?>
									</select> </label>
									<b class="tooltip tooltip-top-right"><i class="fa fa-cloud txt-color-teal"></i> Please select an ROI</b></label>
							</section>
							
						</fieldset>
						<footer>
							<button type="submit" class="btn btn-primary">
								Sign in
							</button>
						</footer>
					</form>

				</div>
				
			</div>
		</div>
	</div>

</div>
<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->

<script type="text/javascript">
	runAllForms();

	$(function() {
		// Validation
		$("#login-form").validate({
			// Rules for form validation
			rules : {
				company : {
					required : true
				}
			},

			// Messages for form validation
			messages : {
				company : {
					required : 'Please choose an ROI',
					min: 'Please select an ROI to open the admin tool'
				}
			},

			// Do not change code below
			errorPlacement : function(error, element) {
				error.insertAfter(element.parent());
			}
		});
	});
</script>

<?php 
	//include footer
	include("inc/footer.php"); 
?>