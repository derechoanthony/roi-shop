<?php

	function myActions(){
		
		$calculator = new CalculatorActions($db);

		// Stakeholder and Savings Type to be added in the future. Only companies that
		// have signed up for this will see the stakeholder graph and savings graph in
		// their actions.
		
		$companyRoiSpecs = $calculator->retrieveRoiSpecs();
		
		// Get verification Level form the php/verification.php file
		$verification_lvl = verificationLevel();

		// Build the beginning of the dropdown menu
		
		$dropdown =	'<li class="dropdown">
						<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
							My Actions <i class="fa fa-caret-down"></i>
						</a>
						<ul class="dropdown-menu dropdown-alerts">';

		if( $verification_lvl > 1 ){
	
			$dropdown .=	'<li>
								<a onclick="verificationModal()">Show Verification Link</a>
							</li>
							<li>
								<a onclick="resetVerificationModal()">Reset Verification Link</a>
							</li>
							<li>
								<a class="showHideSections">Show/Hide Sections</a>
							</li>
							<li>
								<a class="change-currency">Change ROI Currency</a>
							</li>
							<li>
								<a onclick="contributorsModal()">Add Allowed Users</a>
							</li>';
		
		}

		$dropdown	.=	'<li>
							<a onclick="currentContributorsModal()">View Allowed Users</a>
						</li>';

		// Add Remove Section to be added later. This will only be allowed if the user isn't accessing the ROI
		// through a verification link and if they have privileges to manipulate the ROI structure.
		
		/* Dropdown list item for later use
		
		$dropdown	.=	'<li>
							<a class="add-remove-section" href="javascript:void(0);">Add / Remove Section</a>
						</li>';
		
		*/

		// Stakeholder graph and Savings Type graph to be added later.
		// For now only The ROI Shop will show this in the dropdown menu until the functionality is added
		// to other ROIs if they choose to opt in.
		
		if( $companyRoiSpecs['compID'] == 1 ){

			// If the compID is 1, the the ROI is The ROI Shop, therefore add the stakeholder graph list
			
			$dropdown	.=	'<li>
								<a class="stakeholder-graph" href="javascript:void(0);" data-toggle="modal" data-target="#stakeholders">View Stakeholder Graph</a>
							</li>';
							
		}

		if( $verification_lvl > 1 ){

			// If verification level is above 1 then a user is signed in. Provide them access to their profile
			// and the ability to log out of the tool.
			
			$dropdown	.=	'<li class="divider"></li>
							<li>
								<a href="/dashboard/account.php"><i class="fa fa-user"></i> &nbsp; &nbsp;  View Your Profile</a>
							</li>
							<li>
								<a href="../assets/logout.php"><i class="fa fa-power-off"></i> &nbsp; &nbsp; Log Out</a>
							</li>';
		
		}
		
		// Close out the dropdown list
		
		$dropdown	.=	'</ul>
					</li>';
	
		return $dropdown;
		
	}