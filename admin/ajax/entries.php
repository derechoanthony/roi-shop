<?php
	
	require_once("../inc/base.php");
	require_once("../php/classes.admin.php");
	require_once("inc/init.php");

	$admin = new TheROIShopAdmin($db);

	$getComp = $admin->getCompanySpecs();
	$getSections = $admin->getSections();
	$getEntries = $admin->getEntries();
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
				ROI Sections
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

<?php
	for( $i=0; $i<count( $getSections ); $i++ )
	{
?>
			<h2><?=$getSections[$i]['Title']?> </h2>
<?php
		for( $e=0; $e<count( $getEntries ); $e++ )
		{
			if( $getEntries[$e]['sectionName'] == $getSections[$i]['ID'] )
			{
?>
			<div class="jarviswidget entrywidget" style="margin-bottom:10px;" data-entry-id="<?=$getEntries[$e]['ID']?>" id="wid-id-<?=$getSections[$i]['ID'].$getSections[$e]['Position']?>" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="true" data-widget-fullscreenbutton="false" data-widget-collapsed="<?= ($e == 0 ? 'false' : 'true') ?>">

				<header>
					<span class="widget-icon"> <i class="fa fa-resize-vertical"></i> </span>
					<h2><strong><i><?=$getEntries[$e]['Title']?> </i></strong> </h2>				
					
				</header>
				
				<div>
					
					<!-- widget content -->
					<div class="widget-body no-padding">
						
						<h3 class="alert alert-warning"> Edit the <?=$getEntries[$e]['Title']?> Entry below. </h3>
						
						<form action="" id="entry-<?=$getEntries[$e]['ID']?>" novalidate="novalidate">	
							<fieldset>
								<h2 style="margin-left:15px;margin-top:0">Entry Title</h2>
								<div class="row smart-form">
									<input type="hidden" name="entryid" value="<?=$getEntries[$e]['ID']?>">
									<section class="col col-6">
										<label class="input"> <i class="icon-prepend fa fa-user"></i>
											<input type="text" name="title" placeholder="Section Title" value="<?=$getEntries[$e]['Title']?>">
										</label>
									</section>
								</div>

								<h2 style="margin-left:15px;margin-top:0">Entry Characteristics</h2>	
								<div class="row smart-form">
									<section class="col col-6">
										<label class="label">Entry Type</label>
										<label class="select">
											<select name="type" class="input-sm" disabled="disabled">
												<option value="0" <?= $getEntries[$e]['Type'] == '0' ? 'selected="selected"': '' ?>>Input</option>
												<option value="1" <?= $getEntries[$e]['Type'] == '1' ? 'selected="selected"': '' ?>>Output</option>
												<option value="3" <?= $getEntries[$e]['Type'] == '3' ? 'selected="selected"': '' ?>>Section Header</option>
											</select> <i></i> </label>
									</section>
									<section class="col col-6">
										<label class="label">Entry Format</label>
										<label class="select">
											<select name="format" class="input-sm">
												<option value="0" <?= $getEntries[$e]['Format'] == '0' ? 'selected="selected"': '' ?>>Text</option>
												<option value="1" <?= $getEntries[$e]['Format'] == '1' ? 'selected="selected"': '' ?>>Currency</option>
												<option value="2" <?= $getEntries[$e]['Format'] == '2' ? 'selected="selected"': '' ?>>Percent</option>
											</select>
										</label>
									</section>
								</div>

								<h2 style="margin-left:15px;">Entry Help Pop-Up</h2>	
								<div id="tip-<?=$getEntries[$e]['ID']?>" class="row summernote">
									<?=$getEntries[$e]['Tip']?>
								</div>
								
							</fieldset>
							
							<div class="smart-form">
								<footer>
									<button data-entry="<?=$getEntries[$e]['ID']?>" type="submit" class="store-entry btn btn-primary">
										Change <?=$getEntries[$e]['Title']?> Specifications
									</button>
								</footer>
							</div>
							
						</form>						
						
						
					</div>
					<!-- end widget content -->
					
				</div>
				<!-- end widget div -->
				
			</div>
			<!-- end widget -->
			
<?php
			}
		}
	}
?>
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
			<div id="video-iframe" class="modal-body">

				<iframe width="560" height="360" src="//www.youtube.com/embed/0Tz8_WWYzUk?rel=0" frameborder="0" allowfullscreen></iframe>			

			</div>

		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">

	// DO NOT REMOVE : GLOBAL FUNCTIONS!
	pageSetUp();
	// PAGE RELATED SCRIPTS
	
	/*
	 * SUMMERNOTE EDITOR
	 */
	loadScript("<?php echo ASSETS_URL; ?>/js/plugin/summernote/summernote.js", summernoteStart);

	function summernoteStart() {
		$('.summernote').summernote({
			height : 180,
			focus : false,
			tabsize : 2
		});
	}

	/*
	 * MARKDOWN EDITOR
	 */
	
	loadScript("<?php echo ASSETS_URL; ?>/js/plugin/markdown/markdown.min.js", loadToMarkdown);
	
	function loadToMarkdown (){
		loadScript("<?php echo ASSETS_URL; ?>/js/plugin/markdown/to-markdown.min.js", loadBSMarkdown);
	}
	
	function loadBSMarkdown(){
		loadScript("<?php echo ASSETS_URL; ?>/js/plugin/markdown/bootstrap-markdown.min.js", markdownStart);
	}
	
	function markdownStart() {
		$("#mymarkdown").markdown({
			autofocus:false,
			savable:true
		})
	}
	
	$( '.store-entry' ).on( 'click', function(e) {
		var disabled = $( '#entry-'+$(this).data('entry') ).find(':input:disabled').removeAttr('disabled');
		$.ajax({
			type	: 	"POST",
			url		:	"<?= ASSETS_URL; ?>/ajax/ajaxcalls.php",
			data	:	'action=changeEntry&'+$("#entry-"+$(this).data('entry')).serialize()+'&tip='+$("#tip-"+$(this).data('entry')).code(),
			success	:	function() {
				$.smallBox({
					title: "Section successfully changed!",
					content: "The section content was successfully changed. If you view your ROI you'll see these changes reflected. If they are not please contact The ROI Shop.",
					color: "#3F3F89",
					iconSmall: "fa fa-check bounce animated",
					timeout: 20000
				});
			}
		});
		disabled.attr('disabled','disabled');
		e.preventDefault();
	});
	
	window.onbeforeunload = function(e){
		localStorage.clear();
    }


</script>
