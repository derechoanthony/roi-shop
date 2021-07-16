;(function( $ ){
	
	'use strict';
	$.fn.roishopActions = function( options ) {

		var settings = {};
		if ( options ) {
			$.extend( settings, options );
		};
		
		var $this = $(this);
		eval(options.el_action);
		
		function storeRoiArray() {

			var $roiArray = JSON.stringify( serializeRoiArray() ),
				$ajaxData = [];	
			
			$.post("/assets/ajax/calculator.post.php", { action: 'storeRoiArray', roi: getUrlVars()['roi'], array: $roiArray }, function(returned){
				
			});
		};
		
		function serializeRoiArray() {
			
			var $content = $('#roiContent'),
				$eHolder = $content.children(':data(roi-element)');

			return $eHolder.roishop('serialize');
		};
		
		function moveTableRow(element, moveTo){
			
			// Find element to move
			var $element = $('[data-roi-element-id="' + element + '"]');
			var $parent = $element.closest(':data(roi-element)');
			
			var rows = $parent.data('roi-element').options.rows;
			
			var indexToRemove,
				elementToMove;
				
			$.each(rows, function(i, row){
				
				if(row.el_id == element){

					indexToRemove = i;
					elementToMove = row;
				}
			});
			
			rows.splice(indexToRemove, 1);
			
			$parent.roishop('redraw');
			
			var $newElement = $('[element-id="' + moveTo + '"]')
			var newRow = $newElement.data('roi-element').options.rows;
			
			newRow.push(elementToMove);
			
			newRow.sort(function(a, b){

				let comparison = 0;
				if(a.el_pos > b.el_pos){
					comparison = 1;
				} else if (a.el_pos < b.el_pos){
					comparison = -1;
				}
				return comparison;
			});
			
			$newElement.roishop('redraw');
		};
		
		function showElements(ids){

			var $tablesToRedraw = [];
			ids = ids.split('|');
			
			$.each(ids, function(){
				var $div = $('[element-id="' + this + '"]').show();
				if($div.length){
					
					var dOpts = $div.data('roi-element').options;
					dOpts.el_visibility = 1;
				};
				
				// If this is a table row it needs to be shown differently
				var $el = $('[data-roi-element-id="' + this + '"]');
				if($el.length){
					
					// Get row index
					var rIndex = $el.data('rowIndex');
					var $table = $el.closest(':data(roi-element)');
					var opts = $table.data('roi-element').options;
					opts.rows[rIndex].el_visibility = 1;

					if( $.inArray($table, $tablesToRedraw) < 0 ){
						$tablesToRedraw.push($table);
					};
				};
			});
			
			$.each($tablesToRedraw, function(){
				
				this.roishop('redraw');
			});
		};
		
		function hideElements(ids){
			
			var $tablesToRedraw = [];
			ids = ids.split('|');
			
			$.each(ids, function(){
				var $div = $('[element-id="' + this + '"]').hide();
				if($div.length){
					
					var dOpts = $div.data('roi-element').options;
					dOpts.el_visibility = 0;
				};
				
				// If this is a table row it needs to be shown differently
				var $el = $('[data-roi-element-id="' + this + '"]');
				if($el.length){
					
					// Get row index
					var rIndex = $el.data('rowIndex');
					var $table = $el.closest(':data(roi-element)');
					var opts = $table.data('roi-element').options;
					opts.rows[rIndex].el_visibility = 0;
					
					if( $.inArray($table, $tablesToRedraw) < 0 ){
						$tablesToRedraw.push($table);
					};
				};
			});
			
			$.each($tablesToRedraw, function(){
				
				this.roishop('redraw');
			});
		};
		
		function showElement($element){
			
			$('[element-id="' + $element + '"]').show();
		};
		
		function hideElement($element){
			
			$('[element-id="' + $element + '"]').hide();
		};
	};

	$('.activateTab').on('click', function(e){
		e.preventDefault();
		var tab = $(this).data('reference');
		$('a[href="#tab-' + tab + '"]').tab('show');
	});
	
})( window.jQuery || window.Zepto );


