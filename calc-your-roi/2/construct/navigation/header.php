
	<div class="row bottom-border">
		<nav class="navbar navbar-fixed-top" role="navigation" style="margin-bottom: 0">
			<div class="navbar-header" style="padding: 15px 10px 15px 25px;">
				<h3><?= $roiSpecifics['roi_title'] ?></h3>
			</div>						
			<ul class="nav navbar-top-links navbar-right">
				<li>
					<span class="m-r-sm text-muted welcome-message">Powered by <a href="https:\\www.theroishop.com" style="padding-left: 0;">The ROI Shop</a></span>
				</li>
				<li class="dropdown">
					<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
						My Actions <i class="fa fa-caret-down"></i>
					</a>
					<ul class="dropdown-menu dropdown-alerts">
<?php
		
		if( $_SESSION['verification_lvl'] > 1 ){
?>
	
						<li>
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
							<a onclick="contributorsModal()">Add Contributor</a>
						</li>
<?php		
		}
?>
						<li>
							<a onclick="currentContributorsModal()">View Current Contributors</a>
						</li>
<?php
		if( $_SESSION['verification_lvl'] > 1 ){

			// If verification level is above 1 then a user is signed in. Provide them access to their profile
			// and the ability to log out of the tool.
?>
						<li class="divider"></li>
						<li>
							<a href="../../dashboard/account.php"><i class="fa fa-user"></i> &nbsp; &nbsp;  View Your Profile</a>
						</li>
						<li>
							<a href="../../assets/logout.php"><i class="fa fa-power-off"></i> &nbsp; &nbsp; Log Out</a>
						</li>
<?php		
		}
?>		
					</ul>
				</li>
<?php
		if( $_SESSION['verification_lvl'] > 1 ){

			// If verification level is above 1 then a user is signed in. Provide them access to their profile
			// and the ability to log out of the tool.
?>
				<li>
					<a href="../../assets/logout.php">
						<i class="fa fa-sign-out"></i> Log Out
					</a>
				</li>
<?php
		}
?>
			</ul>
		</nav>
	</div>