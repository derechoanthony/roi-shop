<?php
	
	/*************************
		Verification Levels
	 *************************/
		 
	 // 0: No verification found, deny access
	 // 1: Full Access Allowed
		
	$verification_lvl = 0;						// Set Verification to 0 to start.
	$msg = '';									// Empty the msg variable.
		
	if( isset( $_SESSION['UserId'] ) ) {
		
		$has_privilege = $roi_information->check_privilege();

		if( $has_privilege ){
				
			// If the ROI Owner matches the user that is logged in, then
			// the verification_lvl is now 3.
				
			$verification_lvl = 1;
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
		
			break;
	
	}

?>	