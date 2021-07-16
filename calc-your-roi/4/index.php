<?php
	
	// Establish connection to the database
	
	include_once("db/constants.php");
	include_once("db/connection.php");
	
	require_once("../../inc/vendor/autoload.php");
	require_once("../../php/swiftmailer/lib/swift_required.php");
	
	include_once("inc/login.actions.php");
	include_once("inc/verification.php");

?>

<!DOCTYPE html>
<html>

	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>Calculate Your ROI</title>

		<link href="css/demandware.css" rel="stylesheet">
		
		<link href="css/bootstrap/bootstrap.min.css" rel="stylesheet">
		<link href="css/calculator/style.css" rel="stylesheet">
		
		<link href="css/font-awesome/font-awesome.css" rel="stylesheet">
		
		<link href="css/tooltipster/tooltipster.css" rel="stylesheet">
		
		<link href="css/slider/jquery.nouislider.css" rel="stylesheet">
		<link href="css/chosen/chosen.css" rel="stylesheet">
		
		<link href="css/icheck/icheck-custom.css" rel="stylesheet">
		
		<link href="css/datatables/jquery.dataTables.min.css" rel="stylesheet">
		
		<link rel="shortcut icon" href="theroishop.ico" type="image/x-icon" />

	</head>

	<body class="pace-done fixed-sidebar fixed-nav fixed-nav-basic">
	
		<!--<a class="store-html-blob">Store ROI HTML</a>
		<a class="load-html-blob">Get ROI HTML</a>
		<a class="make-editable">Make editable</a>-->
		
		
		<div id="wrapper">

			<!-- Build Order:
			
				1. Left Navigation Panel
				2. Top Navigation
				3. Section Holder
				
			-->
			
			<!-- Left Navigation Panel Menu -->
			<nav class="navbar-default navbar-static-side" role="navigation">

			
			</nav>
			<!-- End Left Navigation Panel Menu -->
			
			<!-- Main ROI Holder -->
			<div id="page-wrapper" class="gray-bg dashbard-1">
			
				<!-- Main ROI Navigation Header --> 
				<div class="row bottom-border">
					
					<!-- Fixed Top Navbar -->
					<nav class="navbar navbar-fixed-top" role="navigation">
						
						<!-- ROI Title Holder -->
						<div class="navbar-header" style="padding: 15px 10px 15px 25px;">
							<h3 data-roi-title>Dell EMC ROI</h3>
						</div>
						<!-- End ROI Title Holder -->
						
						<!-- Navigation List Items -->
						<ul class="nav navbar-top-links navbar-right">
							
							<!-- The ROI Shop Link -->
							<li>
								<span class="m-r-sm text-muted welcome-message">Powered by <a href="https:\\www.theroishop.com" style="padding-left: 0;">The ROI Shop</a></span>
							</li>
							<!-- End The ROI Shop Link -->
							
							<!-- My Actions Dropdown -->
							<li class="dropdown">
								
								<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
									My Actions <i class="fa fa-caret-down"></i>
								</a>
								
								<ul class="dropdown-menu dropdown-alerts">
									
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
									<li>
										<a onclick="currentContributorsModal()">View Current Contributors</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="../../dashboard/account.php"><i class="fa fa-user"></i> &nbsp; &nbsp;  View Your Profile</a>
									</li>
									<li>
										<a href="../../assets/logout.php"><i class="fa fa-power-off"></i> &nbsp; &nbsp; Log Out</a>
									</li>
					
								</ul>
							</li>
							<!-- End My Actions Dropdown -->
							
							<!-- Log Out -->
							<li>
								<a href="../../assets/logout.php">
									<i class="fa fa-sign-out"></i> Log Out
								</a>
							</li>
							<!-- End Log Out -->
							
						</ul>
						<!-- End Navigation List Items -->
					
					</nav>
					<!-- End Fixed Top Navbar -->
				
				</div>	
				<!-- End Main ROI Navigation Header -->
				
				<div id="verificationLevel" style="display: none;" data-verification="<?= $verification_lvl ?>"></div>
				<div id="roiContent">
				
				</div>
				
				<div data-show-with="pdf" data-section-holder-id="#pdf" style="display: none;">
				
					<div class="col-lg-1">
						<a style="display: none;" id="pdf_create_document" class="btn btn-success btn-sm">Download PDF</a><br>
						<a id="pdf_save" class="btn btn-success btn-sm" style="margin-bottom: 5px;">Create PDF</a><br>
						<a style="display: none;" id="pdf_reset" class="btn btn-success btn-sm">Reset PDF</a><br>
						<a style="display: none;" id="pdf_output" href="php/pdf_output.php?roi=roi=<?= $_GET['roi'] ?>" class="btn btn-primary btn-sm">PDF Output</a>
						<a style="display: none;" id="pdf_create_new_template" href="construct/pdf/pdfgenerators/pdfcreator.php?roi=<?= $_GET['roi'] ?>" class="btn btn-primary btn-sm">PDF Output</a>
					</div>
			
					<div data-page="1" style="position: relative; width: 297mm; height:210mm; border: 1px solid #666; margin: auto; background-color: white; margin-bottom: 30px;">
							
						<div contenteditable="true" data-pdf-element="5596" data-pos-x="0" data-pos-y="0" data-content-type="">
							<hr style="width: 95%; display: block; height: 2px; border: 0; border-top: 1px solid #0000ee; margin: 1em; padding: 1em; color: rgb(0, 145, 212);"/>
						</div>
						
						<div contenteditable="true" data-pdf-element="5597" data-pos-x="0" data-pos-y="7" data-content-type="">
							<p style="font-size: 0.7em; margin: auto; text-align: center;"><strong>IDC White Paper</strong><span style="color: rgb(0, 145, 212);"> | </span><span style="color: rgb( 190, 190, 190);">The Business Value of VCE Vblock Systems: Leveraging Convergence to Drive Business Agility</span></p>
						</div>
						
						<div contenteditable="true" style="" data-pdf-element="5598" data-pos-x="0" data-pos-y="30" data-content-type="">
							<div style="width: 20%; border-right: 1px solid rgb(0, 145, 212); font-family: Arial, Helvetica, sans-serif; padding: 1em;">
								
								<p style="margin-top: 70px; font-size: 0.9em; color: rgb(0, 145, 212);"> Sponsored by: <strong>VCE</strong></p>
								<p style="padding-top: 20px; margin-top: 15px; font-size: 0.9em;"><strong>Authors:</strong></p>
								<p style="font-size: 0.9em;">Richard L. Villars</p>
								<p style="font-size: 0.9em;">Randy Perry</p>
								<p style="padding-top: 20px; font-size: 0.9em;"><?= date("M Y") ?></p>
								<p style="padding-top: 35px; font-size: 1.5em; color: rgb(35,64,142)">Business Value Highlights</p>
								<p style="padding-top: 15px; font-size: 1.5em; color: rgb(35,64,142)">4.6</p>
								<p style="font-size: 0.9em; padding-top: -5px;">times more applications developed/delievered per year</p>
								<p style="padding-top: 10px; font-size: 1.5em; color: rgb(35,64,142)"><span data-format="0,0%" data-formula="A28"></span></p>
								<p style="font-size: 0.9em; padding-top: -5px;">less IT time spent keeping the lights on</p>
								<p style="padding-top: 10px; font-size: 1.5em; color: rgb(35,64,142)">4.4</p>
								<p style="font-size: 0.9em; padding-top: -5px;">times faster time to market for services/products</p>
								<p style="padding-top: 10px; font-size: 1.5em; color: rgb(35,64,142)"><span data-format="0,0%" data-formula="A18"></span></p>
								<p style="font-size: 0.9em; padding-top: -5px;">less downtime</p>
								<p style="padding-top: 10px; font-size: 1.5em; color: rgb(35,64,142)">338%</p>
								<p style="font-size: 0.9em; padding-top: -5px;">more IT time spent on business enablement</p>
								<p style="padding-top: 10px; font-size: 1.5em; color: rgb(35,64,142)">55%</p>
								<p style="font-size: 0.9em; padding-top: -5px;">faster application development life cycle</p>
								<p style="padding-top: 10px; font-size: 1.5em; color: rgb(35,64,142)">36%</p>
								<p style="font-size: 0.9em; padding-top: -5px;">reduced IT infrastructure and IT infrastructure staff costs</p>
								<p style="padding-top: 10px; font-size: 1.5em; color: rgb(35,64,142)">2.1%</p>
								<p style="font-size: 0.9em; padding-top: -5px;">productivity increase (all employees)</p>
								<p style="padding-top: 10px; font-size: 1.5em; color: rgb(35,64,142)">2.4%</p>
								<p style="font-size: 0.9em; padding-top: -5px;">higher revenue</p>
							</div>
						</div>
						
						<div contenteditable="true" data-pdf-element="5599" data-pos-x="0" data-pos-y="30" data-content-type="">
							<div style="width: 70%; padding-left: 28%; font-family: Arial, Helvetica, sans-serif; font-weight: 300;">
								
								<p style="font-size: 2em; color: rgb(0, 145, 212);"><img src="../../../company_specific_files/424/pdfs/pdfidc.jpg" style="width: 150px;"></p>
								<p style="padding-top: 40px; font-size: 2.3em;">The Business Value of Vblock Systems: Leveraging Convergence to Drive Business Agility</p>
								<p style="padding: 37px 0px 25px; font-size: 1.2em; color: rgb(35,64,142);"><strong>EXECUTIVE SUMMARY</strong></p>
								<p style="font-size: 1em;">In the past decade, information technology (IT) evolved from an enabler of back-office business processes to the very foundation of a modern business. In the increasingly digital and mobile world, the datacenter is often the first and most frequent point of contact with customers. The ability to innovate quickly lies at the heart of today's changing business models. Businesses expect their IT investments to accelerate their pace of innovation, provide flexibility to meet new demands, and continually reduce the costs of operations.</p>
								<br/>
								<p style="font-size: 1em;">Converged infrastructure is essential for many companies to ensure that their datacenter infrastructures can meet today’s challenges. The business rationale for deploying converged infrastructure goes far beyond traditional IT feeds and speeds. Customers using converged solutions like VCE’s Vblock Systems (Vblock) realize lower costs, greater levels of utilization, and reduced downtime. VCE customers in this study recognized business benefits such as improved organizational agility, faster application development, increased innovation, and improved employee productivity.</p>
								<br/>
								<p style="font-size: 1em;">IDC interviewed 16 VCE Vblock Systems customers to understand and quantify the benefits delivered by the Vblock converged infrastructure deployments. Vblock Systems are built by VCE using computer, network, and storage technologies and virtualization software from Cisco, EMC, and VMware.</p>
								<br/>
								<p style="font-size: 1em;">IDC found that by using Vblock Systems, these organizations recorded improved business outcomes and that these improvements are increasingly driving IT investment decisions. All the VCE customers interviewed for this study generated substantial business value by consolidating their IT infrastructures with Vblock. IDC calculates that these VCE customers will generate five-year discounted benefits worth an average of $384,202 per 100 users by using Vblock, which will result in an average return on investment (ROI) of <span data-format="0,0%" data-formula="ANA4"></span> and a payback period of <span data-format="0,0[.]0" data-formula="ANA5"></span> months.</p>
							</div>
						</div>
						
						<div contenteditable="true" data-pdf-element="5600" data-pos-x="0" data-pos-y="290" data-content-type="">
							<p style="font-size: 0.7em; margin-left: 25px;">Document #255798 &copy; 2015 IDC. <strong><a href="https://www.idc.com">www.idc.com</a></strong><span style="color: rgb(0, 145, 212);"> | </span> Page 1</p>
						</div>
						
						<div contenteditable="true" data-pdf-element="5601" data-pos-x="85" data-pos-y="287" data-content-type="">
							<img src="../../../company_specific_files/424/pdfs/pdfidc.jpg" style="margin-left: 690px; width: 75px;">
						</div>

					</div>
					
					<div data-page="2" style="position: relative; width: 297mm; height:210mm; border: 1px solid #666; margin: auto; background-color: white; margin-bottom: 30px;">
							
						<div contenteditable="true" data-pdf-element="5602" data-pos-x="0" data-pos-y="0" data-content-type="">
							<hr style="width: 95%; display: block; height: 2px; border: 0; border-top: 1px solid #0000ee; margin: 1em; padding: 1em; color: rgb(0, 145, 212);"/>
						</div>
						
						<div contenteditable="true" data-pdf-element="5603" data-pos-x="0" data-pos-y="7" data-content-type="">
							<p style="font-size: 0.7em; margin: auto; text-align: center;"><strong>IDC White Paper</strong><span style="color: rgb(0, 145, 212);"> | </span><span style="color: rgb( 190, 190, 190);">The Business Value of VCE Vblock Systems: Leveraging Convergence to Drive Business Agility</span></p>
						</div>
						
						<div contenteditable="true" style="" data-pdf-element="5607" data-pos-x="0" data-pos-y="30" data-content-type="">
							<div style="width: 20%; border-right: 1px solid rgb(0, 145, 212); font-family: Arial, Helvetica, sans-serif; padding: 1em;">
								
								<p style="margin-top: 70px; font-size: 0.9em; color: rgb(0, 145, 212);">&nbsp;</p>
								<p style="padding-top: 20px; margin-top: 15px; font-size: 0.9em;">&nbsp;</p>
								<p style="font-size: 0.9em;">&nbsp;</p>
								<p style="font-size: 0.9em;">&nbsp;</p>
								<p style="padding-top: 20px; font-size: 0.9em;">&nbsp;</p>
								<p style="padding-top: 35px; font-size: 1.5em; color: rgb(35,64,142)">&nbsp;</p>
								<p style="padding-top: 15px; font-size: 1.5em; color: rgb(35,64,142)">&nbsp;</p>
								<p style="font-size: 0.9em; padding-top: -5px;">&nbsp;</p>
								<p style="padding-top: 10px; font-size: 1.5em; color: rgb(35,64,142)">&nbsp;</p>
								<p style="font-size: 0.9em; padding-top: -5px;">&nbsp;</p>
								<p style="padding-top: 10px; font-size: 1.5em; color: rgb(35,64,142)">&nbsp;</p>
								<p style="font-size: 0.9em; padding-top: -5px;">&nbsp;</p>
								<p style="padding-top: 10px; font-size: 1.5em; color: rgb(35,64,142)">&nbsp;</p>
								<p style="font-size: 0.9em; padding-top: -5px;">&nbsp;</p>
								<p style="padding-top: 10px; font-size: 1.5em; color: rgb(35,64,142)">&nbsp;</p>
								<p style="font-size: 0.9em; padding-top: -5px;">&nbsp;</p>
								<p style="padding-top: 10px; font-size: 1.5em; color: rgb(35,64,142)">&nbsp;</p>
								<p style="font-size: 0.9em; padding-top: -5px;">&nbsp;</p>
								<p style="padding-top: 10px; font-size: 1.5em; color: rgb(35,64,142)">&nbsp;</p>
								<p style="font-size: 0.9em; padding-top: -5px;">&nbsp;</p>
								<p style="padding-top: 10px; font-size: 1.5em; color: rgb(35,64,142)">&nbsp;</p>
								<p style="font-size: 0.9em; padding-top: -5px;">&nbsp;</p>
								<p style="padding-top: 10px; font-size: 1.5em; color: rgb(35,64,142)">&nbsp;</p>
								<p style="font-size: 0.9em; padding-top: -5px;">&nbsp;</p>
								<p style="padding-top: 10px; font-size: 1.5em; color: rgb(35,64,142)">&nbsp;</p>
								<p style="font-size: 0.9em; padding-top: -5px;">&nbsp;</p>
								<p style="padding-top: 10px; font-size: 1.5em; color: rgb(35,64,142)">&nbsp;</p>
								<p style="font-size: 0.9em; padding-top: -5px;">&nbsp;</p>
								<p style="padding-top: 10px; font-size: 1.5em; color: rgb(35,64,142)">&nbsp;</p>
								<p style="font-size: 0.9em; padding-top: -5px;">&nbsp;</p>
							</div>
						</div>
						
						<div contenteditable="true" data-pdf-element="5604" data-pos-x="0" data-pos-y="30" data-content-type="">
							<div style="width: 70%; padding-left: 28%; font-family: Arial, Helvetica, sans-serif; font-weight: 300; border-left: 1px solid rgb(0, 145, 212);">
								
								<p style="font-size: 1em;">Drivers of economic benefits include:</p>
								<br/>
								<p style="font-size: 1em;"><span style="color: rgb(35,64,142);">&raquo;</span> Improved IT agility, which reduces the time needed to deliver applications and services and provisions datacenter resources &#8212; 4.4 times faster time to market for new services/products</p>
								<br/>
								<p style="font-size: 1em;"><span style="color: rgb(35,64,142);">&raquo;</span> Greater business innovation as IT staff spend less time "keeping the lights on" and more time on innovation projects including mobile and analytics &#8212; <span data-format="0,0%" data-formula="A28"></span> less time spent keeping the lights on.</p>
								<br/>
								<p style="font-size: 1em;"><span style="color: rgb(35,64,142);">&raquo;</span> Increased performance, driving higher levels of customer services and satisfaction &#8212; 4.6 times more applications developed/delivered per year</p>
								<br/>
								<p style="font-size: 1em;"><span style="color: rgb(35,64,142);">&raquo;</span> Higher levels of cost-effectiveness, scalability, and reliability in the technology infrastructure &#8212; <span data-format="0,0%" data-formula="A18"></span> less downtime</p>
								<br/>
								<p style="font-size: 1em;">Converged infrastructure enables IT to rapidly deploy proportional infrastructure resources of every type (compute, network, storage, and advanced data services) while reducing operational overhead. Its effective use is a key enabler of business flexibility.</p>
								<p style="padding: 25px 0px 15px; font-size: 1.2em; color: rgb(35,64,142);"><strong>In This White Paper</strong></p>
								<p style="font-size: 1em;">This study presents the data and IOC's analysis of the business value that 16 VCE customers acieved by deploying Vblock systems. These organizations represent a wide variety of countries and industry verticals, ranging in size from 400 to 200,000 employees, with a mean employee base of 27,113. On average, these VCE customers have deployed five Vblock Systems to support an average of 282 business applications used by 85% of all IT users at their organizations. According to VCE customers, they are running important business applications on the platform, and a number of them are supporting IT initiatives such as public cloud, Big Data and analytics, and centralized virtual desktop (CVD).</p>
								<p style="padding: 25px 0px 15px; font-size: 1.5em; color: rgb(35,64,142);">Strategic Focus Shifting to Speed and Agility and Enabling 3rd Platform Innovation</p>
								<p style="font-size: 1em;">The world of IT is undergoing a massive shift from the PC-based client/server-centric computing model of the 2nd Platform to a model dominated by cloud, mobile, Big Data and analytics, and social technologies. IDC refers to this as the 3rd Platform of computing. Today, virtually all business and technical innovation is occurring on 3rd Platform technologies, and the shift to the 3rd Platform is enabling thousands of high-value, industry-transforming solutions and services. 3rd Platform technologies are also driving the development of entirely new business models, altering and improving customer experience, and delivering new insights that are the sources of competitive advantage.</p>
							</div>
						</div>
						
						<div contenteditable="true" data-pdf-element="5605" data-pos-x="0" data-pos-y="290" data-content-type="">
							<p style="font-size: 0.7em; margin-left: 25px;">Document #255798 &copy; 2015 IDC. <strong><a href="https://www.idc.com">www.idc.com</a></strong><span style="color: rgb(0, 145, 212);"> | </span> Page 2</p>
						</div>
						
						<div contenteditable="true" data-pdf-element="5606" data-pos-x="85" data-pos-y="287" data-content-type="">
							<img src="../../../company_specific_files/424/pdfs/pdfidc.jpg" style="margin-left: 690px; width: 75px;">
						</div>

					</div>
			
				</div>
				
				<!-- Insert ROI Sections HERE -->

			</div>
			
		</div>
		
		<div class="modal inmodal" id="modal-shell" tabindex="-1" role="dialog" aria-hidden="true"></div>
		
				<div id="pdf-progress-overlay" style="display:none;">
					<div class="row pdf-progress">
						<div class="col-lg-12">

							Please wait while the PDF is being created...
							<br><br>
							<div class="pdf-progress-alert">Beginning to build the PDF</div>
							<div class="progress progress-striped active">
								<div style="width: 0%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="75" role="progressbar" class="progress-bar pdf-progress-bar progress-bar-danger"></div>
							</div>

						</div>
					</div>
				</div>
		
		<script src="js/jquery/jquery-2.1.1.js"></script>
		<script src="js/bootstrap/bootstrap.min.js"></script>		
		<script src="js/metisMenu/jquery.metisMenu.js"></script>
		
		<script src="js/element_builder.js"></script>
		<script src="js/hardware.js"></script>
		
		<script src="js/charting/highcharts/modules/highcharts.js"></script>
		<script src="js/charting/highcharts/modules/highcharts-3d.js"></script>
		<script src="js/charting/highcharts/modules/highcharts-more.js"></script>		
		<script src="js/charting/highcharts/modules/highcharts-exporting.js"></script>
		<script src="js/charting/highcharts/modules/data.js"></script>
		
		<script src="js/datatables/jquery.dataTables.min.js"></script>
		
		<script src="js/calculator/numeral.js"></script>
		<script src="js/calculator/jquery-calx-2.1.1.js"></script>		
		<script src="js/tooltipster/jquery.tooltipster.min.js"></script>
		<script src="js/quovolver/jquery.quovolver.min.js"></script>
		<script src="js/icheck/icheck.min.js"></script>
		<script src="js/htmltopdf/xepOnline.jqPlugin.js"></script>
		
		<script src="js/slider/jquery.nouislider.all.min.js"></script>
		<script src="js/fitvids/fitvids.js"></script>
		<script src="js/chosen/chosen.jquery.js"></script>
		
		<script src="js/calculator/buildchart.js"></script>
		<script src="js/modal/modals.js"></script>
		
		<script src="js/calculator/theroishop.masterfunctions.js"></script>
		<script src="js/calculator/theroishop.sliders.js"></script>
		<script src="js/calculator/theroishop.toggles.js"></script>
		<script src="js/noty/jquery.noty.packaged.min.js"></script>
		
		<script src="js/calculator/languages/languages.js"></script>

	</body>
	
</html>