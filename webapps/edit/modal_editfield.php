<div id="modal-editfield" class="modal fade" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 "><h3 class="m-t-none m-b">Edit Field</h3>

                            <p>Tell us about this field.</p>

                            <form role="form">
                            	<div class="row">
                                <div class="form-group col-sm-7"><label>Field Name</label> <input type="text" placeholder="Enter Field Name" class="form-control fieldctl" name="Label"></div>
                                <div class="form-group col-sm-5"><label>Short Name</label> <input type="text" placeholder="Enter Short Alias" class="form-control fieldctl" name="shortName"></div>
                                </div>
                                
                                <div class="row">
                                <div class="form-group col-sm-2"><input type="text" placeholder="Column" class="form-control fieldctl" name="cellcolumn"> </div>
                                <div class="form-group col-sm-2"><input type="text" placeholder="Row" class="form-control fieldctl" name="cell"> </div>
                                <div class="form-group col-sm-3"><button class="btn btn-info" type="submit">Get Next Row</button> </div>
                                </div>
                                
                                <div class="row">
                                <div class="form-group col-sm-12">
                                	<label>Field Type</label> <select class="form-control m-b fieldctl" name="InputType">
                                        <option value="1">Text</option>
                                        <option value="2">Number</option>
                                        <option value="3">Lookup</option>
                                        <option value="4">Yes/No</option>
                                        <option value="100">Email</option>
                                    </select>
                                </div>
                                </div>
                                
                                <div class="row">
								<div class="form-group col-sm-4"><label>Caclulation Type</label><select class="form-control m-b fieldctl" name="fieldType">
                                        <option value="1">User Input</option>
                                        <option value="2">Calculation</option>                                       
                                    </select> </div>
								<div class="form-group col-sm-8"><label>Formula</label> <input type="text" placeholder="Enter Placeholder" class="form-control fieldctl" name="formula"></div>
								</div>
                                
                                
                                <div class="row">
                                <div class="form-group col-sm-12"><label> <input type="checkbox" class="i-checks fieldctl" name="required">Is Required </label></div>
                                </div>
                                
                                
                                <div class="row">
                                <div class="form-group col-sm-4"><label>Format</label>
                                	<select class="form-control m-b" name="format">
                                        <option>Number</option>
                                        <option>Currency</option>
                                        <option>Percentage</option>
                                        <option>Date</option>
                                        <option>General</option>
                                        <option>Number</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-4"><label>Decimals</label><input type="text" placeholder="Num Decimals" class="form-control"> </div>
                                <div class="form-group col-sm-4"><label>Units</label> <input type="text" placeholder="Enter Units" class="form-control"></div> 
								</div>
								
								<div class="row">
								<div class="form-group col-sm-12"><label>Placeholder</label> <input type="text" placeholder="Enter Placeholder" class="form-control fieldctl" name="placeholder"></div>
								</div>
								
								<div class="row">
								<div class="form-group col-sm-12"><label>Demo Value</label> <input type="text" placeholder="Enter Demo Value" class="form-control fieldctl" name="demovalue"></div>
								</div>
								
                             </form>   
                                
                            
                                
                                  
                                
                                 
                                
                                <div>
                                    <button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong>Save</strong></button>
                                   
                                </div>
                            </form>
                        </div>
                        </div>
            </div>
            </div>
        </div>
</div>
