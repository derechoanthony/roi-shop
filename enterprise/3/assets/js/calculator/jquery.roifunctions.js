$.fn.serializeElement = function() {

    var $array,
		$element = $(this);
		
	step = function(level) {

		var $childArray 	= 	[],
			$elements 		= 	level.children(':data(roi-element)');
                
		$elements.each(function() {

			var $child		= $(this),
				$options	= $child.data('roi-element').options,
				$subChild  	= $child.children(':data(roi-element)');

			if ($subChild.length) {
				$options.children = step($child);
			};

			$childArray.push($options);
		});
				
		return $childArray;
	};

	$array = step($element);
	return $array;
};

$.fn.updateChart = function() {
	
	var opts = $(this).data('roi-element').options,
		series = opts.highchart.series,
		highchart = $(this).data('roi-element').$highchart;
			
	if(series){
		$.each(series, function(index, value){
			var formulas = value.formula;
			if(formulas){
				var data = [];
				$.each(formulas, function(count, formula){
					var evaluated_formula = $('#wrapper').calx('evaluate', formula);
					data.push(evaluated_formula);
				});
			};
			value.data = data;
				
			highchart.series[index].setData(data);
		});				
	};
};

function updateCharts() {
	
	var roishop_elements = $(':data(roi-element)'),
		that = this;
	
	var graphs = $.map(roishop_elements, function(element){
		if( $(element).data('roi-element').options.el_type == 'graph' ){
			return element;
		}
	});
			
	$.each(graphs, function(){
		$(this).updateChart();
	});
}

