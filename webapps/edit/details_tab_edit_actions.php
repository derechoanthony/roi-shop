

                
                    
                        <div class="row">
                    		<div class="col-sm-12">
                    			<h2>Modify the Actions Used in This Project.</h2>
                    			<hr>
                    		</div>
                    	</div>
                    	<div class="row>">
                    	<div class="col-sm-12">
                    		<p>
                    			Select An Action to edit details about it, or create a <a class="btn btn-primary " data-toggle="modal" href="#modal-newmacro"><i class="fa fa-plus"></i>&nbsp;New Macro</a>
                    		</p>
                    		
                    		
                    		
                                    <h4>The following actions are used in this ROI:</h4>
                                    
                                    
                                     <?php 
						
										
										
										$SQL = "SELECT * , 
													(SELECT wb_roi_ID 
													FROM `wb_roi_reports` t2 
													WHERE t2.wb_roi_report_ID=t1.reportID) wbroiID
                                                
												FROM `wb_roi_reports_macros` t1
                                                JOIN `wb_roi_macros` t3
                                                ON t3.macroID=t1.macroID
												HAVING wbroiID=$wbappID OR wbappID=$wbappID
												ORDER BY reportID ASC, elementID ASC";
										
										
										$list = $g->returnarray($SQL);
				                        
										
										$numrows = count($list);
								        $x = 0;
										$macrolist = '<div class="panel-group" id="macrolist">';									
										if($numrows>0){
											foreach($list as $r){
												$x = $x + 1;
												$btncode = highlight_string('<button type="button" class="btn btn-w-m roimacro" data-elemid="' . $r['elementID'] . '">');
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
															<div class="form-group col-sm-12"><label>Button Code: </label><input type="text" class="form-control fieldctl value="'. $btncode .'"></div>
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
														$macroargsinput = '<td><input type="text" class="form-control fieldctl macroval" data-aurgid="'. $s['aurgID'] .'" value="' . $s['varValue'] . '"></td>';
														break;
														
														case 10:
														//This is a select with the reports in this ROI project
														$macroargsinput = '<td><select class="form-control m-b macroval" data-aurgid="'. $s['aurgID'] .'" name="format">';
					                                        
															
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
														
														case 11:
														//This is a select with the PDF Reports in this ROI project
														$macroargsinput = '<td><select class="form-control m-b macroval" data-aurgid="'. $s['aurgID'] .'" name="format">';
					                                        
															
															$SQL2 = "SELECT *            
																	FROM `wb_roi_reports` t1
					                                                WHERE t1.wb_roi_ID=$wbappID AND roiReportType=1
																	ORDER BY t1.isprimary DESC, roiReportName ASC";
															
															
															$list2 = $g->returnarray($SQL2);
									                        
															
															$numrows2 = count($list1);
								        
														if ($t['wb_roi_report_ID']==0){$selected='selected';}else{$selected='';}	
														$macroargsinput = $macroargsinput . '<option value="0" ' . $selected . '>None Selected </option>';	
																			
														if($numrows2>0){
															foreach($list2 as $t){
																if ($t['wb_roi_report_ID']==$s['varValue']){$selected='selected';}else{$selected='';}
																$macroargsinput = $macroargsinput . '<option value="' . $t['wb_roi_report_ID'] .'" ' . $selected . '>' . $t['roiReportName'] .  '</option>';
															}}
									                         
					                                        
					                                    $macroargsinput = $macroargsinput . '</select></td>';
														
														
														break;
														
														case 12:
														//This is a select with the Modal Forms in this ROI project
														$macroargsinput = '<td><select class="form-control m-b macroval" data-aurgid="'. $s['aurgID'] .'" name="format">';
					                                        
															
															$SQL2 = "SELECT *            
																	FROM `wb_roi_reports` t1
					                                                WHERE t1.wb_roi_ID=$wbappID AND roiReportType=4
																	ORDER BY t1.isprimary DESC, roiReportName ASC";
															
															
															$list2 = $g->returnarray($SQL2);
									                        
															
															$numrows2 = count($list1);
								        
														if ($t['wb_roi_report_ID']==0){$selected='selected';}else{$selected='';}	
														$macroargsinput = $macroargsinput . '<option value="0" ' . $selected . '>None Selected </option>';	
														
																			
														if($numrows2>0){
															foreach($list2 as $t){
																if ($t['wb_roi_report_ID']==$s['varValue']){$selected='selected';}else{$selected='';}
																$macroargsinput = $macroargsinput . '<option value="' . $t['wb_roi_report_ID'] .'" ' . $selected . '>' . $t['roiReportName'] .  '</option>';
															}}
									                         
					                                        
					                                    $macroargsinput = $macroargsinput . '</select></td>';
														
														
														break;
														
														case 13:
														//This is a select with the Email Forms in this ROI project
														$macroargsinput = '<td><select class="form-control m-b macroval" data-aurgid="'. $s['aurgID'] .'" name="format">';
					                                        
															
															$SQL2 = "SELECT *            
																	FROM `wb_roi_reports` t1
					                                                WHERE t1.wb_roi_ID=$wbappID AND roiReportType=3
																	ORDER BY t1.isprimary DESC, roiReportName ASC";
															
															
															$list2 = $g->returnarray($SQL2);
									                        
															
															$numrows2 = count($list1);
								        
														if ($t['wb_roi_report_ID']==0){$selected='selected';}else{$selected='';}	
														$macroargsinput = $macroargsinput . '<option value="0" ' . $selected . '>None Selected </option>';	
														
																			
														if($numrows2>0){
															foreach($list2 as $t){
																if ($t['wb_roi_report_ID']==$s['varValue']){$selected='selected';}else{$selected='';}
																$macroargsinput = $macroargsinput . '<option value="' . $t['wb_roi_report_ID'] .'" ' . $selected . '>' . $t['roiReportName'] .  '</option>';
															}}
									                         
					                                        
					                                    $macroargsinput = $macroargsinput . '</select></td>';
														
														
														break;
														
														
														
														case 20:
														//This is a select with the fields in this ROI project
														$macroargsinput = '<td><select class="form-control m-b macroval" data-aurgid="'. $s['aurgID'] .'" name="format">';
					                                        
															
															$SQL2 = "SELECT *            
																	FROM `wb_roi_fields` t1
					                                                WHERE t1.wb_roi_ID=$wbappID
																	ORDER BY t1.fieldType ASC, t1.cellcolumn ASC, t1.cell ASC";
															
															
															$list2 = $g->returnarray($SQL2);
									                        
															
															$numrows2 = count($list1);
								        				
														if ($t['wb_roi_report_ID']==0){$selected='selected';}else{$selected='';}	
														$macroargsinput = $macroargsinput . '<option value="0" ' . $selected . '>None Selected </option>';	
														
																			
														if($numrows2>0){
															foreach($list2 as $t){
																if ($t['fieldID']==$s['varValue']){$selected='selected';}else{$selected='';}
																$macroargsinput = $macroargsinput . '<option value="' . $t['fieldID'] .'" ' . $selected . '>' . $t['shortName'] .  '</option>';
															}}
									                         
					                                        
					                                    $macroargsinput = $macroargsinput . '</select></td>';
														break;
														
														case 30:
														//This is a select with given values
														$macroargsinput = '<td><select class="form-control m-b macroval" data-aurgid="'. $s['aurgID'] .'" name="format">';
					                                        
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
														$macroargsinput = '<td><select class="form-control m-b macroval" data-aurgid="'. $s['aurgID'] .'" name="format">';
														if ($s['varValue']==0){$noselected='selected';$yesselected='';}else{$noselected='';$yesselected='selected';}
														$macroargsinput = $macroargsinput . '<option value="0" ' . $noselected . '>NO</option>';
														$macroargsinput = $macroargsinput . '<option value="1" ' . $yesselected . '>YES</option>';
														$macroargsinput = $macroargsinput . '</select></td>';
														break;
														
														case 50:
														//This is a select of Marketo Connections
														$macroargsinput = '<td><select class="form-control m-b macroval" data-aurgid="'. $s['aurgID'] .'" name="format">';
					                                        
															
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
															 $macroargsinput = '<td><input type="text" class="form-control fieldctl macroval" data-aurgid="'. $s['aurgID'] .'" value="' . $s['varValue'] . '"></td>';
														
														
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
                                    
                                    
                                    
                                
                    		
                    		
                    		
                    	</div>
                    		<div class="col-sm-8">
                            <form class="form-horizontal">
                                
                                
                                
                                
                                
                            </form>
                        </div>
	            		</div>
                   
                
	<?php include 'modal_newmacro.php';?>
	
	

	

