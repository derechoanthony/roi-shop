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
		
	$login = new LogInActions($db);				// Create login object.
		
	// Check the url to determine if an ROI has been defined
	// and if a verification link has also been included.
		
	if( isset($_GET['v']) && isset($_GET['roi']) ){
			
		// Check verification link entered with database
		$ver_user = $login->verifyUser();
			
		if($ver_user){ 
				
			// If ver_user is returned then verification matches
			// verification level is now 1.
		
			$verification_lvl = 1; 
			
		}
	
	}	
		
	// Check the url to determine if an ROI has been defined
	// and if ver_user has been returned. If so, verification
	// link was successfully verified previously.
		
	if( isset( $_GET['roi'] ) && isset( $ver_user ) )
	{
			
		// Determine if an additional password has been defined.
		$password = $login->password();
			
		if($password){
				
			// If password is returned then an additional password
			// was defined. Verification level is now 2.
				
			$verification_lvl = 2;
			
		}
	
	}
		
	// Check the url to determine if a username and a password are
	// defined. If so, the user has entered the additional password
	// that was required. Now determine if username and password
	// match that in the database.
		
	if( isset($_POST['username']) && isset($_POST['password']) ){
			
		// Determine if the additional username and password match
		// the values in the database
			
		$passprotected = $login->roiLogin();
			
		if($passprotected){
				
			// If passprotected is returned then the user has successfully
			// entered the username and password required. Verification_lvl
			// is now 1.
				
			$verification_lvl = 1;
				
		} else {
				
			// If passprotected isn't returned then the user incorrectly
			// entered the required information.
			
			$msg='Username and password do not match. Please try again.';
			
		}
	}
		
	// If someone is logged into the system, check if they are the owner
	// of the ROI. Also any The ROI Shop administrators are allowed access
	// as well.
		
	if( isset( $_SESSION['Username'] ) && isset( $_GET['roi'] ) )
	{
		// Return the owner of the ROI
			
		$calculatorOwner = $login->roiOwner();
		if( rtrim(strtolower($calculatorOwner['Username'])) === rtrim(strtolower($_SESSION['Username'])) || $_SESSION['Username'] == 'mfarber@theroishop.com' ){
				
			// If the ROI Owner matches the user that is logged in, then
			// the verification_lvl is now 3.
				
			$verification_lvl = 3;
		
		}
			
		$calculatorManager = $login->roiManager();
		if( rtrim(strtolower($calculatorManager['Username'])) === rtrim(strtolower($_SESSION['Username'])) ){
				
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
	
	switch ( $verification_lvl )
	{

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