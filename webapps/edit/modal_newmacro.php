<div id="modal-newmacro" class="modal fade" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 "><h3 class="m-t-none m-b">Add A New Macro</h3>

                            <p>Select A Macro To Get Started.</p>

                            <form role="form">
                            	<div class="row">
                                <div class="form-group col-sm-12"><label>Name This Macro</label> <input type="text" placeholder="Enter Field Name" class="form-control fieldctl" name="macroname" id="macroname"></div>
                       
                                </div>
                                
                                
                                
                                <?php 
										$SQL = "SELECT * 
												FROM `wb_roi_macros` t1
												ORDER BY macroID ASC";
										
										
										$list = $g->returnarray($SQL);
				                        
										
										$numrows = count($list);
								        $x = 0;
										$macrolist = '';									
										if($numrows>0){
											foreach($list as $r){
												$x = $x + 1;
												$macrolist = $macrolist . '<option value="' . $r['macroID'] . '">'. $r['macroName'] . '</option>';
											}
										}
										
									?>
                                
                                
                                
                                <div class="row">
                                <div class="form-group col-sm-12">
                                	<label>Macro To Run</label> <select class="form-control m-b fieldctl" name="macroid" id="macroid">
                                        <?php echo $macrolist; ?>
                                    </select>
                                </div>
                                </div>
                                

                             </form>   
                                
                            
                                
                                  
                                
                                 
                                
                                <div>
                                    <button class="btn btn-sm btn-primary pull-right m-t-n-xs newmacro" type="button"><strong>Save</strong></button>
                                   
                                </div>
                            </form>
                        </div>
                        </div>
            </div>
            </div>
        </div>
</div>
