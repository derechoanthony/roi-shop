<?php

	// Establish connection to the database
	
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/constants.php");
	require_once("$root/db/connection.php");
	
	require_once("php/dashboard.actions.php");
	
	require_once("$root/email/swiftmailer/lib/swift_required.php");
	
	$dashboard = new DashboardActions($db);
	$user_permissions = $dashboard->userPermissions();
	$company_specs = $dashboard->companySpecs();
	$company_users = $dashboard->companyUsers();
	$company_structures = $dashboard->companyStructures();
	$company_rois = $dashboard->companyRois();
	$company_versions = $dashboard->companyVersions();
	$version_paths = $dashboard->versionPaths();

	if( isset($user_permissions) && $user_permissions['has_dashboard_access'] == 1 || $_SESSION['FullAccess'] == 1 ) {

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $company_specs['company_name'] ?></title>
	
	<link rel="stylesheet" href="css/plugins/editable/bootstrap-editable.css">

	<!-- Bootstrap CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">

	<!-- Animate CSS -->
	<link href="css/animate.css" rel="stylesheet" media="screen">

	<!-- Main CSS -->
	<link href="css/main.css" rel="stylesheet" media="screen">
	
	<!-- Data Tables -->
	<link rel="stylesheet" href="css/datatables/dataTables.bs.min.css">
	<link rel="stylesheet" href="css/datatables/autoFill.bs.min.css">
	<link rel="stylesheet" href="css/datatables/fixedHeader.bs.css">
	<link href="css/plugins/alertify/alertify.core.css" rel="stylesheet">
	<link href="css/plugins/alertify/alertify.default.css" rel="stylesheet">
	<link href="css/plugins/dropzone/dropzone.css" rel="stylesheet">

	<!-- Icomoon Icons -->
	<link href="fonts/icomoon/icomoon.css" rel="stylesheet" />
	<link href="fonts/font-awesome.css" rel="stylesheet" />
	<link href="css/plugins/chosen/chosen.css" rel="stylesheet">
	
	<!-- HTML5 shiv and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="js/html5shiv.js"></script>
		<script src="js/respond.min.js"></script>
	<![endif]-->
	
	<style>
	
button.dt-button, div.dt-button, a.btn-default {
    position: relative;
    display: inline-block;
    box-sizing: border-box;
    margin-right: 0.333em;
    padding: 0.5em 1em;
    border: 1px solid #999;
    border-radius: 2px;
    cursor: pointer;
    font-size: 0.88em;
    color: black;
    white-space: nowrap;
    overflow: hidden;
    background-color: #e9e9e9;
    background-image: -webkit-linear-gradient(top, #fff 0%, #e9e9e9 100%);
    background-image: -moz-linear-gradient(top, #fff 0%, #e9e9e9 100%);
    background-image: -ms-linear-gradient(top, #fff 0%, #e9e9e9 100%);
    background-image: -o-linear-gradient(top, #fff 0%, #e9e9e9 100%);
    background-image: linear-gradient(to bottom, #fff 0%, #e9e9e9 100%);
    filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,StartColorStr='white', EndColorStr='#e9e9e9');
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    text-decoration: none;
    outline: none;
}

</style>

</head>

	<body class="pink">

		<!-- Left sidebar start -->
		<div class="vertical-nav">

			<!-- Collapse menu starts -->
			<button class="collapse-menu">
				<i class="icon-dehaze"></i>
			</button>
			<!-- Collapse menu ends -->

			<!-- Logo starts -->
			<div class="logo">
				<a href="index.html">
					<img src="company_specific_files/1/logo/logo.png" alt="Sunrise" />
				</a>
			</div>
			<!-- Logo ends -->

			<!-- Sidebar menu start -->
			<ul class="menu clearfix">
				<li>
					<a href="#">
						<i class="icon-graphic_eq"></i>
						<span class="menu-item"><?= $company_specs['company_name'] ?></span>
						<span class="down-arrow"></span>
					</a>
					<ul>
						<li>
							<a href="index.html">
								Manage Users
							</a>
						</li>
					</ul>
				</li>
			</ul>
			<!-- Sidebar menu snd -->
			
		</div>
		<!-- Left sidebar end -->

		<!-- Dashboard Wrapper Start -->
		<div class="dashboard-wrapper dashboard-wrapper-lg">

			<!-- Header start -->
			<header>
				<ul class="pull-left" id="left-nav">
					<li class="dropdown pull-left">
						<ul class="dropdown-menu fadeInUp animated messages">
							<li class="dropdown-header">Meetings today</li>
						</ul>
					</li>
				</ul>
				<div class="pull-right">
					<ul id="mini-nav" class="clearfix">
						<li class="list-box dropdown hidden-xs">
							<a href="login.html">
								<i class="icon-exit_to_app"></i>
							</a>
						</li>
						<li class="list-box hidden-lg hidden-md">
							<button type="button" id="toggleMenu" class="toggle-menu">
								<i class="icon-menu"></i>
							</button>
						</li>
					</ul>
				</div>
				<div class="custom-search hidden-sm hidden-xs">
					<input type="text" class="search-query" placeholder="Search here ...">
					<i class="icon-search4"></i>
				</div>
			</header>
			<!-- Header ends -->

			<!-- Top Bar Starts -->
			<div class="top-bar clearfix">
				<div class="page-title">
					<h4><?= $company_specs['company_name'] ?> Dashboard</h4>
				</div>
			</div>
			<!-- Top Bar Ends -->

			<ol class="breadcrumb blue-bg">
				<li><a href="#" data-original-title="" title=""><?= $company_specs['company_name'] ?></a></li>
				<li><a href="#" data-original-title="" title="">Dashboard</a></li>
				<li class="active">Manage Users</li>
			</ol>
									
			<!-- Main Container Start -->
			<div class="main-container">

				<!-- Container fluid Starts -->
				<div class="container-fluid">

					<!-- Row Starts -->
					<div class="row gutter">
						<div class="col-md-2 col-sm-4 col-xs-12">
							<div class="panel panel-red social-stats">
								<div class="panel-body">
									<div class="social-icon">
										<i class="fa fa-users text-danger"></i>
									</div>
									<div class="stats-details">
										<h2 class="text-danger"><?= count($company_users) ?></h2>
										<h2 class="text-grey">Total Users</h2>
									</div>
								</div>
							</div>
						</div>
						<!--<div class="col-md-2 col-sm-4 col-xs-12">
							<div class="panel panel-brown social-stats">
								<div class="panel-body">
									<div class="social-icon">
										<i class="fa fa-tasks text-brown"></i>
									</div>
									<div class="stats-details">
										<h2 class="text-brown"><?= count($company_structures) ?></h2>
										<h2 class="text-grey">Templates</h2>
									</div>
								</div>
							</div>
						</div>-->
						<div class="col-md-2 col-sm-4 col-xs-12">
							<div class="panel panel-fb social-stats">
								<div class="panel-body">
									<div class="social-icon">
										<i class="fa fa-pencil text-fb"></i>
									</div>
									<div class="stats-details">
										<h2 class="text-fb"><?= count($company_rois) ?></h2>
										<h2 class="text-grey">Business Cases</h2>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Row Ends -->
					
					<h1 class="text-grey">Manage Users</h1>
					<hr>
					
					<div class="row gutter">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="panel panel-blue">
								<div class="panel-heading">
									<h4>Current Company Users</h4>
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table data-order='[[ 1, "asc" ]]' id="basicExample" class="table table-striped table-condensed table-bordered no-margin">
											<thead>
												<tr>
													<th>Username</th>
													<th>Created Cases</th>
													<th>Manager</th>
													<th>Actions</th>
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th>Username</th>
													<th>Created Cases</th>
													<th>Manager</th>
													<th>Actions</th>
												</tr>
											</tfoot>
											<tbody>
<?php
								foreach($company_users as $user) {
									$users_rois = array_keys(array_column($company_rois, 'user_id'),$user['user_id']);
									$manager = array_keys(array_column($company_users, 'user_id'),$user['manager']);
?>
												<tr>
													<!--<td>
														<button class="btn btn-xs <?= $user['status'] == 0 ? 'btn-danger' : 'btn-primary' ?> toggle-activity" type="button"><?= $user['status'] == 0 ? 'Inactive' : 'Active' ?></button>
													</td>-->
													<td><a data-user-id="<?= $user['user_id'] ?>" data-username><?= $user['username'] ?></a></td>
													<td><?= count($users_rois); ?></td>
													<td><a href="#" data-name="<?= $user['user_id'] ?>" class="user-manager" data-type="select" data-pk="1" data-title="Change User Manager"><?= $company_users[$manager[0]]['username'] ?></a></td>
													<td>
														<button class="btn btn-xs btn-primary reset-username" type="button">Change Username</button>
														<button class="btn btn-xs btn-success transfer-user-rois" type="button">Transfer</button>
														<button class="btn btn-xs btn-danger delete-user" type="button">Delete</button>
													</td>
												</tr>
<?php
								}
?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>					
					</div>

					<div class="row gutter">						
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="panel panel-blue">
								<div class="panel-heading">
									<h4>Add New Users</h4>
								</div>
								<div class="panel-body">
									<div class="row gutter">
										<div class="col-lg-12">
											<div class="alert alert-info">
												Add Users to the table below. Once all users are ready to be added click Add New Users on the table below. You currently have <span class="users-remaining"><?= $company_specs['users'] - count($company_users) ?></span> licenses remaining.
											</div>
										</div>
										<!--<div class="col-lg-12">
											<div class="alert alert-info">
												Or add users manually.
											</div>
										</div>-->
									</div>
									<div class="row gutter">
										<!--<div class="col-lg-2">									
											<form action="php/upload.php?companyid=<?= $_GET['companyid'] ?>" class="dropzone" style="min-height: 200px; border-color: #6e91cb;" id="roi_users">
												<div class="fallback">
													Drop a user csv file here to upload
													<input accept=".csv" name="file" type="file" id="inputImage" multiple />
												</div>
											</form>
										</div>-->
										<div class="col-lg-12">										
											<form id="addUsertoTable">
												<div class="form-group">
													<div class="row gutter">
														<div class="col-md-12">
															<label class="control-label">Email (Username)</label>
															<input type="text" class="form-control" name="email">
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="row gutter">
														<div class="col-md-6">
															<label class="control-label">First Name</label>
															<input type="text" class="form-control" name="first_name">
														</div>
														<div class="col-md-6">
															<label class="control-label">Last Name</label>
															<input type="text" class="form-control" name="last_name">
														</div>
													</div>
												</div>													
											</form>
											<button type="submit" class="btn btn-danger pull-right add-user-table">Check Availibility</button>
										</div>
									</div>
									<br/>
									<div class="row gutter">
										<div class="table-responsive">
											<table id="usersToAdd" class="table table-striped table-condensed table-bordered no-margin">
												<thead>
													<th>Status</th>
													<th>First Name</th>
													<th>Last Name</th>
													<th>Email</th>
													<th>Issues</th>
													<th>Actions</th>
												</thead>
											</table>
										</div>
										<div class="col-lg-12">
											<button type="button" class="btn btn-success add-users">Add New Users</button>
										</div>
									</div>
								</div>
							</div>					
						</div>
					</div>

					<h1 class="text-grey">All ROIs</h1>
					<hr>
					
					<div class="row gutter">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="panel panel-blue">
								<div class="panel-heading">
									<h4>Current Company ROIs</h4>
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table data-order='[[ 1, "asc" ]]' id="companyROIs" class="table table-striped table-condensed table-bordered no-margin">
											<thead>
												<tr>
													<th>Username</th>
													<th>ROI Name</th>
													<th>Link to ROI</th>
													<th>Created Date</th>
													<th>Visits</th>
													<th>Unique</th>
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th>Username</th>
													<th>ROI Name</th>
													<th>Link to ROI</th>
													<th>Created Date</th>
													<th>Visits</th>
													<th>Unique</th>
												</tr>
											</tfoot>
											<tbody>
<?php
								foreach($company_rois as $roi) {
									$users_id = array_keys(array_column($company_users, 'user_id'),$roi['user_id']);
									$username = $company_users[$users_id[0]]['username'];
									$version_level = array_keys(array_column($company_versions, 'version_id'),$roi['roi_version_id']);
									
									$version_level = $company_versions[$version_level[0]]['ep_version_level'];
									$version_path = array_keys(array_column($version_paths, 'version_level_id'),$version_level);
									
									$final_path = $version_paths[$version_path[0]]['version_path'];
									
									if($username) {
?>
												<tr>
													<td><?= $username ?></td>
													<td><?= $roi['roi_title'] ?></td>
													<td><a href="https://www.theroishop.com/<?= $final_path ?>?roi=<?= $roi['roi_id'] ?>">https://www.theroishop.com/<?= $final_path  ?>?roi=<?= $roi['roi_id'] ?>&v=<?= $roi['verification_code'] ?></a></td>
													<td><?= $roi['dt'] ?></td>
													<td><?= $roi['visits'] ?></td>
													<td><?= $roi['unique_ip'] ?></td>
												</tr>
<?php
									}
								}
?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>					
					</div>					
				</div>
				<!-- Container fluid ends -->

			</div>
			<!-- Main Container Start -->

			<!-- Footer Start -->
			<footer>
				Copyright The ROI Shop <span>2017</span>.
			</footer>
			<!-- Footer end -->
			<div class="modal inmodal" id="modal-shell" tabindex="-1" role="dialog" aria-hidden="true"></div>
		</div>
		<!-- Dashboard Wrapper End -->

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="js/jquery.js"></script>	
		<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jq-2.2.4/jszip-2.5.0/pdfmake-0.1.18/dt-1.10.13/af-2.1.3/b-1.2.4/b-colvis-1.2.4/b-flash-1.2.4/b-html5-1.2.4/b-print-1.2.4/fc-3.2.2/fh-3.1.2/kt-2.2.0/r-2.1.1/rr-1.2.0/sc-1.4.2/se-1.2.0/datatables.min.js"></script>	

		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/bootstrap.min.js"></script>

		<!-- Sparkline graphs -->
		<script src="js/sparkline.js"></script>

		<!-- Easy Pie charts -->
		<script src="js/jquery.easy-pie-chart.js"></script>
					
		<!-- jquery ScrollUp JS -->
		<script src="js/scrollup/jquery.scrollUp.js"></script>
		<script src="js/plugins/alertify/alertify.js"></script>
		
		<!-- Custom JS -->
		<script src="js/custom.js"></script>
		<script src="js/custom-components.js"></script>
		<script src="js/plugins/editable/bootstrap-editable.js"></script>
		<script src="js/plugins/dropzone/dropzone.js"></script>
		<script src="js/dashboard.functions.js"></script>
		<script src="js/plugins/chosen/chosen.jquery.js"></script>
		<script src="js/plugins/modal/modals.js"></script>

		<script src="js/datatables/custom-datatables.js"></script>
		<script src="js/setup.plugins.js"></script>
	</body>

</html>

<?php

	} else {
		
		header("Location: /login?ref=".$_SERVER["REQUEST_URI"]);
		break;		
	}
?>
