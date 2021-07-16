

   <style type="text/css">
   	
   	.CodeMirror {
		  border: 1px solid #eee;
		  height: 700px;
		}
   	
   </style>
   
                
                    
                        <div class="row">
                    		<div class="col-sm-12">
                    			<h2>Modify the Layout of Reports Used in This App.</h2>
                    			<hr>
                    		</div>
                    	</div>
                    	<div class="row>">
                    	<!--<div class="col-sm-2">
                    		<p>
                    			Select A Report to edit details about it.
                    		</p>
                    		<ul class="folder-list " style="padding: 0">-->
                    			
                    			<?php 
                    			 $SQL = "SELECT *
                        		FROM wb_roi_reports t1
                        		WHERE wb_roi_ID=$wbappID
								ORDER BY t1.isprimary DESC, t1.roiReportName ASC;";
								
								$list = $g->returnarray($SQL);
		                        $listitems 	= '';
								$dropitem	= '';
								
								$numrows = count($list);
						        $x = 0;
								
								$primaryid 	= $list[0]['wb_roi_report_ID'];
								$reportType = $list[0]['roiReportType'];
								$reportName	= $list[0]['roiReportName'];
								
								if($numrows>0){
									foreach($list as $r){
										$x = $x + 1;
										//$listitems 	= $listitems .'<li class="m-t-sm"><a class="btn btn-block btn-default getreport" data-reportID="' . $r['wb_roi_report_ID'] . '" href="#">' . $r['roiReportName'] . '</a></li> ';
			                        	$dropitem	= $dropitem . '<li><a href="#" class="changereportID" data-reportid="' . $r['wb_roi_report_ID'] . '" >' . $r['roiReportName'] . '</a></li>';
										}
								}
								
								
								//echo $listitems;
                    			//echo '<input type="text" id="selectedreport" value="' . $primaryid . '"/>';
                    			$selectedreport = $primaryid;
								
								//Get all of the archives for this report
								$SQL = "SELECT *
                        		FROM wb_roi_reports_archives t1
                        		WHERE wb_roi_report_ID=$selectedreport
								ORDER BY t1.dateCreated DESC, t1.archiveName ASC;";
								
								$list = $g->returnarray($SQL);
		                        $archiveitems 	= '';
								
								$numrows = count($list);
						        $x = 0;
								
													
								if($numrows>0){
									$archiveitems	= $archiveitems . '<li class="divider"></li>';
									foreach($list as $r){
										$x = $x + 1;
										$archiveitems	= $archiveitems . '<li><a class="getarchive" data-archiveid="' . $r['archiveID'] . '" >' . $r['archiveName'] . ' (' . $g->shortdate($r['dateCreated']) . ')</a></li>';
										}
									//$archiveitems	= $archiveitems . '</div>';
								}
								
								
								
                    			?>
                    			
					            
					            <!--<li class="m-t-sm"><a class="btn btn-block btn-primary newreport" href="#">New Report</a></li>
					        </ul>
                    	</div>-->
                    		<div class="col-sm-12">
                            <form class="form-horizontal">
                                <div class="row">
                                	<div class="col-sm-12">
                                		<!-- Toolbar Row  -->
                                		<p>
                                			<div class="btn-group">
					                            <button data-toggle="dropdown" class="btn btn-white dropdown-toggle"> <i class="fa fa-file-text-o "></i> ROI Reports <span class="caret"></span></button>
	                                			<ul class="dropdown-menu">
					                                <?php echo $dropitem; ?>
					                                <li class="divider"></li>
				                                	<li><a data-toggle="modal" href="#modal-newreport">New Report</a></li>
					                            </ul>
					                        </div>
                                			
                                			<a data-toggle="modal" href="#modal-settings" class="btn btn-white btn-bitbucket savehtmlcode"><i class="fa fa-cog"></i> Report Settings</a>
                                			
                                			<a class="btn btn-white btn-bitbucket savehtmlcode"><i class="fa fa-save"></i> Save</a>
                                			
                                			<div class="btn-group">
                                			<button data-toggle="dropdown" class="btn btn-white dropdown-toggle"> <i class="fa fa-folder-open "></i> Archive <span class="caret"></span></button>
                                			<ul class="dropdown-menu" id="archivelist">
				                                <li><a data-toggle="modal" href="#modal-savearchive">Save to Archive</a></li>
				                                
				                                <?php echo $archiveitems; ?>
				                                
				                            </ul>
				                            </div>
				                            
				                            <div class="btn-group">
                                			<button data-toggle="dropdown" class="btn btn-white dropdown-toggle"> <i class="fa fa-files-o "></i> Assets <span class="caret"></span></button>
                                			<ul class="dropdown-menu">
				                                <li><a data-toggle="modal" href="#modal-uploadimage">Upload Image</a></li>
				                                <li><a href="#" data-toggle="modal" href="#modal-uploadcss">Upload CSS</a></li>
				                                <li><a href="#" data-toggle="modal" href="#modal-uploadfont">Upload Font</a></li>
				                                <li class="divider"></li>
				                                <li><a href="#" data-toggle="modal" href="#modal-insertfield">Insert Field</a></li>
				                                <li><a href="#">Insert Image</a></li>
				                                <li><a href="#">Insert CSS</a></li>
				                            </ul>
				                            </div>
				                            
				                            <a class="btn btn-white btn-bitbucket preview"><i class="fa fa-eye "></i> Preview</a>
				                            
				                            <div data-toggle="buttons-checkbox" class="btn-group">
					                            <button class="btn btn-info toggle-view active" type="button" aria-pressed="true" data-type="css"> CSS</button>
					                            <button class="btn btn-info toggle-view active" type="button" aria-pressed="true" data-type="html">HTML</button>
					                            <button class="btn btn-info toggle-view active" type="button" aria-pressed="true" data-type="script"> Scripts</button>
					                        </div>
					                        
                                		</p>
                                		
                                	</div>
                                </div>
                                
                                <div id="reportspecs">
                                	<h4>Editing <strong><?php echo $reportName;?></strong></h4>
                                	<?php 
			                        echo '<input type="hidden" id="selectedreport" value="' . $selectedreport . '">';
									echo '<input type="hidden" id="reportType" value="' . $reportType . '">';
									?>
                                </div>
                                
                                <div class="row">
                                	
                                	<div class="col-sm-12" id="htmlfields" style="display: table; table-layout:fixed; overflow-y:auto; width: 100%; max-width: 100%;">
                                	
                                		
                                	<div class="css" style="display: table-cell; width: 30%; height: 700px; overflow-y: auto;">
                                		<strong>CSS</strong>
                                		<?php 
                                		
		                                		
		                                		$code			= $g->DLookup('CSS','wb_roi_reports','wb_roi_report_ID=' . $selectedreport);
												$addcodeview 	= '<form name="updatecode"> <textarea id="csshtml" class="CodeMirror css">' . $code . '</textarea></form>';
		                                		
		                                		echo $addcodeview;
		                                		?>
                                		
                                		
                                	</div>
                                	
                                	<div class="html" style="display: table-cell;  height: 700px; overflow-y: auto;">
                                		<strong>HTML</strong>
                                		<?php 
                                		
		                                		
		                                		$code			= $g->DLookup('HTML','wb_roi_reports','wb_roi_report_ID=' . $selectedreport);
												$addcodeview 	= '<form name="updatecode"> <textarea id="html" name="htmlcode">' . $code . '</textarea></form>';
		                                		
		                                		echo $addcodeview;
		                                		?>
                                		
                                		
                                	</div>
                                	
                                	<div class="script" style="display: table-cell;  height: 700px; overflow-y: auto;">
                                		<strong>Javascript</strong>
                                		<?php 
                                		
		                                		
		                                		$code			= $g->DLookup('Scripts','wb_roi_reports','wb_roi_report_ID=' . $selectedreport);
												$addcodeview 	= '<form name="updatecode"> <textarea id="script" name="htmlcode">' . $code . '</textarea></form>';
		                                		
		                                		echo $addcodeview;
		                                		?>
                                		
                                		
                                	</div>
                                	
                                	
                                </div>	
                                </div>
                                
                                
                                
                                
                                
                            </form>
                        </div>
	            		</div>
                  
                  <div id="modalholder"></div>
                  
    <?php include 'modal_newreport.php';?>            
	<?php include 'modal_settings.php';?>
	<?php include 'modal_savearchive.php';?>
	<?php include 'modal_uploadimage.php';?>
	
	

	

