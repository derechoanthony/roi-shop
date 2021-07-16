<?php
	
	function sectionForm(){
		
		$calculator = new CalculatorActions($db);
		
		$roiSectionsCreated = '';
		foreach($_SESSION['roiSections'] as $section){
			
			$include = 'true';
			
			foreach($_SESSION['sectionsExcluded'] as $exclude) {
				
				if($exclude['entity_id'] === $section['ID']) {
					$include = 'false';
				}
			}
		
			if($include == 'true') {
				
				$roiSectionsCreated .= 	'<div data-show-with="section" data-section-holder-id="#section'. $section['ID'] .'">
											<div id="section'. $section['ID'] .'" class="row border-bottom white-bg dashboard-header">		
												<div class="col-lg-12">';
												
				if($section['grandtotal'] == 1) {
				
					$roiSectionsCreated .= 			'<h1 style="margin-bottom: 20px;">Summary 
														<span class="pull-right pod-total section-total grand-total" data-format="($0,0)" data-formula="GRANDTOTAL(\'true\')"></span>
													</h1>';
				} else {
					
					$roiSectionsCreated .=			'<h1 style="margin-bottom: 20px;">'. $section['Title'] . ( $section['formula'] || $section['customformula'] ? '<span class="pull-right pod-total '. ( $section['customformula'] ? 'txt-money' : 'section-total' ). '" data-section-id="'.$section['ID'].'" data-format="$0,0" data-formula="'. ( $section['customformula'] ? $section['customformula'] : 'SECTIONTOTAL('.$section['formula'].', \'total\', '.$section['ID'].')' ) .'">$ 0</span></h1>' : '' );
				}								
					
					$roiSectionsCreated .=		'</div>
											</div>
											<div class="row border-bottom gray-bg dashboard-header">
												<div class="col-lg-12">';
				
				// If the section has a caption add it to the section
				if( $section['Caption'] ){
					
					$roiSectionsCreated .=	'<div class="row">
												<div class="col-md-12 col-sm-12 col-xs-12">
													<div class="ibox float-e-margins">
														<div class="ibox-title">
															<h5>'. $section['Title'] .'</h5>';
				
					// Once functionality is added to add/remove sections this configuration will be added to the pods
					// as long as the user isn't accessing the ROI through a verification link
					
					/* Configuration code for later use:
					
					if($verification_lvl > 1) {
			
						$roiSectionsCreated .=	'<div class="ibox-tools">
													<a class="collapse-link">
														<i class="fa fa-chevron-up"></i>
													</a>
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

					$roiSectionsCreated .=	'</div>
											<div class="ibox-content" style="padding-left: 30px;">
												<div class="row">
													<div class="'. ( $section['Video'] ? 'col-md-7' : 'col-md-12' ) .' section-writeup" role="alert">
														<p class="caption-text">'. $section['Caption'] .'</p>';
															
					if( $section['testimonials'] == 1 ){
						
						$roiSectionsCreated .=	'<hr/>
													<div class="quotes">';
						
						// Retrieve all testimonials that are associate with the current ROI
						$testimonials = $calculator->retrieveTestimonials();
						
						foreach( $testimonials as $testimonial ){
													
							$roiSectionsCreated .=	'<div id="blockquote" class="row" style="min-height: 220px; margin: 0;">
														<blockquote '. ( $testimonial['author'] == 'twitter' ? 'class="twitter-tweet" lang="en" data-conversation="none"' : '' ) .'>
															<p>'. $testimonial['testimonial'] .'</p>
															'. ( $testimonial['author'] && $testimonial['author'] != 'twitter' ? '<p>— '.$testimonial['author'].'</p>' : '' ) .
														'</blockquote>
													</div>';
						}
						
						$roiSectionsCreated .= '</div>';
					}
					
					$roiSectionsCreated .=	'</div>';
										

					if( $section['Video'] ){
											
						$roiSectionsCreated .=	'<div class="col-md-5 player">
													<a class="popup-iframe" href="'. $section['Video'] .'"></a>
													<iframe width="425" height="239" style="margin-left: 5px;" src="'. $section['Video'].'?rel=0&wmode=transparent' .'" frameborder="0" allowfullscreen></iframe>
												</div>';
					}

						$roiSectionsCreated .=	'</div>
											</div>
										</div>
									</div>
								</div>';
				}
					
				$roiSectionsCreated .=	'</div>';
					
				// Build the form of the savings section
					
				$roiSectionsCreated .=	'<div class="col-md-12 col-sm-12 col-xs-12">
											<div class="row">
												<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">';
												
				if($_SESSION['calculatorSpecs']['multiyear'] == 1) {
					
					$roiSectionsCreated .=			'<div class="tabs-container">
														<ul class="nav nav-tabs">';
														
					for($yr = 1; $yr<=$_SESSION['calculatorSpecs']['retPeriod']; $yr++) {
					
						$roiSectionsCreated .=				'<li class="'.( $yr == 1 ? 'active' : '' ).'"><a data-toggle="tab" href="#tab-'. $section['ID'].$yr .'">Year '. $yr .'</a></li>';
					}
				
					$roiSectionsCreated .=				'</ul>
														<div class="tab-content multiyear-values">';				
				}

														
				if($_SESSION['calculatorSpecs']['multiyear'] == 1) {
				
					for($yr = 1; $yr<=$_SESSION['calculatorSpecs']['retPeriod']; $yr++) {
					
						$roiSectionsCreated .=				'<div id="tab-'. $section['ID'].$yr .'" class="tab-pane'.( $yr == 1 ? ' active' : '' ).'">
																<div class="ibox float-e-margins">
																	<div class="ibox-content" style="border-top: none;">
																		<form class="form-horizontal">';
					
					foreach( $_SESSION['sectionEntries'] as $entry ){
						if( $entry['sectionName'] == $section['ID'] ){
							
							// Create the form element. Send the entry information, roi preferences and the fact that it is
							// a section element to the procedure.

							$roiSectionsCreated .=	buildFormElement($entry, $_SESSION['roiPreferences'], 'section', $_SESSION['verification_lvl'], $yr);
						
						}
					}

					// Complete the form and the section form section.
					$roiSectionsCreated .=								'</form>
																	</div>
																</div>
															</div>';
					}
					
					$roiSectionsCreated .=				'</div>
													</div>';
				} else {
					
					$roiSectionsCreated .=						'<div class="ibox float-e-margins">
																	<div class="ibox-content" style="border-top: none;">
																		<form class="form-horizontal">';
					
						foreach( $_SESSION['sectionEntries'] as $entry ){
							if( $entry['sectionName'] == $section['ID'] ){
								
								// Create the form element. Send the entry information, roi preferences and the fact that it is
								// a section element to the procedure.

								$roiSectionsCreated .=	buildFormElement($entry, $_SESSION['roiPreferences'], 'section', $_SESSION['verification_lvl'], $yr);
							
							}
						}

						// Complete the form and the section form section.
						$roiSectionsCreated .=							'</form>
																	</div>
																</div>';				
				}
				
				$roiSectionsCreated .=					'</div>';
								
					if( $section['statistics'] == 1 ) {
						
						$roiSectionsCreated .= '<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
													<div class="ibox float-e-margins">
														<div class="ibox-title">
															<h5 class="col-lg-12">ROI Statistics</h5>
														</div>
														<div class="faq-item">
															<div class="row">
																<div class="col-lg-8">
																	<a class="faq-question collapsed nohover">Return on Investment</a>
																</div>
																<div class="col-lg-4">
																	<div class="pull-right return-on-investment" data-format="(0,0%)" data-formula="ROI()"></div>
																</div>
															</div>
														</div>
														<div class="faq-item">
															<div class="row">
																<div class="col-lg-8">
																	<a class="faq-question collapsed nohover">Net Present Value</a>
																</div>
																<div class="col-lg-4">
																	<div class="pull-right net-present-value" data-format="($0,0)" data-formula="NETPV(0.02)"></div>
																</div>
															</div>
														</div>
														<div class="faq-item">
															<div class="row">
																<div class="col-lg-7">
																	<a class="faq-question collapsed nohover">Payback Period</a>
																</div>
																<div class="col-lg-5">
																	<div class="pull-right"><span data-format="0,0[.]00" data-formula="PAYBACK()"></span> months</div>
																</div>
															</div>
														</div>
													</div>
													<div class="ibox float-e-margins">
														<div class="faq-item">
															Implementation Period: <span class="pull-right">0 months</span>
															<div class="row" style="padding-top: 15px;">
																<div class="col-lg-12">
																	<div id="drag-fixed" class="slider_red implementation_period"></div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>';
					} else {
						
						$roiSectionsCreated .= createSectionSidebar($section['ID']);
					}
					
					foreach( $_SESSION['roiGraphs'] as $graph ) {
						
						if( $graph['sectionid'] == $section['ID'] ) {
							
							$roiSectionsCreated .= '<div class="row border-bottom gray-bg dashboard-header">
														<div class="col-lg-12">
															<div class="row">
																<div class="col-md-12 col-sm-12 col-xs-12">
																	<div class="ibox float-e-margins">
																		<div class="ibox-content" style="padding-left: 30px;">'
																		. $graph['html'] .
																		'</div>
																	</div>
																</div>
															</div>
														</div>
													</div>';
						}
					}
			}	
		}
		
		return $roiSectionsCreated;
		
	}
	

	function createSectionSidebar($currentSection){
	
		$calculator = new CalculatorActions($db);	
		
		$sectionSidebar = '<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">';
		
			foreach( $_SESSION['roiSections'] as $section ){
			
			// If the section has a formula then it has a section total. In that case list in in the dropdown
			// of all the section totals
			
			if( $section['formula'] && $section['ID'] == $currentSection ){
				
				$sectionSidebar .=	'<div class="ibox float-e-margins">
										<div class="ibox-title">
											<h5 class="col-lg-12">
												Baseline Totals
											</h5>
										</div>
										<div class="faq-item">
											<div class="row">
												<div class="col-lg-8 col-md-12">
													<a class="faq-question collapsed" href="'. $currentSection .'faq'. $section['ID'] .'" data-toggle="collapse" aria-expanded="false">'. $section['Title'] .'</a>
												</div>
												<div class="col-lg-4 col-md-12">
													<div class="pull-right">
														<span class="section-total" data-section-id="'. $section['ID'] .'" data-format="($0,0)" data-formula="SECTIONTOTAL('. $section['formula'] .', \'total\', '. $section['ID'] .')" style="white-space: no wrap;"></span>
													</div>
												</div>
												<div class="row">
													<div class="col-lg-12 annual-totals">
														<div class="'. $currentSection .'faq'. $section['ID'] .' panel-collapse faq-answer collapse '. ( $section['ID'] === $currentSection ? "in" : "" ) .'" aria-expanded="false" style="">
															<ul>';
				
				
				for( $i=1; $i<=$_SESSION['calculatorSpecs']['retPeriod']; $i++ ){
	
					// Add an annual value to the section total summary for each year of the ROI
						$sectionSidebar .=						'<li class="value-holder">
																	Year '. $i .':
																	<span class="pull-right section-total" data-yr="'. $i .'" data-section-id="'. $section['ID'] .'" data-format="($0,0)" data-formula="SECTIONTOTAL('. $section['formula'] .', '. $i .', '. $section['ID'] .')"></span>
																</li>';
				}
				
					$sectionSidebar .=							'<li>
																	<hr class="calculation-divider">
																</li>
																<li class="value-holder">
																	Section Total:
																	<span class="pull-right section-total" data-section-id="'. $section['ID'] .'" data-format="($0,0)" data-formula="SECTIONTOTAL('. $section['formula'] .', \'total\', '. $section['ID'] .')"></span>
																</li>
																<li class="value-holder" style="padding-top: 10px;">
																	Conservative Factor: <span class="pull-right">35 %</span>
																	<div class="row" style="padding-top: 10px;">
																		<div class="col-lg-12">
																			<div id="drag-fixed" class="conservative_slider slider_red" data-conservative-section-id="'. $section['ID'] .'"></div>
																		</div>
																	</div>
																</li>
															</ul>
															<button class="btn btn-block btn-primary btn-include" data-included-section-id="'. $section['ID'] .'" data-checked-state="1" type="button">
																<i class="fa fa-check"></i>
																Included
															</button>
														</div>
													</div>
												</div>	
											</div>
										</div>
									</div>';
			
			}
		}
		
		// Loop through the ROI notes to get a count of how many notes are within each section
		$noteSections = [];
		foreach($_SESSION['calculatorNotes'] as $note) {
			array_push($noteSections, 'section'.$note['sectionid']);
		}
		$noteSections = array_count_values($noteSections);		
		
		// Add in implementation slider and add section note button
		$sectionSidebar .=	'<div class="ibox float-e-margins"'. ( $_SESSION['calculatorSpecs']['implementation'] != 0 ? '' : ' style="display:none;"' ) .'>
								<div class="faq-item">
									Implementation Period: <span class="pull-right">0 months</span>
									<div class="row" style="padding-top: 15px;">
										<div class="col-lg-12">
											<div id="drag-fixed" class="slider_red implementation_period"></div>
										</div>
									</div>
								</div>
							</div>
							<a class="btn btn-success btn-section-notes col-lg-12" data-section-id="'. $currentSection .'">
								<span style="float: left;">View Section Notes </span><span class="badge badge-info" style="float: right;">'. ( $noteSections['section'.$currentSection] ? $noteSections['section'.$currentSection] : 0 ).'</span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>';
		
		return $sectionSidebar;

	}
	
?>