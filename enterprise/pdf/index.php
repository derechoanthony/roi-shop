<?php

	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/constants.php");
	require_once("$root/db/connection.php");

	// Retrieve ROI Specifics
	require_once("../php/roi.retrieval.php");
	$roi_retrieval = new RoiRetrieval($db);
	$roi_specifics = $roi_retrieval->roiSpecifics();
	$roi_owner = $roi_retrieval->roiOwner();
	$roi_contributors = $roi_retrieval->roiContributors();
	
	$contributors = '';
	foreach($roi_contributors as $contributor) {
		$contributors .= $contributor['contributor_name'] . '<br/>';
	};
	
	if($roi_specifics['roi_version_id'] == 490) {
		require_once( "$root/enterprise/pdf/mpdf/mpdf.php" );
		$stylesheet = file_get_contents('style.css');
		
		$html = '<body class="gradient-background"></body>';
						
		$mpdf = new mPDF('c', 'A4-L', '','',0, 0, 0, 0);
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->WriteHTML($html);
		
		$mpdf->WriteFixedPosHTML('<img style="z-index:1" src="../company_specific_files/490/img/background-image-1.png">',50,0,280,450,'visible');
		$mpdf->WriteFixedPosHTML('<h1 style="font-family: Arial, Helvetica, sans-serif; font-size: 2.5em; line-height: 1.2em;">Nimble Storage<br/>Total Cost of Ownership</h1>',15,45,200,30,'visible');
		$mpdf->WriteFixedPosHTML('<h2 style="font-family: Arial, Helvetica, sans-serif; font-size: 1.5em; line-height: 1em; color: rgb(133,182,70);">Proposal prepared<br/>for '. $roi_specifics['roi_title'] .'</h2>',15,90,200,30,'visible');
		$mpdf->WriteFixedPosHTML('<h2 style="font-family: Arial, Helvetica, sans-serif; font-size: 1.5em; line-height: 1em; color: rgb(120,120,120);">'. $roi_owner['first_name']. ' ' . $roi_owner['last_name'] . '</h2>',15,135,200,30,'visible');
		$mpdf->WriteFixedPosHTML('<h2 style="font-family: Arial, Helvetica, sans-serif; font-size: 1.5em; line-height: 1em; color: rgb(120,120,120);">'. $contributors . '</h2>',15,145,200,30,'visible');
		
		$mpdf->AddPage();
		$mpdf->BeginLayer(1);
		$mpdf->WriteFixedPosHTML('<img style="z-index:1" src="../company_specific_files/490/img/background-image-2.png">',158,0,280,450,'visible');
		$mpdf->EndLayer(1);
		$mpdf->BeginLayer(2);
		$mpdf->WriteFixedPosHTML('<h1 style="font-family: Arial, Helvetica, sans-serif; font-size: 2.5em; line-height: 1.2em;">TCO Summary</h1>',15,15,200,30,'visible');
		$mpdf->WriteFixedPosHTML('<img src="../company_specific_files/490/img/background-image-3.png">',155,35,97,97,'visible');
		$mpdf->WriteFixedPosHTML('<img style="z-index:1" src="../company_specific_files/images/' . $_GET['roi'] . 'chart106.png">',10,35,140,100,'visible');
		$mpdf->WriteFixedPosHTML('<div style="background-color: rgb(119,188,31); width: 550px;"><h1 style="margin:2px; color: white; padding-left: 10px;">Infrastructure Savings: '. $_GET['infra'] .'</h1></div>',10,135,140,20,'visible');
		$mpdf->WriteFixedPosHTML('<div style="background-color: rgb(119,188,31); width: 550px;"><h1 style="margin:2px; color: white; padding-left: 10px;">Data Center Savings: '. $_GET['enviro'] .'</h1></div>',10,150,140,20,'visible');
		$mpdf->WriteFixedPosHTML('<div style="background-color: rgb(119,188,31); width: 550px;"><h1 style="margin:2px; color: white; padding-left: 10px;">Total Savings: '. $_GET['totalsav'] .'</h1></div>',10,165,140,20,'visible');
		$mpdf->EndLayer(2);
		$mpdf->BeginLayer(3);
		$mpdf->WriteFixedPosHTML('<h1 style="margin:auto; color: white; padding-top: '. ( strlen( $_GET['total'] ) - 3 ) * 7.5 .'px; padding-left: 10px; font-size: '. ( 86 - ( strlen( $_GET['total'] ) - 3 ) * 18 ) .'px;"><center>'. $_GET['total'] .'</center></h1>',180,66,50,50,'visible');	
		$mpdf->EndLayer(3);
		
		$mpdf->AddPage();
		$mpdf->BeginLayer(1);
		$mpdf->WriteFixedPosHTML('<img style="z-index:1" src="../company_specific_files/490/img/background-image-2.png">',158,0,280,450,'visible');
		$mpdf->EndLayer(1);
		$mpdf->BeginLayer(2);
		$mpdf->WriteFixedPosHTML('<h1 style="font-family: Arial, Helvetica, sans-serif; font-size: 2.5em; line-height: 1.2em;">Infrastructure Summary</h1>',15,15,200,30,'visible');
		$mpdf->WriteFixedPosHTML('<img src="../company_specific_files/490/img/background-image-3.png">',155,35,97,97,'visible');
		$mpdf->WriteFixedPosHTML('<img style="z-index:1" src="../company_specific_files/images/' . $_GET['roi'] . 'chart107.png">',10,35,140,100,'visible');
		$mpdf->WriteFixedPosHTML('<div style="background-color: rgb(119,188,31); width: 550px;"><h1 style="margin:2px; color: white; padding-left: 10px;">No Forklift Upgrades: '. $_GET['upgrade'] .'</h1></div>',10,135,140,20,'visible');
		$mpdf->WriteFixedPosHTML('<div style="background-color: rgb(119,188,31); width: 550px;"><h1 style="margin:2px; color: white; padding-left: 10px;">No Software Licensing: '. $_GET['software'] .'</h1></div>',10,150,140,20,'visible');
		$mpdf->WriteFixedPosHTML('<div style="background-color: rgb(119,188,31); width: 550px;"><h1 style="margin:2px; color: white; padding-left: 10px;">Flat Maintenance: '. $_GET['maintenance'] .'</h1></div>',10,165,140,20,'visible');
		$mpdf->EndLayer(2);
		$mpdf->BeginLayer(3);
		$mpdf->WriteFixedPosHTML('<h1 style="margin:auto; color: white; padding-top: '. ( strlen( $_GET['total'] ) - 3 ) * 7.5 .'px; padding-left: 10px; font-size: '. ( 86 - ( strlen( $_GET['total'] ) - 3 ) * 18 ) .'px;"><center>'. $_GET['total'] .'</center></h1>',180,66,50,50,'visible');	
		$mpdf->EndLayer(3);

		$mpdf->AddPage();
		$mpdf->BeginLayer(1);    
		$mpdf->WriteFixedPosHTML('<img style="z-index:1" src="../company_specific_files/490/img/background-image-2.png">',158,0,280,450,'visible');
		$mpdf->EndLayer(1);
		$mpdf->BeginLayer(2);
		$mpdf->WriteFixedPosHTML('<h1 style="font-family: Arial, Helvetica, sans-serif; font-size: 2.5em; line-height: 1.2em;">Environmentals Summary</h1>',15,15,200,30,'visible');
		$mpdf->WriteFixedPosHTML('<img src="../company_specific_files/490/img/background-image-3.png">',155,35,97,97,'visible');
		$mpdf->WriteFixedPosHTML('<img style="z-index:1" src="../company_specific_files/images/' . $_GET['roi'] . 'chart108.png">',10,35,140,100,'visible');
		$mpdf->WriteFixedPosHTML('<div style="background-color: rgb(119,188,31); width: 550px;"><h1 style="margin:2px; color: white; padding-left: 10px;">Data Center: '. $_GET['rack'] .'</h1></div>',10,135,140,20,'visible');
		$mpdf->WriteFixedPosHTML('<div style="background-color: rgb(119,188,31); width: 550px;"><h1 style="margin:2px; color: white; padding-left: 10px;">Power: '. $_GET['power'] .'</h1></div>',10,150,140,20,'visible');
		$mpdf->WriteFixedPosHTML('<div style="background-color: rgb(119,188,31); width: 550px;"><h1 style="margin:2px; color: white; padding-left: 10px;">Cooling: '. $_GET['cooling'] .'</h1></div>',10,165,140,20,'visible');
		$mpdf->EndLayer(2);
		$mpdf->BeginLayer(3);
		$mpdf->WriteFixedPosHTML('<h1 style="margin:auto; color: white; padding-top: '. ( strlen( $_GET['total'] ) - 3 ) * 7.5 .'px; padding-left: 10px; font-size: '. ( 86 - ( strlen( $_GET['total'] ) - 3 ) * 18 ) .'px;"><center>'. $_GET['total'] .'</center></h1>',180,66,50,50,'visible');	
		$mpdf->EndLayer(3);
		
		$mpdf->Output( $roi_specifics['roi_title'] . '.pdf', 'D' );
	} else if ($roi_specifics['roi_version_id'] == 491) {
		require_once( "$root/enterprise/pdf/mpdf/mpdf.php" );
		$stylesheet = file_get_contents('advancedmdstyle.css');
		
		$html = '<body class="gradient-background"></body>';
						
		$mpdf = new mPDF('c', 'A4-L', '','',0, 0, 0, 0);
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->WriteHTML($html);
		
		$mpdf->WriteFixedPosHTML('<table style="font-size:18px; font-family: \'Georgia\', serif; line-height: 22px; font-weight: 100;"><tbody><tr><td><center><h2 style="text-align:center; font-size:28px; font-family: \'Georgia\', serif; line-height: 32px; font-variant: small-caps; font-weight: 100;"><em>Prepared for <strong class="prepared-for">'. $roi_specifics['roi_title'] .'</strong></em></center></h2></td></tr></tbody></table>',100,95,100,30,'visible');
		$mpdf->WriteFixedPosHTML('<img id="company_logo" alt="image" src="../company_specific_files/491/logo/logo.png">',10,10,75,125,'visible');
		$mpdf->WriteFixedPosHTML('<table style="font-size:18px; font-family: \'Georgia\', serif; line-height: 22px; font-weight: 100;"><tbody><tr><td style="text-align:right;">Prepared by:</td><td style="width: 1cm"></td><td class="roi-owner">'. $roi_owner['first_name']. ' ' . $roi_owner['last_name'] . '</td></tr><tr><td style="text-align:right;">Email:</td><td></td><td class="roi-owner-email">'. $roi_owner['username']. '</td></tr><tr><td style="text-align:right;">Phone Number:</td><td></td><td class="roi-owner-phone">'. $roi_owner['phone']. '</td></tr><tr><td style="text-align:right;">Date Created:</td><td></td><td class="roi-date">'. $roi_owner['created_dt']. '</td></tr><tr><td style="text-align:right;">Link to ROI:</td><td></td><td class="roi-link">https://www.theroishop.com/enterprise/?roi='.$roi_specifics['roi_id']. '&v=' .$roi_specifics['verification_code']. '</td></tr></tbody></table>',15,160,275,30,'visible');
		$mpdf->WriteFixedPosHTML('<table style="font-size:18px; font-family: \'Georgia\', serif; line-height: 22px; font-weight: 100;"><tbody><tr><td><strong><h1 class="company-name"><center>AdvancedMD</center></h1></strong></td></tr><tr><td><strong><h1 class="return-on-investment-title"><center>Return on Investment</center></h1></strong></td></tr></tbody></table>',100,75,100,30,'visible');
		
		$mpdf->AddPage();
		$mpdf->WriteFixedPosHTML('<img src="../company_specific_files/images/' . $_GET['roi'] . 'chart109.png" style="width: 610px; height: 420px;">',10,40,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<h1 class="pdf-section-total"><strong>' . $_GET['13'] . '</strong></h1>',15,10,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="pdf-section-header"><table class="pdf-page-header"><tr><td width="50%" style="color: white; font-size: 24px;">Executive Summary </td><td width="50%" class="prepared-for" style="text-align:right;"></td></tr></table></div>',0,0,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="section-writeup">With AdvancedMD, your organization could realize an annual ROI of:</div>',0,10,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="content-bubble right"><p class="content-header"><strong>ROI Statistics:</strong></p></div>',0,60,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="content-bubble right"><h3> Return On Investment: ' . $_GET['1'] . '</h3></div>',0,76,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="content-bubble right"><h3> Net Present Value: ' . $_GET['2'] . '</h3></div>',0,90,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="content-bubble right"><h3> Payback Period: ' . $_GET['3'] . ' months</h3></div>',0,104,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="content-bubble right"><h3> The table to the left shows your annual savings by category</h3><br><h3> You can visit the ROI by clicking the link in the footer</h3></div>',0,135,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<table class="savingsTable" style="width: 180mm;">
	<thead>
		<tr>
			<th colspan="2"></th>
			<th colspan="1">Year 1</th>
			<th colspan="1">Total</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th colspan="2"><strong>Claims and Operating Expense</strong></th>
			<td colspan="1">' . $_GET['4'] . '</td>
			<td colspan="1">' . $_GET['4'] . '</td>
		</tr>
		<tr>
			<th colspan="2"><strong>Revenue Review</strong></th>
			<td colspan="1">' . $_GET['6'] . '</td>
			<td colspan="1">' . $_GET['6'] . '</td>
		</tr>
		<tr>
			<th colspan="2"><strong>Practice Efficiency</strong></th>
			<td colspan="1">' . $_GET['8'] . '</td>
			<td colspan="1">' . $_GET['8'] . '</td>
		</tr>
		<tr>
			<th colspan="2"><strong>Cost</strong></th>
			<td colspan="1">' . $_GET['10'] . '</td>
			<td colspan="1">' . $_GET['10'] . '</td>
		</tr>
		<tr>
			<th colspan="2"><strong>Total</strong></th>
			<td colspan="1">' . $_GET['13'] . '</td>
			<td colspan="1">' . $_GET['13'] . '</td>
		</tr>		
	</tbody>
</table>',10,145,297,100,'visible');		
		$mpdf->SetFooter('Link to the ROI: https://www.theroishop.com/enterprise/?roi='.$_GET['roi'].'&v='.$roi_specifics['verification_code']);
		
		$mpdf->AddPage();
		$mpdf->WriteFixedPosHTML('<img src="../company_specific_files/images/' . $_GET['roi'] . 'chart110.png" style="width: 500px; height: 315px;">',105,100,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="pdf-section-header"><table class="pdf-page-header"><tr><td width="50%" style="color: white; font-size: 24px;">Claims and Operating Expense</td><td width="50%" class="prepared-for" style="text-align:right; float: right; color:white; font-size: 24px;"></td></tr></table></div>',0,0,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<h1 class="pdf-section-total"><strong>' . $_GET['4'] . '</strong></h1>',0,43,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="section-writeup">
			<p>AdvancedRCM services with AdvancedMD allows you to stay connected to practice performance with transparent dashboards and claim tracking.  Choose the software and services that fit the needs of your practice. 
			</p>
			<p style="padding-top: 15px;">Our proprietary ClaimsCenter™ gives you auto-generated worklists, claims status tracking and centralized billing for multiple providers and sites. You can easily identify claim issues before submission with ClaimInspector™, that automatically scrubs claims for potential errors. ClaimInspector runs 3.5 million edits on each claim for CCI, HIPAA, LCD and carrier-specific requirements prior to submission. <strong>We guarantee first-pass claim acceptance of 95% or better.</strong></p>
		</div>',0,10,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="content-bubble right" style="width: 80mm;"><p class="content-paragraph">Not only does AdvancedMD offer your office a set of practice efficiency tools you have a team of experienced billing professionals supporting you. Vacation and sick days don\'t slow down your revenue.</p></div>',0,80,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="point-highlight small" style="width: 400px;">
									<h3 style="font-weight: 500; color: black;"> Average Reimbursement per visit: ' . $_GET['16'] . '</h3>
									<h3 style="font-weight: 500; color: black;"> Expected additional claims: ' . $_GET['17'] . '</h3>
									<h3 style="font-weight: 500; color: black;"> Projected revenue gains with AdvancedMD: ' . $_GET['18'] . '</h3>
									<hr/>
									<h3 style="font-weight: 500; color: black;"> Annual hours saved by billing improvements: ' . $_GET['19'] . '</h3>
									<h3 style="font-weight: 500; color: black;"> Hourly rate of billing employee: ' . $_GET['29'] . '</h3>
									<h3 style="font-weight: 500; color: black;"> Productivity gains: ' . $_GET['30'] . '</h3>
									<hr/>
									<h3 style="font-weight: 500; color: black;"> Annual Software savings: ' . $_GET['31'] . '</h3>
								</div>',0,80,297,100,'visible');
		$mpdf->SetFooter('Link to the ROI: https://www.theroishop.com/enterprise/?roi='.$_GET['roi'].'&v='.$roi_specifics['verification_code']);
		
		$mpdf->AddPage();
		$mpdf->WriteFixedPosHTML('<img src="../company_specific_files/images/' . $_GET['roi'] . 'chart111.png" style="width: 500px; height: 315px;">',105,100,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<h1 class="pdf-section-total"><strong>' . $_GET['6'] . '</strong></h1>',0,36,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="pdf-section-header"><table class="pdf-page-header"><tr><td width="50%" style="color: white; font-size: 24px;">Revenue Review</td><td width="50%" class="prepared-for" style="text-align:right; float: right; color:white; font-size: 24px;"></td></tr></table></div>',0,0,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="section-writeup">
			<p>Best-in-class billing services do far more than just process claims. They follow up with insurance companies, appeal denials, work rejections, no-pays and slow-pays; they manage patient payment plans, and monthly statement cycles and offer you the right tools to boost your revenue.</p>
			<p style="padding-top: 15px;">Most importantly, they involve you in your business.  AdvancedMD’s full transparency model ensures you have the tools and information you need to manage your business!</p>
		</div>',0,10,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="content-bubble right" style="width: 80mm;"><p class="content-paragraph">"We couldn’t do the volume we do in our office with our current staff if we didn\'t use the AdvancedMD billing service"</p></div>',0,80,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="point-highlight small" style="width: 400px;">
									<h3 style="font-weight: 500; color: black;"> Total outstanding AR: ' . $_GET['20'] . '</h3>
									<h3 style="font-weight: 500; color: black;"> AR balance over 120 days: ' . $_GET['21'] . '</h3>
									<h3 style="font-weight: 500; color: black;"> AR balance over 120 days with AdvancedMD: ' . $_GET['22'] . '</h3>
									<h3 style="font-weight: 500; color: black;"> Revenue opportunity: ' . $_GET['23'] . '</h3>
								</div>',0,80,297,100,'visible');
		$mpdf->EndLayer(2);
		$mpdf->SetFooter('Link to the ROI: https://www.theroishop.com/enterprise/?roi='.$_GET['roi'].'&v='.$roi_specifics['verification_code']);
		
		$mpdf->AddPage();
		$mpdf->WriteFixedPosHTML('<img src="../company_specific_files/images/' . $_GET['roi'] . 'chart112.png" style="width: 500px; height: 315px;">',105,100,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<h1 class="pdf-section-total"><strong>' . $_GET['8'] . '</strong></h1>',0,28,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="pdf-section-header"><table class="pdf-page-header"><tr><td width="50%" style="color: white; font-size: 24px;">Practice Efficiency</td><td width="50%" class="prepared-for" style="text-align:right;"></td></tr></table></div>',0,0,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="section-writeup">
			<p>Demands of the medical industry force practices to be up to date on new codes, regulations, technology and industry trends.  In order to survive and thrive, private practices must continually adopt and embrace business models designed to meet the complexity of today\'s healthcare. With AdvancedMD, you have access to cutting edge technology and tools to help you meet the demands of the industry and the needs of your patients.</p>
		</div>',0,10,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="content-bubble right" style="width: 80mm;"><p class="content-paragraph">"Our patients are very well-educated and well-informed, and they want to see results quickly. The practice has to run extremely efficiently and be accessible to them. The nice thing about [AdvancedMD] is it allows me to be more efficient both in and out of the office. Now I don\'t have to come back into the office, which is great for my family and everything saves me a lot of time - probably an hour a day on the three days I\'m in the second office." - Keith Berkowitz</p></div>',0,45,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="point-highlight small" style="width: 400px;">
									<h3 style="font-weight: 500; color: black;"> Annual savings from EPCS: ' . $_GET['24'] . '</h3>
									<h3 style="font-weight: 500; color: black;"> Paper and toner savings: ' . $_GET['25'] . '</h3>
									<h3 style="font-weight: 500; color: black;"> Productivity gains by automating patient forms: ' . $_GET['26'] . '</h3>
									<h3 style="font-weight: 500; color: black;"> Productivity gains by using appointment reminders: ' . $_GET['27'] . '</h3>
									<h3 style="font-weight: 500; color: black;"> Productivity gains by using AdvancedFax: ' . $_GET['28'] . '</h3>
								</div>',0,80,297,100,'visible');
		$mpdf->SetFooter('Link to the ROI: https://www.theroishop.com/enterprise/?roi='.$_GET['roi'].'&v='.$roi_specifics['verification_code']);
		
		$mpdf->Output( $roi_specifics['roi_title'] . '.pdf', 'D' );
	}  else if ($roi_specifics['roi_version_id'] == 508) {
		require_once( "$root/enterprise/pdf/mpdf/mpdf.php" );
		$stylesheet = file_get_contents('workfrontstyle.css');
		
		$html = '<body class="gradient-background"></body>';
						
		$mpdf = new mPDF('', 'A4-L', '','',0, 0, 0, 0);
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->WriteHTML($html);
		
		$mpdf->BeginLayer(1);
		$mpdf->WriteFixedPosHTML('<img style="z-index:1" src="../company_specific_files/508/img/background-image-1.png">',0,0,925,10,'visible');
		$mpdf->EndLayer(1);
		$mpdf->BeginLayer(2);
		$mpdf->WriteFixedPosHTML('<img style="z-index:1" src="../company_specific_files/508/img/company-logo.png">',42,20,100,10,'visible');
		$mpdf->EndLayer(2);
		$mpdf->BeginLayer(3);
		$mpdf->WriteFixedPosHTML('<h1 style="font-size: 4.5em; line-height: 1.2em; color: white;">Value Assessment</h1>',42,80,200,30,'visible');
		$mpdf->WriteFixedPosHTML('<h2 style="font-size: 1.5em; line-height: 1.2em; color: white;">Prepared for:<br/> '. $contributors . '<br/>' . $roi_specifics['roi_title'] . '<br/>' . date("l jS \of F Y") . '<br/><br/><a href="https://www.theroishop.com/enterprise/?roi='.$_GET['roi'].'&v='.$roi_specifics['verification_code'].'">Link to ROI Calculation</a></h2>',42,120,200,30,'visible');
		$mpdf->EndLayer(3);
		
		$mpdf->AddPage();
		$mpdf->BeginLayer(1);
		$mpdf->WriteFixedPosHTML('<img style="z-index:1" src="../company_specific_files/508/img/background-image-2.png">',0,0,925,10,'visible');
		$mpdf->EndLayer(1);
		$mpdf->BeginLayer(4);
		$mpdf->WriteFixedPosHTML('<h1 style="font-size: 3.5em; line-height: 1.2em; font-weight: 300;">EXECUTIVE SUMMARY</h1>',23,15,200,30,'visible');
		$mpdf->WriteFixedPosHTML('<p style="font-size: 1.5em; line-height: 1.0em;">Workfront is a work management solution that modernizes the ways teams and organizations plan, execute and deliver that work. With the use of Workfront over the next 3 years, your organization will realize a total return on investment of:</p>',23,48,250,30,'visible');
		$mpdf->WriteFixedPosHTML('<img src="../company_specific_files/images/' . $_GET['roi'] . 'chart114.png" style="width: 700px; height: 380px;">',58,95,297,100,'visible');		
		$mpdf->EndLayer(4);
		$mpdf->BeginLayer(3);
		$mpdf->WriteFixedPosHTML('<img style="z-index:1" src="../company_specific_files/508/img/footer-logo.png">',28,186,55,20,'visible');		
		$mpdf->WriteFixedPosHTML('<p style="color: rgb(244, 121, 18); font-weight: 900; font-size: 12px;"><strong>workfront.com</strong></p>',179,197,50,30,'visible');
		$mpdf->WriteFixedPosHTML('<p style="color: rgb(244, 121, 18); font-weight: 900; font-size: 12px;"><strong>+ 1 866 441 0001</strong></p>',215,197,50,30,'visible');
		$mpdf->WriteFixedPosHTML('<p style="color: rgb(244, 121, 18); font-weight: 900; font-size: 12px;"><strong>+44 (0) 1256 807352</strong></p>',251,197,50,30,'visible');
		$mpdf->EndLayer(3);		
		$mpdf->BeginLayer(2);
		$mpdf->WriteFixedPosHTML('<table width="100%"><tr><td><th align="center"><h1 style="font-size: 4.5em; line-height: 1.2em; font-weight: 300; margin-right: auto; margin-left: auto; width: 90%;">' . $_GET['1'] . '</h1></tr></td></tr></table>',0,70,500,30,'visible');
		$mpdf->WriteFixedPosHTML('<p style="color: rgb(244, 121, 18); font-weight: 900; font-size: 12px;"><strong>2</strong></p>',14,194,30,30,'visible');
		$mpdf->EndLayer(2);
		
		$mpdf->AddPage();
		$mpdf->BeginLayer(1);
		$mpdf->WriteFixedPosHTML('<img style="z-index:1" src="../company_specific_files/508/img/background-image-2.png">',0,0,925,10,'visible');
		$mpdf->EndLayer(1);
		$mpdf->BeginLayer(2);
		$mpdf->WriteFixedPosHTML('<h1 style="font-size: 3.5em; line-height: 1.2em; font-weight: 300;">AUTOMATION SAVINGS SUMMARY</h1>',23,15,250,30,'visible');
		$mpdf->WriteFixedPosHTML('<p style="font-size: 1.5em; line-height: 1.0em;">The way that we work is changing, and automating certain aspects of work should be a top priority for most organizations. In a recent Workfront survey, automating knowledge work is the C-Suite\'s top priority (20%).</p>',23,48,250,30,'visible');
		$mpdf->EndLayer(2);
		$mpdf->BeginLayer(3);
		$mpdf->WriteFixedPosHTML('<table width="100%"><tr><td><th align="center"><h1 style="font-size: 4.5em; line-height: 1.2em; font-weight: 300; margin-right: auto; margin-left: auto; width: 90%;">' . $_GET['2'] . '</h1></th></td></tr></table>',0,70,500,30,'visible');
		$mpdf->WriteFixedPosHTML('<img style="z-index:1" src="../company_specific_files/508/img/right_sidebar_2.png">',198,98,75,10,'visible');
		$mpdf->WriteFixedPosHTML('<p style="font-size: 1.2em; line-height: 1.0em;">Reduced Cost to Manage a Job by 60%</p>',198,107,75,10,'visible');
		$mpdf->WriteFixedPosHTML('<img style="z-index:1" src="../company_specific_files/508/img/2014_Trek_Color_Horizontal_on_white.png">',198,132,75,10,'visible');
		$mpdf->WriteFixedPosHTML('<p style="font-size: 1.2em; line-height: 1.0em;">Team Members Regained 30% of Their Time for Innovation</p>',198,152,75,10,'visible');
		$mpdf->WriteFixedPosHTML('<div class="point-highlight small" style="width: 570px;">
									<table width="100%">
										<tr style="paddng-bottom: 1em;">
											<th align="left"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>Email Savings:</strong></h3></th>
											<th align="right"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>' . $_GET['3'] . '</strong></h3></th>
										</tr>
										<tr style="paddng-top: 5px;">
											<th align="left"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>Meeting Savings:</strong></h3></th>
											<th align="right"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>' . $_GET['4'] . '</strong></h3></th>
										</tr>
										<tr>
											<th align="left"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>Status Updates:</strong></h3></th>
											<th align="right"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>' . $_GET['5'] . '</strong></h3></th>
										</tr>
										<tr>
											<th align="left"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>Reporting Savings:</strong></h3></th>
											<th align="right"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>' . $_GET['6'] . '</strong></h3></th>
										</tr>
										<tr>
											<th align="left"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>Approval Savings:</strong></h3></th>
											<th align="right"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>' . $_GET['7'] . '</strong></h3></th>
										</tr>
										<tr>
											<th align="left"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>Tool Consolidation:</strong></h3></th>
											<th align="right"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>' . $_GET['24'] . '</strong></h3></th>
										</tr>
									</table>
								</div>',23,98,297,100,'visible');		
		$mpdf->EndLayer(3);
		$mpdf->BeginLayer(4);
		$mpdf->WriteFixedPosHTML('<p style="color: rgb(244, 121, 18); font-weight: 900; font-size: 12px;"><strong>3</strong></p>',14,194,30,30,'visible');
		$mpdf->WriteFixedPosHTML('<img style="z-index:1" src="../company_specific_files/508/img/footer-logo.png">',28,186,55,20,'visible');
		$mpdf->WriteFixedPosHTML('<p style="color: rgb(244, 121, 18); font-weight: 900; font-size: 12px;"><strong>workfront.com</strong></p>',179,197,50,30,'visible');
		$mpdf->WriteFixedPosHTML('<p style="color: rgb(244, 121, 18); font-weight: 900; font-size: 12px;"><strong>+ 1 866 441 0001</strong></p>',215,197,50,30,'visible');
		$mpdf->WriteFixedPosHTML('<p style="color: rgb(244, 121, 18); font-weight: 900; font-size: 12px;"><strong>+44 (0) 1256 807352</strong></p>',251,197,50,30,'visible');
		$mpdf->EndLayer(4);

		$mpdf->AddPage();
		$mpdf->BeginLayer(1);
		$mpdf->WriteFixedPosHTML('<img style="z-index:1" src="../company_specific_files/508/img/background-image-2.png">',0,0,925,10,'visible');
		$mpdf->EndLayer(1);
		$mpdf->BeginLayer(2);
		$mpdf->WriteFixedPosHTML('<h1 style="font-size: 3.5em; line-height: 1.2em; font-weight: 300;">INCREASE VELOCITY SAVINGS SUMMARY</h1>',23,15,250,30,'visible');
		$mpdf->WriteFixedPosHTML('<p style="font-size: 1.5em; line-height: 1.0em;">Increase your overall efficiency to speed up work cycles, get more visibility into the work that’s being done, and maximize your resource capacity, so you can realize greater efficiencies and time savings.</p>',23,48,250,30,'visible');
		$mpdf->EndLayer(2);
		$mpdf->BeginLayer(3);
		$mpdf->WriteFixedPosHTML('<table width="100%">
	<tr>
		<th align="center">
			<h1 style="font-size: 2.5em; line-height: 1.2em; font-weight: 300; margin-right: auto; margin-left: auto; width: 90%;">Decrease Small Project Duration by ' . $_GET['9'] . '</h1>
		</th>
	</tr>
	<tr>
		<th align="center">
			<h1 style="font-size: 2.5em; line-height: 1.2em; font-weight: 300; margin-right: auto; margin-left: auto; width: 90%;">Decrease Large Project Duration by ' . $_GET['10'] . '</h1>
		</th>
	</tr>
</table>',0,70,500,30,'visible');
		$mpdf->WriteFixedPosHTML('<img style="z-index:1" src="../company_specific_files/508/img/right_sidebar_7.png">',198,106,75,10,'visible');
		$mpdf->WriteFixedPosHTML('<p style="font-size: 1.2em; line-height: 1.0em;">Increased Project Velocity by 50%</p>',198,127,75,10,'visible');
		$mpdf->WriteFixedPosHTML('<div class="point-highlight small" style="width: 570px;">
									<table width="100%">
										<tr style="paddng-bottom: 1em;">
											<th align="left"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>Small Project Average Duration:</strong></h3></th>
											<th align="right"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>' . $_GET['11'] . ' Days</strong></h3></th>
										</tr>
										<tr style="paddng-top: 5px;">
											<th align="left"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>Large Project Average Duration:</strong></h3></th>
											<th align="right"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>' . $_GET['12'] . ' Days</strong></h3></th>
										</tr>
										<tr>
											<th align="left"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>Projected Version per asset:</strong></h3></th>
											<th align="right"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>' . $_GET['13'] . '</strong></h3></th>
										</tr>
									</table>
								</div>',23,108,297,100,'visible');		
		$mpdf->EndLayer(3);
		$mpdf->BeginLayer(4);
		$mpdf->WriteFixedPosHTML('<p style="color: rgb(244, 121, 18); font-weight: 900; font-size: 12px;"><strong>4</strong></p>',14,194,30,30,'visible');
		$mpdf->WriteFixedPosHTML('<img style="z-index:1" src="../company_specific_files/508/img/footer-logo.png">',28,186,55,20,'visible');
		$mpdf->WriteFixedPosHTML('<p style="color: rgb(244, 121, 18); font-weight: 900; font-size: 12px;"><strong>workfront.com</strong></p>',179,197,50,30,'visible');
		$mpdf->WriteFixedPosHTML('<p style="color: rgb(244, 121, 18); font-weight: 900; font-size: 12px;"><strong>+ 1 866 441 0001</strong></p>',215,197,50,30,'visible');
		$mpdf->WriteFixedPosHTML('<p style="color: rgb(244, 121, 18); font-weight: 900; font-size: 12px;"><strong>+44 (0) 1256 807352</strong></p>',251,197,50,30,'visible');
		$mpdf->EndLayer(4);

		$mpdf->AddPage();
		$mpdf->BeginLayer(1);
		$mpdf->WriteFixedPosHTML('<img style="z-index:1" src="../company_specific_files/508/img/background-image-2.png">',0,0,925,10,'visible');
		$mpdf->EndLayer(1);
		$mpdf->BeginLayer(2);
		$mpdf->WriteFixedPosHTML('<h1 style="font-size: 3.5em; line-height: 1.2em; font-weight: 300;">INCREASE THROUGHPUT SUMMARY</h1>',23,15,250,30,'visible');
		$mpdf->WriteFixedPosHTML('<p style="font-size: 1.5em; line-height: 1.0em;">Using Workfront to manage work processes will 
		allow you to improve efficiency, and as a result, complete more projects with the same amount of resources.</p>',23,48,250,30,'visible');
		$mpdf->EndLayer(2);
		$mpdf->BeginLayer(3);
		$mpdf->WriteFixedPosHTML('<table width="100%"><tr><td><th align="center"><h1 style="font-size: 4.5em; line-height: 1.2em; font-weight: 300; margin-right: auto; margin-left: auto; width: 90%;">' . $_GET['14'] . '</h1></th></td></tr></table>',0,70,500,30,'visible');
		$mpdf->WriteFixedPosHTML('<img style="z-index:1" src="../company_specific_files/508/img/right_sidebar_12.png">',198,92,75,10,'visible');
		$mpdf->WriteFixedPosHTML('<p style="font-size: 1.2em; line-height: 1.0em;">Increased Project Capacity by More Than 50%</p>',198,107,75,10,'visible');
		$mpdf->WriteFixedPosHTML('<img style="z-index:1" src="../company_specific_files/508/img/right_sidebar_10.png">',206,127,75,10,'visible');
		$mpdf->WriteFixedPosHTML('<p style="font-size: 1.2em; line-height: 1.0em;">99% On Time Delivery Rate</p>',198,152,75,10,'visible');
		$mpdf->WriteFixedPosHTML('<div class="point-highlight small" style="width: 570px;">
									<table width="100%">
										<tr style="paddng-bottom: 1em;">
											<th align="left"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>Additional Small Projects:</strong></h3></th>
											<th align="right"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>' . $_GET['15'] . '</strong></h3></th>
										</tr>
										<tr style="paddng-top: 5px;">
											<th align="left"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>Additional Large Projects:</strong></h3></th>
											<th align="right"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>' . $_GET['16'] . '</strong></h3></th>
										</tr>
										<tr>
											<th>&nbsp;</th>
										</tr>
										<tr>
											<th align="left"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>Small Project:</strong></h3></th>
											<th align="right"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>' . $_GET['17'] . '</strong></h3></th>
										</tr>
										<tr>
											<th align="left"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>Large Project:</strong></h3></th>
											<th align="right"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>' . $_GET['18'] . '</strong></h3></th>
										</tr>
										<tr>
											<th>&nbsp;</th>
										</tr>
										<tr>
											<th align="left"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>Projected On Time Rate With Workfront</strong></h3></th>										
										</tr>
										<tr>
											<th align="left"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>Small Projects:</strong></h3></th>
											<th align="right"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>' . $_GET['19'] . '</strong></h3></th>
										</tr>
										<tr>
											<th align="left"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>Large Projects:</strong></h3></th>
											<th align="right"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>' . $_GET['20'] . '</strong></h3></th>
										</tr>
									</table>
								</div>',23,98,297,100,'visible');	
		$mpdf->EndLayer(3);
		$mpdf->BeginLayer(4);
		$mpdf->WriteFixedPosHTML('<p style="color: rgb(244, 121, 18); font-weight: 900; font-size: 12px;"><strong>5</strong></p>',14,194,30,30,'visible');
		$mpdf->WriteFixedPosHTML('<img style="z-index:1" src="../company_specific_files/508/img/footer-logo.png">',28,186,55,20,'visible');
		$mpdf->WriteFixedPosHTML('<p style="color: rgb(244, 121, 18); font-weight: 900; font-size: 12px;"><strong>workfront.com</strong></p>',179,197,50,30,'visible');
		$mpdf->WriteFixedPosHTML('<p style="color: rgb(244, 121, 18); font-weight: 900; font-size: 12px;"><strong>+ 1 866 441 0001</strong></p>',215,197,50,30,'visible');
		$mpdf->WriteFixedPosHTML('<p style="color: rgb(244, 121, 18); font-weight: 900; font-size: 12px;"><strong>+44 (0) 1256 807352</strong></p>',251,197,50,30,'visible');
		$mpdf->EndLayer(4);

		$mpdf->AddPage();
		$mpdf->BeginLayer(1);
		$mpdf->WriteFixedPosHTML('<img style="z-index:1" src="../company_specific_files/508/img/background-image-2.png">',0,0,925,10,'visible');
		$mpdf->EndLayer(1);
		$mpdf->BeginLayer(2);
		$mpdf->WriteFixedPosHTML('<h1 style="font-size: 3.5em; line-height: 1.2em; font-weight: 300;">COMPLIANCE SAVINGS SUMMARY</h1>',23,15,250,30,'visible');
		$mpdf->WriteFixedPosHTML('<p style="font-size: 1.5em; line-height: 1.0em;">Mitigating risk and ensuring compliance can be an expensive proposition if not done right. Determining now what you can save by enforcing compliance and avoiding fines will make all the difference to your overall bottom line, as well as your ability to execute.</p>',23,48,250,30,'visible');
		$mpdf->EndLayer(2);
		$mpdf->BeginLayer(3);
		$mpdf->WriteFixedPosHTML('<table width="100%"><tr><td><th align="center"><h1 style="font-size: 4.5em; line-height: 1.2em; font-weight: 300; margin-right: auto; margin-left: auto; width: 90%;">' . $_GET['21'] . '</h1></th></td></tr></table>',0,70,500,30,'visible');
		$mpdf->WriteFixedPosHTML('<img style="z-index:1" src="../company_specific_files/508/img/atb_vert.png">',198,92,75,10,'visible');
		$mpdf->WriteFixedPosHTML('<p style="font-size: 1.2em; line-height: 1.0em;">Uses Workfront to Manage and Create Drug Advertisements That Must Comply With FDA Regulations</p>',198,107,75,10,'visible');
		$mpdf->WriteFixedPosHTML('<div class="point-highlight small" style="width: 570px;">
									<table width="100%">
										<tr style="paddng-bottom: 1em;">
											<th align="left"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>Compliance Savings:</strong></h3></th>
											<th align="right"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>' . $_GET['22'] . '</strong></h3></th>
										</tr>
										<tr style="paddng-top: 5px;">
											<th align="left"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>Audit Cost Reduction:</strong></h3></th>
											<th align="right"><h3 style="font-weight: 500; color: white; font-size: 22px; line-height: 2em;"><strong>' . $_GET['23'] . '</strong></h3></th>
										</tr>
									</table>
								</div>',23,102,297,100,'visible');		
		$mpdf->EndLayer(3);
		$mpdf->BeginLayer(4);
		$mpdf->WriteFixedPosHTML('<p style="color: rgb(244, 121, 18); font-weight: 900; font-size: 12px;"><strong>6</strong></p>',14,194,30,30,'visible');
		$mpdf->WriteFixedPosHTML('<img style="z-index:1" src="../company_specific_files/508/img/footer-logo.png">',28,186,55,20,'visible');
		$mpdf->WriteFixedPosHTML('<p style="color: rgb(244, 121, 18); font-weight: 900; font-size: 12px;"><strong>workfront.com</strong></p>',179,197,50,30,'visible');
		$mpdf->WriteFixedPosHTML('<p style="color: rgb(244, 121, 18); font-weight: 900; font-size: 12px;"><strong>+ 1 866 441 0001</strong></p>',215,197,50,30,'visible');
		$mpdf->WriteFixedPosHTML('<p style="color: rgb(244, 121, 18); font-weight: 900; font-size: 12px;"><strong>+44 (0) 1256 807352</strong></p>',251,197,50,30,'visible');
		$mpdf->EndLayer(4);

		$mpdf->AddPage();		
		$mpdf->BeginLayer(1);
		$mpdf->WriteFixedPosHTML('<img style="z-index:1" src="../company_specific_files/508/img/backcover.png">',0,0,925,10,'visible');
		$mpdf->EndLayer(1);
		
		$mpdf->Output( $roi_specifics['roi_title'] . '.pdf', 'D' );		
	} else if ($roi_specifics['roi_version_id'] == 506) {
		require_once( "$root/enterprise/pdf/mpdf/mpdf.php" );
		$stylesheet = file_get_contents('mercurygatestyle.css');
		
		$html = '<body class="gradient-background"></body>';
						
		$mpdf = new mPDF('', 'A4-L', '','',0, 0, 0, 0);
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->WriteHTML($html);
		
		$mpdf->WriteFixedPosHTML('<img id="company_logo" alt="image" src="../company_specific_files/506/img/cover1.png">',0,0,925,10,'visible');
		$mpdf->WriteFixedPosHTML('<img id="company_logo" alt="image" src="../company_specific_files/506/img/cover2.png">',180,165,95,10,'visible');
		$mpdf->WriteFixedPosHTML('<h1 style="font-size: 3.5em; line-height: 1.2em; font-weight: 700;"><strong>RETURN ON INVESTMENT<br/>PREPARED FOR <em>'. $roi_specifics['roi_title'] .'</em></strong></h1>',10,95,200,30,'visible');
		$mpdf->WriteFixedPosHTML('<div class="section-writeup" style="font-size: 2.5em;">Account Executive<br/>Prepared on: '. date('M jS, Y', strtotime( roi_owner['created_dt']) ) . '</div>',10,155,200,30,'visible');
		
		$mpdf->AddPage();
		$mpdf->WriteFixedPosHTML('<img src="../company_specific_files/images/' . $_GET['roi'] . 'chart113.png" style="width: 610px; height: 340px;">',10,40,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<h1 class="pdf-section-total"><strong>' . $_GET['1'] . '</strong></h1>',15,13,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="pdf-section-header"><table class="pdf-page-header"><tr><td width="50%" style="color: white; font-size: 24px;">Executive Summary </td><td width="50%" class="prepared-for" style="text-align:right;"></td></tr></table></div>',0,0,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="section-writeup">With the use of MercuryGate your organization could realize a savings of:</div>',0,10,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="content-bubble right"><p class="content-header"><strong>ROI Statistics:</strong></p></div>',0,60,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="content-bubble right"><h3> Return On Investment: ' . $_GET['2'] . '</h3></div>',0,76,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="content-bubble right"><h3> Net Present Value: ' . $_GET['3'] . '</h3></div>',0,90,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="content-bubble right"><h3> Payback Period: ' . $_GET['4'] . ' months</h3></div>',0,104,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="content-bubble right"><h3> The table to the left shows your annual savings by category</h3><br><h3> You can visit the ROI by clicking the link in the footer</h3></div>',0,135,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<table class="savingsTable" style="width: 180mm;">
	<thead>
		<tr>
			<th colspan="2"></th>
			<th colspan="1">Year 1</th>
			<th colspan="1">Year 2</th>
			<th colspan="1">Year 3</th>
			<th colspan="1">Total</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th colspan="2"><strong>Plan / Optimize</strong></th>
			<td colspan="1">' . $_GET['5'] . '</td>
			<td colspan="1">' . $_GET['6'] . '</td>
			<td colspan="1">' . $_GET['7'] . '</td>
			<td colspan="1">' . $_GET['8'] . '</td>			
		</tr>
		<tr>
			<th colspan="2"><strong>Procure / Execute</strong></th>
			<td colspan="1">' . $_GET['9'] . '</td>
			<td colspan="1">' . $_GET['10'] . '</td>
			<td colspan="1">' . $_GET['11'] . '</td>
			<td colspan="1">' . $_GET['12'] . '</td>			
		</tr>
		<tr>
			<th colspan="2"><strong>Settlement</strong></th>
			<td colspan="1">' . $_GET['13'] . '</td>
			<td colspan="1">' . $_GET['14'] . '</td>
			<td colspan="1">' . $_GET['15'] . '</td>
			<td colspan="1">' . $_GET['16'] . '</td>			
		</tr>
		<tr>
			<th colspan="2"><strong>Manage Fleet</strong></th>
			<td colspan="1">' . $_GET['17'] . '</td>
			<td colspan="1">' . $_GET['18'] . '</td>
			<td colspan="1">' . $_GET['19'] . '</td>
			<td colspan="1">' . $_GET['20'] . '</td>
		</tr>
		<tr>
			<th colspan="2"><strong>Manage Carriers</strong></th>
			<td colspan="1">' . $_GET['21'] . '</td>
			<td colspan="1">' . $_GET['22'] . '</td>
			<td colspan="1">' . $_GET['23'] . '</td>
			<td colspan="1">' . $_GET['24'] . '</td>
		</tr>
		<tr>
			<th colspan="2"><strong>Cost</strong></th>
			<td colspan="1">' . $_GET['25'] . '</td>
			<td colspan="1">' . $_GET['26'] . '</td>
			<td colspan="1">' . $_GET['27'] . '</td>
			<td colspan="1">' . $_GET['28'] . '</td>
		</tr>		
		<tr>
			<th colspan="2"><strong>Total</strong></th>
			<td colspan="1">' . $_GET['29'] . '</td>
			<td colspan="1">' . $_GET['30'] . '</td>
			<td colspan="1">' . $_GET['31'] . '</td>
			<td colspan="1">' . $_GET['32'] . '</td>			
		</tr>		
	</tbody>
</table>',10,125,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<hr/><div style="margin-top: -5px; text-align: right;">Link to the ROI: https://www.theroishop.com/enterprise/?roi='.$_GET['roi'].'&v='.$roi_specifics['verification_code'].'</div>',5,195,287,100,'visible');
		
		$mpdf->AddPage();
		$mpdf->WriteFixedPosHTML('<img src="../company_specific_files/images/' . $_GET['roi'] . 'chart134.png" style="width: 500px; height: 315px;">',135,80,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="pdf-section-header"><table class="pdf-page-header"><tr><td width="50%" style="color: white; font-size: 24px;">Plan / Optimize - 3 Year Projection</td><td width="50%" class="prepared-for" style="text-align:right; float: right; color:white; font-size: 24px;"></td></tr></table></div>',0,0,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<h1 class="pdf-section-total"><strong>' . $_GET['8'] . '</strong></h1>',0,38,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="section-writeup">
			<p>The ability to consolidate shipments and create multiple stop truckload moves as well as the construction and utilization of pools and mode skipping. Optimization can occur for all locations from a single management point. Shipment consolidation from single origins or to single destinations provides ROI, but combined with the ability to optimize routes will result in substantial ROI. The combined ability to route freight to and from multiple facilities and also incorporate the use of pooling and cross docking results in a truly cost effective supply chain.</p>
		</div>',0,10,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="point-highlight small" style="width: 400px;">
									<h3 style="font-weight: 500;"> Rate and Mode Savings: ' . $_GET['33'] . '</h3>
									<h3 style="font-weight: 500;"> Multi Stop Savings: ' . $_GET['34'] . '</h3>
									<h3 style="font-weight: 500;"> Outbound Savings: ' . $_GET['35'] . '</h3>
									<h3 style="font-weight: 500;"> Inbound Savings: ' . $_GET['36'] . '</h3>
									<h3 style="font-weight: 500;"> Network Optimization: ' . $_GET['37'] . '</h3>
								</div>',0,90,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="section-writeup">
			<p>"We’ve fully automated the process where we have shipments being created automatically within MercuryGate TMS using system integration,” he says. “Customers love that efficiency. They send the shipments to us and they all get optimized with no touch."<br/><strong>Scott Anderson, LynnCo’s director of information technology</strong></p>
		</div>',0,165,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<hr/><div style="margin-top: -5px; text-align: right;">Link to the ROI: https://www.theroishop.com/enterprise/?roi='.$_GET['roi'].'&v='.$roi_specifics['verification_code'].'</div>',5,195,287,100,'visible');
		
		$mpdf->AddPage();
		$mpdf->WriteFixedPosHTML('<img src="../company_specific_files/images/' . $_GET['roi'] . 'chart135.png" style="width: 500px; height: 315px;">',135,80,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<h1 class="pdf-section-total"><strong>' . $_GET['12'] . '</strong></h1>',0,36,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="pdf-section-header"><table class="pdf-page-header"><tr><td width="50%" style="color: white; font-size: 24px;">Procure / Execute</td><td width="50%" class="prepared-for" style="text-align:right; float: right; color:white; font-size: 24px;"></td></tr></table></div>',0,0,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="section-writeup">
			<p>Typical transportation processes are traditionally manual, labor intense and re-active in nature. MercuryGate is designed to support best of breed transportation processes and provide a pro-active, manage-by-exceptions environment. The improvements in operations and administration allow resources to be allocated to productive cost reducing and revenue generating activities.</p>
		</div>',0,10,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="point-highlight small" style="width: 400px;">
									<h3 style="font-weight: 500;"> RFP Savings: ' . $_GET['38'] . '</h3>
									<h3 style="font-weight: 500;"> RFP Administration Savings: ' . $_GET['39'] . '</h3>
									<h3 style="font-weight: 500;"> Contracts Administration Savings: ' . $_GET['40'] . '</h3>
									<h3 style="font-weight: 500;"> Outbound Compliance Savings: ' . $_GET['41'] . '</h3>
									<h3 style="font-weight: 500;"> Inbound Compliance Savings: ' . $_GET['42'] . '</h3>
									<h3 style="font-weight: 500;"> Load Execution Savings: ' . $_GET['43'] . '</h3>
									<h3 style="font-weight: 500;"> Penalty Savings: ' . $_GET['44'] . '</h3>
								</div>',0,80,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="section-writeup">
			<p>"Using the MercuryGate TMS, we’ve been able to accommodate a lot of different transportation modes."<br/><strong>Director of Transportation Services, Saddle Creek</strong></p>
		</div>',0,165,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<hr/><div style="margin-top: -5px; text-align: right;">Link to the ROI: https://www.theroishop.com/enterprise/?roi='.$_GET['roi'].'&v='.$roi_specifics['verification_code'].'</div>',5,195,287,100,'visible');
		
		$mpdf->AddPage();
		$mpdf->WriteFixedPosHTML('<img src="../company_specific_files/images/' . $_GET['roi'] . 'chart136.png" style="width: 500px; height: 315px;">',135,80,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="pdf-section-header"><table class="pdf-page-header"><tr><td width="50%" style="color: white; font-size: 24px;">Settlement - 3 Year Projection</td><td width="50%" class="prepared-for" style="text-align:right; float: right; color:white; font-size: 24px;"></td></tr></table></div>',0,0,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<h1 class="pdf-section-total"><strong>' . $_GET['16'] . '</strong></h1>',0,43,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="section-writeup">
			<p>MercuryGate streamlines invoicing and payment process and accelerates time to settle with carriers and logistics service providers. This earns their trust and they are willing to do more business with you. Typically the audit and pay process is a very manual and tedious routine. Often companies turn to external payment companies to audit and pay their freight invoices adding incremental costs to the transportation budget. MercuryGate enables automatic freight audits based on defined business rules, enables identification of discrepancies and facilitates dispute resolution. The savings are in avoidance of overpaying carriers and improving accounting department productivity.</p>
		</div>',0,10,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="point-highlight small" style="width: 400px;">
									<h3 style="font-weight: 500;"> Accounting Department Savings: ' . $_GET['45'] . '</h3>
									<h3 style="font-weight: 500;"> Freight Audit Savings: ' . $_GET['46'] . '</h3>
								</div>',0,80,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="section-writeup">
			<p>"With MercuryGate’s leading transportation management software, Trinity created opportunities for operational savings between 10-30 percent of overhead expenses."<br/><strong>Trinity Transport, Inc., Director of Logistics Sales</strong></p>
		</div>',0,165,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<hr/><div style="margin-top: -5px; text-align: right;">Link to the ROI: https://www.theroishop.com/enterprise/?roi='.$_GET['roi'].'&v='.$roi_specifics['verification_code'].'</div>',5,195,287,100,'visible');
		
		$mpdf->AddPage();
		$mpdf->WriteFixedPosHTML('<img src="../company_specific_files/images/' . $_GET['roi'] . 'chart137.png" style="width: 500px; height: 315px;">',135,80,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<h1 class="pdf-section-total"><strong>' . $_GET['20'] . '</strong></h1>',0,36,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="pdf-section-header"><table class="pdf-page-header"><tr><td width="50%" style="color: white; font-size: 24px;">Manage Fleet - 3 Year Projection</td><td width="50%" class="prepared-for" style="text-align:right; float: right; color:white; font-size: 24px;"></td></tr></table></div>',0,0,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="section-writeup">
			<p>Fleet management solution allows you to manage all three - drivers, equipment and operations, realizing benefits in each area as well as streamlining your entire fleet management process. This solution is tailored for asset-based carriers, asset light carriers and private shipper fleets.</p>
		</div>',0,10,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="point-highlight small" style="width: 400px;">
									<h3 style="font-weight: 500;"> Increased Asset Utilization Savings: ' . $_GET['47'] . '</h3>
									<h3 style="font-weight: 500;"> Administration Savings: ' . $_GET['48'] . '</h3>
									<h3 style="font-weight: 500;"> Maintenance Savings: ' . $_GET['49'] . '</h3>
									<h3 style="font-weight: 500;"> Driver Management Savings: ' . $_GET['50'] . '</h3>
								</div>',0,80,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="section-writeup">
			<p>"As a testament to MercuryGate\'s functionality and design – we quickly implemented this complete and complex Logistics solution while relatively new to the application. Other complex freight management accounts followed along with catching up with the original Brokerage plan. We could not have attempted this without MercuryGate."<br/><strong>Vice President - Xpress Network Solutions</strong></p>
		</div>',0,165,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<hr/><div style="margin-top: -5px; text-align: right;">Link to the ROI: https://www.theroishop.com/enterprise/?roi='.$_GET['roi'].'&v='.$roi_specifics['verification_code'].'</div>',5,195,287,100,'visible');
		
		$mpdf->AddPage();
		$mpdf->WriteFixedPosHTML('<img src="../company_specific_files/images/' . $_GET['roi'] . 'chart138.png" style="width: 500px; height: 315px;">',135,80,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<h1 class="pdf-section-total"><strong>' . $_GET['24'] . '</strong></h1>',0,36,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="pdf-section-header"><table class="pdf-page-header"><tr><td width="50%" style="color: white; font-size: 24px;">Manage Carriers - 3 Year Projection</td><td width="50%" class="prepared-for" style="text-align:right; float: right; color:white; font-size: 24px;"></td></tr></table></div>',0,0,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="section-writeup">
			<p>Carrier Management solution Carma collects data, approves carriers, analyzes carrier capabilities, and creates alerts when conditions require your attention. The workflow process is configurable too. You can implement and modify your processes as your business grows. Bottom line – Carma automates the details of carrier data management so you can focus on running your business.</p>
		</div>',0,10,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="point-highlight small" style="width: 400px;">
									<h3 style="font-weight: 500;"> Carrier Administration Savings: ' . $_GET['51'] . '</h3>
									<h3 style="font-weight: 500;"> Carrier Onboarding Savings: ' . $_GET['52'] . '</h3>
								</div>',0,80,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<div class="section-writeup">
			<p>"When one of our carriers has a service failure, we immediately know about it and can begin to solve the issue as a team."<br/><strong>Manager of Truckload Solutions - Unisource</strong></p>
		</div>',0,165,297,100,'visible');
		$mpdf->WriteFixedPosHTML('<hr/><div style="margin-top: -5px; text-align: right;">Link to the ROI: https://www.theroishop.com/enterprise/?roi='.$_GET['roi'].'&v='.$roi_specifics['verification_code'].'</div>',5,195,287,100,'visible');
		
		$mpdf->AddPage();		
		$mpdf->BeginLayer(1);
		$mpdf->WriteFixedPosHTML('<img style="z-index:1" src="../company_specific_files/506/img/headerbar.png">',0,0,925,10,'visible');
		$mpdf->WriteFixedPosHTML('<img style="z-index:1" src="../company_specific_files/506/img/backcover.png">',0,94,925,10,'visible');
		$mpdf->EndLayer(1);
		
		$mpdf->Output( $roi_specifics['roi_title'] . '.pdf', 'D' );
	} else if ($roi_specifics['roi_version_id'] == 535) {
		require_once( "$root/webapps/mpdf/mpdf.php" );
		require_once( "$root/webapps/core/init.php" );
		$reportID  = 108;
		
		$wbroiID 	= $g->Dlookup('wb_roi_ID','wb_roi_reports','wb_roi_report_ID=' . $reportID);
		$orient		= $g->Dlookup('PDForientation','wb_roi_reports','wb_roi_report_ID=' . $reportID);
			
		switch ($orient) {
			case 0:
				$orient = '';
				break;
			case 1:
				$orient = '-L';
				break;
			default:
				$orient = '';
		}		

		$reportCSS 	= $g->Dlookup('CSS','wb_roi_reports','wb_roi_report_ID=' . $reportID);
		$reportHTML = $g->Dlookup('html','wb_roi_reports','wb_roi_report_ID=' . $reportID);
		
		$reportHTML = str_replace('<tag>grandtotal</tag>', $_GET['1'], $reportHTML);
		$reportHTML = str_replace('<tag>Rep Name</tag>', $roi_owner['first_name']. ' ' . $roi_owner['last_name'], $reportHTML);
		$reportHTML = str_replace('<tag>01/10/18</tag>', date('m/d/Y', time()), $reportHTML);
		$reportHTML = str_replace('Prepared for Testing', $roi_specifics['roi_title'], $reportHTML);
		$reportHTML = str_replace('roi-grandtotal-graph', '../company_specific_files/images/' . $_GET['roi'] . 'chart142.png', $reportHTML);
		$reportHTML = str_replace('<tag>ROI</tag>', $_GET['1'], $reportHTML);
		$reportHTML = str_replace('<tag>claim</tag>', $_GET['2'], $reportHTML);
		$reportHTML = str_replace('<tag>operating</tag>', $_GET['3'], $reportHTML);
		$reportHTML = str_replace('<tag>efficiency</tag>', $_GET['4'], $reportHTML);
		$reportHTML = str_replace('<tag>cost</tag>', $_GET['5'], $reportHTML);
		$reportHTML = str_replace('<tag>6</tag>', $_GET['6'], $reportHTML);
		$reportHTML = str_replace('<tag>7</tag>', $_GET['7'], $reportHTML);
		$reportHTML = str_replace('<tag>8</tag>', $_GET['8'], $reportHTML);
		$reportHTML = str_replace('<tag>9</tag>', $_GET['9'], $reportHTML);
		$reportHTML = str_replace('<tag>10</tag>', $_GET['10'], $reportHTML);
		$reportHTML = str_replace('<tag>11</tag>', $_GET['11'], $reportHTML);
		$reportHTML = str_replace('<tag>12</tag>', $_GET['12'], $reportHTML);
		$reportHTML = str_replace('<tag>13</tag>', $_GET['13'], $reportHTML);
		$reportHTML = str_replace('<tag>14</tag>', $_GET['14'], $reportHTML);
		$reportHTML = str_replace('<tag>15</tag>', $_GET['15'], $reportHTML);
		$reportHTML = str_replace('<tag>16</tag>', $_GET['16'], $reportHTML);
		$reportHTML = str_replace('<tag>17</tag>', $_GET['17'], $reportHTML);
		$reportHTML = str_replace('<tag>18</tag>', $_GET['18'], $reportHTML);
		$reportHTML = str_replace('<tag>19</tag>', $_GET['19'], $reportHTML);
		$reportHTML = str_replace('<tag>20</tag>', $_GET['20'], $reportHTML);
		
		$report = '<html><head>' . $reportCSS . '</head><body class="pdfbody">' . $reportHTML . '</body></html>';
		
		$stylesheet = file_get_contents('../assets/css/pdfstyle.css');
		$comp_stylesheet = file_get_contents('../assets/css/style.css');
		
		$mpdf->showImageErrors = true;	
		
		$mpdf = new mPDF('c', 'A4' . $orient);
		
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->WriteHTML($comp_stylesheet,1);
			
		$mpdf->WriteHTML($report);
		
		$mpdf->Output( $roi_specifics['roi_title'] . '.pdf', 'D' );	
	}
?>


