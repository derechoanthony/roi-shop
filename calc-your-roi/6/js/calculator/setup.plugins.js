;(function( $ ){
	
	'use strict';
	$.fn.setupPlugins = function( options ) {

		var settings = {
			videoHolder: '.player',
			calxSelector: '#wrapper',
			tooltipster: '.tooltipstered',
			datatable: '.datatable',
			chosenSelector: '.chosen-selector',
			graphSelector: '.ROICalcElemID',
			calxAction: 'blur'
		};
		
		if ( options ) {
			$.extend( settings, options );
		};
		
		/***
		*
			Video Plugin 
		*
		*/
		
		$( settings.videoHolder ).fitVids();
		
		/***
		*
			Calculation Plugin Definition 
		*
		*/
		
		$( settings.calxSelector ).calx({
			
			// Define when the calculator will actually perform the calculations.
			autoCalculate			:	false,
			onAfterCalculate		:	function() {
				//updateChart();
			}
		});
		
		/***
		*
			Tooltipster Plugin Definition 
		*
		*/
		
		$( settings.tooltipster ).tooltipster({
			
			// Setup tooltips. Define tooltip options.
			theme: 'tooltipster-light',
			maxWidth: 300,
			animation: 'grow',
			position: 'right',
			arrow: false,
			interactive: true,
			contentAsHTML: true
		});
		
		
		/***
		*
			Setup the Chosen Plugin
		*
		*/
		
		$( settings.chosenSelector ).chosen({
			width: '100%',
			disable_search_threshold: 10
		});		
		
		/***
		*
			Datatable set up 
		*
		*/
		
		$( settings.datatable ).each(function(){
			
			$(this).dataTable();
		});		

		/***
		*
			Calculate the Entire Sheet 
		*
		*/
		
		$( settings.calxSelector ).calx('getSheet').calculate();
		
		/***
		*
			Setup Graphs 
		*
		*/
		
		$( settings.graphSelector ).each(function() {
			
			var roi_elem_id = $(this).attr('data-id');

			$.ajax({
							
				type		:	'GET',
				url			:	'ajax/calculator.get.php',
				data		:	{
					action		:	'getchartarray',
					chartid		:	roi_elem_id
				},
				dataType: 'json',
				success		:	function(chartarray) {

					var chart = new Highcharts.Chart(
						JSON.parse(chartarray)
					);
				}
				
			});
			
		});
			
			
		$('input[data-cell-reference]').on(settings.calxAction, function(){

			// Get the ID of the current cell that was changed.
			var current_cell = $(this).data('cell');

			// Get the dependents of the cell that was changed.
			var cell_dependents = $( settings.calxSelector ).calx('getCell', current_cell).getAllDependents();

			// Make cell dependent array contain only unique values.
			cell_dependents = $.unique( cell_dependents );

			// Loop through all cell dependents and render the values.
			var i;

			for( i in cell_dependents ) {

				// Get reference of current cell to be evaluated
				var cell_reference = $(settings.calxSelector).calx('getCell', cell_dependents[i]);

				// Get cell value
				var cell_value = cell_reference.evaluateFormula();

				// Set value and render the value

				cell_reference
					.setValue(cell_value)
					.renderComputedValue();

			};
			
			updateChart();
		});
		
		$('input').on('focus', function(){
			
			// Select contents of inputs when user focuses on input. This is done so that
			// user can quickly enter values even if input already contains a number or
			// more importantly is a percentage. Otherwise cursor is to right of the "%"
			$(this).select();
		});
		
		$('.input-addon').focus(function(){
			
			// Add border styling to add on portion of input when input is focused.
			$(this).parent().find('.helper').toggleClass('input-addon-border');
		}).blur(function(){
			
			// Remove border styling from add on potion of input when input loses fous.
			$(this).parent().find('.helper').toggleClass('input-addon-border');
		});
		
		/***
		*
			Smooth Scroll 
		*
		*/		
		
		if( $(".smooth-scroll").length>0 ) {
			
			if( $(".header.fixed").length>0 ) {
				
				$('.smooth-scroll a[href*=#]:not([href=#]), a[href*=#]:not([href=#]).smooth-scroll').click( function() {
					if( location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname ) {
						
						var target = $(this.hash);
						target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
						
						if( target.length ) {
							
							$('html,body').animate({
								scrollTop: target.offset().top-85
							}, 1000);
							return false;
						}
					}
				});
				
			} else {
				
				$('.smooth-scroll a[href*=#]:not([href=#]), a[href*=#]:not([href=#]).smooth-scroll').click( function() {
					if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
						
						var target = $(this.hash);
						target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
						
						if (target.length) {
							
							$('html,body').animate({
								scrollTop: target.offset().top - $('.navbar-fixed-top').height()
							}, 1000);
							return false;
						}
					}
				});
			}
		
		}		
	};
	
})( window.jQuery || window.Zepto );

function updateChart(){
		
	try {

		$('.graph-holder').each(function(){

			var chartHolder = $(this);
			$(this).find('.ROICalcElemID').each(function(){
					
				var chart = $(this).highcharts();
				var totalSeries = 0;
					
				chartHolder.find('.series-holder').each(function(){
					
					var series_name = $(this).data('series-name');
					var series_data = [];
					
					$(this).find('input.graph-formula').each(function(){
						
						var cell = $(this).attr('data-cell');
						var input_value = $('#wrapper').calx('getCell', cell).getValue();
						
						if(chart.options.chart.type == 'pie'){
							series_data.push({ name: $(this).attr('data-series-name'), y: input_value, sliced: $(this).attr('data-sliced') == 1 ? true : false });
						} else {
							series_data.push(input_value);
						}
					});
					
					chart.series[totalSeries].update({name: series_name}, false);
					chart.series[totalSeries].update({data: series_data}, false);
					totalSeries++;
				});
					
				chart.redraw();
			});
			
		});
	
	} catch(e) {}
}

function getUrlVars() {
		
	// Get URL Vars function gets the variable from the browser address bar. This is needed to
	// send variables through AJAX calls which include address bar variables.
	
	var vars = [], hash;
	var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');

	for(var i = 0; i < hashes.length; i++) {
		
		hash = hashes[i].split('=');
		vars.push(hash[0]);
		if(hash[1]) {
			vars[hash[0]] = hash[1].replace('#','');
		}
	}
	
	return vars;
}