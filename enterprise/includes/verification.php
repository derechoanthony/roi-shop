<?php
	
	/*************************
		Verification Levels
	 *************************/
		 
	 // 0: No verification found, deny access
	 // 1: Owner of ROI not signed in, verification link used matches database
	 // 2: Additional password security required
	 // 3: Owner of ROI is signed in
	 // 4: User is an admin of the current company ROI
		
	$verification_lvl = 0;						// Set Verification to 0 to start.
	$msg = '';									// Empty the msg variable.
		
	// Check the url to determine if an ROI has been defined
	// and if a verification link has also been included.
		
	if( ( isset($_GET['v']) || isset($_GET['amp;v']) ) && isset($_GET['roi']) ){

		if( $_GET['v'] === $roi_data['verification_code'] ){ 
				
			// If ver_user is returned then verification matches
			// verification level is now 1.
		
			$verification_lvl = 1;	
		}
	
	}
	
	// If someone is logged into the system, check if they are the owner
	// of the ROI. Also any The ROI Shop administrators are allowed access
	// as well.
		
	if( isset( $_SESSION['Username'] ) && isset( $_GET['roi'] ) ) {

		if( rtrim(strtolower($roi_owner['username'])) === rtrim(strtolower($_SESSION['Username'])) || $_SESSION['Username'] == 'mfarber@theroishop.com' ){
				
			// If the ROI Owner matches the user that is logged in, then
			// the verification_lvl is now 3.
				
			$verification_lvl = 3;
		
		}

		if( $manager['user_id'] === $_SESSION['UserId'] ){
				
			// If the ROI Owner matches the user that is logged in, then
			// the verification_lvl is now 3.
				
			$verification_lvl = 3;
		
		}
		
	}
	
	switch ( $verification_lvl ) {

		/*************************
			Verification Levels
		 *************************/
		 
		 // 0: No verification found, deny access
		 // 1: Owner of ROI not signed in, verification link used matches database
		 // 2: Additional password security required
		 // 3: Owner of ROI is signed in	
		
		case 0:
		
			// If the ROI is unverified redirect them to the login screen and append
			// the current ROI to the url. User will be returned after logging in.
			
			header("Location: /login?ref=".$_SERVER["REQUEST_URI"]);
			break;
			
		case 1:
		
			// If email is set to false in the url then do not add hit to the record
			// and do not send an email notification
			
			if( isset($_GET['email']) && $_GET['email']=='false' ){
				
				break;
			} else {
				
				// If Session ROI matches the ROI in the url then user has refreshed
				// the page, so do not add a hit to the record and do not send an email
				// notification
				
				//if( $_SESSION['roi'] != $_GET['roi'] ){
					
					// Add new hit to the ROI and set the Session ROI to be the current
					// ROI
					
					$login->addHit();
					//$_SESSION['roi'] = $_GET['roi'];
					
				//}
				break;
			}

		case 2:
		
			// Additional verification is needed, load ROI login for the user to enter
			// their credentails.

			break;
			
		case 3:
		
			// The user logged in is either an administrator or the owner of the ROI so full
			// access to the ROI is granted.
		
	}

?>	