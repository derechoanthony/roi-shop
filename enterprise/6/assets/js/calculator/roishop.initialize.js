;(function( $ ){
	
	// Ajax call to return the build of the current ROI
	var current_roi	= getQueryVariable('roi'),
		action 		= 'getroiarray',
		ajax_url 	= 'assets/ajax/calculator.get.php';
	
	$.get( ajax_url, { action: action, roi: current_roi } )
		.done(function(elements){

			elements = JSON.parse(elements);	
console.log(elements);
			options = {
				elements: elements.structure,
				fields: elements.fields,
				filters: elements.filters,
				navigation: elements.navigation,
				roiTemplate: {
					company_id: 52
				},
				roiInfo: elements.roiInfo
			};
			
			$('#wrapper').roishopCalculator(options);
		});
	

	
})( window.jQuery || window.Zepto );

function getQueryVariable(variable) {
	
	var query = window.location.search.substring(1),
		vars = query.split("&");

	for (var i=0;i<vars.length;i++) {
		
		var pair = vars[i].split("=");
		if(pair[0] == variable){ return pair[1]; }
	}
	
	return(false);
};		