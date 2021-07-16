<?php
	
	require_once("../inc/base.php");
	require_once("../php/classes.admin.php");
	require_once("inc/init.php");

	$admin = new TheROIShopAdmin($db);

	$getComp = $admin->getCompanySpecs();
	$getUsers = $admin->getUsers();
	$getRois = $admin->getRois();
	$getChildComps = $admin->getChildren();
		
	$totalViews = 0;
	$monthlyViews = array();
	$monthlyRois = array();
	for( $i=0; $i<count($getRois); $i++ )
	{
		for( $yr=0; $yr<12; $yr++ )
		{
			if( date("y", strtotime($getRois[$i]['dt'])) == date("y", strtotime("-" .(12-$yr). " months")) && 
				date("m", strtotime($getRois[$i]['dt'])) == date("m", strtotime("-" .(12-$yr). " months")) )
			{
				$monthlyViews[$yr] += $getRois[$i]['visits'];
				$monthlyRois[$yr] += 1;
			}
		}

		$totalViews += $getRois[$i]['visits'];
	}
	
?>

<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> Dashboard <span>> My Dashboard</span></h1>
	</div>
	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
		<ul id="sparks" class="">
			<li class="sparks-info">
				<h5> ROIs Created <span class="txt-color-purple"><i class="fa fa-pencil-square-o"></i>&nbsp;<?=array_sum($monthlyRois)?></span></h5>
				<div class="sparkline txt-color-purple hidden-mobile hidden-md hidden-sm">
					<?= $monthlyRois[0] ?>, <?= $monthlyRois[1] ?>, <?= $monthlyRois[2] ?>, <?= $monthlyRois[3] ?>, <?= $monthlyRois[4] ?>, 
					<?= $monthlyRois[5] ?>, <?= $monthlyRois[6] ?>, <?= $monthlyRois[7] ?>, <?= $monthlyRois[8] ?>, <?= $monthlyRois[9] ?>, 
					<?= $monthlyRois[10] ?>, <?= $monthlyRois[11] ?>
				</div>
			</li>
			<li class="sparks-info">
				<h5> ROI Views <span class="txt-color-blue"><i class="fa fa-eye"></i>&nbsp;<?=array_sum($monthlyViews)?></span></h5>
				<div class="sparkline txt-color-blue hidden-mobile hidden-md hidden-sm">
					<?= $monthlyViews[0] ?>, <?= $monthlyViews[1] ?>, <?= $monthlyViews[2] ?>, <?= $monthlyViews[3] ?>, <?= $monthlyViews[4] ?>, 
					<?= $monthlyViews[5] ?>, <?= $monthlyViews[6] ?>, <?= $monthlyViews[7] ?>, <?= $monthlyViews[8] ?>, <?= $monthlyViews[9] ?>, 
					<?= $monthlyViews[10] ?>, <?= $monthlyViews[11] ?>
				</div>
			</li>
		</ul>
	</div>
</div>
<!-- widget grid -->
<section id="widget-grid" class="">

	<!-- row -->
	<div class="row">
		<article class="col-sm-12">
			<!-- new widget -->
			<div class="jarviswidget" id="wid-id-0" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
				<!-- widget options:
				usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

				data-widget-colorbutton="false"
				data-widget-editbutton="false"
				data-widget-togglebutton="false"
				data-widget-deletebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-custombutton="false"
				data-widget-collapsed="true"
				data-widget-sortable="false"

				-->
				<header>
					<span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
					<h2>Live Feeds </h2>

					<ul class="nav nav-tabs pull-right in" id="myTab">
						<li class="active">
							<a data-toggle="tab" href="#s1"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">Users</span></a>
						</li>

						<li>
							<a data-toggle="tab" href="#s2"><i class="fa fa-facebook"></i> <span class="hidden-mobile hidden-tablet">Social Network</span></a>
						</li>

						<li>
							<a data-toggle="tab" href="#s3"><i class="fa fa-dollar"></i> <span class="hidden-mobile hidden-tablet">Revenue</span></a>
						</li>
					</ul>

				</header>

				<!-- widget div-->
				<div class="no-padding">
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">

						test
					</div>
					<!-- end widget edit box -->

					<div class="widget-body">
						<!-- content -->
						<div id="myTabContent" class="tab-content">
							<div class="tab-pane fade active in padding-10 no-padding-bottom" id="s1">
								<div class="row no-space">
									<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
										<span class="demo-liveupdate-1"> <span class="onoffswitch-title">Live switch</span> <span class="onoffswitch">
												<input type="checkbox" name="start_interval" class="onoffswitch-checkbox" id="start_interval">
												<label class="onoffswitch-label" for="start_interval"> 
													<span class="onoffswitch-inner" data-swchon-text="ON" data-swchoff-text="OFF"></span> 
													<span class="onoffswitch-switch"></span> </label> </span> </span>
										<div id="updating-chart" class="chart-large txt-color-blue"></div>

									</div>
									<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 show-stats">

										<div class="row">
											<div class="col-xs-6 col-sm-6 col-md-12 col-lg-12"> <span class="text"> Users <span class="pull-right"><?= count($getUsers).($getComp['maxUsers']?'/'.$getComp['maxUsers']:''); ?></span> </span>
												<div class="progress">
													<div class="progress-bar bg-color-blueDark" style="width: <?= ($getComp['maxUsers']?count($getUsers)/$getComp['maxUsers']*100:0) .'%' ?>;"></div>
												</div> </div>
											<div class="col-xs-6 col-sm-6 col-md-12 col-lg-12"> <span class="text"> Users Available <span class="pull-right"><?= ($getComp['maxUsers']?($getComp['maxUsers']-count($getUsers)<0?0:$getComp['maxUsers']-count($getUsers)):'Unlimited').($getComp['maxUsers']?'/'.$getComp['maxUsers']:''); ?></span> </span>
												<div class="progress">
													<div class="progress-bar bg-color-blueDark" style="width: <?= ($getComp['maxUsers']?($getComp['maxUsers']-count($getUsers))/$getComp['maxUsers']*100:100) .'%' ?>;"></div>
												</div> </div>
											<div class="col-xs-6 col-sm-6 col-md-12 col-lg-12"> <span class="text"> ROIs Created <span class="pull-right"><?= count($getRois) ?></span> </span>
												<div class="progress">
													<div class="progress-bar bg-color-blue" style="width: 34%;"></div>
												</div> </div>
											<div class="col-xs-6 col-sm-6 col-md-12 col-lg-12"> <span class="text"> ROI Views <span class="pull-right"><?= $totalViews ?></span> </span>
												<div class="progress">
													<div class="progress-bar bg-color-blue" style="width: 77%;"></div>
												</div> </div>

											<span class="show-stat-buttons"> <span class="col-xs-12 col-sm-6 col-md-6 col-lg-6"> <a href="#ajax/users.php" class="btn btn-default btn-block hidden-xs">Manage Users</a> </span> <span class="col-xs-12 col-sm-6 col-md-6 col-lg-6"> <a href="javascript:void(0);" class="btn btn-default btn-block hidden-xs">Manage ROIs</a> </span> </span>

										</div>

									</div>
								</div>

							</div>
							<!-- end s1 tab pane -->

							<div class="tab-pane fade" id="s2">
								<div class="widget-body-toolbar bg-color-white">

									<form class="form-inline" role="form">

										<div class="form-group">
											<label class="sr-only" for="s123">Show From</label>
											<input type="email" class="form-control input-sm" id="s123" placeholder="Show From">
										</div>
										<div class="form-group">
											<input type="email" class="form-control input-sm" id="s124" placeholder="To">
										</div>

										<div class="btn-group hidden-phone pull-right">
											<a class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown"><i class="fa fa-cog"></i> More <span class="caret"> </span> </a>
											<ul class="dropdown-menu pull-right">
												<li>
													<a href="javascript:void(0);"><i class="fa fa-file-text-alt"></i> Export to PDF</a>
												</li>
												<li>
													<a href="javascript:void(0);"><i class="fa fa-question-sign"></i> Help</a>
												</li>
											</ul>
										</div>

									</form>

								</div>
								<div class="padding-10">
									<div id="statsChart" class="chart-large has-legend-unique"></div>
								</div>

							</div>
							<!-- end s2 tab pane -->

							<div class="tab-pane fade" id="s3">

								<div class="widget-body-toolbar bg-color-white smart-form" id="rev-toggles">

									<div class="inline-group">

										<label for="gra-0" class="checkbox">
											<input type="checkbox" name="gra-0" id="gra-0" checked="checked">
											<i></i> Target </label>
										<label for="gra-1" class="checkbox">
											<input type="checkbox" name="gra-1" id="gra-1" checked="checked">
											<i></i> Actual </label>
										<label for="gra-2" class="checkbox">
											<input type="checkbox" name="gra-2" id="gra-2" checked="checked">
											<i></i> Signups </label>
									</div>

									<div class="btn-group hidden-phone pull-right">
										<a class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown"><i class="fa fa-cog"></i> More <span class="caret"> </span> </a>
										<ul class="dropdown-menu pull-right">
											<li>
												<a href="javascript:void(0);"><i class="fa fa-file-text-alt"></i> Export to PDF</a>
											</li>
											<li>
												<a href="javascript:void(0);"><i class="fa fa-question-sign"></i> Help</a>
											</li>
										</ul>
									</div>

								</div>

								<div class="padding-10">
									<div id="flotcontainer" class="chart-large has-legend-unique"></div>
								</div>
							</div>
							<!-- end s3 tab pane -->
						</div>

						<!-- end content -->
					</div>

				</div>
				<!-- end widget div -->
			</div>
			<!-- end widget -->

		</article>
	</div>

	<!-- end row -->

	<!-- row -->

	<div class="row">

		<article class="col-sm-12 col-md-12 col-lg-6">

			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-custombutton="false">
				<!-- widget options:
					usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
					
					data-widget-colorbutton="false"	
					data-widget-editbutton="false"
					data-widget-togglebutton="false"
					data-widget-deletebutton="false"
					data-widget-fullscreenbutton="false"
					data-widget-custombutton="false"
					data-widget-collapsed="true" 
					data-widget-sortable="false"
					
				-->
				<header>
					<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
					<h2>ROI Specifics </h2>				
					
				</header>

				<!-- widget div-->
				<div>
					
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->
						
					</div>
					<!-- end widget edit box -->
					
					<!-- widget content -->
					<div class="widget-body no-padding">
						
						<form action="" id="review-form" class="smart-form">
							<header>
								<?= $getComp['compName']; ?>
							</header>

							<fieldset class="margin-top-10">
								<section>
									<label class="label">Company Name</label>
									<label class="input"> <i class="icon-append fa fa-user"></i>
										<input type="text" name="compName">
									</label>
								</section>

								<section>
									<label>Return Period</label>
									<input type="text" class="slider slider-primary" id="sa5" value="" 
									data-slider-min="1" 
									data-slider-max="5"  
									data-slider-value="1" 
									data-slider-selection = "before"
									data-slider-handle="round">
								</section>
							</fieldset>
							<footer>
								<button type="submit" class="btn btn-primary">
									Validate Form
								</button>
							</footer>
						</form>						
						
					</div>
					<!-- end widget content -->
					
				</div>
				<!-- end widget div -->
				
			</div>
			<!-- end widget -->	

			<!-- new widget -->
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-3" data-widget-colorbutton="false">

				<!-- widget options:
				usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

				data-widget-colorbutton="false"
				data-widget-editbutton="false"
				data-widget-togglebutton="false"
				data-widget-deletebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-custombutton="false"
				data-widget-collapsed="true"
				data-widget-sortable="false"

				-->
				<header>
					<span class="widget-icon"> <i class="fa fa-calendar"></i> </span>
					<h2> My Events </h2>
					<div class="widget-toolbar">
						<!-- add: non-hidden - to disable auto hide -->
						<div class="btn-group">
							<button class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown">
								Showing <i class="fa fa-caret-down"></i>
							</button>
							<ul class="dropdown-menu js-status-update pull-right">
								<li>
									<a href="javascript:void(0);" id="mt">Month</a>
								</li>
								<li>
									<a href="javascript:void(0);" id="ag">Agenda</a>
								</li>
								<li>
									<a href="javascript:void(0);" id="td">Today</a>
								</li>
							</ul>
						</div>
					</div>
				</header>

				<!-- widget div-->
				<div>
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">

						<input class="form-control" type="text">

					</div>
					<!-- end widget edit box -->

					<div class="widget-body no-padding">
						<!-- content goes here -->
						<div class="widget-body-toolbar">

							<div id="calendar-buttons">

								<div class="btn-group">
									<a href="javascript:void(0)" class="btn btn-default btn-xs" id="btn-prev"><i class="fa fa-chevron-left"></i></a>
									<a href="javascript:void(0)" class="btn btn-default btn-xs" id="btn-next"><i class="fa fa-chevron-right"></i></a>
								</div>
							</div>
						</div>
						<div id="calendar"></div>

						<!-- end content -->
					</div>

				</div>
				<!-- end widget div -->
			</div>
			<!-- end widget -->

		</article>

		<article class="col-sm-12 col-md-12 col-lg-6">

			<!-- new widget -->
			<div class="jarviswidget" id="wid-id-2" data-widget-colorbutton="false" data-widget-editbutton="false">

				<!-- widget options:
				usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

				data-widget-colorbutton="false"
				data-widget-editbutton="false"
				data-widget-togglebutton="false"
				data-widget-deletebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-custombutton="false"
				data-widget-collapsed="true"
				data-widget-sortable="false"

				-->

				<header>
					<span class="widget-icon"> <i class="fa fa-map-marker"></i> </span>
					<h2>Birds Eye</h2>
					<div class="widget-toolbar hidden-mobile">
						<span class="onoffswitch-title"><i class="fa fa-location-arrow"></i> Realtime</span>
						<span class="onoffswitch">
							<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" checked="checked" id="myonoffswitch">
							<label class="onoffswitch-label" for="myonoffswitch"> <span class="onoffswitch-inner" data-swchon-text="YES" data-swchoff-text="NO"></span> <span class="onoffswitch-switch"></span> </label> </span>
					</div>
				</header>

				<!-- widget div-->
				<div>
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<div>
							<label>Title:</label>
							<input type="text" />
						</div>
					</div>
					<!-- end widget edit box -->

					<div class="widget-body no-padding">
						<!-- content goes here -->

						<div id="vector-map" class="vector-map"></div>
						<div id="heat-fill">
							<span class="fill-a">0</span>

							<span class="fill-b">5,000</span>
						</div>

						<table class="table table-striped table-hover table-condensed">
							<thead>
								<tr>
									<th>Country</th>
									<th>Visits</th>
									<th class="text-align-center">User Activity</th>
									<th class="text-align-center">Online</th>
									<th class="text-align-center">Demographic</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><a href="javascript:void(0);">USA</a></td>
									<td>4,977</td>
									<td class="text-align-center">
									<div class="sparkline txt-color-blue text-align-center" data-sparkline-height="22px" data-sparkline-width="90px" data-sparkline-barwidth="2">
										2700, 3631, 2471, 1300, 1877, 2500, 2577, 2700, 3631, 2471, 2000, 2100, 3000
									</div></td>
									<td class="text-align-center">143</td>
									<td class="text-align-center">
									<div class="sparkline display-inline" data-sparkline-type='pie' data-sparkline-piecolor='["#E979BB", "#57889C"]' data-sparkline-offset="90" data-sparkline-piesize="23px">
										17,83
									</div>
									<div class="btn-group display-inline pull-right text-align-left hidden-tablet">
										<button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
											<i class="fa fa-cog fa-lg"></i>
										</button>
										<ul class="dropdown-menu dropdown-menu-xs pull-right">
											<li>
												<a href="javascript:void(0);"><i class="fa fa-file fa-lg fa-fw txt-color-greenLight"></i> <u>P</u>DF</a>
											</li>
											<li>
												<a href="javascript:void(0);"><i class="fa fa-times fa-lg fa-fw txt-color-red"></i> <u>D</u>elete</a>
											</li>
											<li class="divider"></li>
											<li class="text-align-center">
												<a href="javascript:void(0);">Cancel</a>
											</li>
										</ul>
									</div></td>
								</tr>
								<tr>
									<td><a href="javascript:void(0);">Australia</a></td>
									<td>4,873</td>
									<td class="text-align-center">
									<div class="sparkline txt-color-blue text-align-center" data-sparkline-height="22px" data-sparkline-width="90px" data-sparkline-barwidth="2">
										1000, 1100, 3030, 1300, -1877, -2500, -2577, -2700, 3631, 2471, 4700, 1631, 2471
									</div></td>
									<td class="text-align-center">247</td>
									<td class="text-align-center">
									<div class="sparkline display-inline" data-sparkline-type='pie' data-sparkline-piecolor='["#E979BB", "#57889C"]' data-sparkline-offset="90" data-sparkline-piesize="23px">
										22,88
									</div>
									<div class="btn-group display-inline pull-right text-align-left hidden-tablet">
										<button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
											<i class="fa fa-cog fa-lg"></i>
										</button>
										<ul class="dropdown-menu dropdown-menu-xs pull-right">
											<li>
												<a href="javascript:void(0);"><i class="fa fa-file fa-lg fa-fw txt-color-greenLight"></i> <u>P</u>DF</a>
											</li>
											<li>
												<a href="javascript:void(0);"><i class="fa fa-times fa-lg fa-fw txt-color-red"></i> <u>D</u>elete</a>
											</li>
											<li class="divider"></li>
											<li class="text-align-center">
												<a href="javascript:void(0);">Cancel</a>
											</li>
										</ul>
									</div></td>
								</tr>
								<tr>
									<td><a href="javascript:void(0);">India</a></td>
									<td>3,671</td>
									<td class="text-align-center">
									<div class="sparkline txt-color-blue text-align-center" data-sparkline-height="22px" data-sparkline-width="90px" data-sparkline-barwidth="2">
										3631, 1471, 2400, 3631, 471, 1300, 1177, 2500, 2577, 3000, 4100, 3000, 7700
									</div></td>
									<td class="text-align-center">373</td>
									<td class="text-align-center">
									<div class="sparkline display-inline" data-sparkline-type='pie' data-sparkline-piecolor='["#E979BB", "#57889C"]' data-sparkline-offset="90" data-sparkline-piesize="23px">
										10,90
									</div>
									<div class="btn-group display-inline pull-right text-align-left hidden-tablet">
										<button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
											<i class="fa fa-cog fa-lg"></i>
										</button>
										<ul class="dropdown-menu dropdown-menu-xs pull-right">
											<li>
												<a href="javascript:void(0);"><i class="fa fa-file fa-lg fa-fw txt-color-greenLight"></i> <u>P</u>DF</a>
											</li>
											<li>
												<a href="javascript:void(0);"><i class="fa fa-times fa-lg fa-fw txt-color-red"></i> <u>D</u>elete</a>
											</li>
											<li class="divider"></li>
											<li class="text-align-center">
												<a href="javascript:void(0);">Cancel</a>
											</li>
										</ul>
									</div></td>
								</tr>
								<tr>
									<td><a href="javascript:void(0);">Brazil</a></td>
									<td>2,476</td>
									<td class="text-align-center">
									<div class="sparkline txt-color-blue text-align-center" data-sparkline-height="22px" data-sparkline-width="90px" data-sparkline-barwidth="2">
										2700, 1877, 2500, 2577, 2000, 3631, 2471, -2700, -3631, 2471, 1300, 2100, 3000,
									</div></td>
									<td class="text-align-center">741</td>
									<td class="text-align-center">
									<div class="sparkline display-inline" data-sparkline-type='pie' data-sparkline-piecolor='["#E979BB", "#57889C"]' data-sparkline-offset="90" data-sparkline-piesize="23px">
										34,66
									</div>
									<div class="btn-group display-inline pull-right text-align-left hidden-tablet">
										<button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
											<i class="fa fa-cog fa-lg"></i>
										</button>
										<ul class="dropdown-menu dropdown-menu-xs pull-right">
											<li>
												<a href="javascript:void(0);"><i class="fa fa-file fa-lg fa-fw txt-color-greenLight"></i> <u>P</u>DF</a>
											</li>
											<li>
												<a href="javascript:void(0);"><i class="fa fa-times fa-lg fa-fw txt-color-red"></i> <u>D</u>elete</a>
											</li>
											<li class="divider"></li>
											<li class="text-align-center">
												<a href="javascript:void(0);">Cancel</a>
											</li>
										</ul>
									</div></td>
								</tr>
								<tr>
									<td><a href="javascript:void(0);">Turkey</a></td>
									<td>1,476</td>
									<td class="text-align-center">
									<div class="sparkline txt-color-blue text-align-center" data-sparkline-height="22px" data-sparkline-width="90px" data-sparkline-barwidth="2">
										1300, 1877, 2500, 2577, 2000, 2100, 3000, -2471, -2700, -3631, -2471, 2700, 3631
									</div></td>
									<td class="text-align-center">123</td>
									<td class="text-align-center">
									<div class="sparkline display-inline" data-sparkline-type='pie' data-sparkline-piecolor='["#E979BB", "#57889C"]' data-sparkline-offset="90" data-sparkline-piesize="23px">
										75,25
									</div>
									<div class="btn-group display-inline pull-right text-align-left hidden-tablet">
										<button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
											<i class="fa fa-cog fa-lg"></i>
										</button>
										<ul class="dropdown-menu dropdown-menu-xs pull-right">
											<li>
												<a href="javascript:void(0);"><i class="fa fa-file fa-lg fa-fw txt-color-greenLight"></i> <u>P</u>DF</a>
											</li>
											<li>
												<a href="javascript:void(0);"><i class="fa fa-times fa-lg fa-fw txt-color-red"></i> <u>D</u>elete</a>
											</li>
											<li class="divider"></li>
											<li class="text-align-center">
												<a href="javascript:void(0);">Cancel</a>
											</li>
										</ul>
									</div></td>
								</tr>
								<tr>
									<td><a href="javascript:void(0);">Canada</a></td>
									<td>146</td>
									<td class="text-align-center">
									<div class="sparkline txt-color-orange text-align-center" data-sparkline-height="22px" data-sparkline-width="90px" data-sparkline-barwidth="2">
										5, 34, 10, 1, 4, 6, -9, -1, 0, 0, 5, 6, 7
									</div></td>
									<td class="text-align-center">23</td>
									<td class="text-align-center">
									<div class="sparkline display-inline" data-sparkline-type='pie' data-sparkline-piecolor='["#E979BB", "#57889C"]' data-sparkline-offset="90" data-sparkline-piesize="23px">
										50,50
									</div>
									<div class="btn-group display-inline pull-right text-align-left hidden-tablet">
										<button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
											<i class="fa fa-cog fa-lg"></i>
										</button>
										<ul class="dropdown-menu dropdown-menu-xs pull-right">
											<li>
												<a href="javascript:void(0);"><i class="fa fa-file fa-lg fa-fw txt-color-greenLight"></i> <u>P</u>DF</a>
											</li>
											<li>
												<a href="javascript:void(0);"><i class="fa fa-times fa-lg fa-fw txt-color-red"></i> <u>D</u>elete</a>
											</li>
											<li class="divider"></li>
											<li class="text-align-center">
												<a href="javascript:void(0);">Cancel</a>
											</li>
										</ul>
									</div></td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<td colspan=5>
									<ul class="pagination pagination-xs no-margin">
										<li class="prev disabled">
											<a href="javascript:void(0);">Previous</a>
										</li>
										<li class="active">
											<a href="javascript:void(0);">1</a>
										</li>
										<li>
											<a href="javascript:void(0);">2</a>
										</li>
										<li>
											<a href="javascript:void(0);">3</a>
										</li>
										<li class="next">
											<a href="javascript:void(0);">Next</a>
										</li>
									</ul></td>
								</tr>
							</tfoot>
						</table>

						<!-- end content -->

					</div>

				</div>
				<!-- end widget div -->
			</div>
			<!-- end widget -->

			<!-- new widget -->
			<div class="jarviswidget jarviswidget-color-blue" id="wid-id-4" data-widget-editbutton="false" data-widget-colorbutton="false">

				<!-- widget options:
				usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

				data-widget-colorbutton="false"
				data-widget-editbutton="false"
				data-widget-togglebutton="false"
				data-widget-deletebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-custombutton="false"
				data-widget-collapsed="true"
				data-widget-sortable="false"

				-->

				<header>
					<span class="widget-icon"> <i class="fa fa-check txt-color-white"></i> </span>
					<h2> ToDo's </h2>
					<!-- <div class="widget-toolbar">
					add: non-hidden - to disable auto hide

					</div>-->
				</header>

				<!-- widget div-->
				<div>
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<div>
							<label>Title:</label>
							<input type="text" />
						</div>
					</div>
					<!-- end widget edit box -->

					<div class="widget-body no-padding smart-form">
						<!-- content goes here -->
						<h5 class="todo-group-title"><i class="fa fa-warning"></i> Critical Tasks (<small class="num-of-tasks">1</small>)</h5>
						<ul id="sortable1" class="todo">
							<li>
								<span class="handle"> <label class="checkbox">
										<input type="checkbox" name="checkbox-inline">
										<i></i> </label> </span>
								<p>
									<strong>Ticket #17643</strong> - Hotfix for WebApp interface issue [<a href="javascript:void(0);" class="font-xs">More Details</a>] <span class="text-muted">Sea deep blessed bearing under darkness from God air living isn't. </span>
									<span class="date">Jan 1, 2014</span>
								</p>
							</li>
						</ul>
						<h5 class="todo-group-title"><i class="fa fa-exclamation"></i> Important Tasks (<small class="num-of-tasks">3</small>)</h5>
						<ul id="sortable2" class="todo">
							<li>
								<span class="handle"> <label class="checkbox">
										<input type="checkbox" name="checkbox-inline">
										<i></i> </label> </span>
								<p>
									<strong>Ticket #1347</strong> - Inbox email is being sent twice <small>(bug fix)</small> [<a href="javascript:void(0);" class="font-xs">More Details</a>] <span class="date">Nov 22, 2013</span>
								</p>
							</li>
							<li>
								<span class="handle"> <label class="checkbox">
										<input type="checkbox" name="checkbox-inline">
										<i></i> </label> </span>
								<p>
									<strong>Ticket #1314</strong> - Call customer support re: Issue <a href="javascript:void(0);" class="font-xs">#6134</a><small> (code review)</small>
									<span class="date">Nov 22, 2013</span>
								</p>
							</li>
							<li>
								<span class="handle"> <label class="checkbox">
										<input type="checkbox" name="checkbox-inline">
										<i></i> </label> </span>
								<p>
									<strong>Ticket #17643</strong> - Hotfix for WebApp interface issue [<a href="javascript:void(0);" class="font-xs">More Details</a>] <span class="text-muted">Sea deep blessed bearing under darkness from God air living isn't. </span>
									<span class="date">Jan 1, 2014</span>
								</p>
							</li>
						</ul>

						<h5 class="todo-group-title"><i class="fa fa-check"></i> Completed Tasks (<small class="num-of-tasks">1</small>)</h5>
						<ul id="sortable3" class="todo">
							<li class="complete">
								<span class="handle"> <label class="checkbox state-disabled" style="display:none">
										<input type="checkbox" name="checkbox-inline" checked="checked" disabled="disabled">
										<i></i> </label> </span>
								<p>
									<strong>Ticket #17643</strong> - Hotfix for WebApp interface issue [<a href="javascript:void(0);" class="font-xs">More Details</a>] <span class="text-muted">Sea deep blessed bearing under darkness from God air living isn't. </span>
									<span class="date">Jan 1, 2014</span>
								</p>
							</li>
						</ul>

						<!-- end content -->
					</div>

				</div>
				<!-- end widget div -->
			</div>
			<!-- end widget -->

		</article>

	</div>

	<!-- end row -->

</section>
<!-- end widget grid -->

<script type="text/javascript">
	// DO NOT REMOVE : GLOBAL FUNCTIONS!
	pageSetUp();
	
	/*
	 * PAGE RELATED SCRIPTS
	 */
	
	$(".js-status-update a").click(function () {
	    var selText = $(this).text();
	    var $this = $(this);
	    $this.parents('.btn-group').find('.dropdown-toggle').html(selText + ' <span class="caret"></span>');
	    $this.parents('.dropdown-menu').find('li').removeClass('active');
	    $this.parent().addClass('active');
	});
	
	/*
	 * TODO: add a way to add more todo's to list
	 */
	
	// initialize sortable
	$(function () {
	    $("#sortable1, #sortable2").sortable({
	        handle: '.handle',
	        connectWith: ".todo",
	        update: countTasks
	    }).disableSelection();
	});
	
	// check and uncheck
	$('.todo .checkbox > input[type="checkbox"]').click(function () {
	    var $this = $(this).parent().parent().parent();
	
	    if ($(this).prop('checked')) {
	        $this.addClass("complete");
	
	        // remove this if you want to undo a check list once checked
	        //$(this).attr("disabled", true);
	        $(this).parent().hide();
	
	        // once clicked - add class, copy to memory then remove and add to sortable3
	        $this.slideUp(500, function () {
	            $this.clone().prependTo("#sortable3").effect("highlight", {}, 800);
	            $this.remove();
	            countTasks();
	        });
	    } else {
	        // insert undo code here...
	    }
	
	})
	// count tasks
	function countTasks() {
	
	    $('.todo-group-title').each(function () {
	        var $this = $(this);
	        $this.find(".num-of-tasks").text($this.next().find("li").size());
	    });
	
	}
	
	/*
	 * RUN PAGE GRAPHS
	 */
	
	// Load FLOAT dependencies (related to page)
	loadScript("<?php echo ASSETS_URL; ?>/js/plugin/flot/jquery.flot.cust.js", loadFlotResize);
	
	function loadFlotResize() {
	    loadScript("<?php echo ASSETS_URL; ?>/js/plugin/flot/jquery.flot.resize.js", loadFlotToolTip);
	}
	
	function loadFlotToolTip() {
	    loadScript("<?php echo ASSETS_URL; ?>/js/plugin/flot/jquery.flot.tooltip.js", generatePageGraphs);
	}
	
	function generatePageGraphs() {
	
	    /* TAB 1: UPDATING CHART */
	    // For the demo we use generated data, but normally it would be coming from the server
	
	    var data = [],
	        totalPoints = 200,
	        $UpdatingChartColors = $("#updating-chart").css('color');
	
	    function getRandomData() {
	        if (data.length > 0)
	            data = data.slice(1);
	
	        // do a random walk
	        while (data.length < totalPoints) {
	            var prev = data.length > 0 ? data[data.length - 1] : 50;
	            var y = prev + Math.random() * 10 - 5;
	            if (y < 0)
	                y = 0;
	            if (y > 100)
	                y = 100;
	            data.push(y);
	        }
	
	        // zip the generated y values with the x values
	        var res = [];
	        for (var i = 0; i < data.length; ++i)
	            res.push([i, data[i]])
	        return res;
	    }
	
	    // setup control widget
	    var updateInterval = 1500;
	    $("#updating-chart").val(updateInterval).change(function () {
	
	        var v = $(this).val();
	        if (v && !isNaN(+v)) {
	            updateInterval = +v;
	            $(this).val("" + updateInterval);
	        }
	
	    });
	
	    // setup plot
	    var options = {
	        yaxis: {
	            min: 0,
	            max: 100
	        },
	        xaxis: {
	            min: 0,
	            max: 100
	        },
	        colors: [$UpdatingChartColors],
	        series: {
	            lines: {
	                lineWidth: 1,
	                fill: true,
	                fillColor: {
	                    colors: [{
	                        opacity: 0.4
	                    }, {
	                        opacity: 0
	                    }]
	                },
	                steps: false
	
	            }
	        }
	    };
	
	    var plot = $.plot($("#updating-chart"), [getRandomData()], options);
	
	    /* live switch */
	    $('input[type="checkbox"]#start_interval').click(function () {
	        if ($(this).prop('checked')) {
	            $on = true;
	            updateInterval = 1500;
	            update();
	        } else {
	            clearInterval(updateInterval);
	            $on = false;
	        }
	    });
	
	    function update() {
	        if ($on == true) {
	            plot.setData([getRandomData()]);
	            plot.draw();
	            setTimeout(update, updateInterval);
	
	        } else {
	            clearInterval(updateInterval)
	        }
	
	    }
	
	    var $on = false;
	
	    /*end updating chart*/
	
	    /* TAB 2: Social Network  */
	
	    $(function () {
	        // jQuery Flot Chart
	        var twitter = [
	            [1, 27],
	            [2, 34],
	            [3, 51],
	            [4, 48],
	            [5, 55],
	            [6, 65],
	            [7, 61],
	            [8, 70],
	            [9, 65],
	            [10, 75],
	            [11, 57],
	            [12, 59],
	            [13, 62]
	        ],
	            facebook = [
	                [1, 25],
	                [2, 31],
	                [3, 45],
	                [4, 37],
	                [5, 38],
	                [6, 40],
	                [7, 47],
	                [8, 55],
	                [9, 43],
	                [10, 50],
	                [11, 47],
	                [12, 39],
	                [13, 47]
	            ],
	            data = [{
	                label: "Twitter",
	                data: twitter,
	                lines: {
	                    show: true,
	                    lineWidth: 1,
	                    fill: true,
	                    fillColor: {
	                        colors: [{
	                            opacity: 0.1
	                        }, {
	                            opacity: 0.13
	                        }]
	                    }
	                },
	                points: {
	                    show: true
	                }
	            }, {
	                label: "Facebook",
	                data: facebook,
	                lines: {
	                    show: true,
	                    lineWidth: 1,
	                    fill: true,
	                    fillColor: {
	                        colors: [{
	                            opacity: 0.1
	                        }, {
	                            opacity: 0.13
	                        }]
	                    }
	                },
	                points: {
	                    show: true
	                }
	            }];
	
	        var options = {
	            grid: {
	                hoverable: true
	            },
	            colors: ["#568A89", "#3276B1"],
	            tooltip: true,
	            tooltipOpts: {
	                //content : "Value <b>$x</b> Value <span>$y</span>",
	                defaultTheme: false
	            },
	            xaxis: {
	                ticks: [
	                    [1, "JAN"],
	                    [2, "FEB"],
	                    [3, "MAR"],
	                    [4, "APR"],
	                    [5, "MAY"],
	                    [6, "JUN"],
	                    [7, "JUL"],
	                    [8, "AUG"],
	                    [9, "SEP"],
	                    [10, "OCT"],
	                    [11, "NOV"],
	                    [12, "DEC"],
	                    [13, "JAN+1"]
	                ]
	            },
	            yaxes: {
	
	            }
	        };
	
	        var plot3 = $.plot($("#statsChart"), data, options);
	    });
	
	    // END TAB 2
	
	    // TAB THREE GRAPH //
	    /* TAB 3: Revenew  */
	
	    $(function () {
	
	        var trgt = [
	            [1354586000000, 153],
	            [1364587000000, 658],
	            [1374588000000, 198],
	            [1384589000000, 663],
	            [1394590000000, 801],
	            [1404591000000, 1080],
	            [1414592000000, 353],
	            [1424593000000, 749],
	            [1434594000000, 523],
	            [1444595000000, 258],
	            [1454596000000, 688],
	            [1464597000000, 364]
	        ],
	            prft = [
	                [1354586000000, 53],
	                [1364587000000, 65],
	                [1374588000000, 98],
	                [1384589000000, 83],
	                [1394590000000, 980],
	                [1404591000000, 808],
	                [1414592000000, 720],
	                [1424593000000, 674],
	                [1434594000000, 23],
	                [1444595000000, 79],
	                [1454596000000, 88],
	                [1464597000000, 36]
	            ],
	            sgnups = [
	                [1354586000000, 647],
	                [1364587000000, 435],
	                [1374588000000, 784],
	                [1384589000000, 346],
	                [1394590000000, 487],
	                [1404591000000, 463],
	                [1414592000000, 479],
	                [1424593000000, 236],
	                [1434594000000, 843],
	                [1444595000000, 657],
	                [1454596000000, 241],
	                [1464597000000, 341]
	            ],
	            toggles = $("#rev-toggles"),
	            target = $("#flotcontainer");
	
	        var data = [{
	            label: "Target Profit",
	            data: trgt,
	            bars: {
	                show: true,
	                align: "center",
	                barWidth: 30 * 30 * 60 * 1000 * 80
	            }
	        }, {
	            label: "Actual Profit",
	            data: prft,
	            color: '#3276B1',
	            lines: {
	                show: true,
	                lineWidth: 3
	            },
	            points: {
	                show: true
	            }
	        }, {
	            label: "Actual Signups",
	            data: sgnups,
	            color: '#71843F',
	            lines: {
	                show: true,
	                lineWidth: 1
	            },
	            points: {
	                show: true
	            }
	        }]
	
	        var options = {
	            grid: {
	                hoverable: true
	            },
	            tooltip: true,
	            tooltipOpts: {
	                //content: '%x - %y',
	                //dateFormat: '%b %y',
	                defaultTheme: false
	            },
	            xaxis: {
	                mode: "time"
	            },
	            yaxes: {
	                tickFormatter: function (val, axis) {
	                    return "$" + val;
	                },
	                max: 1200
	            }
	
	        };
	
	        plot2 = null;
	
	        function plotNow() {
	            var d = [];
	            toggles.find(':checkbox').each(function () {
	                if ($(this).is(':checked')) {
	                    d.push(data[$(this).attr("name").substr(4, 1)]);
	                }
	            });
	            if (d.length > 0) {
	                if (plot2) {
	                    plot2.setData(d);
	                    plot2.draw();
	                } else {
	                    plot2 = $.plot(target, d, options);
	                }
	            }
	
	        };
	
	        toggles.find(':checkbox').on('change', function () {
	            plotNow();
	        });
	        plotNow()
	
	    });
	
	}
	
	/*
	 * VECTOR MAP
	 */
	
	data_array = {
	    "US": 4977,
	    "AU": 4873,
	    "IN": 3671,
	    "BR": 2476,
	    "TR": 1476,
	    "CN": 146,
	    "CA": 134,
	    "BD": 100
	};
	
	// Load Map dependency 1 then call for dependency 2
	loadScript("<?php echo ASSETS_URL; ?>/js/plugin/vectormap/jquery-jvectormap-1.2.2.min.js", loadMapFile);
	
	// Load Map dependency 2 then rendeder Map
	function loadMapFile() {
	    loadScript("<?php echo ASSETS_URL; ?>/js/plugin/vectormap/jquery-jvectormap-world-mill-en.js", renderVectorMap);
	}
	
	function renderVectorMap() {
	    $('#vector-map').vectorMap({
	        map: 'world_mill_en',
	        backgroundColor: '#fff',
	        regionStyle: {
	            initial: {
	                fill: '#c4c4c4'
	            },
	            hover: {
	                "fill-opacity": 1
	            }
	        },
	        series: {
	            regions: [{
	                values: data_array,
	                scale: ['#85a8b6', '#4d7686'],
	                normalizeFunction: 'polynomial'
	            }]
	        },
	        onRegionLabelShow: function (e, el, code) {
	            if (typeof data_array[code] == 'undefined') {
	                e.preventDefault();
	            } else {
	                var countrylbl = data_array[code];
	                el.html(el.html() + ': ' + countrylbl + ' visits');
	            }
	        }
	    });
	}
	
	/*
	 * FULL CALENDAR JS
	 */
	
	// Load Calendar dependency then setup calendar
	loadScript("<?php echo ASSETS_URL; ?>/js/plugin/fullcalendar/jquery.fullcalendar.min.js", setupCalendar);
	
	function setupCalendar() {
	
	    if ($("#calendar").length) {
	        var date = new Date();
	        var d = date.getDate();
	        var m = date.getMonth();
	        var y = date.getFullYear();
	
	        var calendar = $('#calendar').fullCalendar({
	
	            editable: true,
	            draggable: true,
	            selectable: false,
	            selectHelper: true,
	            unselectAuto: false,
	            disableResizing: false,
	
	            header: {
	                left: 'title', //,today
	                center: 'prev, next, today',
	                right: 'month, agendaWeek, agenDay' //month, agendaDay,
	            },
	
	            select: function (start, end, allDay) {
	                var title = prompt('Event Title:');
	                if (title) {
	                    calendar.fullCalendar('renderEvent', {
	                            title: title,
	                            start: start,
	                            end: end,
	                            allDay: allDay
	                        }, true // make the event "stick"
	                    );
	                }
	                calendar.fullCalendar('unselect');
	            },
	
	            events: [{
	                title: 'All Day Event',
	                start: new Date(y, m, 1),
	                description: 'long description',
	                className: ["event", "bg-color-greenLight"],
	                icon: 'fa-check'
	            }, {
	                title: 'Long Event',
	                start: new Date(y, m, d - 5),
	                end: new Date(y, m, d - 2),
	                className: ["event", "bg-color-red"],
	                icon: 'fa-lock'
	            }, {
	                id: 999,
	                title: 'Repeating Event',
	                start: new Date(y, m, d - 3, 16, 0),
	                allDay: false,
	                className: ["event", "bg-color-blue"],
	                icon: 'fa-clock-o'
	            }, {
	                id: 999,
	                title: 'Repeating Event',
	                start: new Date(y, m, d + 4, 16, 0),
	                allDay: false,
	                className: ["event", "bg-color-blue"],
	                icon: 'fa-clock-o'
	            }, {
	                title: 'Meeting',
	                start: new Date(y, m, d, 10, 30),
	                allDay: false,
	                className: ["event", "bg-color-darken"]
	            }, {
	                title: 'Lunch',
	                start: new Date(y, m, d, 12, 0),
	                end: new Date(y, m, d, 14, 0),
	                allDay: false,
	                className: ["event", "bg-color-darken"]
	            }, {
	                title: 'Birthday Party',
	                start: new Date(y, m, d + 1, 19, 0),
	                end: new Date(y, m, d + 1, 22, 30),
	                allDay: false,
	                className: ["event", "bg-color-darken"]
	            }, {
	                title: 'Smartadmin Open Day',
	                start: new Date(y, m, 28),
	                end: new Date(y, m, 29),
	                className: ["event", "bg-color-darken"]
	            }],
	
	            eventRender: function (event, element, icon) {
	                if (!event.description == "") {
	                    element.find('.fc-event-title').append("<br/><span class='ultra-light'>" + event.description +
	                        "</span>");
	                }
	                if (!event.icon == "") {
	                    element.find('.fc-event-title').append("<i class='air air-top-right fa " + event.icon +
	                        " '></i>");
	                }
	            }
	        });
	
	    };
	
	    /* hide default buttons */
	    $('.fc-header-right, .fc-header-center').hide();
	
	}
	
	// calendar prev
	$('#calendar-buttons #btn-prev').click(function () {
	    $('.fc-button-prev').click();
	    return false;
	});
	
	// calendar next
	$('#calendar-buttons #btn-next').click(function () {
	    $('.fc-button-next').click();
	    return false;
	});
	
	// calendar today
	$('#calendar-buttons #btn-today').click(function () {
	    $('.fc-button-today').click();
	    return false;
	});
	
	// calendar month
	$('#mt').click(function () {
	    $('#calendar').fullCalendar('changeView', 'month');
	});
	
	// calendar agenda week
	$('#ag').click(function () {
	    $('#calendar').fullCalendar('changeView', 'agendaWeek');
	});
	
	// calendar agenda day
	$('#td').click(function () {
	    $('#calendar').fullCalendar('changeView', 'agendaDay');
	});
	
</script>
