function createCalxCustomFunctions() {
	
	$('#page-wrapper').calx('registerFunction', 'SECTIONTOTAL', function(eq, yr, id, ex){
		
		// Get section conservative factor
		var confactor = ( 100 - $('#currentValueCon'+id).val() ) / 100;
		
		// Get implementation period
		var impperiod = $('#impPeriod').val();
		
		// Get the return period of the ROI
		var returnperiod = $('#retPeriod').val();
		
		// Determine if section is included or not
		var included = $('#check'+id).val();

		if(yr == "total"){
			
			// Calculate percentage of ROI savings after implementation period
			var totalpercentage = ( returnperiod * 12 - impperiod ) / ( returnperiod * 12 );
		} else {
			
			// If year is defined then calculate total for that year only
			var totalpercentage = 1;
			if(impperiod >= yr * 12){ totalpercentage = 0; }
			else if(impperiod < yr * 12 && impperiod >= ( yr - 1 ) * 12) { totalpercentage = ( yr * 12 -impperiod ) / 12; }
			returnperiod = 1;
		}

		var sectiontotal = eq * confactor * returnperiod * totalpercentage;
		
		if(!ex) { return sectiontotal } else { return sectiontotal * included };
		
	});
	
	$('#page-wrapper').calx('registerFunction', 'SECTIONGRAND', function(id){
		
		// Get section conservative factor
		var confactor = ( 100 - $('#currentValueCon'+id).val() ) / 100 || 1;
		
		// Get implementation period
		var impperiod = $('#impPeriod').val();
		
		// Get the return period of the ROI
		var returnperiod = $('#retPeriod').val();
		
		// Determine if section is included or not
		var included = $('#check'+id).val();
		
		var eq = $('#page-wrapper').calx('evaluate', $('.section-equations[data-section-id="'+id+'"]').data('formula') );

		// Calculate percentage of ROI savings after implementation period
		var totalpercentage = ( returnperiod * 12 - impperiod ) / ( returnperiod * 12 );
		
		var sectiontotal = eq * confactor * returnperiod * totalpercentage;

		return sectiontotal;
		
	});
	
	$('#page-wrapper').calx('registerFunction', 'TOTALSAVINGS', function(ex){
		
		var grandTotal = 0;
		$('.section-equations').each(function(){

			grandTotal += $('#page-wrapper').calx('evaluate','SECTIONTOTAL('+$(this).data('formula')+', "total", '+$(this).data('section-id')+','+ex+')');
		});
		
		return grandTotal;
		
	});
	
	$('#page-wrapper').calx('registerFunction', 'NETPV', function(ex){
		
		// Get the return period of the ROI
		var returnperiod = $('#retPeriod').val();
		
		var npv_string = '';
		
		for( i=1; i<=returnperiod; i++ ){
			
			var annualTotal = 0;
			
			$('.section-equations').each(function(){

				annualTotal += $('#page-wrapper').calx('evaluate','SECTIONTOTAL('+$(this).data('formula')+', ' + i + ', '+$(this).data('section-id')+',"true")');
			});
			
			annualTotal += $('#page-wrapper').calx('evaluate','ANNUALCOST(' + i + ')');
			
			if( npv_string ) { npv_string += ', ' + annualTotal } else { npv_string += annualTotal };
		
		}
		
		return $('#page-wrapper').calx('evaluate','NPV(0.02, ' + npv_string + ')');
		
	});
	
	$('#page-wrapper').calx('registerFunction', 'GRANDTOTAL', function(ex){
	
		grandtotal = $('#page-wrapper').calx('evaluate','TOTALSAVINGS('+ex+')') + $('#page-wrapper').calx('evaluate','ANNUALCOST("total")');
		
		return grandtotal;
	});
	
	$('#page-wrapper').calx('registerFunction', 'ANNUALCOST', function(yr){
		
		var totalcost = 0;
		
		if(yr==1) {
		
			$('[data-cost-yr="0"]').each(function(e){
				totalcost -= numeral().unformat( $(this).val() );
			});
			
			$('[data-cost-yr="1"]').each(function(e){
				totalcost -= numeral().unformat( $(this).val() );
				//totalcost -= $('#page-wrapper').calx('getCell', $(this).data('cell')).getValue();
			});
		} else {
			
			$('[data-cost-yr="'+yr+'"]').each(function(e){
				totalcost -= numeral().unformat( $(this).val() );
				//totalcost -= $('#page-wrapper').calx('getCell', $(this).data('cell')).getValue();
			});			
		}
		
		if(yr=="total") {
			
			// Loop through all costs
			$('[data-cost-yr]').each(function(e){
				totalcost -= numeral().unformat( $(this).val() );
			});
		}
		
		return totalcost;
	});
	
	$('#page-wrapper').calx('registerFunction', 'ANNUALTOTAL', function(yr){
		
		var annualTotal = 0;
		
		$('.section-equations').each(function(){
			
			annualTotal += $('#page-wrapper').calx('evaluate','SECTIONTOTAL('+$(this).data('formula')+', '+yr+', '+$(this).data('section-id')+',"true")');
		});
		
		return annualTotal -= $('#page-wrapper').calx('evaluate','ABS(ANNUALCOST(' + yr + '))');
	});
	
	$('#page-wrapper').calx('registerFunction', 'ROI', function(){
		
		var totalSavings = $('#page-wrapper').calx('evaluate','TOTALSAVINGS("true")');

		var annualCost = $('#page-wrapper').calx('evaluate','ABS(ANNUALCOST("total"))');

		return totalSavings / annualCost;
		
	});
	
	$('#page-wrapper').calx('registerFunction', 'PAYBACK', function(){
		
		var retPeriod = $('#retPeriod').val();
		
		var impPeriod = $('#impPeriod').val();
		
		var totalCost = $('#page-wrapper').calx('evaluate','ABS(ANNUALCOST("total"))');
		
		var currentSavings = -totalCost;
		
		var savingsReturned = false;
		
		// Loop through each year of ROI
		for( var yr = 1; yr <= retPeriod; yr++ ) 
		{

			var annualSavings = 0;
			
			$('.section-equations').each(function(){

				annualSavings += $('#page-wrapper').calx('evaluate','SECTIONTOTAL('+$(this).data('formula')+', '+yr+', '+$(this).data('section-id')+',"true")');
			});
			
			currentSavings += annualSavings;
			
			if(currentSavings > 0 && !savingsReturned) {
				return ( annualSavings - currentSavings ) / annualSavings * ( yr * 12 ) + impPeriod * 1;
				savingsReturned = true;
			}
			
		}
		
		if(currentSavings<=0 && !savingsReturned) {
			return retPeriod * 12 + impPeriod * 1;
		}
		
	});
	
}