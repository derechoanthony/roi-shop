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
			if( date("y", strtotime($getRois[$i]['dt'])) == date("y", strtotime("-" .(12-($yr+1)). " months")) && 
				date("m", strtotime($getRois[$i]['dt'])) == date("m", strtotime("-" .(12-($yr+1)). " months")) )
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
		<h1 class="page-title txt-color-blueDark">
				Dashboard 
			<span>> 
				Manage Users
			</span>
		</h1>
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

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
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
					<span class="widget-icon"> <i class="fa fa-user"></i> </span>
					<h2>Your Current Users </h2>

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
						<div class="widget-body-toolbar">

						</div>
						<table id="datatable_col_reorder" class="table table-striped table-hover">
							<thead>
								<tr>
									<th>ID</th>
									<th>Name</th>
									<th>Phone</th>
									<th>Company</th>
									<th>Total ROIs</th>
									<th>Manager</th>
									<th>Currency</th>
									<th><center>Actions</center></th>
								</tr>
							</thead>
							<tbody>
<?php
	
	for( $i=0; $i<count($getUsers); $i++ )
	{
		$userPhone = preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', '($1) $2-$3', $getUsers[$i]['phone']);
		$manager = '';
		$managerid = 0;
		foreach( $getUsers as $user )
		{
			if( $user['user_id'] == $getUsers[$i]['manager'] )
			{
				$manager = $user['username'];
				$managerid = $user['user_id'];
			}
		}
		
		$totalRois = 0;
		foreach( $getRois as $roi )
		{
			if( $roi['user_id'] == $getUsers[$i]['user_id'] )
			{
				$totalRois += 1;
			}
		}
	
?>
								<tr>
									<td><?= $i+1; ?></td>
									<td id="user"><?= $getUsers[$i]['username']; ?></td>
									<td><?= $userPhone; ?></td>
									<td><?= $getComp['compName']; ?></td>
									<td><?= $totalRois; ?></td>
									<td><a href="form-x-editable.html#" class="manager" data-type="select" data-pk="1" data-value="<?= $managerid; ?>" data-source="/manager" data-original-title="Select manager"><?= $manager; ?></a></td>							
									<td>						
										<ul class="header-dropdown-list hidden-xs" style="float: none; padding-left: 0px; top: -18px;">
											<li>
												<a id="currency-indicator" href="#" class="currency-indicator dropdown-toggle" data-toggle="dropdown"> <img alt="" src="<?php echo ASSETS_URL; ?>/img/flags/<?= $getUsers[$i]['currency']=="gbp"?'uk.png':'us.png' ?>"> <span> <?= $getUsers[$i]['currency']=="gbp"?'UK':'US' ?> </span> <i class="fa fa-angle-down"></i> </a>
												<ul class="dropdown-menu">
													<li>
														<a class="currency-type" href="javascript:void(0)"><img alt="" src="<?php echo ASSETS_URL; ?>/img/flags/us.png"> US</a>
													</li>
													<li>
														<a class="currency-type" href="javascript:void(0)"><img alt="" src="<?php echo ASSETS_URL; ?>/img/flags/uk.png"> UK</a>
													</li>
												</ul>
											</li>
										</ul>
									</td>
									<td>
										<center>
											<ul class="demo-btns">
												<li class="resetUser">
													<a data-toggle="modal" href="#myModal" class="btn btn-labeled btn-success"> <span class="btn-label"><i class="fa fa-refresh"></i></span>Reset </a>
												</li>
												<li class="transferRois">
													<a data-toggle="modal" href="#transferModal" class="btn btn-labeled btn-primary"> <span class="btn-label"><i class="fa fa-exchange"></i></span>Transfer </a>
												</li>
												<li>
													<a class="btn btn-labeled btn-danger deleteUser"> <span class="btn-label"><i class="glyphicon glyphicon-remove"></i></span>Delete </a>
												</li>
											</ul>
										</center>
									</td>
								</tr>
<?php
	}
?>
							</tbody>
						</table>

					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->

		</article>
		<!-- WIDGET END -->
		
		<!-- NEW WIDGET START -->
		<article class="col-sm-12 col-md-12 col-lg-12">

			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false" data-widget-deletebutton="false">
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
					<span class="widget-icon"> <i class="fa fa-check"></i> </span>
					<h2>Add a User </h2>

				</header>

				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body">

						<div class="row">
							<form id="wizard-1" novalidate="novalidate">
								<div id="bootstrap-wizard-1" class="col-sm-12">
									<div class="form-bootstrapWizard">
										<ul class="bootstrapWizard form-wizard" <?= $getComp['maxUsers'] && count($getUsers) - $getComp['maxUsers'] >= 0 ? 'disabled="disabled"' : '' ?>>
											<li class="active" data-target="#step1">
												<a href="#tab1" data-toggle="tab"> <span class="step">1</span> <span class="title">Account Setup</span> </a>
											</li>
											<li data-target="#step2">
												<a href="#tab2" data-toggle="tab"> <span class="step">2</span> <span class="title">Personal Information</span> </a>
											</li>
											<li data-target="#step3">
												<a href="#tab3" data-toggle="tab"> <span class="step">3</span> <span class="title">ROI Setup</span> </a>
											</li>
											<li data-target="#step4">
												<a href="#tab4" data-toggle="tab"> <span class="step">4</span> <span class="title">Complete Add User</span> </a>
											</li>
										</ul>
										<div class="clearfix"></div>
									</div>
									<div class="tab-content">
										<div class="tab-pane active" id="tab1">
											<br>
											<h3><strong>Step 1 </strong> - Account Setup</h3>
							
<?php
										if( !$getComp['maxUsers'] || $getComp['maxUsers']-count($getUsers) > 0 )
										{
?>
											<div class="alert alert-info">
												<i class="fa fa-exclamation"></i> You have <?= !$getComp['maxUsers'] ? 'unlimited' : $getComp['maxUsers']-count($getUsers).' more' ?> licenses available. 
											</div>	
<?php
										} else {
?>
											<div class="alert alert-danger">
												<i class="fa fa-cross"></i> You do not have any more licenses available. Delete an existing account or contact
													The ROI Shop in order to set up additional licenses. 
											</div>
<?php
										}
?>
											
											<div class="row">

												<div class="col-sm-12">
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-envelope fa-lg fa-fw"></i></span>
															<input class="form-control input-lg" placeholder="email@address.com" type="text" name="username" id="username" <?= $getComp['maxUsers'] && count($getUsers) - $getComp['maxUsers'] >= 0 ? 'disabled="disabled"' : '' ?> >

														</div>
													</div>

												</div>

											</div>

											<div class="row">
												<div class="col-sm-12">
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-lock fa-lg fa-fw"></i></span>
															<input class="form-control input-lg" placeholder="password" type="password" name="password" id="password" <?= $getComp['maxUsers'] && count($getUsers) - $getComp['maxUsers'] >= 0 ? 'disabled="disabled"' : '' ?> >
														</div>
													</div>

												</div>
												
											</div>

											<div class="alert alert-warning">
												<i class="fa fa-exclamation"></i> If a password is not entered for the user one will automatically be generated.
													Once the account is setup an email will be sent to the user with their password. 
											</div>	

										</div>
										<div class="tab-pane" id="tab2">
											<br>
											<h3><strong>Step 2</strong> - Personal Information</h3>

											<div class="row">
												<div class="col-sm-6">
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-user fa-lg fa-fw"></i></span>
															<input class="form-control input-lg" placeholder="First Name" type="text" name="fname" id="fname">

														</div>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-user fa-lg fa-fw"></i></span>
															<input class="form-control input-lg" placeholder="Last Name" type="text" name="lname" id="lname">

														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-phone fa-lg fa-fw"></i></span>
															<input class="form-control input-lg" data-mask="(999) 999-9999" data-mask-placeholder= "X" placeholder="(XXX) XXX-XXXX" type="text" name="wphone" id="wphone">
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-user fa-lg fa-fw"></i></span>
															<select class="form-control input-lg" name="manager">
																<option value="" selected="selected">Select Manager</option>
<?php		
											foreach( $getUsers as $user )
											{
?>
																<option value="<?= $user['user_id'] ?>"><?= $user['username'] ?></option>
<?php
											}
?> 
															</select>
														</div>
													</div>
												</div>	
											</div>
										</div>
										<div class="tab-pane" id="tab3">
											<br>
											<h3><strong>Step 3</strong> - ROI Setup</h3>
											<div class="alert alert-info">
												<i class="fa fa-exclamation"></i> Add any of your company ROIs that you'd like the rep to have access to. If you only 
													have one ROI it will be added automatically. Also, choose what currency your rep will be using, it will be defaulted
													to dollars if you do not choose one.
											</div>
											<div id="addUserRoiSelection" class="row userRoiSelection">
												<div class="col-sm-12">
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-suitcase fa-lg fa-fw"></i></span>
															<select class="form-control input-lg addUserRoi" name="addUserRoi[]" <?= $getChildComps ? '' : 'disabled="disabled"' ?>>
<?php
												if( $getChildComps )
												{
?>
																<option value="" selected="selected">Select an ROI to add to account</option>
<?php												
												}
												
												foreach( $getChildComps as $children )
												{
?>
																<option value="<?= $children['version_id'] ?>"><?= $children['version_name'] ?></option>
<?php
												}
?>
															</select>
														</div>													
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-money fa-lg fa-fw"></i></span>
															<select class="form-control input-lg defineCurrency" name="defineCurrency">
																<option value="usd" selected="selected">United States (dollars)</option>
																<option value="gbp">Great Britain (pounds)</option>
																<option value="eur">Euros</option>
															</select>
														</div>													
													</div>
												</div>
											</div>
										</div>
										<div class="tab-pane" id="tab4">
											<br>
											<h3><strong>Step 4</strong> - Save Form</h3>
											<br>
											<h1 class="text-center text-success"><strong><i class="fa fa-check fa-lg"></i> Complete</strong></h1>
											<h4 class="text-center">Click next to finish</h4>
											<br>
											<br>
										</div>

										<div class="form-actions">
											<div class="row">
												<div class="col-sm-12">
													<ul class="pager wizard no-margin">
														<li class="previous disabled">
															<a href="javascript:void(0);" class="btn btn-lg btn-default" <?= $getComp['maxUsers'] && count($getUsers) - $getComp['maxUsers'] >= 0 ? 'disabled="disabled"' : '' ?>> Previous </a>
														</li>
														<li class="next">
															<a href="javascript:void(0);" class="btn btn-lg txt-color-darken" <?= $getComp['maxUsers'] && count($getUsers) - $getComp['maxUsers'] >= 0 ? 'disabled="disabled"' : '' ?>> Next </a>
														</li>
														<li class="next finish" style="display:none;">
															<a href="javascript:void(0);" class="btn btn-lg txt-color-darken"> Finish </a>
														</li>
													</ul>
												</div>
											</div>
										</div>

									</div>
								</div>
							</form>
						</div>

					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->

		</article>
		<!-- WIDGET END -->

	</div>

	<!-- end row -->

	<!-- end row -->

</section>
<!-- end widget grid -->

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title">
					<img src="<?php echo ASSETS_URL; ?>/img/logo.png" width="150" alt="SmartAdmin">
				</h4>
			</div>
			<div class="modal-body no-padding">

				<form action="#" id="login-form" class="smart-form">

							<fieldset>
								<section>
									<div class="row">
										<label class="label col col-2">Username</label>
										<div class="col col-10">
											<label class="input"> <i class="icon-append fa fa-user"></i>
												<input type="email" name="email">
												<input type="hidden" class="userid" name="userid" id="userid">
											</label>
										</div>
									</div>
								</section>

								<section>
									<div class="row">
										<label class="label col col-2">Password</label>
										<div class="col col-10">
											<label class="input"> <i class="icon-append fa fa-lock"></i>
												<input type="password" name="password">
											</label>
											<div class="note">
												<a href="javascript:void(0)">If password is omitted a random password will be generated.</a>
											</div>
										</div>
									</div>
								</section>
							</fieldset>

							<header>Optional Information</header>
							
							<fieldset>	
								<section>
									<div class="row">
										<label class="label col col-2">Full Name</label>
										<div class="col col-10">
											<label class="input"> <i class="icon-append fa fa-user"></i>
												<input type="text" name="fullname">
											</label>
										</div>
									</div>
								</section>
								
								<section>
									<div class="row">
										<label class="label col col-2">Phone Number</label>
										<div class="col col-10">
											<label class="input"> <i class="icon-append fa fa-phone"></i>
												<input type="text" name="phone">
											</label>
										</div>
									</div>
								</section>
							</fieldset>
							
							<footer>
								<button id="changeUser" type="submit" class="btn btn-primary">
									Change Account Info
								</button>
								<button id="dismissModal" type="button" class="btn btn-default" data-dismiss="modal">
									Cancel
								</button>

							</footer>
						</form>						
						

			</div>

		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal -->
<div class="modal fade" id="transferModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title">
					<img src="<?php echo ASSETS_URL; ?>/img/logo.png" width="150" alt="SmartAdmin">
				</h4>
			</div>
			<div class="modal-body no-padding">

				<form action="#" id="transfer-form" class="smart-form">

							<fieldset>
								<section>
									<div class="row">
										<label class="label col col-4">Available Users</label>
										<div class="col col-8">
											<div class="input-group">
												<input type="hidden" class="userid" name="userid" id="userid">
												<span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
												<select class="form-control" name="manager">
													<option value="" selected="selected">Select User</option>
<?php		
											foreach( $getUsers as $user )
											{
?>
													<option value="<?= $user['user_id'] ?>"><?= $user['username'] ?></option>
<?php
											}
?> 
												</select>
											</div>
										</div>
									</div>
								</section>
								
							</fieldset>
							
							<footer>
								<button id="transferRoi" type="submit" class="btn btn-primary">
									Transfer User ROIs
								</button>
								<button id="dismissTransferModal" type="button" class="btn btn-default" data-dismiss="modal">
									Cancel
								</button>

							</footer>
						</form>						
						

			</div>

		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">

	// DO NOT REMOVE : GLOBAL FUNCTIONS!
	pageSetUp();
	setupUserRoiSelection();
	
	// PAGE RELATED SCRIPTS

		
	loadScript("<?php echo ASSETS_URL; ?>/js/plugin/x-editable/moment.min.js", loadMockJax);

	function loadMockJax() {
		loadScript("<?php echo ASSETS_URL; ?>/js/plugin/x-editable/jquery.mockjax.min.js", loadXeditable);
	}

	function loadXeditable() {
		loadScript("<?php echo ASSETS_URL; ?>/js/plugin/x-editable/x-editable.min.js", loadTypeHead);
	}
	
	function loadTypeHead() {
		loadScript("<?php echo ASSETS_URL; ?>/js/plugin/typeahead/typeahead.min.js", loadTypeaheadjs);
	}
	
	function loadTypeaheadjs() {
		loadScript("<?php echo ASSETS_URL; ?>/js/plugin/typeahead/typeaheadjs.min.js", runXEditDemo);
	}	    
	
	function runXEditDemo() {
		(function (e) {
			"use strict";
			var t = function (e) {
				this.init("address", e, t.defaults)
			};
			e.fn.editableutils.inherit(t, e.fn.editabletypes.abstractinput);
			e.extend(t.prototype, {
				render: function () {
					this.$input = this.$tpl.find("input")
				},
				value2html: function (t, n) {
					if (!t) {
						e(n).empty();
						return
					}
					var r = e("<div>").text(t.city).html() + ", " + e("<div>").text(t.street).html() +
						" st., bld. " + e("<div>").text(t.building).html();
					e(n).html(r)
				},
				html2value: function (e) {
					return null
				},
				value2str: function (e) {
					var t = "";
					if (e)
						for (var n in e)
							t = t + n + ":" + e[n] + ";";
					return t
				},
				str2value: function (e) {
					return e
				},
				value2input: function (e) {
					if (!e)
						return;
					this.$input.filter('[name="city"]').val(e.city);
					this.$input.filter('[name="street"]').val(e.street);
					this.$input.filter('[name="building"]').val(e.building)
				},
				input2value: function () {
					return {
						city: this.$input.filter('[name="city"]').val(),
						street: this.$input.filter('[name="street"]').val(),
						building: this.$input.filter('[name="building"]').val()
					}
				},
				activate: function () {
					this.$input.filter('[name="city"]').focus()
				},
				autosubmit: function () {
					this.$input.keydown(function (t) {
						t.which === 13 && e(this).closest("form").submit()
					})
				}
			});
			t.defaults = e.extend({}, e.fn.editabletypes.abstractinput.defaults, {
				tpl: '<div class="editable-address"><label><span>City: </span><input type="text" name="city" class="input-small"></label></div><div class="editable-address"><label><span>Street: </span><input type="text" name="street" class="input-small"></label></div><div class="editable-address"><label><span>Building: </span><input type="text" name="building" class="input-mini"></label></div>',
				inputclass: ""
			});
			e.fn.editabletypes.address = t
		})(window.jQuery);

		//ajax mocks
		$.mockjaxSettings.responseTime = 500;

		$.mockjax({
			url: '/post',
			response: function (settings) {
				log(settings, this);				
			}
		});
		
	    $.mockjax({
	        url: '/manager',
	        response: function (settings) {
	            this.responseText = [
					{
						value: 0,
						text: ''
					}
<?php		
			foreach( $getUsers as $user )
			{
?>				
				,{
					value: <?= $user['user_id']; ?>,
					text: "<?= $user['username']; ?>"
	            }
<?php
			}
?>				
				];
	            log(settings, this);
	        }
	    });		
		
		//TODO: add this div to page
	    function log(settings, response) {
	        var s = [],
	            str;
	        s.push(settings.type.toUpperCase() + ' url = "' + settings.url + '"');
	        for (var a in settings.data) {
	            if (settings.data[a] && typeof settings.data[a] === 'object') {
	                str = [];
	                for (var j in settings.data[a]) {
	                    str.push(j + ': "' + settings.data[a][j] + '"');
	                }
	                str = '{ ' + str.join(', ') + ' }';
	            } else {
	                str = '"' + settings.data[a] + '"';
	            }
	            s.push(a + ' = ' + str);
	        }
	        s.push('RESPONSE: status = ' + response.status);

	        if (response.responseText) {
	            if ($.isArray(response.responseText)) {
	                s.push('[');
	                $.each(response.responseText, function (i, v) {
	                    s.push('{value: ' + v.value + ', text: "' + v.text + '"}');
	                });
	                s.push(']');
	            } else {
	                s.push($.trim(response.responseText));
	            }
	        }
	        s.push('--------------------------------------\n');
	        $('#console').val(s.join('\n') + $('#console').val());
	    }

	    /*
	     * X-EDITABLES
	     */

	    $('#inline').on('change', function (e) {
	        if ($(this).prop('checked')) {
	            window.location.href = '?mode=inline#ajax/plugins.html';
	        } else {
	            window.location.href = '?#ajax/plugins.html';
	        }
	    });

	    if (window.location.href.indexOf("?mode=inline") > -1) {
	        $('#inline').prop('checked', true);
	        $.fn.editable.defaults.mode = 'inline';
	    } else {
	        $('#inline').prop('checked', false);
	        $.fn.editable.defaults.mode = 'popup';
	    }

	    //defaults
	    $.fn.editable.defaults.url = '/post';
	    //$.fn.editable.defaults.mode = 'inline'; use this to edit inline

	    //enable / disable
	    $('#enable').click(function () {
	        $('#user .editable').editable('toggleDisabled');
	    });
		
		$('.resetUser').click(function() {
			$('.userid').val( $(this).closest( 'tr' ).find( '#user' ).html() );
		});
		
		$('.transferRois').click(function() {
			$('.userid').val( $(this).closest( 'tr' ).find( '#user' ).html() );
		});

	    $('.manager').editable({
	        showbuttons: false,
			success: function(response, newValue) {
				$.ajax({
					type	: 	"POST",
					url		:	"<?= ASSETS_URL; ?>/ajax/ajaxcalls.php",
					data	:	{ action: 'changeManager', user: $(this).closest( 'tr' ).find( '#user' ).html(), manager: newValue },
					success	:	function( values ) {
						//success callback
					}
				});
			}
	    });

	}	
	
	
	/* remove previous elems */
	
	if($('.DTTT_dropdown.dropdown-menu').length){
		$('.DTTT_dropdown.dropdown-menu').remove();
	}

	loadDataTableScripts();
	function loadDataTableScripts() {

		loadScript("<?php echo ASSETS_URL; ?>/js/plugin/datatables/jquery.dataTables-cust.min.js", dt_2);

		function dt_2() {
			loadScript("<?php echo ASSETS_URL; ?>/js/plugin/datatables/ColReorder.min.js", dt_3);
		}

		function dt_3() {
			loadScript("<?php echo ASSETS_URL; ?>/js/plugin/datatables/FixedColumns.min.js", dt_4);
		}

		function dt_4() {
			loadScript("<?php echo ASSETS_URL; ?>/js/plugin/datatables/ColVis.min.js", dt_5);
		}

		function dt_5() {
			loadScript("<?php echo ASSETS_URL; ?>/js/plugin/datatables/ZeroClipboard.js", dt_6);
		}

		function dt_6() {
			loadScript("<?php echo ASSETS_URL; ?>/js/plugin/datatables/media/js/TableTools.min.js", dt_7);
		}

		function dt_7() {
			loadScript("<?php echo ASSETS_URL; ?>/js/plugin/datatables/DT_bootstrap.js", runDataTables);
		}

	}

	function runDataTables() {

		/*
		 * BASIC
		 */
		$('#dt_basic').dataTable({
			"sPaginationType" : "bootstrap_full"
		});

		/* END BASIC */

		/* Add the events etc before DataTables hides a column */
		$("#datatable_fixed_column thead input").keyup(function() {
			oTable.fnFilter(this.value, oTable.oApi._fnVisibleToColumnIndex(oTable.fnSettings(), $("thead input").index(this)));
		});

		$("#datatable_fixed_column thead input").each(function(i) {
			this.initVal = this.value;
		});
		$("#datatable_fixed_column thead input").focus(function() {
			if (this.className == "search_init") {
				this.className = "";
				this.value = "";
			}
		});
		$("#datatable_fixed_column thead input").blur(function(i) {
			if (this.value == "") {
				this.className = "search_init";
				this.value = this.initVal;
			}
		});	
		

		var oTable = $('#datatable_fixed_column').dataTable({
			"sDom" : "<'dt-top-row'><'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
			//"sDom" : "t<'row dt-wrapper'<'col-sm-6'i><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'>>",
			"oLanguage" : {
				"sSearch" : "Search all columns:"
			},
			"bSortCellsTop" : true
		});

		/*
		 * COL ORDER
		 */
		var colTable = $('#datatable_col_reorder').dataTable({
			"sPaginationType" : "bootstrap",
			"sDom" : "R<'dt-top-row'Clf>r<'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
			"fnInitComplete" : function(oSettings, json) {
				$('.ColVis_Button').addClass('btn btn-default btn-sm').html('Columns <i class="icon-arrow-down"></i>');
			}
		});
		
		/* END COL ORDER */

		/* TABLE TOOLS */
		$('#datatable_tabletools').dataTable({
			"sDom" : "<'dt-top-row'Tlf>r<'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
			"oTableTools" : {
				"aButtons" : ["copy", "print", {
					"sExtends" : "collection",
					"sButtonText" : 'Save <span class="caret" />',
					"aButtons" : ["csv", "xls", "pdf"]
				}],
				"sSwfPath" : "<?php echo ASSETS_URL; ?>/js/plugin/datatables/media/swf/copy_csv_xls_pdf.swf"
			},
			"fnInitComplete" : function(oSettings, json) {
				$(this).closest('#dt_table_tools_wrapper').find('.DTTT.btn-group').addClass('table_tools_group').children('a.btn').each(function() {
					$(this).addClass('btn-sm btn-default');
				});
			}
		});
		
		/* END TABLE TOOLS */

	}
	
	$( '.currency-type' ).on( 'click', function(e)
	{
		$(this).closest('.header-dropdown-list').find('#currency-indicator').find('img').attr("src","<?= ASSETS_URL; ?>/img/flags/"+$(this).text().toLowerCase().trim()+".png");
		$(this).closest('.header-dropdown-list').find('#currency-indicator').find('span').html( $(this).text() );
		$.ajax({
			type	: 	"POST",
			url		:	"<?= ASSETS_URL; ?>/ajax/ajaxcalls.php",
			data	:	"action=changeCurrency&user="+$(this).closest('tr').find('td#user').text()+"&currency="+($(this).text().trim()=='UK'?'gbp':'usd'),
		});
	});
	
	$( '.deleteUser' ).on( 'click', function(e)
	{
		thistr = $(this).closest('tr');
		thisuser = thistr.find('#user').html();
		$.SmartMessageBox({
			title : "<i class='fa fa-exclamation'></i> Delete "+thisuser+"?",
			content : "Are you sure you'd like to delete "+thisuser+"? This action cannot be undone and all associated ROIs may be lost!",
			buttons : '[No][Yes]'
		}, function(ButtonPressed) {
			if (ButtonPressed === "Yes") {

				$.ajax({
					type	: 	"POST",
					url		:	"<?= ASSETS_URL; ?>/ajax/ajaxcalls.php",
					data	:	'action=deleteUser&user='+thisuser,
					success	:	function( values ) {
						$.smallBox({
							title : values+" removed",
							content : "<i class='fa fa-clock-o'></i> <i> The user "+thisuser+" was removed from your account</i>",
							color : "#659265",
							iconSmall : "fa fa-check fa-2x fadeInRight animated",
							timeout : 4000
						});
						thistr.hide("explode", 400, function(){$(this).remove()});
						$('#userCount').html($('#userCount').html()-1);
					}
				});
			}
			if (ButtonPressed === "No") {
				//callback for no
			}

		});
		e.preventDefault();
	});
	
	$( '#changeUser' ).on( 'click', function(e)
	{
		var newUser = $('#login-form').serializeArray();
		if(newUser[0]['value'])
		{
			$.ajax({
				type	: 	"POST",
				url		:	"<?= ASSETS_URL; ?>/ajax/ajaxcalls.php",
				data	:	{ action: 'changeUser', newuser: newUser[0]['value'], user: newUser[1]['value'], password: newUser[2]['value'], fullname: newUser[3]['value'], phone: newUser[4]['value'] },
				success	:	function( values ) {
					if( values == '		Exists' )
					{
						$.smallBox({
							title: "User already exists",
							content: "<i class='fa fa-user'></i> <i>You can edit the user specifics on this table. If the user does not appear within your company list, please contact The ROI Shop.</i>",
							color: "#895F5F",
							iconSmall: "fa fa-warning bounce animated",
							timeout: 10000
						});
					} else {
						$.smallBox({
							title: "User Successfully Changed!",
							content: "<i class='fa fa-user'></i> <i>"+newUser[1]['value']+" was changed to "+newUser[0]['value']+". They will receive an email with details on how to complete their registration. If they do not receive an email please contact The ROI Shop.</i>",
							color: "#5F895F",
							iconSmall: "fa fa-check bounce animated",
							timeout: 40000
						});
						$('#datatable_col_reorder td').filter(function() {
							return $(this).text() == newUser[1]['value'];
						}).text(newUser[0]['value']);
					}					
				}
			});
		}
		$( '#dismissModal' ).click();
		e.preventDefault();
	});
	
	$( '#transferRoi' ).on( 'click', function(e)
	{
		var newUser = $('#transfer-form').serializeArray();
		if(newUser[0]['value'])
		{
			$.ajax({
				type	: 	"POST",
				url		:	"<?= ASSETS_URL; ?>/ajax/ajaxcalls.php",
				data	:	{ action: 'transferRoi', user: newUser[0]['value'], newuser: newUser[1]['value'] },
				success	:	function( values ) {
					$.smallBox({
						title: "User Successfully Changed!",
						content: "<i class='fa fa-user'></i> <b>All of "+newUser[0]['value']+"'s ROIs were successfully transferred.</b>",
						color: "#5F895F",
						iconSmall: "fa fa-check bounce animated",
						timeout: 40000
					});					
				}
			});
		}
		$( '#dismissTransferModal' ).click();
		e.preventDefault();
	});
	
	function setupUserRoiSelection()
	{
		$( '.addUserRoi' ).off().on( 'change', function(e)
		{
			if( $(this).val() )
			{
				if( $(this).closest("#addUserRoiSelection").is(":nth-last-child(2)") )
				{
					var userRois = $("[name='addUserRoi[]']").map(function(){return $(this).val();}).get();			
					var roiSelectionClone = $("#addUserRoiSelection").clone();
					roiSelectionClone.insertAfter("div.userRoiSelection:last").find('.addUserRoi option:first').text( 'Add additional ROIs or leave unchanged if no more are needed' );
					setupUserRoiSelection();
				}
			} else {
				$(this).closest("#addUserRoiSelection").nextAll("div.userRoiSelection").remove();
			}
		});
	}	
	
	//Wizard Javascript
	
	/*
	 * Load bootstrap wizard dependency
	 */
	loadScript("<?php echo ASSETS_URL; ?>/js/plugin/bootstrap-wizard/jquery.bootstrap.wizard.min.js", runBootstrapWizard);
	
	//Bootstrap Wizard Validations
	
	function runBootstrapWizard() {
	  
		var $validator = $("#wizard-1").validate({
	    
			rules: {
				username: {
					required: true,
					email: "Your email address must be in the format of name@domain.com"
				}
			},
	    
			messages: {
				username: {
					required: "We need your email address in order to send the verification email",
					email: "Your email address must be in the format of name@domain.com"
				}
			},
			
			highlight: function (element) {
				$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
			},
			unhighlight: function (element) {
				$(element).closest('.form-group').removeClass('has-error').addClass('has-success');
			},
			errorElement: 'span',
			errorClass: 'help-block',
			errorPlacement: function (error, element) {
				if (element.parent('.input-group').length) {
					error.insertAfter(element.parent());
				} else {
					error.insertAfter(element);
				}
			}
		});
	  
		$('#bootstrap-wizard-1').bootstrapWizard({
			'tabClass': 'form-wizard',
			'onTabShow': function(tab, navigation, index) {
				var $total = navigation.find('li').length;
				var $current = index+1;

				if($current >= $total) {
					$('#bootstrap-wizard-1').find('.pager .next').hide();
					$('#bootstrap-wizard-1').find('.pager .finish').show();
					$('#bootstrap-wizard-1').find('.pager .finish').removeClass('disabled');
				} else {
					$('#bootstrap-wizard-1').find('.pager .next').show();
					$('#bootstrap-wizard-1').find('.pager .finish').hide();
				}
				if( $('input#username').is(':disabled') ) {
					$('.form-wizard li > a').css('cursor','not-allowed');
					$('.form-wizard li > a').on('click', function(e) {
						e.preventDefault();
						return false;
					});
				}
			},
			'onNext': function (tab, navigation, index) {
				var $valid = $("#wizard-1").valid();
				if (!$valid) {
					$validator.focusInvalid();
					return false;
				} else {
					$('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(index - 1).addClass('complete');
					$('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(index - 1).find('.step').html('<i class="fa fa-check"></i>');
				}
			}
		});
		
		$('#bootstrap-wizard-1 .finish').click(function() {
			var companyAdded = false;
			$('[name="addUserRoi[]"]').each(function() {
				if($(this).val()){ companyAdded = true; }
			});
			if(companyAdded) {
				if (!$('#bootstrap-wizard-1').find('#username').val()) {
					$('#bootstrap-wizard-1').find("a[href*='tab1']").trigger('click');
					return false;
				} else {
					var disabled = $("#wizard-1").find(':input:disabled').removeAttr('disabled');
					$.ajax({
						type	: 	"POST",
						url		:	"<?= ASSETS_URL; ?>/ajax/ajaxcalls.php",
						data	:	'action=addUser&'+$("#wizard-1").serialize(),
						success	:	function( values ) {
							if( values == "Exists" )
							{
								$.smallBox({
									title: "User already exists",
									content: "<i class='fa fa-user'></i> <i>You can edit the user specifics above. If the user does not appear within your company list, please contact The ROI Shop.</i>",
									color: "#895F5F",
									iconSmall: "fa fa-warning bounce animated",
									timeout: 10000
								});
							} else {
								$.smallBox({
									title: "New user added!",
									content: "<i class='fa fa-user'></i> <i>"+values+" was added to your company's user list. They will receive an email with details on how to complete their registration. If they do not receive an email please contact The ROI Shop.</i>",
									color: "#5F895F",
									iconSmall: "fa fa-check bounce animated",
									timeout: 40000
								});
							}
						}
					});
					$("#wizard-1").find('input').val('');
					disabled.attr('disabled','disabled');
				}
			} else {
				$.smallBox({
					title: "No company ROIs specified",
					content: "Please select at least one ROI to be added to this account.</i>",
					color: "#895F5F",
					iconSmall: "fa fa-warning bounce animated",
					timeout: 10000
				});
			}
		});		
	  
	}

</script>
