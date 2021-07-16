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
			<div class="jarviswidget sectionwidget" style="margin-bottom:10px;" data-section-id="<?=$getSections[$i]['ID']?>" id="wid-id-<?=$getSections[$i]['Position']?>" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="true" data-widget-fullscreenbutton="false" data-widget-collapsed="<?= ($i == 0 ? 'false' : 'true') ?>">

				<header>
					<span class="widget-icon"> <i class="fa fa-resize-vertical"></i> </span>
					<h2><strong><i><?=$getSections[$i]['Title']?> </i></strong> </h2>				
					
				</header>
				
				<div>
					
					<!-- widget content -->
					<div class="widget-body no-padding">
						
						<h3 class="alert alert-warning"> Edit the <?=$getSections[$i]['Title']?> Section below. </h3>
						
						<form action="" id="section-<?=$getSections[$i]['ID']?>" novalidate="novalidate">	
							<fieldset>
								<h2 style="margin-left:15px;margin-top:0">Section Title</h2>
								<div class="row smart-form">
									<input type="hidden" name="sectionid" value="<?=$getSections[$i]['ID']?>">
									<section class="col col-6">
										<label class="input"> <i class="icon-prepend fa fa-user"></i>
											<input type="text" name="title" placeholder="Section Title" value="<?=$getSections[$i]['Title']?>">
										</label>
									</section>
									<section class="col col-6">
										<label class="input"> <i class="icon-prepend fa fa-user"></i><i class="icon-append fa fa-question-circle"></i>
											<input type="text" name="nickname" placeholder="Nickname" value="<?=$getSections[$i]['nickname']?>">
											<b class="tooltip tooltip-top-right">
												Section titles longer than 20 characters may not display properly on<br>
												the pod page. If a nickname is specified it will be used instead. </b> 										
										</label>										
									</section>
								</div>

								<h2 style="margin-left:15px;margin-top:0">Section Write Up</h2>	
								<div id="caption-<?=$getSections[$i]['ID']?>" class="row summernote">
									<?=$getSections[$i]['Caption']?>
								</div>
								
								<h2 style="margin-left:15px;margin-top:0">Section Video</h2>	
								<section class="smart-form" style="margin-left:15px;margin-right:15px">
									<label class="input"><i data-toggle="modal" href="#myModal" class="video-preview icon-prepend fa fa-video-camera"></i><i class="icon-append fa fa-question-circle"></i>
										<input class="video-link" type="text" name="video" placeholder="Video embed link" value="<?=$getSections[$i]['Video']?>">
										<b class="tooltip tooltip-top-right">
											Your video should have an embed link associate with it. Within<br>
											the link there will be a <code>src</code>. Copy this and enter it here.<br>
											Click the video icon on the left to preview the video. </b> 											
									</label>
								</section>

								<h2 style="margin-left:15px;">Section Pop Up On Load</h2>	
								<div id="growl-<?=$getSections[$i]['ID']?>" class="row summernote">
									<?=$getSections[$i]['growl']?>
								</div>
								
							</fieldset>
							
							<div class="smart-form">
								<footer>
									<button data-section="<?=$getSections[$i]['ID']?>" type="submit" class="store-section btn btn-primary">
										Change <?=$getSections[$i]['Title']?> Specifications
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
	
	$( '.video-preview' ).on( 'click', function() {
		$('#video-iframe').html( '<iframe width="560" height="360" src="'+$(this).parent().find('.video-link').val()+'?rel=0&showinfo=0&controls=0" frameborder="0" allowfullscreen></iframe>' );
	});
	
	$( '.store-section' ).on( 'click', function(e) {
		$.ajax({
			type	: 	"POST",
			url		:	"<?= ASSETS_URL; ?>/ajax/ajaxcalls.php",
			data	:	'action=changeSection&'+$("#section-"+$(this).data('section')).serialize()+'&caption='+$("#caption-"+$(this).data('section')).code()+'&growl='+$("#growl-"+$(this).data('section')).code(),
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
		e.preventDefault();
	});


</script>
