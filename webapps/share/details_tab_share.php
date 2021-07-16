
                                    
    

	<div class="row">
		
	<div class="col-sm-4">
		
	</div>	
		
	<div class="col-sm-12">
	
	<div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#email"><i class="fa fa-envelope-o"></i>Email</a></li>
                <li class=""><a data-toggle="tab" href="#saleslink"><i class="fa fa-link"></i>Sales Links</a></li>
                <li class=""><a data-toggle="tab" href="#embed"><i class="fa fa-paperclip"></i>Embed</a></li>
                <li class=""><a data-toggle="tab" href="#social"><i class="fa fa-facebook-official"></i>Social Media</a></li>
            </ul>
            <div class="tab-content">
                <div id="email" class="tab-pane active">
                    <div class="panel-body">
                        <div class="row">
                    		<div class="col-sm-12">
                    			<h2>Email A Link To This App.</h2>
                    			<hr>
                    		</div>
                    	</div>
                    	<div class="row>">
                    	<div class="col-sm-4">
                    		<p>
                    			You may email a direct link to this app as a stand-alone webpage.  You may also enable or disable social media sharing and comments on 
                    			that stand-alone app page from the Social Media tab above. 
                    		</p>
                    	</div>
                    		<div class="col-sm-6">
                            <form class="form-horizontal">
                                
                                <div class="form-group"><label class="col-lg-2 control-label">Email</label>

                                    <div class="col-lg-10"><input placeholder="Email Address of Recipient" class="form-control" type="email"> 
                                    </div>
                                </div>
                                <div class="form-group"><label class="col-lg-2 control-label">Comments</label>

                                    <div class="col-lg-10"><textarea class="form-control message-input" name="message" placeholder="Enter message text"></textarea></div>
                                </div>
                                   
                                <?php 
                                
                                $status = $g->Dlookup('status','wb_roi_list','wb_roi_ID=' . $wbappID);
                                $dev = '';
								if($status==0){
									//This is a demo site allow development notes
									$dev = '<div class="form-group">';
									$dev = $dev . '<div class="col-lg-offset-2 col-lg-10">';
									$dev = $dev . '<label> <input value="" type="checkbox"> Allow Development Notes </label>';
									$dev = $dev . '</div></div>';
								}
								
								echo $dev;
								
                                ?>
                                
                                
                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <a class="btn btn-success">
				                            <i class="fa fa-email"> </i>  Send Email
				                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
	            		</div>
                    </div>
                </div>
                <div id="embed" class="tab-pane">
                    <div class="panel-body">
                    	
                    	<div class="row">
                    		<div class="col-sm-12">
                    			<h2>Embed This App In Your Website.</h2>
                    			<hr>
                    		</div>
                    	</div>
                    	<div class="row">
                    		<div class="col-sm-4">
                    			<p>
                    			You may embed this app in as many webpages as you wish.  You may also track the effectiveness of each page by adding the pages 
                    			to your list and specifying that webpage for the embed link generator.	
                    			</p>
                    			
                    			
                    		</div>
                    		
                    		<div class="col-sm-8">
                    			<form class="form-horizontal">
                                
                                <div class="form-group"><label class="col-sm-2 control-label">Embed Link</label>

                                    <div class="col-sm-10"><textarea placeholder="Embed Link" class="form-control" type="text"></textarea>
                                    </div>
                                </div>
                                <div class="form-group"><label class="col-sm-2 control-label">Webpage</label>

                                    <div class="col-sm-4">
                                    	<select class="form-control m-b">
                                    		<option>Unspecified</option>
                                    		<option>theROIShop.com</option>
                                    		<option>sandbox.theROIshop.com</option>
                                    	</select>
                                    </div>
                                    
                                    <div class="col-sm-2">
                                    	<a class="btn btn-success">
				                            <i class="fa fa-plus"> </i>  Add New Webpage to monitor
				                        </a>
                                    </div>
                                </div>
                                   
                                
                                
                                
                               
                            </form>
                    			
                    			
                    		</div>
                    		
                    		
                    		
                    	</div>
                    	
                         </div>
                </div>
                
                <div id="saleslink" class="tab-pane">
                    <div class="panel-body">
                        <div class="row">
                    		<div class="col-sm-12">
                    			<h2>Give Your Sales Team Individual Links To This App.</h2>
                    			<hr>
                    		</div>
                    	</div>
                    </div>
                </div>
                
                <div id="social" class="tab-pane">
                    <div class="panel-body">
                        <div class="row">
                    		<div class="col-sm-12">
                    			<h2>Share This App Across Social Media.</h2>
                    			<hr>
                    		</div>
                    	</div>
                    	<div class="row">
                    		<div class="col-sm-4">
                    			<p>
                    			Share a direct link to this app on social media.
                    			</p>
                    			
                    			
                    		</div>
                    		
                    		<div class="col-sm-8">
                    			<form class="form-horizontal">
                                
                                <div class="form-group"><label class="col-sm-2 control-label">Share Link</label>

                                    <div class="col-sm-6"><textarea placeholder="Share Link" class="form-control" type="text"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">

								<div class="text-center">
                        <a class="btn btn-social-icon btn-adn"><span class="fa fa-adn"></span></a>
                        <a class="btn btn-social-icon btn-bitbucket"><span class="fa fa-bitbucket"></span></a>
                        <a class="btn btn-social-icon btn-dropbox"><span class="fa fa-dropbox"></span></a>
                        <a class="btn btn-social-icon btn-facebook"><span class="fa fa-facebook"></span></a>
                        <a class="btn btn-social-icon btn-flickr"><span class="fa fa-flickr"></span></a>
                        <a class="btn btn-social-icon btn-foursquare"><span class="fa fa-foursquare"></span></a>
                        <a class="btn btn-social-icon btn-github"><span class="fa fa-github"></span></a>
                        <a class="btn btn-social-icon btn-google"><span class="fa fa-google"></span></a>
                        <a class="btn btn-social-icon btn-instagram"><span class="fa fa-instagram"></span></a>
                        <a class="btn btn-social-icon btn-linkedin"><span class="fa fa-linkedin"></span></a>
                        <a class="btn btn-social-icon btn-microsoft"><span class="fa fa-windows"></span></a>
                        <a class="btn btn-social-icon btn-openid"><span class="fa fa-openid"></span></a>
                        <a class="btn btn-social-icon btn-pinterest"><span class="fa fa-pinterest"></span></a>
                        <a class="btn btn-social-icon btn-reddit"><span class="fa fa-reddit"></span></a>
                        <a class="btn btn-social-icon btn-soundcloud"><span class="fa fa-soundcloud"></span></a>
                        <a class="btn btn-social-icon btn-tumblr"><span class="fa fa-tumblr"></span></a>
                        <a class="btn btn-social-icon btn-twitter"><span class="fa fa-twitter"></span></a>
                        <a class="btn btn-social-icon btn-vimeo"><span class="fa fa-vimeo-square"></span></a>
                        <a class="btn btn-social-icon btn-vk"><span class="fa fa-vk"></span></a>
                        <a class="btn btn-social-icon btn-yahoo"><span class="fa fa-yahoo"></span></a>
                    </div>

                                    <div class="col-sm-4">
                                    	<a class="btn btn-block btn-social btn-facebook"><span class="fa fa-facebook"></span> Share On Facebook </a>
                                    	<a class="btn btn-block btn-social btn-linkedin"><span class="fa fa-linkedin"></span> Share On LinkedIn</a>
                                    	<a class="btn btn-block btn-social btn-twitter"><span class="fa fa-twitter"></span> Share On Twitter</a>
                                    	<a class="btn btn-block btn-social btn-google"><span class="fa fa-google"></span> Share On Google</a>
                                    </div>
                                    
                                   
                                </div>
                                   
                                
                                
                                
                               
                            </form>
                    			
                    			
                    		</div>
                    		
                    		
                    		
                    	</div>
                    </div>
                </div>
            </div>


        </div>
		

        
        
    </div>
	</div>
	
	
	
	

	

