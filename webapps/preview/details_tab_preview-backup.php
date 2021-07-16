<div class="panel-body">
                                    
    

	<div class="row">

	<div class="col-lg-8">
	
		
        <div class="panel panel-default">
        
        <!--<?php 
            
            $flexible = DLookup('flexible','wb_roi_settings','wb_roi_ID=' . $roiID);
            
            if($flexible==0) {
            	$style = 'style="min-width:100%; min-height:100%; max-width:100%; max-height:100%;' .
            					 
            					'width:' . DLookup('width','wb_roi_settings','wb_roi_ID=' . $roiID) . 'px;"';
            	echo $style;
			} 
            
            ?>-->
		
		
        
            <div class="panel-heading">
            	
            	<div class="ibox-tools">
            		
            		<div class="form-group pull-right">

			        <div class="btn-group">
			            <button class="btn btn-white btn-xs" type="button">Demo</button>
			            <button class="btn btn-primary btn-xs" type="button">Active</button>
			        </div>
			        </div>
            		
                    
                </div>
            	
            	
			            	
            	
                <?php echo '<strong>' . DLookup('roiName','wb_roi_list','wb_roi_ID=' . $roiID) . '</strong> Preview';?>
            </div>
            <div class="panel-body">
            
            <!--<?php 
            
            $flexible = DLookup('flexible','wb_roi_settings','wb_roi_ID=' . $roiID);
            
            if($flexible==0) {
            	$style = 'style="min-width:100%; min-height:100%; max-width:100%; max-height:100%;
            					height:' . DLookup('height','wb_roi_settings','wb_roi_ID=' . $roiID) . 'px;' . 
            					'width:' . DLookup('width','wb_roi_settings','wb_roi_ID=' . $roiID) . 'px;"';
            	echo $style;
			} 
            
            ?>-->
		
		
            
            <!-- If the webapp is static dimensions, need to set the preview container to those dimensions 
            
            <div 
		
		<?php 
            
            $flexible = DLookup('flexible','wb_roi_settings','wb_roi_ID=' . $roiID);
            
            if($flexible==0) {
            	$style = 'style="height:' . DLookup('height','wb_roi_settings','wb_roi_ID=' . $roiID) . 'px;' . 
            					'width:' . DLookup('width','wb_roi_settings','wb_roi_ID=' . $roiID) . 'px;"';
            	echo $style;
			} 
            
            ?>
		
		>-->
            
            <?php $wbappID 		= $_GET['wbappID'];
            		$key 		= $_GET['key'];	
            		$iframesrc	= 'icalc/icalc.php?wbappID=' . $wbappID . '&key=' . $key ?>	
            <!--<iframe width="100%" height="100%" frameborder="0" src="<?php echo $iframesrc ?>"></iframe>-->	
            <!--<?php include 'icalc/icalc.php'; ?>-->
            
            <!--<?php echo DLookup('roiHTML','wb_roi_list','wb_roi_ID=' . $roiID);?>-->
                
            <!--</div>-->    
                
            </div>
			
			<div class="panel-footer">
                <a class="btn btn-primary btn-bitbucket"><i class="fa fa-desktop"></i> Full Screen</a>
                <a class="btn btn-primary btn-bitbucket"><i class="fa fa-code"></i> View Code</a>
                <a class="btn btn-primary btn-bitbucket"><i class="fa fa-refresh"></i> Reset</a>
            </div>
			
        </div>
        
        
        
        
    </div>
	
	
	<div class="col-lg-4">
		
        
        <h2>Share</h2>
        <hr>
        
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#email"><i class="fa fa-envelope-o"></i>Email</a></li>
                <li class=""><a data-toggle="tab" href="#embed"><i class="fa fa-paperclip"></i>Embed</a></li>
                <li class=""><a data-toggle="tab" href="#social"><i class="fa fa-facebook-official"></i>Social Media</a></li>
            </ul>
            <div class="tab-content">
                <div id="email" class="tab-pane active">
                    <div class="panel-body">
                        
                            <form class="form-horizontal">
                                <p>Send a link to this WebApp.</p>
                                <div class="form-group"><label class="col-lg-2 control-label">Email</label>

                                    <div class="col-lg-10"><input placeholder="Email" class="form-control" type="email"> 
                                    </div>
                                </div>
                                <div class="form-group"><label class="col-lg-2 control-label">Comments</label>

                                    <div class="col-lg-10"><textarea class="form-control message-input" name="message" placeholder="Enter message text"></textarea></div>
                                </div>
                                   
                                
                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <label> <input value="" type="checkbox"> Allow Development Notes </label>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <button class="btn btn-sm btn-success" type="submit">Send</button>
                                    </div>
                                </div>
                            </form>
                        
	            
                    </div>
                </div>
                <div id="embed" class="tab-pane">
                    <div class="panel-body">
                        <strong>Donec quam felis</strong>

                        <p>Thousand unknown plants are noticed by me: when I hear the buzz of the little world among the stalks, and grow familiar with the countless indescribable forms of the insects
                            and flies, then I feel the presence of the Almighty, who formed us in his own image, and the breath </p>

                        <p>I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite
                            sense of mere tranquil existence, that I neglect my talents. I should be incapable of drawing a single stroke at the present moment; and yet.</p>
                    </div>
                </div>
                <div id="social" class="tab-pane">
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
	
	<div class="row"></div>
	
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