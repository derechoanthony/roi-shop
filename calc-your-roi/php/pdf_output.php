<?php

	require_once( "../../inc/config.php" );	
	require_once( "../../inc/base.php" );	
	require_once( "../../php/roi.actions.php" );
	require_once( "../../php/calculator.actions.php" );
	require_once( "../../php/discovery.actions.php" );
	require_once( "../../php/user.preferences.php" );
	require_once( "../../assets/plugins/mpdf/mpdf.php" );
	
	//Create dashboard object
	$roi = new RoiActions($db);
	$discovery = new DiscoveryActions($db);	
	$calculator = new CalculatorActions($db);
	$user = new UserPreferences($db);
	
	/**
	 * Retrieve the current roi company's specifications.
	 **/	
	$roiSpecs = $roi->retrieveRoiSpecs();
	$roiDashboard = $roi->retrieveRoiDashboard();
	$roiSummary = $roi->retrieveRoiSummary();
	
	$roiSections = $calculator->retrieveRoiSections();
	$roiEntries = $calculator->retrieveRoiEntries();
	$roiPreferences = $calculator->retrieveRoiPreferences();
	$roiContributors = $calculator->retrieveRoiContributors();
	$testimonials = $calculator->retrieveTestimonials();
	
	$userPreferences = $user->retrieveUserPreferences();
	
	$discoveryQuestions = $discovery->retrieveDiscoveryQuestions();
	$discoveryDocuments = $discovery->retrieveDiscoveryDocuments();
	
	$roiOwner = $calculator->retrieveRoiOwner();
	
	$stylesheet = file_get_contents('../../assets/css/smart_admin/font-awesome.min.css');
	
	$html = '<style>
			h1 {
				text-align: center;
				color: #444;
				font-size: 38.5px;
			}
			.prepared-table {
				padding-top: 400px;
				width: 500px;
			}
			#fullpage img {
				margin: 20px 0 0 15px;
			}
			hr {
				border-color: #666;
				margin: 20px 20px 0;
			}
			.title-block {
				text-align: center;
				padding-top: 150px;
				color:#4e443c;
				font-family: \'Georgia, serif\';
				font-size: 28px;
				font-variant: small-caps;
				font-weight: 100;
				line-height: 35px;		
			}
			.prepared-for {
				text-align: center;
				padding-top: 60px;
				color:#4e443c;
				font-family: \'Georgia, serif\';
				font-size: 20px;
				font-weight: 100;
			}
			.page-header {
				border: 1px solid #c9ab40;
				padding: 15px;
				border-radius: 2px;
				margin-bottom: 30px;
				background: -moz-linear-gradient(center top , #fdefbc 0px, #ffe68e 100%) repeat scroll 0 0 rgba(0, 0, 0, 0);
				border-color: #c9ab40;
			}
			p.page-writeup {
				padding: 1px 5px;
			}
			p.list-style {
				margin: 0 0 0 10px;
			}
			#pdf_create_document {
				display:none;
			}
			#pdf_output {
				display:none;
			}
			.fa-dollar:before,.fa-usd:before{content:"\f155"}
			.fa {
				display: inline-block;
				font-family: FontAwesome;
				font-style: normal;
				font-weight: normal;
				line-height: 1;
			}
			.flex-video {
				display: none;
			}
			.table-responsive {
				border: 1px solid #666;
				border-radius: 2px;
			}
			dottab.menu {
				outdent: 4em;
			}
			td.menu {
				text-align: left;
				padding-right: 4em;
			}			
			</style>' . $roiPreferences['pdf'];
	
	$mpdf=new mPDF('c');
	$keep_table_proportions = TRUE;
	$mpdf->WriteHTML($stylesheet,1);
	$html = html_entity_decode(preg_replace("/%u([0-9a-f]{3,4})/i", "&#x\\1;", urldecode( utf8_encode($html) )), null, 'UTF-8');
	$mpdf->WriteHTML( $html );
	$mpdf->Output( $roiPreferences['ListText'].'.pdf', 'D' );
	exit;
	
?>
