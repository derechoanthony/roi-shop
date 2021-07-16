<?php

	function buildDashboard(){
	
		$calculator = new CalculatorActions($db);
		
		// Build the beginning of the dashboard
		$dashboard =	'<div data-show-with="section" data-section-holder-id="#dash">
							<div id="dash" class="row border-bottom white-bg dashboard-header">		
								<div class="col-lg-12">
									<h1 style="margin-bottom: 20px;">ROI Dashboard | '. $_SESSION['calculatorSpecs']['retPeriod'] .' Year Projection ';
									
									if( $_SESSION['calculatorSpecs']['dashboardtotal'] ) {
										
										$dashboard .= '<span class="pull-right pod-total section-total grand-total" data-format="($0,0)" data-formula="GRANDTOTAL(\'true\')"</span>';
									}
									
									$dashboard .= '</h1>
								</div>
							</div>
							<div class="row border-bottom gray-bg dashboard-header">
								<div class="col-lg-12">
									<div class="ibox-content">
										<h3 style="font-size: 18px; font-weight: 700;">Select a section below to review your ROI</h3>
										<p style="font-size: 16px;">
											To calculate your return on investment, begin with the first section below. The information 
											entered therein will automatically populate corresponding fields in the other sections. You 
											will be able to move from section to section to add and/or adjust values to best reflect your 
											organization and process. To return to this screen, click the ROI Dashboard button to the left.
										</p>';
									
									if( $_SESSION['calculatorSpecs']['company_note'] ) {
									
										$dashboard .= $_SESSION['calculatorSpecs']['company_note'];
									}							
									
		$dashboard .=				'</div>
								</div>';
		
		// Loop through each section and create a pod
		foreach( $_SESSION['roiSections'] as $section ){
			
			$include = 'true';
			
			foreach($_SESSION['sectionsExcluded'] as $exclude) {
				
				if($exclude['entity_id'] === $section['ID']) {
					$include = 'false';
				}
			}

			if($include == 'true') {
				$dashboard .= createPod($section, $_SESSION['verification_lvl']);
			}
		}
		
		$dashboard .=	'</div>
					</div>';
		
		return $dashboard;
	
	}
	
	function createPod($section, $verification_lvl){
		
		// Build dashboard pods
		$calculator = new CalculatorActions($db);
		
		$pod =	'<div class="col-lg-3">
							<div class="widget white-bg" style="visibility: hidden;">
								<div class="p-m row">
									<div class="row">
										<h2 class="'.( $verification_lvl > 1 ? 'col-lg-10' : 'col-md-12' ).' font-bold no-margins pod-header">
											<a class="smooth-scroll section-navigator" '.
												( $section == 'savings' ? 'href="#summary" data-section-type="section">Summary' : 'href="#section'. $section['ID'] .'" data-section-type="section">'. $section['Title'] )
											.'</a>	
										</h2>';

		if( $verification_lvl > 1 ){

			// Configuration to be added later. This will allow user to eliminate section or change the
			// appearance of the pod at a later date. This will only be available to the owner of the ROI
			// or someone with similar privileges.
				
			/* Configuration code to be added later
			$dashboard .=	'<div class="col-lg-2 ibox-tools no-padding">
								<a class="dropdown-toggle" href="#" data-toggle="dropdown">
									<i class="fa fa-wrench"></i>
								</a>
								<ul class="dropdown-menu dropdown-user">
									<li>
										<a href="#">Config option 1</a>
									</li>
									<li>
										<a href="#">Config option 2</a>
									</li>
								</ul>
								<a class="close-link">
									<i class="fa fa-times"></i>
								</a>
							</div>';
			*/
		}
		
		$pod .= '</div>';

		if($section['customformula']) {
			
			// If the section has a formula then add a section total and a progress bar to the pod. Conservative slider
			// and include button are also added
			$pod .=	'<h1 class="txt-right pod-total txt-money" data-section-id="'. $section['ID'] .'" data-format="($0,0)" data-formula="'. $section['customformula'] . '"></h1>';			
		} else if($section['formula'] && $section['grandtotal'] == 0){

			// If the section has a formula then add a section total and a progress bar to the pod. Conservative slider
			// and include button are also added
			$pod .=	'<h1 class="txt-right pod-total section-total" data-section-id="'. $section['ID'] .'" data-format="($0,0)" data-formula="SECTIONTOTAL('. $section['formula'] .', \'total\', '. $section['ID'] .')"></h1>
							<div class="progress progress-small">
								<div data-section-id="'. $section['ID'] .'" data-equation="'. $section['formula'] .'" class="progress-bar section-percentage progress-bar-success" role="progressbar" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100" style="width: 35%"></div>
							</div>
							<div class="row" style="padding: 0 20px;">
								<div class="value-holder" style="padding: 15px 0;">
									Conservative Factor: <span class="pull-right">35 %</span>
									<div class="row" style="padding-top: 10px;">
										<div class="col-lg-12">
											<div id="drag-fixed" class="conservative_slider slider_red" data-conservative-section-id="'. $section['ID'] .'"></div>
										</div>
									</div>
								</div>
								<button class="btn btn-block btn-primary btn-include" data-included-section-id="'. $section['ID'] .'" data-checked-state="1" type="button">
									<i class="fa fa-check"></i>
									Included
								</button>
							</div>';
		
		} else if($section['grandtotal'] == 1) {
		
			$pod .=	'<h1 class="txt-right pod-total section-total grand-total" data-format="($0,0)" data-formula="GRANDTOTAL(\'true\')"></h1>';
			
		}
		
		$pod .=	'</div>
			</div>
		</div>';

		return $pod;
		
	}
	
?>