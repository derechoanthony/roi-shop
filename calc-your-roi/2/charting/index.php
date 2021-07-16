<?php

	// Establish connection to the database
	
	require_once("../db/constants.php");
	require_once("../db/connection.php");
	
	require_once( "../construct/charting/charts.general.functions.php" );				
	require_once( "../construct/charting/fontmodal.constructor.php" );
	//require_once( "../php/construct/elements/charts.storeoptions.php" );
	
?>

<!-- Start of the ROI Header -->

<?php

	include_once("../inc/header.php");
?>


<?php
	//Now that all the references are made
	//Create a call to needed classes and 
	//get array values that may be used repeatedly 
	
	$charting 		= new ChartFunctions($db);				// Calls a class for general functions associated with charts construct/elements/charts.general.functions.php
	$fontmodal	 	= new FontModal($db);					// Calls a General Constructor class in Miscellaneous/fontmodal.constructor.php --> Used for fontModals
	$chartopts 		= $charting->retrieveChartOpts();		// An array of values for this chart.
	$seriesopts		= $charting->retrieveSeriesOptions();	// An array of values for each series in this chart.
	$companies 		= $charting->retrieveCompanies();		// An array of companies.  Can be removed when CompID is no longer used for ROIs
	$fonts 			= $charting->retrieveFonts();			// An array of fonts. Used in the Font Modal PHP
?>
	
<!-- End of the ROI Header -->
	
<!-- Start of the ROI Body -->
	
	<body class="fixed-sidebar pace-done fixed-nav">


		<div id="wrapper">
		
			<nav class="navbar-default navbar-static-side" role="navigation">
				<div class="sidebar-collapse">
					<ul class="nav" id="side-menu">
						<li class="nav-header">
							<div class="dropdown profile-element">
								<span>
									<img id="company_logo" class="some-button" alt="image" src="../company_specific_files/1/logo/logo.png" />
								</span>
							</div>
						</li>
						
					</ul>
				</div>
			</nav>

        <div id="page-wrapper" class="gray-bg">
        
        
		        	
        	
        	
        	
        <div class="row border-bottom">
        <nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
            <form role="search" class="navbar-form-custom" action="search_results.html">
                <div class="form-group">
                    <input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
                </div>
            </form>
        </div>
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <span class="m-r-sm text-muted welcome-message">Welcome to INSPINIA+ Admin Theme.</span>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                        <i class="fa fa-envelope"></i>  <span class="label label-warning">16</span>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        <li>
                            <div class="dropdown-messages-box">
                                <a href="profile.html" class="pull-left">
                                    <img alt="image" class="img-circle" src="img/a7.jpg">
                                </a>
                                <div class="media-body">
                                    <small class="pull-right">46h ago</small>
                                    <strong>Mike Loreipsum</strong> started following <strong>Monica Smith</strong>. <br>
                                    <small class="text-muted">3 days ago at 7:58 pm - 10.06.2014</small>
                                </div>
                            </div>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <div class="dropdown-messages-box">
                                <a href="profile.html" class="pull-left">
                                    <img alt="image" class="img-circle" src="img/a4.jpg">
                                </a>
                                <div class="media-body ">
                                    <small class="pull-right text-navy">5h ago</small>
                                    <strong>Chris Johnatan Overtunk</strong> started following <strong>Monica Smith</strong>. <br>
                                    <small class="text-muted">Yesterday 1:21 pm - 11.06.2014</small>
                                </div>
                            </div>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <div class="dropdown-messages-box">
                                <a href="profile.html" class="pull-left">
                                    <img alt="image" class="img-circle" src="img/profile.jpg">
                                </a>
                                <div class="media-body ">
                                    <small class="pull-right">23h ago</small>
                                    <strong>Monica Smith</strong> love <strong>Kim Smith</strong>. <br>
                                    <small class="text-muted">2 days ago at 2:30 am - 11.06.2014</small>
                                </div>
                            </div>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <div class="text-center link-block">
                                <a href="mailbox.html">
                                    <i class="fa fa-envelope"></i> <strong>Read All Messages</strong>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell"></i>  <span class="label label-primary">8</span>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <li>
                            <a href="mailbox.html">
                                <div>
                                    <i class="fa fa-envelope fa-fw"></i> You have 16 messages
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="profile.html">
                                <div>
                                    <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                    <span class="pull-right text-muted small">12 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="grid_options.html">
                                <div>
                                    <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <div class="text-center link-block">
                                <a href="notifications.html">
                                    <strong>See All Alerts</strong>
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>


                <li>
                    <a href="login.html">
                        <i class="fa fa-sign-out"></i> Log out
                    </a>
                </li>
                
                <li>
                    <a class="right-sidebar-toggle">
                        <i class="fa fa-tasks"></i>
                    </a>
                </li>
                
            </ul>


        
        
        
        
    
        
        
        
   </div>     
           </nav>     
        
        
        
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-4">
                    <h2>ROI Template Editor</h2>
                    ROI Version 1
                    
                </div>
                
            </div>
        <div class="row">
            <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Chart Customizer</h5>
                            
                        </div>
                        <form class="form-horizontal" id="formChartOpts">
                        
                        <div class="ibox-content ibox-heading">
                        
                        <div class="tabs-container col-lg-7">
						
						


                        <div class="tabs">
                            <ul class="nav nav-tabs">
                                <li class="active SaveChartOpts"><a data-toggle="tab" href="#tab-general"> General</a></li>
                                <li class="SaveChartOpts"><a data-toggle="tab" href="#tab-titles"> Titles</a></li>
                                <li class="SaveChartOpts"><a data-toggle="tab" href="#tab-xaccess"> X-Access</a></li>
                                <li class="SaveChartOpts"><a data-toggle="tab" href="#tab-series"> Data Series</a></li>
                                <li class="SaveChartOpts"><a data-toggle="tab" href="#tab-gridlines"> Grid Lines</a></li>
                                <li class="SaveChartOpts"><a data-toggle="tab" href="#tab-legend"> Legend</a></li>
                                <li class="SaveChartOpts"><a data-toggle="tab" href="#tab-tooltips"> Tooltips</a></li>
                                
                            </ul>
                            <div class="tab-content ">
                            	
                                                     	
                                <div id="tab-general" class="tab-pane active">
                                    <div class="panel-body">
                                    	
                                             
							
                            
                                <div class="form-group">
                                	<label class="col-lg-2 control-label smallbold">Html Code</label>
                                    <div class="col-lg-9">
                                    	<input type="text" disabled="" placeholder="HTML Code to Copy" class="form-control" value="<div class=''ROICalcElemID'' id=''ROICalcElemID<?php echo $_GET['chartID'];?>'' data-id=''<?php echo $_GET['chartID'];?>'' data-animate=''1''></div>">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                	<label class="col-lg-2 control-label smallbold">ChartID</label>
                                    <div class="col-lg-9">
                                    	<input type="text" disabled="" name="chartID" id="chartID"  value="<?php echo $_GET['chartID'];?>" placeholder="HTML Code to Copy" class="form-control">
                                    </div>
                                </div>
                                
                                <div class="hr-line-dashed"></div>
                                
                                <div class="form-group">
                                	<label class="col-lg-2 control-label smallbold">Chart Name</label>
                                    <div class="col-lg-9">
                                    	<input type="text" placeholder="Chart Name" name="chartName" value="<?php echo $chartopts['chartName']?>" class="form-control"> 
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                	<label class="col-lg-2 control-label smallbold">Company</label>
									<div class="col-lg-9">
										<select name="compID" class="form-control m-b" >
                                    	<?php 
                                    	foreach( $companies as $comp ) {
                                    		echo '<option value="' . $comp[compID] . '"' .  ($chartopts['compID']==$comp['compID'] ? 'selected="selected"' : '' ) . '>' . $comp[compName] . '</option>';	
                                    	}	
                                    	?>
                                    	</select>
                                    <span class="help-block m-b-none col-lg-12">Taking the Place of ROI Calculator ID Temporarily</span>
                                	</div>
                               </div>
                               
                               
                                
                            <div class="hr-line-dashed"></div>
                                
                            <div class="form-group">
                            	<label class="col-lg-2 control-label smallbold">Back Color</label>
                                <div class="col-lg-3">
                                	<input type="text" placeholder="#FFFFFF" name="backColor" value="<?php echo $chartopts['backColor']?>" class="form-control colorpicker SaveChartOpts">
                                	
                                </div>
                               	<label class="col-lg-1"><a class="btn btn-white btn-bitbucket back-change" href="#"><i class="fa fa-paint-brush"></i></a></label> 
                               	
                               	
                               	<label class="col-lg-2 control-label smallbold text-right">Border Color</label>
                               	
                                <div class="col-lg-3">
                                	<input type="text" placeholder="#FFFFFF" name="borderColor" value="<?php echo $chartopts['borderColor']?>" class="form-control colorpicker SaveChartOpts"> 
                                </div>
                            </div>
                            <div class="form-group">    
                                <label class="col-lg-2 control-label smallbold">Border Thickness</label>
								<div class="col-lg-3">
									<select class="form-control m-b SaveChartOpts" name="borderThickness">
                                    	<?php 
                                    	for ( $i=0; $i<9; $i++) {
                                    		echo '<option value="' . $i . '"' .  ($chartopts['borderThickness']==$i ? 'selected="selected"' : '' ) . '>' . $i . ' px</option>';	
                                    	}	
                                    	?>
                                    </select>
                                </div>
                                
                                <label class="col-lg-2 col-lg-offset-1  control-label smallbold text-right">Border Radius</label>                                    
                                <div class="col-lg-3">
                                	<select class="form-control m-b SaveChartOpts" name="borderRadius">
                                    	<?php 
                                    	for ( $i=0; $i<45; $i+=5) {
                                    		echo '<option value="' . $i . '"' .  ($chartopts['borderRadius']==$i ? 'selected="selected"' : '' ) . '>' . $i . ' px</option>';	
                                    	}		
                                    	?>
                                    </select>
                                </div>
							</div>
                             	<!-- End Div for Row of this tab -->
                                    </div>
                                </div>
                                
                                <div id="tab-titles" class="tab-pane">
                                    <div class="panel-body">
                                        
                                <br>
                                <br>
                                <div id="test">
                               <div class="form-group">
                               		<label class="col-lg-2 control-label smallbold">Chart Title</label>
                                    <div class="col-lg-8">
                                    	<input type="text" placeholder="Chart Title" name="titletext" value="<?php echo $chartopts['titleText']?>" class="form-control SaveChartOpts"> 
                                    </div>
                                	
							   
							   <!-- Begin Font Style Modal Subtitles -->
									<?php 
	                                        
	                                $modalID 				= "titlefont";			//What href ID is given in the modal toggle link?
									$fonttitle	 			= "Chart Title";		//What Title would you like to give this modal  "__ Font Styles"
									$fontID					= 'titlefontID';		//field name of the fontID
									$fontsize				= 'titlefontsize';		//field name of the font size
	                       			$fontcolor				= 'titleColor';			//field name of the font color
	                       			$fontbold				= 'titleBold';			//field name of the font bold toggle
	                       			$fontitalic				= 'titleItalic';		//field name of the font italic toggle
	                       			$fontunderline			= 'titleUnderline';		//field name of the font underline toggle
									
	                                $modal = $fontmodal->createFontModal($modalID,$fonttitle,$fontID,$fontsize, $fontcolor, $fontbold, $fontitalic, $fontunderline);
	                                echo $modal;
	                                ?> 
							  <!-- End Font Style Modal -->
							  </div>
							  </div>
							  	 
                              <div class="hr-line-dashed"></div>
                              
                              <!-- Begin Text Input Fields for SubTitle -->
                              	<div class="form-group">
                                	<label class="col-lg-2 control-label smallbold">Chart SubTitle</label>
                                    <div class="col-lg-8">
                                    <input type="text" placeholder="Chart Title" name="subtitletext" value="<?php echo $chartopts['subtitleText']?>" class="form-control SaveChartOpts">	
                                    </div>
                               		
								
			                        <!-- Begin Font Style Modal Subtitles -->

                                        <?php 
                                        
                                        $modalID 				= "subtitlefont";		//What href ID is given in the modal toggle link?
										$fonttitle	 			= "Subtitle";			//What Title would you like to give this modal  "__ Font Styles"
										$fontID					= "subtitlefontID";		//field name of the fontID
										$fontsize				= "subtitlefontsize";	//field name of the font size
                               			$fontcolor				= "subtitleColor";		//field name of the font color
                               			$fontbold				= 'subtitleBold';		//field name of the font bold toggle
                               			$fontitalic				= 'subtitleItalic';		//field name of the font italic toggle
                               			$fontunderline			= 'subtitleUnderline';	//field name of the font underline toggle
										
                                        $modal = $fontmodal->createFontModal($modalID,$fonttitle,$fontID,$fontsize, $fontcolor, $fontbold, $fontitalic, $fontunderline);
                                        	echo $modal;
                                        ?> 

			                        <!-- End Font Style Modal -->
			                        
			                        </div>
			                        
							<!-- End Text Input Fields for SubTitle -->
                                
                                
                                
                                </div>
                                </div>
                                
                                
                                <div id="tab-xaccess" class="tab-pane">
                                    <div class="panel-body">
                                        
                            <br>	
                            <br>	
                            	<div class="form-group">
                            		<label class="col-lg-2 control-label smallbold">X-Axis Series</label>
                                    <div class="col-sm-9">
                                    <select class="form-control m-b SaveChartOpts" name="xaxisSeries">
                                        <option>Return Period (Formatted)</option>
                                        <option>Date Since ROI Creation</option>
                                    </select>
                                	</div>
                               </div>
                               
                               <!-- Begin Text Input Fields for SubTitle -->
                                <div class="form-group">
                                	<label class="col-lg-2 control-label smallbold">X-Axis Title</label>
                                    <div class="col-lg-4">
                                    <input type="text" placeholder="X-Axis Title" name="xAxisTitleText" value="<?php echo $chartopts['xAxisTitleText']?>" class="form-control SaveChartOpts">	
                                    </div>
                               		
								
			                        <!-- Begin Font Style Modal Subtitles -->

                                        <?php 
                                        
                                        $modalID 				= "xAxisTitlefontModal";		//What href ID is given in the modal toggle link?
										$fonttitle	 			= "X-Axis Title";				//What Title would you like to give this modal  "__ Font Styles"
										$fontID					= "xAxisTitleFontID";			//field name of the fontID
										$fontsize				= "xAxisTitleFontSize";			//field name of the font size
                               			$fontcolor				= "xAxisTitlecolor";			//field name of the font color
                               			$fontbold				= "xAxisTitleBold";				//field name of the font bold toggle
                               			$fontitalic				= "xAxisTitleItalic";			//field name of the font italic toggle
                               			$fontunderline			= "xAxisTitleUnderline";		//field name of the font underline toggle
										
                                        $modal = $fontmodal->createFontModal($modalID,$fonttitle,$fontID,$fontsize, $fontcolor, $fontbold, $fontitalic, $fontunderline);
                                        echo $modal;
                                        ?> 

			                        <!-- End Font Style Modal -->
			                        
			                        </div>
			                        
                                 <!-- End Text Input Fields for SubTitle -->
                               
                            	
                                
                                <div class="hr-line-dashed"></div>
                                
                                <div class="form-group">
                                	<label class="col-lg-2 control-label smallbold">X-Axis Labels</label>
                                	<div class="col-lg-2">
                                		<div ><label> <input type="checkbox" class="i-checks SaveChartOpts" name="xAxisLabelsEnabled" <?php echo ( $chartopts['xAxisLabelsEnabled'] == 1 ? 'checked="checked"' : '' ) ?> ><i></i> Enabled </label></div>
                                	</div>
                                  	
                                  	<div class="col-sm-2"><select class="form-control m-b SaveChartOpts" name="xAxisLabelsRotation">
                                    	<?php 
                                    	for ( $i=-90; $i<=90; $i+=15) {
                                    		echo '<option value="' . $i . '"' .  ($chartopts['xAxisLabelsRotation']==$i ? 'selected="selected"' : '' ) . '>' . $i . ' degrees</option>';	
                                    	}
                                    		
                                    	?>
                                    	</select>
                                	</div>
                                	
                                	
								
			                        <!-- Begin Font Style Modal Subtitles -->

                                        <?php 
                                        
                                        $modalID 				= "xAxisTLabelModal";		//What href ID is given in the modal toggle link?
										$fonttitle	 			= "X-Axis Labels";				//What Title would you like to give this modal  "__ Font Styles"
										$fontID					= "xAxisLabelsFontID";			//field name of the fontID
										$fontsize				= "xAxisLabelsFontSize";			//field name of the font size
                               			$fontcolor				= "xAxisLabelsColor";			//field name of the font color
                               			$fontbold				= "xAxisLabelsBold";				//field name of the font bold toggle
                               			$fontitalic				= "xAxisLabelsItalic";			//field name of the font italic toggle
                               			$fontunderline			= "xAxisLabelsUnderline";		//field name of the font underline toggle
										
                                        $modal = $fontmodal->createFontModal($modalID,$fonttitle,$fontID,$fontsize, $fontcolor, $fontbold, $fontitalic, $fontunderline);
                                        echo $modal;
                                        ?> 

			                        <!-- End Font Style Modal -->
			                        </div>
                                	<!--
                                	<label class="col-lg-1 control-label">Color</label>

                                    <div class="col-lg-1"><input type="text" placeholder="#FFFFFF" value="<?php echo $chartopts['xAxisLabelsColor']?>" class="form-control" name="xAxisLabelsColor"> 
                                    </div>
                                <label class="col-lg-1 control-label">Font Size</label>

                                    <div class="col-lg-1"><input type="text" placeholder="10" value="<?php echo $chartopts['xAxisLabelsFontSize']?>" class="form-control" name="xAxisLabelsFontSize"> 
                                    </div>
                                
                                    <div class="col-lg-1">
                                        <div class="i-checks"><label> <input type="checkbox" name="xAxisLabelsBold" <?php echo ( $chartopts['xAxisLabelsBold'] == 1 ? 'checked="checked"' : '' ) ?> ><i></i> Bold </label></div>
                                    </div> -->
                                 
                                <div class="hr-line-dashed"></div>
                                
                                
                                </div>
                                </div>
                                <!-- ****************************************************************************************** -->
                                <!-- Begin Series Tab************************************************************************** -->
                                <!-- ****************************************************************************************** -->
                                <div id="tab-series" class="tab-pane">
                                <div class="panel-body">
                               
	                            	
                                
                                
                                <!-- Add Series Information Here -->
                                <div id="SeriesInfo">
                                <div class="form-group">
                                	
                                	<div class="form-group">
                                	<!--<label class="col-lg-2 col-lg-offset-2  smallbold">No.SeriesDB</label>-->
                                    <div class="col-lg-2"><input type="hidden" placeholder="No of Series" class="form-control" id="series_number" value="<?php $numseries = $charting->retrieveNoSeries(); echo $numseries[0]; ?>"></div>
                                	<!--<label class="col-lg-2 control-label smallbold">No.SeriesForm</label>-->
                                    <div class="col-lg-2"><input type="hidden" placeholder="No of Series" class="form-control" name="form_series_number" id="form_series_number" value="0"></div>
                                	</div>
                                	
                                	<!-- Use the following div to place the series information 
                                		from the modal for each series -->
                                	<div id="serieshiddeninfo">
                                		<?php 
                                		$i = 0;
										foreach ($seriesopts as $seriesopt){
											$i = $i + 1;
											echo '<input type="hidden" name="' . $i . '-seriestype" value="' . $seriesopts[$i-1]['seriesType'] . '" />';
											echo '<input type="hidden" name="' . $i . '-seriestitle" value="' . $seriesopts[$i-1]['seriesTitle'] . '" />';
											echo '<input type="hidden" name="' . $i . '-seriesareacolor" value="' . $seriesopts[$i-1]['seriesAreaColor'] . '" />';
											echo '<input type="hidden" name="' . $i . '-seriesareatrans" value="' . $seriesopts[$i-1]['seriesAreaTrans'] . '" />';
											echo '<input type="hidden" name="' . $i . '-seriespointcolor" value="' . $seriesopts[$i-1]['seriesPointColor'] . '" />';
											echo '<input type="hidden" name="' . $i . '-seriespointsymbol" value="' . $seriesopts[$i-1]['seriesPointSymbol'] . '" />';
											echo '<input type="hidden" name="' . $i . '-seriespointsize" value="' . $seriesopts[$i-1]['seriesPointSize'] . '" />';
											echo '<input type="hidden" name="' . $i . '-serieslinecolor" value="' . $seriesopts[$i-1]['seriesLineColor'] . '" />';
											echo '<input type="hidden" name="' . $i . '-serieslinestyle" value="' . $seriesopts[$i-1]['seriesLineStyle'] . '" />';
											echo '<input type="hidden" name="' . $i . '-datalabelEnabled" value="' . $seriesopts[$i-1]['datalabelEnabled'] . '" />';
											echo '<input type="hidden" name="' . $i . '-datalabelPrefix" value="' . $seriesopts[$i-1]['datalabelPrefix'] . '" />';
											echo '<input type="hidden" name="' . $i . '-datalabelValue" value="' . $seriesopts[$i-1]['datalabelValue'] . '" />';
											echo '<input type="hidden" name="' . $i . '-datalabelSuffix" value="' . $seriesopts[$i-1]['datalabelSuffix'] . '" />';
											echo '<input type="hidden" name="' . $i . '-datalabelFontID" value="' . $seriesopts[$i-1]['datalabelFontID'] . '" />';
											echo '<input type="hidden" name="' . $i . '-datalabelFontColor" value="' . $seriesopts[$i-1]['datalabelFontColor'] . '" />';
											echo '<input type="hidden" name="' . $i . '-datalabelFontSize" value="' . $seriesopts[$i-1]['datalabelFontSize'] . '" />';
										};
										?>
                                		
                                	</div>
                                	
                                </div>
                                
                               </div>  <!-- End Insert for new series div -->
                                
                                <br>
                                 <div class="form-group">
                                    <div class="col-lg-offset-1 col-lg-10">
                                        <button type="button" class="btn btn-primary pull-left newSeries"><span class="glyphicon glyphicon-plus"></span> New Series</button>
                                    </div>
                                </div>
                                <br>
                                <!-- End Series Information -->
                                
                           
                                    </div>
                                </div>
                                
                                
                           	<!-- ****************************************************************************************** -->
                            <!-- Begin Grid Line Tab************************************************************************** -->
                            <!-- ****************************************************************************************** -->
                            <div id="tab-gridlines" class="tab-pane">
                            <div class="panel-body">
                            <div class="row">
                            <br>	
                            <br>
                            
                            
                            
                            </div></div></div>	     
                           <!--End Labels Tabb -->
                           
                           <!--Begin Legend Tabb -->
                            <div id="tab-legend" class="tab-pane">
                            <div class="panel-body">
                            
                            <br>	
                            <br>
                            
                            <div class="form-group">
	                            
	                            <label class="col-lg-2 control-label smallbold">Legend</label>
	                            
	                            <div class="col-lg-2">
	                            <div> <label> <input type="checkbox" class="i-checks SaveChartOpts" name="LegendEnabled" <?php echo ( $chartopts['LegendEnabled'] == 1 ? 'checked="checked"' : '' ) ?> ><i></i> Enabled </label></div>
	                            </div>
								
								<label class="col-lg-2 control-label smallbold">Legend Items</label>
			                        
										<!-- Begin Font Style Modal Legend Items -->
                                        <?php 
                                        
                                        $modalID 				= "legendItemModal";			//What href ID is given in the modal toggle link in the label?
										$fonttitle	 			= "Legend Item Label";			//What Title would you like to give this modal  "__ Font Styles"
										$fontID					= "legendItemFontID";			//field name of the fontID
										$fontsize				= "legendItemFontSize";			//field name of the font size
                               			$fontcolor				= "legendItemColor";			//field name of the font color
                               			$fontbold				= "legendItemBold";				//field name of the font bold toggle
                               			$fontitalic				= "legendItemItalic";			//field name of the font italic toggle
                               			$fontunderline			= "legendItemUnderline";		//field name of the font underline toggle
										
                                        $modal = $fontmodal->createFontModal($modalID,$fonttitle,$fontID,$fontsize, $fontcolor, $fontbold, $fontitalic, $fontunderline);
                                        echo $modal;
                                        ?> 
                                        <!-- End Font Style Modal Legend Items -->
                            </div>
                            
                            <div class="form-group">
	                        	<label class="col-lg-2 control-label smallbold">Legend Title</label>
	                            <div class="col-lg-4">
	                            <input type="text" placeholder="Legend Title" name="legendTitle" value="<?php echo $chartopts['legendTitle']?>" class="form-control SaveChartOpts"> 
	                            </div>
	                           	
	                           			<!-- Begin Font Style Modal Legend Title -->
                                        <?php 
                                        
                                        $modalID 				= "legendTitleModal";			//What href ID is given in the modal toggle link in the label?
										$fonttitle	 			= "Legend Title";			//What Title would you like to give this modal  "__ Font Styles"
										$fontID					= "legendTitleFontID";			//field name of the fontID
										$fontsize				= "legendTitleFontSize";			//field name of the font size
                               			$fontcolor				= "legendTitleFontColor";			//field name of the font color
                               			$fontbold				= "legendTitleBold";				//field name of the font bold toggle
                               			$fontitalic				= "legendTitleItalic";			//field name of the font italic toggle
                               			$fontunderline			= "legendTitleUnderline";		//field name of the font underline toggle
										
                                        $modal = $fontmodal->createFontModal($modalID,$fonttitle,$fontID,$fontsize, $fontcolor, $fontbold, $fontitalic, $fontunderline);
                                        echo $modal;
                                        ?> 
	                           			<!-- End Font Style Modal Legend Title -->
                            </div>
                            
                            
                            <hr>
                            
                        	<div class="form-group"><label class="col-lg-2 control-label smallbold ">Position</label>
                            <div class="col-sm-4"><select class="form-control m-b SaveChartOpts" name="legendPosition" id="legendPosition" onchange="updateLegendPosition()">
                                <option <?php echo ($chartopts['legendPosition']=='Top Left' ? 'selected="selected"' : '');  ?>>Top Left</option>                               
                                <option <?php echo ($chartopts['legendPosition']=='Top Right' ? 'selected="selected"' : '');  ?>>Top Right</option>
                                <option <?php echo ($chartopts['legendPosition']=='Bottom Left' ? 'selected="selected"' : '');  ?>>Bottom Left</option>
                                <option <?php echo ($chartopts['legendPosition']=='Bottom Center' ? 'selected="selected"' : '');  ?>>Bottom Center</option>
                                <option <?php echo ($chartopts['legendPosition']=='Bottom Right' ? 'selected="selected"' : '');  ?>>Bottom Right</option>
                                <option <?php echo ($chartopts['legendPosition']=='Left' ? 'selected="selected"' : '');  ?>>Left</option>
                                <option <?php echo ($chartopts['legendPosition']=='Right' ? 'selected="selected"' : '');  ?>>Right</option>
                                <option <?php echo ($chartopts['legendPosition']=='Center Chart' ? 'selected="selected"' : '');  ?>>Center Chart</option>
                                
                            </select>
                        	</div>
                        	
                        	<label class="col-sm-1 SaveChartOpts"><a class="btn btn-white btn-bitbucket SaveChartOpts" onclick="updateLegendXPosition(-5)"><i class="fa fa-arrow-left"></i></a></label>
                        	<label class="col-sm-1 SaveChartOpts"><a class="btn btn-white btn-bitbucket SaveChartOpts" onclick="updateLegendXPosition(5)"><i class="fa fa-arrow-right"></i></a></label>
                        	<label class="col-sm-1 SaveChartOpts"><a class="btn btn-white btn-bitbucket SaveChartOpts" onclick="updateLegendYPosition(-5)"><i class="fa fa-arrow-up"></i></a></label>
                        	<label class="col-sm-1 SaveChartOpts"><a class="btn btn-white btn-bitbucket SaveChartOpts" onclick="updateLegendYPosition(5)"><i class="fa fa-arrow-down"></i></a></label>
                        	
                       		</div>

							
							<script>
							
							function updateLegendXPosition(i){
								$("input[name=legendx]").val(($("input[name=legendx]").val()/1)+i);
							}
							
							function updateLegendYPosition(i){
								$("input[name=legendy]").val(($("input[name=legendy]").val()/1)+i);
							}
							
							function updateLegendPosition(){
								var pos = $('#legendPosition').val();
								
								switch (pos) {
								
								case 'Left':
									$("select[name=legendAlign]").val('left');
									$("select[name=legendVerticalAlign]").val('middle');
									$("input[name=legendx]").val(0);
									$("input[name=legendy]").val(0);
									$("select[name=legendLayout]").val('vertical');
									break;
								
								case 'Right':
									$("select[name=legendAlign]").val('right');
									$("select[name=legendVerticalAlign]").val('middle');
									$("input[name=legendx]").val(0);
									$("input[name=legendy]").val(0);
									$("select[name=legendLayout]").val('vertical');
									break;
								
								case 'Bottom Center':
									$("select[name=legendAlign]").val('center');
									$("select[name=legendVerticalAlign]").val('bottom');
									$("input[name=legendx]").val(0);
									$("input[name=legendy]").val(0);
									$("select[name=legendLayout]").val('horizontal');
									break;	
								
								case 'Bottom Right':
									$("select[name=legendAlign]").val('right');
									$("select[name=legendVerticalAlign]").val('bottom');
									$("input[name=legendx]").val(0);
									$("input[name=legendy]").val(0);
									break;
									
								case 'Bottom Left':
									$("select[name=legendAlign]").val('left');
									$("select[name=legendVerticalAlign]").val('bottom');
									$("input[name=legendx]").val(0);
									$("input[name=legendy]").val(0);
									break;
								
								case 'Top Left':
									$("select[name=legendAlign]").val('left');
									$("select[name=legendVerticalAlign]").val('top');
									$("input[name=legendx]").val(0);
									$("input[name=legendy]").val(0);
									$("select[name=legendLayout]").val('vertical');
									break;
								
								case 'Top Right':
									$("select[name=legendAlign]").val('right');
									$("select[name=legendVerticalAlign]").val('top');
									$("input[name=legendx]").val(0);
									$("input[name=legendy]").val(0);
									$("select[name=legendLayout]").val('vertical');
									break;	
									
								case 'Center Chart':
									$("select[name=legendAlign]").val('center');
									$("select[name=legendVerticalAlign]").val('middle');
									$("input[name=legendx]").val(0);
									$("input[name=legendy]").val(0);
									break;
								
								default:
								
									break;
									
								}
								
								

							}
							
								
								
								
								
								
								
							</script>
							
							
							
							<div class="form-group">
	                        	<label class="col-lg-2 col-lg-offset-2 control-label  smallbold">Align</label>
	                            <div class="col-lg-2">
	                            <select class="form-control m-b SaveChartOpts" name="legendAlign">
                                	<option value="left" <?php echo ($chartopts['legendAlign']=='left' ? 'selected="selected"' : '');  ?>>Left</option>
                                	<option value="center" <?php echo ($chartopts['legendAlign']=='center' ? 'selected="selected"' : '');  ?>>Center</option>
                                	<option value="right" <?php echo ($chartopts['legendAlign']=='right' ? 'selected="selected"' : '');  ?>>Right</option>
                                </select>	
	                            </div>
	                           	
	                           	<label class="col-lg-1 control-label smallbold text-right">X</label>
	                            <div class="col-lg-2">
	                            <input type="text" placeholder="" name="legendx" value="<?php echo $chartopts['legendx']?>" class="form-control SaveChartOpts"> 
	                            </div>
                            </div>
							
							<div class="form-group">
	                        	<label class="col-lg-2  col-lg-offset-2 control-label  smallbold">Vertical Align</label>
	                            <div class="col-lg-2">
	                            <select class="form-control m-b SaveChartOpts" name="legendVerticalAlign">
                                	<option value="top" <?php echo ($chartopts['legendVerticalAlign']=='top' ? 'selected="selected"' : '');  ?>>Top</option>
                                	<option value="middle" <?php echo ($chartopts['legendVerticalAlign']=='middle' ? 'selected="selected"' : '');  ?>>Middle</option>
                                	<option value="bottom" <?php echo ($chartopts['legendVerticalAlign']=='bottom' ? 'selected="selected"' : '');  ?>>Bottom</option>
                                </select>		 
	                            </div>
	                           	
	                           	<label class="col-lg-1 control-label smallbold text-right">Y</label>
	                            <div class="col-lg-2">
	                            <input type="text" placeholder="" name="legendy" value="<?php echo $chartopts['legendy']?>" class="form-control SaveChartOpts"> 
	                            </div>
                            </div>
                            
                            <div class="form-group">
	                        	<label class="col-lg-2  col-lg-offset-2 control-label  smallbold">Layout</label>
	                            <div class="col-lg-2">
	                            <select class="form-control m-b SaveChartOpts" name="legendLayout">
                                	<option value="horizontal" <?php echo ($chartopts['legendLayout']=='horizontal' ? 'selected="selected"' : '');  ?>>Horizontal</option>
                                	<option value="vertical" <?php echo ($chartopts['legendLayout']=='vertical' ? 'selected="selected"' : '');  ?>>Vertical</option>
                                </select>		 
	                            </div>
                            </div>
							
							<hr>

                            <div class="form-group">
	                        	<label class="col-lg-2 control-label smallbold">Back Color</label>
	                            <div class="col-lg-2">
	                            <input type="text" placeholder="#FFFFFF" name="legendBackColor" value="<?php echo $chartopts['legendBackColor']?>" class="form-control colorpicker SaveChartOpts"> 
	                            </div>
	                           	
	                           	<label class="col-lg-2 control-label smallbold text-right">Border Color</label>
	                            <div class="col-lg-2">
	                            <input type="text" placeholder="#FFFFFF" name="legendBorderColor" value="<?php echo $chartopts['legendBorderColor']?>" class="form-control colorpicker SaveChartOpts"> 
	                            </div>
                            </div>
                            
                            <div class="form-group">    
                                <label class="col-lg-2 control-label smallbold">Border Thickness</label>
								<div class="col-sm-2">
								<select class="form-control m-b SaveChartOpts" name="legendBorderThickness">
                                	<?php 
                                	for ( $i=0; $i<9; $i++) {
                                		echo '<option value="' . $i . '"' .  ($chartopts['legendBorderThickness']==$i ? 'selected="selected"' : '' ) . '>' . $i . ' px</option>';	
                                	}	
                                	?>
                                </select>
                                </div>
                                
                                <label class="col-lg-2 control-label smallbold text-right">Border Radius</label>                                    
                                <div class="col-sm-2">
                            	<select class="form-control m-b SaveChartOpts" name="legendBorderRadius">
                                	<?php 
                                	for ( $i=0; $i<45; $i+=5) {
                                		echo '<option value="' . $i . '"' .  ($chartopts['legendBorderRadius']==$i ? 'selected="selected"' : '' ) . '>' . $i . ' px</option>';	
                                	}		
                                	?>
                                </select>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="form-group">    
                                <label class="col-lg-2 control-label smallbold">Legend Shadow</label>
								<div class="col-sm-2">
									<label> <input type="checkbox" class="i-checks SaveChartOpts" name="legendShadow" <?php echo ( $chartopts['legendShadow'] == 1 ? 'checked="checked"' : '' ) ?> ><i></i> Enabled </label></div>
                                
                                
                                <label class="col-lg-2 control-label smallbold text-right">Shadow Color</label>                                    
                                <div class="col-sm-2">
                            	<input type="text" placeholder="#FFFFFF" name="legendShadowColor" value="<?php echo $chartopts['legendShadowColor']?>" class="form-control colorpicker SaveChartOpts">
                                </div>
                            </div>
                            
                            
                            </div></div>	     
                           <!--End Labels Tabb -->     
                                
                                
                            </div>

                        </div>
					</div>
					</form>
					<div class="col-lg-5">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            Chart Preview
                                        </div>
                                        <div class="panel-body">
                                            <div class="ROICalcElemID" id="ROICalcElemID1" data-id="1" data-animate="1"></div>
                                        </div>

                                    </div>
                                </div>



                    
                        
                    <div class="row">
                            	<div class="form-group">
                                    <div class="col-lg-12">
                                        <button class="btn btn-sm btn-primary SaveChartOpts"  id="">Save</button>
                                    </div>
                                </div>    
                    </div>    	
            </div>  	<!-- ENd Content Box -->
                        	
                        
                </div>

			
			</div>
            
            
        </div>
        






        </div>
        
        
        
        
        
        
        </div>

<?php
	include_once("../inc/footer.php");
?>

    <script>
        $(document).ready(function(){

		$('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green'
            });
        
        
        
        $('.colorpicker').colorpicker();





		//$('.colorpicker').colorpicker().on('changeColor', function(ev){
		//	var colorbutton = $(this).next('.back-change').style;
		//	colorbutton.backgroundColor = ev.color.toHex();
		//});

       //     var divStyle = $('.back-change')[0].style;
       //     $('.colorpicker').colorpicker({
       //         color: divStyle.backgroundColor
       //     }).on('changeColor', function(ev) {
       //                 divStyle.backgroundColor = ev.color.toHex();
       //             });


           
        });

        
    </script>


</body>
</html>
