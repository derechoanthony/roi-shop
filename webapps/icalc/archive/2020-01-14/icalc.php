
<?php 
	
	//**************************************************************************************
	//Set up required references
	
	require_once '../core/init.php';	//Connect to Database
	require_once 'icalc.class.php';		// Set up a class for the weblet functions
	$icalc = new icalc();
	
	//****************************************************************************************
	
	// Get URL Variables
	
	//A. get the ID for the requested ROI
	$wbappID 	= $_GET['wbappID'];

	//A-1. if there is a report other than default that is requested get the id
	if(isset($_GET['r'])) {
    $reportID 	= $_GET['r'];
	}else{$reportID=0;}
	
	
	//A-1. if there is a report other than default that is requested get the id
	if(isset($_GET['d'])) {
    $demoType 	= $_GET['d'];
	}

	
	//B. get the key corresponding to this ROI
	if(isset($_GET['key'])) {
    $wbappkey 	= $_GET['key'];
	}else{$wbappkey=0;}
	
	
	//The next two variables will be used for 
	//analytics of method of iframe retreival
	
	//C. Get the Source variable
	if(isset($_GET['source'])) {
    $source 	= $_GET['source'];
	}else{$source=0;}
	
	//D. Get the subSource variable
	if(isset($_GET['subsource'])) {
    $subsource 	= $_GET['subsource'];
	}else{$subsource=0;}

	//E. Determine if this is a mobile view
	function isMobile() {
		return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	}
	
	if(isMobile()){
		$Mobileredirect=$icalc->getMobileRedirect($reportID);
		$reportID = ($Mobileredirect == 0) ? $reportID : $Mobileredirect;
	}



	//****************************************************************************************
	
	//0. Retrueve properties about this ROI
	if($reportID==0){
		$roi=$icalc->retrieveRoi($wbappID);															//OK for PDO
	} else {
		$roi=$icalc->retrieveRoiSpecificReport($wbappID,$reportID);															//OK for PDO
	}
																
	
	if(isset($_GET['status'])) {
    $status 	= $_GET['status'];
	}else{$status=$roi['status'];}
	
	
	$key	 		= $roi['key'];
	$roiCSS			= $roi['CSS'];
	$roiScripts		= $roi['Scripts'];
	$roihtml 		= $roi['HTML']; 
	$roiPreScripts 	= $roi['preScripts']; 
	


	
	//****************************************************************************************
	
	//1. Log the instance request
	
		//A.  Get a unique timestamp
		$dt = time();
		
		//B. Get the IP Address
		$ip = $icalc->get_client_ip();								  								//PDO OK
			
		//C. Add the Request to the instance table
		//   Return the last inserted row
		//   This becomes the instanceID
		$instanceID = $icalc->add_ip($wbappID,$ip,$status,$dt);										//PDO OK
	
	//****************************************************************************************
	
	//2. Add Helper divs to stored code
	
	$roihtml = '<body class="calculator-body">
				<div class="calcx" id="calcx">
				<form role="form" name="roiwebapp" id="roiwebapp">
				<input type="hidden" data-cell="INST1" id="instanceID" value="' . $instanceID . '">
				<input type="hidden" data-cell="RPT1" id="reportID" value="' . $reportID . '">
				<input type="hidden" data-cell="WBID1" id="wbroiID1" value="' . $wbappID . '">
				<input type="hidden" id="fieldedits" value="0">
				<div id="modalholder"></div>
                ' . $roihtml . '
				</form></div>
				' . $roiPreScripts . '
				</body></html>';
	
	$roiCSS	= '<!DOCTYPE html><html><head> 
				<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
				<meta content="utf-8" http-equiv="encoding">' . $roiCSS . ' </head>';
	
	$roi	= $roiCSS . $roihtml . $roiScripts . '</html>';
	
	
	//2a. Replace any tags in the roi that are placeholders for formulas and labels, etc
	//    Loop through report and get contents of inputs and outputs
	
	//   Get a list of fields for this instance 
	$list = $icalc->get_roi_fields($wbappID);															//PDO OK

	$numrows = count($list);
	$x=0;
	if($numrows>0){
	  foreach($list as $r){
		$x = $x + 1;	
		$roi = str_replace('<formulatag>' . $r['fieldID'] . '</formulatag>', $r['formula'], $roi);
		$roi = str_replace('<formattag>' . $r['fieldID'] . '</formattag>', $r['formatstring'], $roi);
	  }
	}
	
	//Add time to js files to ensure no caching is done by browser
	$roi = str_replace('<timetag></timetag>', time(), $roi);
	
	//  End Replacing strings in ROI
	

	//****************************************************************************************
	

	//3. Depending on case of status display roi or not
		
		switch($status){
			case 0:
				//Under Construction
				//Compare the key value to the stored key value to see if this request is coming from internal site
				
				//echo ($key==$wbappkey ? $roi : "key no match");
				echo '<input type="hidden" id="instanceID" value="' . $instanceID . '"> 
				      <input type="hidden" id="roicalcID" value="' . $wbappID . '">
				      <input type="hidden" id="roicalcstatus" value="' . $status . '">' . $roi;
			break;
			
			case 100:
				//Active ROI
			echo	'<input type="hidden" id="instanceID" value="' . $instanceID . '"> 
				      <input type="hidden" id="roicalcID" value="' . $wbappID . '">
				      <input type="hidden" id="roicalcstatus" value="' . $status . '">' . $roi;
				
			break;
				
			case 210:
				//Lapsed ROI
				//Need a standard html for lapsed calculators
				echo 'lapsed status';
			break;
			
			default:
				//go ahead and display
				echo '<input type="hidden" id="instanceID" value="' . $instanceID . '"> 
				      <input type="hidden" id="roicalcID" value="' . $wbappID . '">
				      <input type="hidden" id="roicalcstatus" value="' . $status . '">' . $roi;
		}
	
	
	//****************************************************************************************
		 
	//4. update the instance with information about this IP Address
	

	
	$details = json_decode(file_get_contents("http://ipinfo.io/$ip/json"));
	
	$country 	= $details->country;
	$state 		= $details->region;
	$city 		= $details->city;
	
	$loc 		= explode(",",$details->loc);
	$lat		= $loc[0];
	$long 		= $loc[1];	
	//$icalc->update_ip($instanceID,$country,$state,$city,$lat,$long);								//PDO OK
	
	$icalc->update_stdvalues($wbappID,$instanceID,$ip,$country,$state,$city,$lat,$long);						//PDO OK
	 
?>
            
