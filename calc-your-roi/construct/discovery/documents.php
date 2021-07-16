<?php

	function discoveryDocument(){
		
		$calculator = new CalculatorActions($db);
		
		// Retrieve all discovery documents in the database associated with the company of the
		// current ROI
		
		$discoveryDocuments = $calculator->retrieveDiscoveryDocuments();
		
		// Get verification Level form the php/verification.php file
		$verification_lvl = verificationLevel();
		
		// Because multiple discovery documents may exist all script must be appended to existing
		// discoveryOutput. Therefore discoveryOutput must be established before entering the 
		// discovery build.
		$discoveryOutput ='';
		
		foreach($discoveryDocuments as $discovery){

			// Setup beginning of each discovery document
			
			$discoveryOutput .=	'<div data-show-with="none" data-section-holder-id="#disc'. $discovery['id'] .'" style="display: none;">
									<div id="disc'. $discovery['id'] .'" class="row border-bottom white-bg dashboard-header">		
										<div class="col-lg-12">
											<h1 style="margin-bottom: 20px; width: 100%;">'. $discovery['title'];
											
			$roiPreferences = $calculator->retrieveRoiPreferences();
		
			if($_SESSION['calculatorSpecs']['sf_integration']) {
					
				$discoveryOutput .= '<small class="pull-right">This ROI is currently linked to: <span class="sfdc-link text-info" data-sfdc-link="' . $roiPreferences['sfdc_link'] . '">' . ( $roiPreferences['linked_title'] ? $roiPreferences['linked_title'] : 'Click Here to Link' ) . '</span></small>';			
			}
											
			$discoveryOutput .=				'</h1>
										</div>
									</div>			
									<div class="row border-bottom gray-bg dashboard-header">
										<div class="col-md-12 col-sm-12 col-xs-12">	
											<div class="ibox float-e-margins">
												<div class="ibox-title">
													<h5> '. $discovery['title'] .' </h5>';

			// Configuration not yet setup, but this code will be used to add configuration in the future once available.

			/* Configuration code for later use:
			
			$discoveryOutput .=	'<div class="ibox-tools">
									<a class="collapse-link">
										<i class="fa fa-chevron-up"></i>
									</a>
									<a class="dropdown-toggle" data-toggle="dropdown" href="#">
										<i class="fa fa-wrench"></i>
									</a>
									<a class="close-link">
										<i class="fa fa-times"></i>
									</a>
								</div>';
								
			*/
							
			$discoveryOutput .=	'</div>
								<div class="ibox-content">
									<form class="form-horizontal">';

			
			// Get all discovery questions for the current ROI and the roi preferences, both scripts are
			// found in ../php/calculator.actions.php
			
			$discoveryQuestons = $calculator->retrieveDiscoveryQuestions();
			$calculatorPreferences = $calculator->retrieveRoiPreferences();
			
			foreach( $discoveryQuestons as $question ){
				
				// Compare current questions discovery id to the current discovery id that is being built. If
				// they are the same then include the question in the current discovery document.
				if( $question['discovery_id'] == $discovery['id'] ){
					
					// Build element from the script found in ../php/element.creation.php, pass the question
					// parameter, roi preferences and the fact that it's a discovery element being built.
					$discoveryOutput .= buildFormElement($question, $calculatorPreferences, 'discovery', $verification_lvl);
				}
			
			}

			// Finish the form and discovery document build. Also included is the export to sales force button.
			// This will need to be modified with the new integration build, but is only available to The ROI Shop
			// for the time being, so it remains for now.
			
			$discoveryOutput .=	'</form>
							</div>
						</div>
					</div>';
					
			$discoveryOutput .= salesforceIntegration($discovery['id'], $roiPreferences['instance']);
	
			$discoveryOutput .=	'<div class="modal inmodal" id="disc_modal'. $discovery['id'] .'" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog modal-xlg">
							<div class="modal-content animated bounceInRight">
								<div class="modal-header" style="padding-bottom: 0;">
									<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
									<h4 class="modal-title" style="margin-bottom: 20px;">Export to Salesforce</h4>
									<div class="form-group">
										<label class="col-sm-6">This ROI is currently linked to: </label>
										<span class="sfdc-link text-info" data-sfdc-link="' . $roiPreferences['sfdc_link'] . '">' . ( $roiPreferences['linked_title'] ? $roiPreferences['linked_title'] : 'Click Here to Link' ) . '</span>
									</div>
									<p class="font-bold">Select the fields below that you want to export to Salesforce</p>
								</div>
								<div class="modal-body integration-form" data-modal-id="disc_'. $discovery['id'] .'">';
								
				foreach( $discoveryQuestons as $question ){
					
					if( $question['sfdc_element'] ) {
						
						$discoveryOutput .= buildFormElement($question, $calculatorPreferences, 'integration');
					}
				}
						$discoveryOutput .= '<div class="form-group integration-element" data-sfdc-account="'.$_SESSION['calculatorSpecs']['sfdc_account'].'" data-sfdc-opportunity="'.$_SESSION['calculatorSpecs']['sfdc_opportunity'].'" data-sfdc-lead="'.$_SESSION['calculatorSpecs']['sfdc_lead'].'">
												<div class="col-sm-1">
													<div class="i-checks">
														<label>
															<input type="checkbox" class="integration-key sfdc" checked="" value="Visits__c"> <i></i>
														</label>
													</div>
												</div>
												<label class="control-label col-lg-7 col-md-7 col-sm-7">Visits</label>
												<div class="col=lg-4 col=md-4 col-sm-4">
													<input class="form-control integration-value" type="text" data-input-type="alphanumeric" disabled="disabled" value="' . $roiPreferences['visits'] . '">
												</div>	
											</div>
											<div class="form-group integration-element" data-sfdc-account="'.$_SESSION['calculatorSpecs']['sfdc_account'].'" data-sfdc-opportunity="'.$_SESSION['calculatorSpecs']['sfdc_opportunity'].'" data-sfdc-lead="'.$_SESSION['calculatorSpecs']['sfdc_lead'].'">
												<div class="col-sm-1">
													<div class="i-checks">
														<label>
															<input type="checkbox" class="integration-key sfdc" checked="" value="Unique_Visits__c"> <i></i>
														</label>
													</div>
												</div>
												<label class="control-label col-lg-7 col-md-7 col-sm-7">Unique Visits</label>
												<div class="col=lg-4 col=md-4 col-sm-4">
													<input class="form-control integration-value" type="text" data-input-type="alphanumeric" disabled="disabled" value="' . $roiPreferences['unique_ip'] . '">
												</div>	
											</div>
											<div class="form-group integration-element" data-sfdc-account="'.$_SESSION['calculatorSpecs']['sfdc_account'].'" data-sfdc-opportunity="'.$_SESSION['calculatorSpecs']['sfdc_opportunity'].'" data-sfdc-lead="'.$_SESSION['calculatorSpecs']['sfdc_lead'].'">
												<div class="col-sm-1">
													<div class="i-checks">
														<label>
															<input type="checkbox" class="integration-key sfdc" checked="" value="'. $_SESSION['calculatorSpecs']['sfdc_ver_link'] . '"> <i></i>
														</label>
													</div>
												</div>
												<label class="control-label col-lg-7 col-md-7 col-sm-7">Verification Link for this ROI</label>
												<div class="col=lg-4 col=md-4 col-sm-4">
													<input class="form-control integration-value" type="text" data-input-type="alphanumeric" disabled="disabled" value="www.theroishop.com/calc-your-roi/?roi=' . $roiPreferences['roi_id'] . '&v=' . $roiPreferences['verification_code'] . '">
												</div>	
											</div>
									<div class="col-lg-12">
										<a class="check-all">Check All<a/> / <a class="uncheck-all">Uncheck All</a>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
									<button type="button" class="btn btn-primary" onclick="exportToSalesForce('. $discovery['id'] .')">Export</button>
								</div>
							</div>
						</div>
					</div>';
					
			$discoveryOutput .=	'<div class="modal inmodal" id="sfdc_opp" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							<div class="modal-content animated bounceInRight">
								<div class="modal-header" style="padding-bottom: 0;">
									<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
									<h4 class="modal-title" style="margin-bottom: 20px;">Choose Opportunity to link ROI to:</h4>
								</div>
								<div class="modal-body integration-form" data-modal-id="disc_'. $discovery['id'] .'">
									<div class="form-group">
										<label class="col-sm-6">This ROI is currently linked to: </label>
										<div class="col-sm-6">
											<select class="available-opportunities chosen-selector form-control" data-placeholder="Choose an Opportunity"></select>
										</div>
									</div>								
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
									<button type="button" class="btn btn-primary" onclick="exportToSalesForce('. $discovery['id'] .')">Export</button>
								</div>
							</div>
						</div>
					</div>
	
				</div>
			</div>';

			// Loop back to next discovery document
		
		}

		return $discoveryOutput;
	}
	
	function salesforceIntegration($discovery, $instance) {
		
		$calculator = new CalculatorActions($db);
		
		if($_SESSION['calculatorSpecs']['sf_integration']) {
			
			$SFCode = $calculator->retrieveSFCode();
			
			if( $SFCode['code'] ) {
				
				return '<div class="text-center">
							<button type="button" class="btn btn-primary export-salesforce" data-instance-link="' . $instance . '" data-modal-target="disc_modal'. $discovery .'">Export to Salesforce</button>
							<button type="button" class="btn btn-primary" onclick="importFromSalesforce('. $discovery .')">Import from Salesforce</button>
						</div>';				
			} else {
				
				return '<div class="text-center">
							<button type="button" class="btn btn-primary" onclick="setupSalesforceConnection()"">Setup Salesforce Integration</button>
						</div>';
			}
		} else {
			
			return '';
		}
		
	}
	
?>