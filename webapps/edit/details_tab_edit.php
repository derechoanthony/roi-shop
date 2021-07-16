
                                    
    

	<div class="row">
		
	<div class="col-sm-4">
		
	</div>	
		
	<div class="col-sm-12">
	
	<div class="tabs-container" id="tabs-edit-content">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#fields"><i class="fa fa-envelope-o"></i>Fields</a></li>
                <li class=""><a data-toggle="tab" href="#actions"><i class="fa fa-bolt"></i>Actions</a></li>
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
                    	<div class="col-sm-2">
                    		<p>
                    			Select A Field to the left to view more details about it.
                    		<br>
                    		<br>
                    		
                    		
                    		<?php $newfieldlink = "details_edit_newfield.php?wbappID=$wbappID&key=$key";?>
                    		
                    		<a class="btn btn-primary newfield btn-outline" href="<?php  echo $newfieldlink?>"> New Field</a>
                    		</p>
                    		
                    	</div>
                    		<div class="col-sm-10" style="height: 650px; overflow-y: scroll;">
                            <form class="form-horizontal">
                              
                             
                              
                                
                                <table class="table table-hover table-mail">
                <thead>
                	<tr>
                		<th></th>
                		<th>No.</th>
                		<th>Field Name</th>
                		
                		<th>Type</th>
                		<th>Input / Output</th>
                		<th>Formula</th>
                		<th>Cell Name</th>
                		<th>FieldID</th>
                	</tr>
                	
                </thead>
                <tbody>
                
                <?php 
                        
                        $SQL = "SELECT t1.fieldID, t1.shortName, t1.InputType, t1.fieldType, t1.cell, t1.cellcolumn, t1.formula, t2.typeName                      		
                        		FROM wb_roi_fields t1
                        		JOIN wb_fields_input_types t2 ON t2.typeNum=t1.InputType 
                        		WHERE wb_roi_ID=$wbappID
								ORDER BY cellcolumn ASC, cell ASC;";
								
						$list = $g->returnarray($SQL);
                        $listitems = '';
						
						$numrows = count($list);
				        $x = 0;
						
						if($numrows>0){
							foreach($list as $r){
								$x = $x + 1;
								$listitems = $listitems .'<tr class="read"> ';
								$listitems = $listitems .'	<td class=""><a class="btn btn-primary btn-facebook btneditfield" data-toggle="modal" href="#modal-editfield" data-fieldid="' . $r['fieldID'] . '"><i class="fa fa-wrench"></i> Edit</a></td>';
	                        	$listitems = $listitems .'	<td class=""><a data-toggle="modal" href="#modal-editfield">' . $x . '</a></td>';
	                        	$listitems = $listitems .'  <td class="mail-subject"><a href="#" class="ajax_getfielddetails">' . $r['shortName'] . '</a></td>';
								$listitems = $listitems .'  <td class=""><a href="#" class="ajax_getfielddetails">' . $r['typeName'] . '</a></td>';
								$listitems = $listitems .'  <td class=""><a href="#" class="ajax_getfielddetails">' . ($r['fieldType']==1?'Input':'Output') . '</a></td>';
								$listitems = $listitems .'  <td class=""><a href="#" class="ajax_getfielddetails">' . $r['formula'] . '</a></td>';
								$listitems = $listitems .'  <td class=""><a href="#" class="ajax_getfielddetails">' . $r['cellcolumn'] . $r['cell'] . '</a></td>';
								$listitems = $listitems .'  <td class=""><a href="#" class="ajax_getfielddetails">' . $r['fieldID'] . '</a></td>';
	                        	$listitems = $listitems . '</tr>';
								}
						}
						
						
						echo $listitems;	

                        ?>	
                	
                	
                
                </tbody>
                </table>
                                
                                
                                
                            </form>
                        </div>
	            		</div>
	            		
                  
    <?php include 'modal_editfield.php';?>            

	            		
	            		
                    </div>
                </div>
                <div id="actions" class="tab-pane">
                    <div class="panel-body">
                    	
                    	<?php include 'details_tab_edit_actions.php'; ?>
                    	
                    	
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
	
	
	
	

	

