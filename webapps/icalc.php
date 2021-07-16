
<?php 
	
	require_once 'core/init.php';	//Connect to Database

	require_once 'icalc.class.php';		// Set up a class for the weblet functions
	$icalc = new icalc();
	
	
	//get the ID for the requested ROI
	//get the key corresponding to this ROI
	$wbappID 	= $_GET['wbappID'];
	
	if(isset($_GET['key'])) {
    $wbappkey 	= $_GET['key'];
	}else{$wbappkey=0;}
	
	if(isset($_GET['source'])) {
    $source 	= $_GET['source'];
	}else{$source=0;}
	
	if(isset($_GET['subsource'])) {
    $subsource 	= $_GET['subsource'];
	}else{$subsource=0;}
	
	
	
	//1. Get properties for this roi			
	$roi=$icalc->retrieveRoi($wbappID);
	$status 	= $roi['status'];
	$key	 	= $roi['key'];
	$roiCSS		= $roi['CSS'];
	$roiScripts	= $roi['Scripts'];
	$roihtml 	= $roi['HTML']; 
	
	//add helper divs to html;
	$roihtml = '<body class="calculator-body"><div class="calcx" id="calcx">
				<form role="form" name="roiwebapp" id="roiwebapp">
				<input type="hidden" id="instanceID" value="' . $instanceID . '">' . $roihtml . '
				</form></div></body></html>';
	
	$roiCSS		= '<!DOCTYPE html><html><head> ' . $roiCSS . ' </head>';
	
	$roi		= $roiCSS . $roihtml . $roiScripts;
	
	//2. Create New Instance/Request for this ROI
		//a. Get the IP Address
		$ip = $icalc->get_client_ip();
	
		//b. Add the Request to the instance table
		$icalc->add_ip($wbappID,$ip,$status);

		//c. Set a Sesison Variable for this instance
		$instance = $icalc->get_id($wbappID,$ip);
		$instanceID = $instance[0];

	//3. Depending on case of status display roi or not
		
		switch($status){
			case 0:
				//Compare the key value to the stored key value to see if this request is coming from internal site
				//Need a standard html for demo sites
				echo ($key==$wbappkey ? '<input type="hidden" id="instanceID" value="' . $instanceID . '">' . $roi : "key no match");
			break;
			
			case 1:
				echo '<input type="hidden" id="instanceID" value="' . $instanceID . '">' . $roi;
				
			break;
				
			case 2:
				//Need a standard html for lapsed calculators
				echo 'lapsed status';
			break;
			
			default:
				//go ahead and display
				echo $roihtml;
		}
		 
?>
            
