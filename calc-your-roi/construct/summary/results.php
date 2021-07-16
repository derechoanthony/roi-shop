<?php

	function savingsShell(){
		
		$calculator = new CalculatorActions($db);
			
		// Get the ROI Specs for the current ROI
		$roiSections = $calculator->retrieveRoiSections();
		
		// Get verification Level form the php/verification.php file
		$verification_lvl = verificationLevel();		
		
		$savingsShell =	
		
		'<div data-show-with="section" data-section-holder-id="#summary">
			<div id="summary" class="row border-bottom white-bg dashboard-header">		
				<div class="col-lg-12">
					<h1 style="margin-bottom: 20px;">Summary 
						<span class="pull-right pod-total section-total grand-total" data-format="($0,0)" data-formula="GRANDTOTAL(\'true\')"></span>
					</h1>
				</div>
			</div>
			<div class="row border-bottom gray-bg dashboard-header">
				<div class="col-lg-12">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="ibox float-e-margins">
								<div class="ibox-title">
									<h5>Summary</h5>';
		
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
				
		$savingsShell .=		'</div>
								<div class="ibox-content" style="padding-left: 30px;">
									<div class="row">
										<div class="col-md-12 section-writeup" role="alert">
											<p class="caption-text">'
												.( $calculatorSummary['description'] ? $calculatorSummary['description'] : 'The table below represents a summary of the ROI
												you can expect by category and by year. Note the Implementation slider located on the right. You can adjust the
												ROI output to reflect ramp time as you implement. Also, note the expected Net Present Value, your calculated %
												return and projected payback period above.<br><br>Net Present Value is calculated using a 2% inflation rate. 
												Return on Investment is calculated by dividing the total net profit by the total cost. Payback Period calculates 
												the time in months it takes your savings to equal that of your cost, including the implementation period.' ) .
											'</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-9 col-sm-9 col-xs-9">
							<div class="ibox float-e-margins">
								<div class="ibox-title">
									<h5>Summary</h5>';

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
				
			$savingsShell .=	'</div>
								<div class="ibox-content">
									<div class="table-responsive" style="border:2px solid #ddd;">
										<table id="summary-table" class="table table-hover" style="margin-bottom:0;">
											<thead>
												<tr>
													<th></th>';
													
			$roiSpecs = $calculator->retrieveRoiSpecs();
			
			for( $i=0; $i<$roiSpecs['retPeriod']; $i++ ){
				
				$savingsShell .=	'<th>Year '. ($i+1) .'</th>';
			}

			$savingsShell .=						'<th>Total</th>
												</tr>
											</thead>
											<tbody>';

			foreach( $roiSections as $section ){
				
			// Add a table row if the section has a formula defined
				if( $section['formula'] ){
						
					$savingsShell .= '<tr class="value-holder" data-section-name="'. $section['Title'] .'">
												<th class="section-navigation" data-section-id="'. $section['ID'] .'"><a class="section-navigator smooth-scroll table-scroll" data-section-type="section" href="#section'. $section['ID'] .'">'. $section['Title'] .'</a></td>';

					for( $i=0; $i<$roiSpecs['retPeriod']; $i++ ){	
					
						$savingsShell .=	'<td class="section-total" data-section-id="'. $section['ID'] .'" data-format="($0,0)" data-formula="SECTIONTOTAL('. $section['formula'] .', '. ($i+1) .', '. $section['ID'] .')"></td>';
					}

					$savingsShell .=	'<td class="section-total" data-section-id="'. $section['ID'] .'" data-format="($0,0)" data-formula="SECTIONTOTAL('. $section['formula'] .', \'total\', '. $section['ID'] .')"> 0</td>
									</tr>';
					
				}
			}
				
			$savingsShell .=	'<tr class="value-holder" data-section-name="cost">
									<th class="cost-row">Cost</th>';
										
			for( $i=0; $i<$roiSpecs['retPeriod']; $i++ ){
					
				$savingsShell .=	'<td class="cost" data-format="($0,0)" data-formula="ANNUALCOST('. ($i+1) .')"></td>';
			}

			$savingsShell .=	'<td class="cost" data-format="($0,0)" data-formula="ANNUALCOST(\'total\')"></td>
							</tr>
							<tr class="value-holder" data-section-name="total">
								<th class="annual-total-row">Total</td>';

			for( $i=0; $i<$roiSpecs['retPeriod']; $i++ ){	

				$savingsShell .=	'<td class="section-total" data-format="($0,0)" data-formula="';
											
				$sectiontotals = 1;
				foreach( $roiSections as $section ){
						
					if( $section['formula'] ){
							
						$savingsShell .=	( $sectiontotals == 1 ? '' : '+' ). 'SECTIONTOTAL('. $section['formula'] .', '. ($i+1) .', '. $section['ID'] .', \'true\')';
					}
						
					// Add one to the section total
					$sectiontotals++;
				}
				
				$savingsShell .=	'+ ANNUALCOST('. ($i+1) .')"></td>';
			}
				
			$savingsShell .=	'<td class="roi-summary-total section-total" data-format="($0,0)" data-formula="';
				
			$sectiontotals = 1;
			
			foreach( $roiSections as $section ){
				
				if( $section['formula'] ){
					
					$savingsShell .= ($sectiontotals == 1 ? '' : '+' ). 'SECTIONTOTAL('. $section['formula'] .', \'total\', '. $section['ID'] .', \'true\')';
					
					$sectiontotals++;
				}
			}											
				
			$savingsShell .=	'+ ANNUALCOST(\'total\')"></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>';
							
			$savingsShell .=	buildCostInputs();
						
			$savingsShell .=	buildSummaryStatistics();
			
			$savingsShell .=	'</div>
							</div>
						</div>
						<div class="row border-bottom gray-bg dashboard-header">
							<div class="col-lg-12">
								<div class="row">
									<div class="col-md-12 col-sm-12 col-xs-12">
										<div class="ibox float-e-margins">
											<div class="ibox-title">
												<h5>
													Summary
												</h5>';
												
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
			
			$savingsShell .=	'</div>
									<div class="ibox-content" style="padding-left: 30px;">
										<div class="row bar-chart-container">
											<div id="bar-chart" class="bar-chart" style="width:100%"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>';
			
		return $savingsShell;
							
	}
	
	function buildCostInputs(){
		
		$calculator = new CalculatorActions($db);
			
		// Get the ROI Specs for the current ROI
		$roiSections = $calculator->retrieveRoiSections();
		
		// Get verification Level form the php/verification.php file
		$verification_lvl = verificationLevel();
		
		$costInputs	=	
		
			'<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Costs</h5>';
								
		/* Configuration code for later use:
		
		if($verification_lvl>1) {
								
			$costInputs .=	'<div class="ibox-tools">
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

		// Get the ROI Specs, to determine the return period of the ROI.
		$roiSpecs = $calculator->retrieveRoiSpecs();
		
		$costInputs	.=	
				'</div>
				<div class="ibox-content">				
					<div class="table-responsive" style="border:2px solid #ddd;">
						<table class="table" style="margin-bottom:0;">
							<thead>
								<tr>
									<th></th>';

		// Calculator costs does not currently exist, but will be added later to allow users to have their own custom cost values
		// in the header.
		
		if( $calculatorCosts['headers'] ){
			
			$headers = json_decode($calculatorCosts['headers'], true);
			foreach( $headers as $header ){
				
				$costInputs	.=	'<th>'. $header .'</th>';
			}
		} else {
			
			$costInputs	.=	'<th>Implementation</th>';
			
			for( $i=0; $i<$roiSpecs['retPeriod']; $i++ ){
				
				$costInputs	.=	'<th>Subscription - Year '. ($i+1) .'</th>';
			}
		}

		$costInputs	.=	'</tr>
							</thead>
							<tbody>
								<tr>
									<td width="25%" style="margin-top:15px;">Cost</td>
									<td>
										<input data-cost-yr="0" id="cost_imp" class="form-control cost-holder" type="text" name="inicost" data-cell="C1" data-format="$0,0">
									</td>';
							
		$costsadded = 2;
		for( $i=0; $i<$roiSpecs['retPeriod']; $i++ ){		
													
			$costInputs	.=			'<td>
										<input data-cost-yr="'. ($i+1) .'" id="cost_'. ($i+1) .'" class="form-control cost-holder" type="text" name="yr'. ($i+1) .'cost1" data-cell="C'. $costsadded .'" data-format="$0,0">
									</td>';
			
			$costsadded++;
		}
		
		$costInputs	.=			'</tr>
							</tbody>
						</table>
					</div>
				</div>				
			</div>
		</div>';
		
		return $costInputs;

	}
	
	function buildSummaryStatistics(){
	
		$calculator = new CalculatorActions($db);
			
		// Get the ROI Specs for the current ROI
		$roiSpecs = $calculator->retrieveRoiSpecs();
		
		$summaryStatistics .=	'<div class="col-lg-3">
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
								</div>';	
		

		
		return $summaryStatistics;
		
	}

?>