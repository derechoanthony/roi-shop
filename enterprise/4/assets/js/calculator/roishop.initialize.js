;(function( $ ){
	
	var current_roi = getQueryVariable('roi'),
		initial = "assets/ajax/json/mcafee.json";
		url = "assets/ajax/json/" + current_roi + ".json";
		
	var action 		= 'getverification',
		ajax_url 	= 'assets/ajax/calculator.get.php';
	
	$.get( ajax_url, { action: action, roi: current_roi } )
		.done(function(verification){
			verification = JSON.parse(verification);
			$.getJSON(url)
				.done(function(options){
					options.roiInfo = verification.roiInfo;
					$('#wrapper').roishopCalculator(options);
				})
				.fail(function(options){
					$.getJSON(initial)
						.done(function(options){
							options.roiInfo = verification.roiInfo;
							$('#wrapper').roishopCalculator(options);
						});
				});			
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