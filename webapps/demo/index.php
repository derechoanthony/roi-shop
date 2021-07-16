<?php include '../inc/reflect-head.php'; 
	$wbappID 	= $_GET['wbappID'];
	if(isset($_GET['key'])) {
    $wbappkey 	= $_GET['key'];
	}else{$wbappkey=0;}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Express ROI - Demo Page</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Animation CSS -->
    <link href="../assets/css/animate.css" rel="stylesheet">
    <link href="../assets/css/font-awesome.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../assets/css/style.css" rel="stylesheet">


	    <link href="../assets/css/plugins/slick/slick.css" rel="stylesheet">
    <link href="../assets/css/plugins/slick/slick-theme.css" rel="stylesheet">

</head>
<style>
	
	.logo img {
		max-width: 100%;
		margin-bottom: 20px;
	}
	
	.hiddenrow {
		display: none;
	}
	
</style>

<body id="page-top" class="gray-bg dashbard-1">


<div class="row border-bottom">
        <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
        
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

        </nav>
        </div>



	


<section id="features" class="container services">
<div class="row">
	
	<div class="col-sm-3">
		<h4 class="text-left m">This ExpressROI was created for: </h4>
		<div class="logo"><img src="../assets/customwb/<?php echo $wbappID;?>/img/logo.png"><h2><?php echo $g->DLookup('roiName','wb_roi_list','wb_roi_ID=' . $wbappID);?></h2></div>
		
		
		
	</div>
	
	<div class="col-sm-9">



                    <h4 class="text-left m">
                        Reports in this ROI:
                    </h4>



                    <div class="slick_demo_2">

						<?php 
						
						
						$SQL = "SELECT * 
								FROM wb_roi_reports 
								WHERE wb_roi_ID=$wbappID
								ORDER BY isprimary DESC, roiReportType ASC;";
						
						
						$list = $g->returnarray($SQL);
                        $listitems 	= '';
						
						$numrows = count($list);
				        $x = 0;
						
						$primaryid 	= $list[0]['wb_roi_report_ID'];
						$reportType = $list[0]['roiReportType'];
						$reportName	= $list[0]['roiReportName'];
						
						if($numrows>0){
							foreach($list as $r){
								$x = $x + 1;
								$icon = ($r['roiReportType'] > 0) ? 'pdf_icon.png' : 'html_icon.png';
								$listitems 	= $listitems .'<div><div class="ibox-content roi_reports">';
								$listitems 	= $listitems .'<h4>' . $r['roiReportName'] . '</h4>';
								$listitems 	= $listitems .'<p><img src="../assets/img/' . $icon . '" height="75px"></p>';
		                        $listitems 	= $listitems .'<p>' . $r['roiReportDescription'] .  '</p>';
		                        $listitems 	= $listitems .'<p>Last Edited:</p></div></div>';
	                        	//$dropitem	= $dropitem . '<li><a href="#" class="changereportID" data-reportid="' . $r['wb_roi_report_ID'] . '" >' . $r['roiReportName'] . '</a></li>';
								}
						}
						
						echo $listitems;
						
						?>


                        
                    </div>
                
		
		
	</div>
	
	
</div>


<div class="row">
	
	
	<div class="col-lg-12">
		
        <div class="tabs-container">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#preview">Preview</a></li>
                            <li class=""><a data-toggle="tab" href="#fields">Fields</a></li>
                            <li class=""><a data-toggle="tab" href="#actions">Actions</a></li>
                            <li class=""><a data-toggle="tab" href="#notes">Notes</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="preview" class="tab-pane active">
                                <div class="panel-body">
                                    <div class="row">
                                    	<div class="col-sm-12">
                                    		<div class="btn-group">
					                            <button data-toggle="dropdown" class="btn btn-default dropdown-toggle">Frame Width <span class="caret"></span></button>
					                            <ul class="dropdown-menu">
					                                <li><a href="#" class="change_display" data-displaywidth="700">Cell Phone</a></li>
					                                <li><a href="#" class="change_display" data-displaywidth="768">Tablet</a></li>
					                                <li><a href="#" class="change_display" data-displaywidth="992">Medium Desktops</a></li>
					                                <li><a href="#" class="change_display" data-displaywidth="1200">Large Desktops</a></li>
					                                
					                            </ul>
					                        </div>
                                    	</div>
                                    </div>
                                    <div class="row">
                                    	<div class="col-sm-12">
                                    <?php $wbappID 	= $_GET['wbappID'];
						            		$key 		= $_GET['key'];	
						            		$iframesrc	= '../icalc.php?wbappID=' . $wbappID . '&key=' . $key; 
						            		$height		= $g->DLOOKUP('height','wb_roi_settings','wb_roi_ID=' . $wbappID);
						            		$width		= $g->DLOOKUP('width','wb_roi_settings','wb_roi_ID=' . $wbappID);
						            		
						            		?>	
						            <iframe id="iframedemo" width="100%" height="<?php echo $height;?>px" frameborder="0" src="<?php echo $iframesrc ?>"></iframe>
									</div>
                                    </div>						                                    
						                                    
                                </div>
                            </div>
                            <div id="fields" class="tab-pane ">
                                <div class="panel-body">
                               	<h4>The following fields are used in this ROI:</h4>     
                                 <div class="calcx" id="calcx">
                                 
                                 <div class="row">
                                 	<div class="col-sm-12">
                                 		
                                 		<?php
                                 			//Get number of field categories
                                 			//IF more than one, add toggle buttons. 
                                 			
						            		$numcategories		= $g->DCOUNTDISTINCT('demoCategory','wb_roi_fields','wb_roi_ID=' . $wbappID);
						            		
											if($numcategories > 1) {
												
											$SQL = "SELECT DISTINCT demoCategory 
													FROM `wb_roi_fields` t1
													WHERE t1.wb_roi_ID=$wbappID 
													ORDER BY demoCategory ASC;";
										
											
											$list = $g->returnarray($SQL);
					                        $toggles	= '<p><button data-toggle="button" class="btn btn-primary btn-outline showall" type="button" aria-pressed="false">Hide All</button>
					                        				<div data-toggle="buttons-checkbox" class="btn-group">';
											
											$numrows = count($list);
									        $x = 0;
																				
											if($numrows>0){
												foreach($list as $r){
													$x = $x + 1;
													
													if($r['demoCategory']==''){$Catname='General';} else {$Catname=$r['demoCategory'];}
													
													$toggles 	= $toggles .'<button class="btn btn-primary demo-toggle" data-democategory="' . $Catname . ' " type="button" aria-pressed="true">' . $Catname . '</button>';
													}
											}
											$toggles = $toggles . '</div></p>';
											echo $toggles;
													
												
												
												
											}
											
						            		?>
                                 		
                                 		
                                 		
	                                 	
                                 	</div>
                                 	
                                 	<div class="col-sm-12">	
                                 		<table class="table">
			                            <thead>
			                            <tr>
			                                <th>#</th>
			                                <th>Cell</th>
			                                <th>Label</th>
			                                <th></th>
			                                <th></th>
			                            </tr>
			                            </thead>
			                            
			                            <?php 
						
						
										$SQL = "SELECT * , 
													(SELECT format 
													FROM `wb_formats_fields` t2 
													WHERE t2.fieldID=t1.fieldID) format
												FROM `wb_roi_fields` t1
												WHERE t1.wb_roi_ID=$wbappID 
												ORDER BY fieldType ASC, cellcolumn ASC, cell ASC;";
										
										
										$list = $g->returnarray($SQL);
				                        $inputfields	= '<tbody>';
										
										$numrows = count($list);
								        $x = 0;
																			
										if($numrows>0){
											foreach($list as $r){
												$x = $x + 1;
												if($r['demoCategory']==''){$Catname='General';} else {$Catname=$r['demoCategory'];}
												
												$disabled = ($r['fieldType']!=1) ? 'disabled' : '';
												$inputfields 	= $inputfields .'<tr class="demo-' . $Catname . '" id="tr-'.  $r['fieldID'] . '">
																	<td>' . $x . '</td>
																	<td>' . $r['cellcolumn'] . $r['cell'] . '</td>
																	<td>' . $r['shortName'] . '</td>
																	<td><input type="text"
																			tabindex=' . $x . ' 
																			data-cell="' . $r['cellcolumn'] . $r['cell'] . '"
																			data-format="' . $r['format'] . '"
																			placeholder="' . $r['placeholder'] . '"
																			data-formula="' . $r['formula'] . '"
																			' . $disabled .  '
																			class="form-control roishop-wb-field-demo">
																	</td>
																	<td><a data-toggle="modal" class="btn btn-default" href="#modal-form">...</a></td>
																	</tr>';
												
					                        	//$dropitem	= $dropitem . '<li><a href="#" class="changereportID" data-reportid="' . $r['wb_roi_report_ID'] . '" >' . $r['roiReportName'] . '</a></li>';
												}
										}
										$inputfields = $inputfields . '</tbody>';
										echo $inputfields;
										
										
									
										
										
										?>
			                            
			                            
			                            
			                            
			                            
			                        </table>
                                 	<!-- Begin Modal -->	
                                 	<div id="modal-form" class="modal fade" aria-hidden="true">
		                                <div class="modal-dialog">
		                                    <div class="modal-content">
		                                        <div class="modal-body">
		                                            <div class="row">
		                                                <div class="col-sm-12"><h3 class="m-t-none m-b">Field Properties</h3>
		
		                                                    <div class="tabs-container">

									                        <div class="tabs-left">
									                            <ul class="nav nav-tabs">
									                                <li class="active"><a data-toggle="tab" href="#tab-6"> General</a></li>
									                                <li class=""><a data-toggle="tab" href="#tab-7">Format</a></li>
									                                <li class=""><a data-toggle="tab" href="#tab-8">Formula</a></li>
									                            </ul>
									                            <div class="tab-content ">
									                                <div id="tab-6" class="tab-pane active">
									                                    <div class="panel-body">
									                                        
												                             
												                             
									                                        	
												                                <div class="form-group col-sm-12"><label>Field Name</label> <input type="text" placeholder="Enter Field Name" class="form-control fieldctl" name="Label"></div>
												                                
												                                
												                                <div class="form-group col-sm-12">
											                                	<label>Field Type</label> <select class="form-control m-b fieldctl" name="InputType">
											                                        <option value="1">Text</option>
											                                        <option value="2">Number</option>
											                                        <option value="3">Lookup</option>
											                                        <option value="4">Yes/No</option>
											                                        <option value="100">Email</option>
											                                    </select>
											                                </div>
												                                
												                             <div class="form-group col-sm-12"><label>Placeholder</label> <input type="text" placeholder="Enter Placeholder" class="form-control fieldctl" name="placeholder"></div>
												                             
												                             <div class="form-group col-sm-12"><label>Demo Value</label> <input type="text" placeholder="Enter Demo Value" class="form-control fieldctl" name="demovalue"></div>   
												                                
												                                
												                             
												                             
												                             
											                                
											                               
											                                
											                                
									                                    </div>
									                                </div>
									                                <div id="tab-7" class="tab-pane">
									                                    <div class="panel-body">
									                                        
									                                    </div>
									                                </div>
									                                <div id="tab-8" class="tab-pane">
									                                    <div class="panel-body">
									                                        
									                                    </div>
									                                </div>
									                            </div>
									
									                        </div>
									
									                    </div>
		                                                    
		                                                    
		                                                </div>
		                                                
		                                        </div>
		                                    </div>
		                                    </div>
		                                </div>
		                        	</div>	
                                 	<!-- End Modal -->	
                                 	</div>
                                 	
                                 	
                                 </div>
                                 
                                   
                                </div>
                                </div>
                            </div>
                            <div id="actions" class="tab-pane">
                                <div class="panel-body">
                                    <h4>The following actions are used in this ROI:</h4>
                                    
                                    
                                     <?php 
						
										
										
										$SQL = "SELECT * , 
													(SELECT wb_roi_ID 
													FROM `wb_roi_reports` t2 
													WHERE t2.wb_roi_report_ID=t1.reportID) wbroiID
                                                
												FROM `wb_roi_reports_macros` t1
                                                JOIN `wb_roi_macros` t3
                                                ON t3.macroID=t1.macroID
												HAVING wbroiID=$wbappID
												ORDER BY reportID ASC, elementID ASC";
										
										
										$list = $g->returnarray($SQL);
				                        
										
										$numrows = count($list);
								        $x = 0;
										$macrolist = '<div class="panel-group" id="macrolist">';									
										if($numrows>0){
											foreach($list as $r){
												$x = $x + 1;
												$macrolist = $macrolist . '
													<div class="panel panel-default">
                                        			<div class="panel-heading">
                                            			<h5 class="panel-title">
                                                			<a data-toggle="collapse" data-parent="#macrolist" href="#collapse' . $x .  '" aria-expanded="false" class="collapsed">' . $r['givenName'] .  ' - ' . $r['macroName'] . '</a>
                                            			</h5>
                                        			</div>
                                        			<div id="collapse' . $x .  '" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                            			<div class="panel-body">
                                                			<div class="row">
															<div class="form-group col-sm-12">
															<label>Description</label> 
															</div>
															</div>
															<div class="row">
															<div class="form-group col-sm-12">
															<textarea rows="4" style="width:100%">' . $r['macroDescription'] . '</textarea>
															</div>
															</div>
															
															<div class="row">
															<div class="form-group col-sm-4"><label>Triggered On: </label> <input type="text" class="form-control fieldctl" value="Click"></div>
															<div class="form-group col-sm-8"><label>Of Element: </label> <input type="text" class="form-control fieldctl" value="' . $r['elementID'] . '"></div>
															</div>
															
															
															 <div class="row">
							                                 	<div class="col-sm-12">
							                                 		
							                                 		<table class="table">
										                            <thead>
										                            <tr>
										                                <th>#</th>
										                                <th width="33%"></th>
										                                <th></th>
										                                
										                                <th width="33%"></th>
										                            </tr>
										                            </thead>';
															
												
												$usedmacroID = $r['usedmacroID'];
												echo $usedmacroID;
												
												$SQL1 = "SELECT *            
												FROM `wb_roi_reports_macros_aurguments` t1
                                                JOIN `wb_roi_macros_aurguments` t2
                                                ON t2.varID=t1.varID
												HAVING t1.usedMacroID=$usedmacroID
												ORDER BY t1.varID ASC";
										
										
										$list1 = $g->returnarray($SQL1);
				                        $macroargs = '';
										
										$numrows1 = count($list1);
								        $y = 0;
																			
										if($numrows1>0){
											foreach($list1 as $s){
												$y = $y + 1;
												$macroargs = $macroargs . '
													
													<tr><td>' . $y  . '</td><td><label class="control-label">' . $s['varName'] . '</label></td><td>' . $s['varDescription'] . '</td>';
													//<div class="row">
													//		<div class="form-group col-sm-4"><label>' . $s['varName'] . ' </label>';
														
													
													switch ($s['lookupOptionsType']) {
														
														case 0:
														//This is a standard input
														$macroargsinput = '<td><input type="text" class="form-control fieldctl" value="' . $s['varValue'] . '"></td>';
														break;
														
														case 10:
														//This is a select with the reports in this ROI project
														$macroargsinput = '<td><select class="form-control m-b" name="format">';
					                                        
															
															$SQL2 = "SELECT *            
																	FROM `wb_roi_reports` t1
					                                                WHERE t1.wb_roi_ID=$wbappID
																	ORDER BY t1.isprimary DESC, roiReportName ASC";
															
															
															$list2 = $g->returnarray($SQL2);
									                        
															
															$numrows2 = count($list1);
								        
																			
														if($numrows2>0){
															foreach($list2 as $t){
																if ($t['wb_roi_report_ID']==$s['varValue']){$selected='selected';}else{$selected='';}
																$macroargsinput = $macroargsinput . '<option value="' . $t['wb_roi_report_ID'] .'" ' . $selected . '>' . $t['roiReportName'] .  '</option>';
															}}
									                         
					                                        
					                                    $macroargsinput = $macroargsinput . '</select></td>';
														
														
														break;
														
														case 20:
														//This is a select with the fields in this ROI project
														$macroargsinput = '<td><select class="form-control m-b" name="format">';
					                                        
															
															$SQL2 = "SELECT *            
																	FROM `wb_roi_fields` t1
					                                                WHERE t1.wb_roi_ID=$wbappID
																	ORDER BY t1.fieldType ASC, t1.cellcolumn ASC, t1.cell ASC";
															
															
															$list2 = $g->returnarray($SQL2);
									                        
															
															$numrows2 = count($list1);
								        
																			
														if($numrows2>0){
															foreach($list2 as $t){
																if ($t['fieldID']==$s['varValue']){$selected='selected';}else{$selected='';}
																$macroargsinput = $macroargsinput . '<option value="' . $t['fieldID'] .'" ' . $selected . '>' . $t['shortName'] .  '</option>';
															}}
									                         
					                                        
					                                    $macroargsinput = $macroargsinput . '</select></td>';
														break;
														
														case 30:
														//This is a select with given values
														$macroargsinput = '<td><select class="form-control m-b" name="format">';
					                                        
															$varID = $s['varID'];
															$SQL2 = "SELECT *            
																	FROM `wb_roi_macros_selectoptions` t1
					                                                WHERE t1.varID=$varID
																	ORDER BY t1.optionID ASC";
															
															
															$list2 = $g->returnarray($SQL2);
									                        
															
															$numrows2 = count($list1);
								        
																			
														if($numrows2>0){
															foreach($list2 as $t){
																if ($t['optValue']==$s['varValue']){$selected='selected';}else{$selected='';}
																$macroargsinput = $macroargsinput . '<option value="' . $t['optValue'] .'" ' . $selected . '>' . $t['optText'] .  '</option>';
															}}
									                         
					                                        
					                                    $macroargsinput = $macroargsinput . '</select></td>';
														break;
														
														case 40:
														//This is a select YES/NO option
														$macroargsinput = '<td><select class="form-control m-b" name="format">';
														if ($s['varValue']==0){$noselected='selected';$yesselected='';}else{$noselected='';$yesselected='selected';}
														$macroargsinput = $macroargsinput . '<option value="0" ' . $noselected . '>NO</option>';
														$macroargsinput = $macroargsinput . '<option value="1" ' . $yesselected . '>YES</option>';
														$macroargsinput = $macroargsinput . '</select></td>';
														break;
														
														case 50:
														//This is a select of Marketo Connections
														$macroargsinput = '<td><select class="form-control m-b" name="format">';
					                                        
															
															$SQL2 = "SELECT *            
																	FROM `wb_marketo_connections` t1
					                                                WHERE t1.wbroiID=$wbappID
																	ORDER BY t1.mkName ASC";
															
															
															$list2 = $g->returnarray($SQL2);
									                        
															
															$numrows2 = count($list1);
														
														if($numrows2>0){
															foreach($list2 as $t){
																if ($s['varValue']==$t['mkID']){$selected='selected';}else{$selected='';}
																$macroargsinput = $macroargsinput . '<option value="' . $t['mkID'] .'" ' . $selected . '>' . $t['mkName'] .  '</option>';
															}}
									                         
					                                        if ($s['varValue']==0){$selected='selected';}else{$selected='';}
															$macroargsinput = $macroargsinput . '<option value="0" ' . $selected . '>Do Not Send to Marketo</option>';
															
					                                    $macroargsinput = $macroargsinput . '</select></td>';
														break;
														
														default:
															 $macroargsinput = '<td><input type="text" class="form-control fieldctl" value="' . $s['varValue'] . '"></td>';
														
														
													}		
															
													$macroargs =$macroargs . $macroargsinput;
													//$macroargs = $macroargs . '</div></div>';	
													$macroargs = $macroargs . '</tr>';
													//$macroargs = $macroargs . '
													//<div class="form-group">
													//<label class="col-sm-9 control-label">' . $s['varName'] . '</label>
                                    				//<div class="col-sm-3">
                                    				//<input class="form-control" type="text" value="' . $s['varValue'] . '"> 
                                    				//<span class="help-block m-b-none">A block of help text that breaks onto a new line and may extend beyond one line.</span>
                                    				//</div>
                                					//</div>';
															
															
												
					                        	}
										}
										
													$macrolist = $macrolist . $macroargs;		
															
															
															
															
															
													$macrolist = $macrolist . '		
                                            		</table>
                                        			</div>
                                    				</div>
                                    				</div>
                                    				</div></div>';
												
					                        	//$dropitem	= $dropitem . '<li><a href="#" class="changereportID" data-reportid="' . $r['wb_roi_report_ID'] . '" >' . $r['roiReportName'] . '</a></li>';
												}
										}
										
										$macrolist = $macrolist . '</div>';
										echo $macrolist;
										
										
									
										
										
										?>
                                    
                                    
                                    <div class="panel-group" id="accordion">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h5 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" class="collapsed">Collapsible Group Item #1</a>
                                            </h5>
                                        </div>
                                        <div id="collapseOne" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                            <div class="panel-body">
                                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="collapsed" aria-expanded="false">Collapsible Group Item #2</a>
                                            </h4>
                                        </div>
                                        <div id="collapseTwo" class="panel-collapse collapse" aria-expanded="false">
                                            <div class="panel-body">
                                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" class="collapsed" aria-expanded="false">Collapsible Group Item #3</a>
                                            </h4>
                                        </div>
                                        <div id="collapseThree" class="panel-collapse collapse" aria-expanded="false">
                                            <div class="panel-body">
                                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <div id="notes" class="tab-pane">
                                <div class="panel-body">
                                    <strong>Donec quam felis</strong>

                                    <p>Thousand unknown plants are noticed by me: when I hear the buzz of the little world among the stalks, and grow familiar with the countless indescribable forms of the insects
                                        and flies, then I feel the presence of the Almighty, who formed us in his own image, and the breath </p>

                                    <p>I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite
                                        sense of mere tranquil existence, that I neglect my talents. I should be incapable of drawing a single stroke at the present moment; and yet.</p>
                                </div>
                            </div>
                        </div>


                    </div>
                                
	</div>
</div>
</section>




<!-- Mainly scripts -->
<script src="../assets/js/jquery-2.1.1.js"></script>
<script src="../assets/js/numeral.js"></script>
<script src="../assets/js/languages.js"></script>
<script src="../assets/js/jquery-calx-2.1.1.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
<script src="../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="../assets/js/inspinia.js"></script>
<script src="../assets/js/plugins/pace/pace.min.js"></script>
<script src="../assets/js/plugins/wow/wow.min.js"></script>

<!-- slick carousel-->
<script src="../assets/js/plugins/slick/slick.min.js"></script>

<!-- Additional style only for demo purpose -->
    <style>
        .slick_demo_2 .ibox-content {
            margin: 0 10px;
        }
        .selected_report {
        	border-color: red;
        }
    </style>
<script>

    $(document).ready(function () {

    $('.calcx').calx({
		
		autoCalculateTrigger	:	'keyup',
		defaultFormat			:	'0,0[.]00',
		onAfterCalculate		:	function() {
			
			


		}
	});  
     
     
     $('.demo-toggle').click(function(){
     	console.log ('try to toggle');
     	var democlass = 'demo-' + $(this).data('democategory');
     	console.log ('class name: ' + democlass);
     	$('.' + democlass).each(function(){
     		$(this).toggleClass('hiddenrow');
     		console.log('id= ' + $(this).attr('id'));
     	});
     	
     });
     
     
     
     
     
     
      
    $('.change_display').click(function(){
    	var displaywidth = $(this).data('displaywidth');
    	$('#iframedemo').width(displaywidth);
    });
    
      
        
	$('.roi_reports').click(function(){
		$('.roi_reports').each(function(){
			$(this).removeClass('selected_report');
		})
		$(this).addClass('selected_report');
	});

	$(function(){
    $('.testscroll').slimScroll({
        height: '450px'
    });
	});

	
	$('.slick_demo_2').slick({
                infinite: false,
                slidesToShow: 3,
                slidesToScroll: 2,
                centerMode: false,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
                            infinite: true,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            });




});
</script>

</body>
</html>
