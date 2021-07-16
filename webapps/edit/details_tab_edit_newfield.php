
                                    
    

	<div class="row">
		
	<div class="col-sm-4">
		
	</div>	
		
	<div class="col-sm-12">
	
	<div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#fields"><i class="fa fa-envelope-o"></i>Fields</a></li>
                <li class=""><a data-toggle="tab" href="#reports"><i class="fa fa-link"></i>Reports</a></li>
                <li class=""><a data-toggle="tab" href="#misc"><i class="fa fa-paperclip"></i>Miscellaneous</a></li>
                
            </ul>
            <div class="tab-content">
                <div id="fields" class="tab-pane active">
                    <div class="panel-body">
                        <div class="row">
                    		<div class="col-sm-12">
                    			<h2>Modify the Fields Used In This App</h2>
                    			<hr>
                    		</div>
                    	</div>
                    	<div class="row>">
                    	<div class="col-sm-4">
                    		<p>
                    			Tell us something about this field.
                    		<br>
                    		<br>
                    		
                    		
                    		<?php $newfieldlink = "details_edit.php?wbappID=$wbappID&key=$key";?>
                    		
                    		<a class="btn btn-primary" href="<?php  echo $newfieldlink?>"> Back to List</a>
                    		</p>
                    		
                    	</div>
                    		<div class="col-sm-8">
                            
                              
                             <form class="form-horizontal" id="newfield">
                                
                                <div class="form-group"><label class="col-lg-2 control-label">Field Name</label>

                                    <div class="col-lg-10"><input type="text" placeholder="Field Name" class="form-control"> <span class="help-block m-b-none">A short name you can use to identify this field easily</span>
                                    </div>
                                </div>
                                <div class="form-group"><label class="col-lg-2 control-label">Label</label>

                                    <div class="col-lg-10"><input type="text" placeholder="Field Label" class="form-control"><span class="help-block m-b-none">What text would you like users to see on the calculator for this field?</span></div>
                                </div>
                                
                                <div class="form-group"><label class="col-sm-2 control-label">Calculated Field</label>

                                    	<div class="col-sm-10"><label class="checkbox-inline i-checks"> <input type="checkbox" value="option1"> </label></div>
                                </div>
                                
                                <div class="form-group"><label class="col-sm-2 control-label">Field Type</label>

                                    <div class="col-sm-10"><select class="form-control m-b" name="type">
                                        <option value="1">Text</option>
                                        <option value="2">Number</option>
                                        <option value="3">Lookup</option>
                                        
                                    </select>

                                        
                                    </div>
                                </div>
                                
                                <div class="col-sm-4 col-sm-offset-2">
                                    
                                    <button class="btn btn-primary savefield" type="submit">Save changes</button>
                                </div>
                                
                            </form>
                              
                                
                                
                                
                                
                                
                            
                        </div>
	            		</div>
                    </div>
                </div>
                <div id="reports" class="tab-pane">
                    <div class="panel-body">
                    	
                    	<?php include 'details_tab_edit_reports.php'; ?>
                    	
                    	
                         </div>
                </div>
                
                <div id="misc" class="tab-pane">
                    <div class="panel-body">
                        <?php include 'details_tab_edit_misc.php'; ?>
                    </div>
                </div>
                
                
            </div>


        </div>
		

        
        
    </div>
	</div>
	
	
	
	

	

