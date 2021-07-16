<div id="modal-uploadimage" class="modal fade" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12"><h3 class="m-t-none m-b">Upload An Image</h3>

                            <p>Upload an image to use in this ExpressROI.</p>

                            <form role="form" action="ajax_uploadimage.php" method="post" enctype="multipart/form-data">
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
                                <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Select file</span><span class="fileinput-exists">Change</span>
                                <input type="file" name="fileToUpload"></span>
                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                            </div>
                            
                            
                            
                                <input type="hidden" name="wbappID" class="form-control" value="<?php echo $roiID;?>">
                                <input type="hidden" name="key" class="form-control" value="<?php echo $key;?>">
                                <div class="form-group"><label>FileName</label> <input type="text" name="filename" placeholder="Enter Filename To Use" class="form-control"></div>
                                <div class="form-group"><label>Description</label> <input type="text" name="description" placeholder="Save A Description About This Image" class="form-control"></div>
                                
                                
                                <div>
                                    <button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong>Upload</strong></button>
                                    
                                </div>
                            </form>
                        </div>
                        
                </div>
            </div>
            </div>
        </div>
</div>
