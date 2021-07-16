<div class="panel-body">
                                    
    

	<div class="row">

	<div class="col-sm-12">
	
		<div id="preview-panel">
        <div class="panel panel-default">
        
            <div class="panel-heading">
            	
            	<div class="ibox-tools">
            		
            		<div class="form-group pull-right">

			        <div class="btn-group">
			            <button class="btn btn-white btn-xs" type="button">Demo</button>
			            <button class="btn btn-primary btn-xs" type="button">Active</button>
			        </div>
			        </div>
            		
                    
                </div>
                <?php echo '<strong>' . $g->DLookup('roiName','wb_roi_list','wb_roi_ID=' . $roiID) . '</strong> Preview';?>
            </div>
            <div class="panel-body" id="code-panel">
            
            
            
            <?php $wbappID 		= $_GET['wbappID'];
            		$key 		= $_GET['key'];	
            		$iframesrc	= '../icalc/icalc.php?wbappID=' . $wbappID . '&key=' . $key; 
            		$height		= $g->DLOOKUP('height','wb_roi_settings','wb_roi_ID=' . $wbappID);
            		$width		= $g->DLOOKUP('width','wb_roi_settings','wb_roi_ID=' . $wbappID);
            		
            		?>	
            <iframe width="<?php echo $width;?>px" height="<?php echo $height;?>px" frameborder="0" src="<?php echo $iframesrc ?>"></iframe>	
               
                
            </div>
			
			<div class="panel-footer">
                <a class="btn btn-primary btn-bitbucket viewresult"><i class="fa fa-desktop"></i> Full Screen</a>
                <a class="btn btn-primary btn-bitbucket viewcode"><i class="fa fa-code"></i> View Code</a>
                <a class="btn btn-primary btn-bitbucket"><i class="fa fa-refresh"></i> Reset</a>
            </div>
			
        </div>
        </div>

        
        
    </div>
	
	
	
		
		
	</div>
	

	
	<div class="row">
		<div class="col-lg-8">
			
				
			
			
				<div class="panel panel-success">
                    <div class="panel-heading">
                        <h5>Development Notes </h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="#">Config option 1</a>
                                </li>
                                <li><a href="#">Config option 2</a>
                                </li>
                            </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="panel-body">
                        

                <div class="chat-discussion">

                                    <div class="chat-message left">
                                        <img class="message-avatar" src="img/a1.jpg" alt="">
                                        <div class="message">
                                            <a class="message-author" href="#"> Michael Smith </a>
											<span class="message-date"> Mon Jan 26 2015 - 18:39:23 </span>
                                            <span class="message-content">
											Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
                                            </span>
                                        </div>
                                    </div>
                                    <div class="chat-message right">
                                        <img class="message-avatar" src="img/a4.jpg" alt="">
                                        <div class="message">
                                            <a class="message-author" href="#"> Karl Jordan </a>
                                            <span class="message-date">  Fri Jan 25 2015 - 11:12:36 </span>
                                            <span class="message-content">
											Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover.
                                            </span>
                                        </div>
                                    </div>
                                    <div class="chat-message right">
                                        <img class="message-avatar" src="img/a2.jpg" alt="">
                                        <div class="message">
                                            <a class="message-author" href="#"> Michael Smith </a>
                                            <span class="message-date">  Fri Jan 25 2015 - 11:12:36 </span>
                                            <span class="message-content">
											There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration.
                                            </span>
                                        </div>
                                    </div>
                                    <div class="chat-message left">
                                        <img class="message-avatar" src="img/a5.jpg" alt="">
                                        <div class="message">
                                            <a class="message-author" href="#"> Alice Jordan </a>
                                            <span class="message-date">  Fri Jan 25 2015 - 11:12:36 </span>
                                            <span class="message-content">
											All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet.
                                                It uses a dictionary of over 200 Latin words.
                                            </span>
                                        </div>
                                    </div>
                                    <div class="chat-message right">
                                        <img class="message-avatar" src="img/a6.jpg" alt="">
                                        <div class="message">
                                            <a class="message-author" href="#"> Mark Smith </a>
                                            <span class="message-date">  Fri Jan 25 2015 - 11:12:36 </span>
                                            <span class="message-content">
											All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet.
                                                It uses a dictionary of over 200 Latin words.
                                            </span>
                                        </div>
                                    </div>

                                </div>
        
                    </div>
                </div>
			
			
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Comments </h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="#">Config option 1</a>
                                </li>
                                <li><a href="#">Config option 2</a>
                                </li>
                            </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        
                        
                        
                        
                        
                    </div>
                    
                </div>
            
		</div>
		
		
	</div>

	

</div>