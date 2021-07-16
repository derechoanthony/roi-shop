;(function( $ ){
	
	// Ajax call to return the build of the current ROI
	var current_roi	= getQueryVariable('roi'),
		action 		= 'getroiarray',
		ajax_url 	= '/php/ajax/calculator/calculator.get.php';
	
	$.get( ajax_url, { action: action, roi: current_roi } )
		.done(function(roi_structure){

			var roi_structure  		= JSON.parse(roi_structure),
				roi_main_structure  = roi_structure.structure,
				roi_stored_array	= roi_structure.storedArray,
				roi_navigation 		= roi_structure.navigation,
				roi_values	 		= roi_structure.storedValues,
				roi_info			= roi_structure.roiInfo,
				roi_contributors	= roi_structure.contributors,
				roi_owner			= roi_structure.roiOwner,
				roi_template		= roi_structure.roiTemplate,
				roi_sections		= roi_structure.roiSections,
				roi_pdf				= roi_structure.pdfSetup;
console.log(roi_values);
		if(roi_values){
			iterate_child = function(parent) {
				children = parent.children;
				if(children){
					$.each(children, function(index, value){
							
						var value_index = roi_values.findIndex( x => x.el_id === value.el_id ),
							value_array = roi_values[value_index];
							
						if(value_array){
							value.el_value 		= roi_values[value_index].el_value
							value.el_visibility = roi_values[value_index].el_visibility
							value.selectedIndex = roi_values[value_index].selectedIndex							
						};
	
						if(value.children){
							iterate_child(value);
						}
					});
				}
			};

			$.each(roi_main_structure, function(index, value){
				iterate_child(value);
			});			
		}

		roi_elements = roi_main_structure;
		var options = roi_structure.storedArray;
		console.log(options);
		if (!options){
			options = {
				elements: roi_elements,
				navigation: roi_navigation,
				roiInfo: roi_info,
				contributors: roi_contributors,
				pdfSetup: roi_pdf,
				roiOwner: roi_owner,
				roiTemplate: roi_template,
				roiSections: roi_sections,
				currency: 'usd'
			};
		}

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