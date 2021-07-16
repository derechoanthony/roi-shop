<?php

	// Establish connection to the database
	
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/constants.php");
	require_once("$root/db/connection.php");
	
	require_once("../php/roi.retrieval.php");
	
	$roi_retrieval = new RoiRetrieval($db);
	$roi_specifics = $roi_retrieval->roiSpecifics();
	$roi_owner = $roi_retrieval->roiOwner();
	$roi_contributors = $roi_retrieval->roiContributors();
	$roi_currencies = $roi_retrieval->roiCurrencies();
	$roi_currency = $roi_retrieval->roiCurrency();
	$roi_salesforce = $roi_retrieval->roiSFIntegration();
	$roi_salesforce_integration = $roi_retrieval->roiSalesforceIntegrations();
	
?>

<html>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<!-- Define title of the ROI -->
		<title><?= $roi_specifics['roi_title'] ?></title>
		
		<!-- Include the ROI's CSS Files -->
		<link href="../css/bootstrap/bootstrap.min.css" rel="stylesheet">
		<link href="../css/loader/style.css" rel="stylesheet">
		<link href="../css/calculator/style.css" rel="stylesheet">
		<link href="../css/datatables/jquery.dataTables.min.css" rel="stylesheet">
		<link href="../css/font-awesome/font-awesome.css" rel="stylesheet">
		<link href="../css/tooltipster/tooltipster.css" rel="stylesheet">
		<link href="../css/chosen/chosen.css" rel="stylesheet">
		<link href="css/dashboard/style.css" rel="stylesheet">
		
	</head>
	
	<body class="pace-done fixed-sidebar fixed-nav fixed-nav-basic">
		
		<div id="wrapper">
		
			<!-- Wrapper contains the ROI content -->
			
			<nav class="navbar-default navbar-static-side" role="navigation">
				<div class="sidebar-collapse">
					<ul class="nav metismenu" id="side-menu">
						<!--<li class="nav-header">
							<div class="dropdown profile-element"> <span>
								<img alt="image" class="img-circle" src="img/profile_small.jpg" />
								 </span>
								<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">David Williams</strong>
								 </span> <span class="text-muted text-xs block">Art Director <b class="caret"></b></span> </span> </a>
								<ul class="dropdown-menu animated fadeInRight m-t-xs">
									<li><a href="profile.html">Profile</a></li>
									<li><a href="contacts.html">Contacts</a></li>
									<li><a href="mailbox.html">Mailbox</a></li>
									<li class="divider"></li>
									<li><a href="login.html">Logout</a></li>
								</ul>
							</div>
							<div class="logo-element">
								IN+
							</div>
						</li>-->
						<li class="smooth-scroll active">
							<a href="#">
								<i class="fa fa-shield"></i>
								<span class="nav-label">Security</span>
								<span class="fa arrow"></span>
							</a>
							<ul class="nav nav-second-level">
								<li class="active">
									<a href="#dashboard1">Verification Link</a>
								</li>
							</ul>
						</li>
						<li class="smooth-scroll">
							<a href="#">
								<i class="fa fa-users"></i>
								<span class="nav-label">Contributors</span>
								<span class="fa arrow"></span>
							</a>
							<ul class="nav nav-second-level">
								<li class="active">
									<a href="#dashboard2">Current Contributors</a>
								</li>
							</ul>
						</li>
						<li class="smooth-scroll">
							<a href="#dashboard3">
								<i class="fa fa-money"></i>
								<span class="nav-label">Currency</span>
							</a>
						</li>
<?php
	
	if($roi_salesforce_integration) {
?>
						<li class="smooth-scroll">
							<a href="#dashboard3">
								<i class="fa fa-exchange"></i>
								<span class="nav-label">Salesforce Integration</span>
							</a>
						</li>
<?php
	}
?>
					</ul>

				</div>
			</nav>
	
			<div id="page-wrapper" class="gray-bg dashboard-1">
			
				<!-- Main ROI Content Holder -->
				<div class="row bottom-border">
				
					<!-- Fixed Top Navbar -->
					<nav class="navbar navbar-fixed-top" role="navigation">
					
						<!-- ROI Title -->
						<div class="navbar-header" style="padding: 15px 10px 15px 25px;">
							<h3 data-roi-title><?= $roi_specifics['roi_title'] ?></h3>
						</div>
						
						<ul class="nav navbar-top-links navbar-right">
							
							<li>
								<span class="m-r-sm text-muted welcome-message">Powered by <a href="https:\\www.theroishop.com" style="padding-left: 0;">The ROI Shop</a></span>
							</li>
							<li class="dropdown">
								
								<a href="../?roi=<?= $_GET['roi'] ?>">
									Return to Calculator
								</a>
							</li>						
						</ul>
						
					</nav>
					
				</div>
				
                <div id="dashboard1" class="row  border-bottom white-bg dashboard-header">

                    <div class="col-sm-12">
						<h1><i class="fa fa-shield"></i> Security</h1>
						<div class="form-horizontal">
							<div class="form-group">
								<label class="control-label col-xs-12 col-sm-12 col-md-12 col-lg-12"> ROI Verification Link</label>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<div class="input-group">
										<input id="verification_code" type="text" class="form-control no-access" value="https://www.theroishop.com/enterprise/?roi=<?= $_GET['roi'] ?>&v=<?= $roi_specifics['verification_code'] ?>">
										<span class="input-group-btn">
											<button id="copy-button" type="button" class="clipboard-btn btn btn-primary" data-clipboard-target="#verification_code">Copy</button>
										</span>										
										<span class="input-group-btn">
											<button onclick="resetVerificationModal()" type="button" class="btn btn-primary">Reset</button>
										</span>
									</div>
								</div>
							</div>
						</div>
                    </div>

				</div>
				
                <div id="dashboard2" class="row  border-bottom white-bg dashboard-header">

                    <div class="col-sm-12">
						<h1><i class="fa fa-users"></i> Current Contributors</h1>
						<div class="row">
							<div class="col-lg-6">
								<table id="table-contributor" class="table table-hover margin bottom">
									<thead>
										<tr>
											<th style="width: 1%" class="text-center">No.</th>
											<th>Contributor</th>
											<th class="text-center">Type</th>
											<th class="text-center">Added</th>
											<th class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="text-center">1</td>
											<td><?= $roi_owner['first_name']. ' ' . $roi_owner['last_name'] ?></td>
											<td class="text-center small">ROI Owner</td>
											<td class="text-center"></td>
											<td class="text-center"></td>
										</tr>
<?php
								$contributors = 1;
								foreach($roi_contributors as $contributor) {
									$contributors++;
?>
										<tr data-contributor-id="<?= $contributor['contributor_id'] ?>">
											<td class="text-center"><?= $contributors ?></td>
											<td><?= $contributor['contributor_name'] ?></td>
											<td class="text-center small">Contributor</td>
											<td class="text-center"><?= date("F jS, Y", strtotime($contributor['created_dt'])) ?></td>
											<td class="text-center"><button type="button" class="btn btn-circle btn-danger remove-contributor"><i class="fa fa-times"></i></button></td>
										</tr>
<?php
								}
?>
									</tbody>
								</table>
							</div>
							<div class="col-lg-6">
								<div class="ibox float-e-margins">
									<div class="ibox-title">
										<h3>Add a Contributor</h3>
									</div>
									<div class="ibox-content">
										<form id="form-add-contributor" class="form-horizontal">
											<div class="form-group">
												<label class="col-lg-2 control-label">Name</label>
												<div class="col-lg-10">
													<input name="name" type="text" placeholder="Name" class="form-control">
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-2 control-label">Email</label>
												<div class="col-lg-10">
													<input name="email" type="email" placeholder="Email" class="form-control">
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-2 control-label">Company</label>
												<div class="col-lg-10">
													<input name="company" type="text" placeholder="Company" class="form-control">
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-2 control-label">Notes</label>
												<div class="col-lg-10">
													<textarea class="form-control" name="notes" placeholder="Notes"></textarea>
												</div>
											</div>
											<div class="form-group">
												<div class="col-lg-offset-2 col-lg-10">
													<button onclick="addContributor()" class="btn btn-primary" type="button"><i class="fa fa-check"></i>Add Contributor</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>							
						</div>
                    </div>

				</div>
				
                <div id="dashboard3" class="row  border-bottom white-bg dashboard-header">

                    <div class="col-sm-12">
						<h1><i class="fa fa-money"></i> Currency</h1>				
						<div class="col-lg-12">
							<div class="ibox float-e-margins">
								<div class="form-group">
									<label class="control-label col-xs-12 col-sm-12 col-md-12 col-lg-8">Select From Existing Currency</label>
									<div class=" col-xs-12 col-sm-12 col-md-12 col-lg-4">
										<select class="form-control chosen-selector currency-selector" data-placeholder="Please make a selection below">
<?php
	foreach($roi_currencies as $currency) {
?>
											<option data-currency-id="<?= $currency['currency_id'] ?>" value="<?= $currency['currency_symbol'] ?>"><?= $currency['currency_name'] ?></option>
<?php
	}
?>
										</select>
									</div>
								</div>
							</div>
						</div>
                    </div>

				</div>
<?php
	
	if($roi_salesforce_integration) {
?>
                <div id="dashboard4" class="row  border-bottom white-bg dashboard-header">

                    <div class="col-sm-12">
						<h1><i class="fa fa-exchange"></i> Salesforce Integration</h1>				
						<div class="col-lg-12">
<?php
			if( $roi_salesforce['code'] ) {
?>				
							<table class="table margin bottom">
								<tr>
									<td>
										<select class="sf-elements chosen-selector" data-placeholder="Retrieve your salesforce elements below">
<?php
				if( $roi_specifics['linked_title'] ) {
?>
											<option value="<?= $roi_specifics['sfdc_link'] ?>" selected="selected"><?= $roi_specifics['linked_title'] ?></option>
<?php
				}
?>
										</select>
									</td>
									<td><button type="button" class="btn btn-primary link-to-opportunity" onclick="LinkROItoSalesforce(true)">Retrieve all Elements</button></td>
								</tr>	
							</table>
							<div class="panel panel-default">
								<div class="panel-heading">
									<h5 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Filter Opportunities <span class="pull-right fa fa-chevron-down"></span></a>
									</h5>
								</div>
								<div id="collapseOne" class="panel-collapse collapse in">
									<div class="panel-body opportunityFiltering">
										Due to Salesforce limitations only 2000 elements can be returned during a single call. If the number of opportunities returned are greater than 2000, only the first 2000 will be shown.
										<hr/>
										<div class="form-group filterString">
											<div class="col-lg-4">
												<select class="form-control chosen-selector vendorPath" data-placeholder="Please make a selection below">
													<option value="IsClosed">Is Closed</option>
													<option value="Name">Name</option>
												</select>
											</div>
											<div class="col-lg-2">
												<select class="form-control chosen-selector whereCondition" data-placeholder="Please make a selection below">
													<option value="equals">Equals</option>
													<option value="contains">Contains</option>
													<option value="beginswith">Begins With</option>
													<option value="endswith">Ends With</option>
													<option value="greater">Greater Than</option>
													<option value="lesser">Less Than</option>
													<option value="greaterequal">Greater Than or Equal To</option>
													<option value="lesserequal">Less Than or Equal To</option>
												</select>
											</div>
											<div class="col-lg-5">
												<input class="form-control whereClause" name="sfFilterString" type="text" placeholder="Where Clause">
											</div>
											<div class="col-lg-1">
												<button class="btn btn-info btn-circle newFilter" type="button"><i class="fa fa-plus"></i></button>
												<button class="btn btn-danger btn-circle removeFilter" type="button"><i class="fa fa-times"></i></button>
											</div>
										</div>
									</div>
								</div>
							</div>
<?php

				if( $roi_specifics['roi_version_id'] == 508 ) {
?>
								<div>	
									<button type="button" class="btn btn-primary push-to-salesforce" onclick="pushWorkfrontSalesforce()">Push to Salesforce</button>
								</div>
<?php
				}
				
			} else {
?>
							<h3>You must connect to salesforce before you can link this ROI. Please head to you <a href="//www.theroishop.com/dashboard/account.php">account</a> page to do so.</h3>
						</div>
                    </div>

				</div>
<?php
	
			}
	}
?>
				<div class="modal inmodal" id="modal-shell" tabindex="-1" role="dialog" aria-hidden="true"></div>				

			</div>
		
		</div>
		
		<script src="../js/jquery/jquery-2.1.1.js"></script>
		<script src="../js/bootstrap/bootstrap.min.js"></script>
		<script src="../js/datatables/jquery.dataTables.min.js"></script>
		<script src="../js/chosen/chosen.jquery.js"></script>
		<script src="../js/calculator/video/video.functions.js"></script>
		<script src="../js/calculator/calx/numeral.js"></script>
		<script src="../js/calculator/calx/jquery-calx-2.1.1.js"></script>
		<script src="../js/tooltipster/jquery.tooltipster.min.js"></script>
		<script src="../js/highcharts/highcharts.js"></script>
		<script src="../js/clipboard/clipboard.min.js"></script>
		<script src="../js/noty/noty.min.js"></script>
		<script src="../js/modal/modals.js"></script>
		<script src="../js/calculator/setup.plugins.js"></script>
		<script src="../js/scroll/smooth-scroll.js"></script>
		<script src="../js/metisMenu/jquery.metisMenu.js"></script>
		<script src="js/setup.plugins.js?v=4"></script>
		
	</body>
	
</html>