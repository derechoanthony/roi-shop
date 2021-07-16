<?php
	
	function discoveryDocuments(){
		
		$calculator = new CalculatorActions($db);
		
		// Function to be added: Get users permission level. If permission level
		// grants access to discovery document then build available discovery
		// documents, otherwise return false.
		
		$companyDiscoveryDocuments = $calculator->retrieveDiscoveryDocuments();

		// If company has at least 1 discovery document and the user isn't a guest
		// then build all discovery document links that pertain to the company.
		
		// Get verification Level form the php/verification.php file
		$verification_lvl = verificationLevel();
		
		if(count($companyDiscoveryDocuments) && $verification_lvl > 1){

			// Build start of the discovery document list
			$discoveryDocuments = 	'<li id="discovery" class="smooth-scroll">
										<a href="index.html">
											<i class="fa fa-binoculars"></i>
											<span class="nav-label">Discovery Document</span>
											<span class="fa arrow"></span>
										</a>
										<ul class="nav nav-second-level collapse in">';
										
		
			foreach($companyDiscoveryDocuments as $discovery){
						
				// For each discovery document the company has add it to the discovery
				// document list.
				
				$discoveryDocuments .=	'<li>
											<a href="#disc'. $discovery['id'] .'" data-section-id="disc_'.$discovery['id'].'" class="section-navigator" data-section-type="discovery">'
												. $discovery['title'] .
											'</a>
										</li>';
						
			}

			// Close the discovery document list
			$discoveryDocuments .=	'</ul></li>';
			
			return $discoveryDocuments;
			
		}

	}
	
	function roiSections(){
		
		$calculator = new CalculatorActions($db);
		
		// Build start of the roi section list
		
		$sections =	'<li id="sections" class="smooth-scroll">
						<a href="#"><i class="fa fa-calculator"></i> <span class="nav-label">ROI Sections</span><span class="fa arrow"></span></a>
						<ul class="nav nav-second-level collapse in">
							<li>
								<a href="#dash" class="section-navigator" data-section-type="section"> Dashboard</a>
							</li>';

		foreach($_SESSION['roiSections'] as $section){

			// For each section that is a member of the ROI add it to
			// the section list
			
			$include = 'true';
			
			foreach($_SESSION['sectionsExcluded'] as $exclude) {
				
				if($exclude['entity_id'] === $section['ID']) {
					$include = 'false';
				}
			}
			
			if($include == 'true') {
				
				$sections .=	'<li>
									<a href="#section'.$section['ID'].'" class="section-navigator" data-section-type="section">'.$section['Title'].'</a>
								</li>';
			}
		
		}
		
			// Add saving summary to the list and close out the sorted list.
			
			$sections .=	'</ul>
						</li>';
	
		return $sections;
		
	}
	
	function roiPdfs(){
		
		if($_SESSION['pdfSetup']){
			
			// If PDF Specs exist then the ROI has a PDF, so add the link to the PDF to
			// the left side bar
			
			// TO BE USED AT A LATER DATE: <a href="#pdf" data-section-id="pdf" class="section-navigator" data-section-type="pdf"> View PDF</a>
			
			$pdf = 	'<li id="pdf" class="smooth-scroll">
						<a href="#"><i class="fa fa-file-pdf-o"></i> <span class="nav-label">Your PDFs</span> <span class="fa arrow"></span></a>
						<ul class="nav nav-second-level collapse in">				
							<li>
								<a id="create-pdf" data-section-type="pdf"> View PDF</a>
							</li>
						</ul>
					</li>';
					
			return $pdf;
			
		}
	
	}
	
?>