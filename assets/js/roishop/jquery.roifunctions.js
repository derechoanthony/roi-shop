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
	
	var $element = $(this),
		$chart	 = $(this).find('.graph-holder').highcharts(),
		$opts    = $element.data('roi-element').options,
		$series  = $opts.highchart.series,
		$totalSeries = 0;

	if($series){
		
		$.each($series, function(i, s){
			
			var $data = s.data;
			if($data){
				
				var $formulas = $data.formula,
					$values = [];
		
				$.each($formulas, function(i, f){
					
					$evaluatedValue = $('#wrapper').calx('evaluate', f);
					$values.push({
						y:$evaluatedValue
					});
				});		
			};
			
			$chart.series[$totalSeries].update({
				data: $values
			}, false);
			
			$totalSeries++;
		});
		
		$chart.redraw();
	};	
};

function updateCharts() {
	
	var $divs = $('div:data(roi-element)');
	
	$divs.each(function(){
		
		var $opts = $(this).data('roi-element').options,
			$type = $opts.el_type;
			
		if($type == 'graph'){
			$(this).updateChart();
		};
	});
}

function cleanCalxCells($cells) {
	
	var $includedCells = new Array();
	
	$cells.each(function(){
		
		var $cell = $(this),
			$cellId = $cell.data('cell');
		
		if( $.inArray($cellId, $includedCells) != -1 ) {
			
			$cell.removeAttr('data-cell');
		} else {
			
			$includedCells.push( $cellId );
		};
	});
}