<?php
	
	function verificationLevel(){
	
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
		
		$login = new LogInActions($db);				// Create login object.
		
		// Check the url to determine if an ROI has been defined
		// and if a verification link has also been included.

		if( isset($_GET['v']) && isset($_GET['roi']) ){
			
			// Check verification link entered with database
			$ver_user = $login->verifyUser();
			
			if($ver_user){ 
				
				$email_protected = $login->isEmailProtected();

				if( $email_protected['email_protected'] == 1 ){
					$verification_lvl = 2;
				} else {
					$verification_lvl = 1; 
				}

			}
		
		}
		
		// Check the url to determine if a username and a password are
		// defined. If so, the user has entered the additional password
		// that was required. Now determine if username and password
		// match that in the database.

		if ( isset($_POST['email']) ){
			$grant_access = $login->checkEmailVerification();
	
			if (count($grant_access) > 0){
				$verification_lvl = 1;
			}
		}
		
		// If someone is logged into the system, check if they are the owner
		// of the ROI. Also any The ROI Shop administrators are allowed access
		// as well.
		
		if( isset( $_SESSION['Username'] ) && isset( $_GET['roi'] ) ) {
			// Return the owner of the ROI
			
			$calculatorOwner = $login->roiOwner();
			if( rtrim(strtolower($calculatorOwner['username'])) === rtrim(strtolower($_SESSION['Username'])) || $_SESSION['Username'] == 'mfarber@theroishop.com' ){
				
				// If the ROI Owner matches the user that is logged in, then
				// the verification_lvl is now 3.
				
				$verification_lvl = 3;
			
			}
			
			$calculatorManager = $login->roiManager();
			if( rtrim(strtolower($calculatorManager['username'])) === rtrim(strtolower($_SESSION['Username'])) ){
				
				// If the ROI Owner matches the user that is logged in, then
				// the verification_lvl is now 3.
				
				$verification_lvl = 3;
			
			}
			
		}
		
		if( isset( $_SESSION['Username'] ) && isset( $_GET['roi'] ) ) {
			
			// Check to see if the user that is signed in is an admin of the ROI
			
			$calculatorAdmin = $login->userAdmin();
			if($calculatorAdmin['permission']>0) {
				
				// If the permission returned is greater than 0 that the user is
				// an admin for this company's ROI
				
				$verification_lvl = 4;
			}
			
		}
		
		return $verification_lvl;
		
	}

?>	