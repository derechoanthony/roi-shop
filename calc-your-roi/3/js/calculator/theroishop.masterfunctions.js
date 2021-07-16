$(document).ready(function(){
	
	/***************************************************/
	/************ SET UP INITIAL CALCULATOR ************/
	/***************************************************/
	
	// Remove duplicate data-cell definitions and allow calx to assign default data-cell
	// definitions to them. Their values will match the other inputs with the same cell name
	// through the data-cell-reference attribute.
	
	$('input[data-cell-reference]').on('blur', function(){


		var data_cell = $(this).data('cell');
		var sheet = $('#wrapper');
		
		var dependents = sheet.calx('getCell', data_cell).getAllDependents();
		dependents = $.unique(dependents);
		
		for(i=0; i<dependents.length; i++){
			sheet.calx('getCell', dependents[i]).calculate(false, true);
		};

		var cellValues = [];
			
		cellValues.push(getCellValueArray($(this)));

		storeChangedValues(cellValues);

		updateChart();
	});

	var cells = new Array();
	
	$('[data-cell]').each(function(){
			
		// Get current cell ID
		cellId = $(this).data('cell');
		
		// Determine it current cell is in the cells array
		if($.inArray(cellId, cells) != -1){
			
			// If cell is already in array then remove the attr.
			$(this).removeAttr('data-cell');
		} else {
			
			// If cell isn't in array then add it to the array.
			cells.push($(this).data('cell'));
		}	
	});
	
	$('#wrapper').calx({
		
		// Define when the calculator will actually perform the calculations.
		autoCalculate			:	false,
		onAfterCalculate		:	function() {
			updateChart();
		}
	});
	
	$('input').on('focus', function(){
		
		// Select contents of inputs when user focuses on input. This is done so that
		// user can quickly enter values even if input already contains a number or
		// more importantly is a percentage. Otherwise cursor is to right of the "%"
		$(this).select();
	});
	
	$('#side-menu').metisMenu({
		
		// Set up the navigation menu on the left. Setting toggle to false allows multiple
		// section dropdowns to be expanded at once.
		toggle: false
	});
	
	$('.tooltipstered').tooltipster({
		
		// Setup tooltips. Define tooltip options.
		theme: 'tooltipster-light',
		maxWidth: 300,
		animation: 'grow',
		position: 'right',
		arrow: false,
		interactive: true,
		contentAsHTML: true
	});
	
	// Make all videos fit within the div whenever the page is opened or resized.
	$(".player").fitVids();
	
	// Make all dropdowns to use the chosen plugin.
	$('.chosen-selector').chosen({
		width: '100%',
		disable_search_threshold: 10
	});
	
	$('.datatable').each(function(){
		
		$(this).dataTable();
	});
	
	$('select').on('change', function() {
		
		var cellValues = [];
			
		cellValues.push(getCellValueArray($(this)));

		storeChangedValues(cellValues);		
		hideSelectChildren($(this));
		showSelectChildren($(this));
	});
	
	$('.input-addon').focus(function(){
		
		// Add border styling to add on portion of input when input is focused.
		$(this).parent().find('.helper').toggleClass('input-addon-border');
	}).blur(function(){
		
		// Remove border styling from add on potion of input when input loses fous.
		$(this).parent().find('.helper').toggleClass('input-addon-border');
	});
	
	loadValues();
	
	if ($(".smooth-scroll").length>0) {
		if($(".header.fixed").length>0) {
			$('.smooth-scroll a[href*=#]:not([href=#]), a[href*=#]:not([href=#]).smooth-scroll').click(function() {
				if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
					var target = $(this.hash);
					target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
					if (target.length) {
						$('html,body').animate({
							scrollTop: target.offset().top-85
						}, 1000);
						return false;
					}
				}
			});
		} else {
			$('.smooth-scroll a[href*=#]:not([href=#]), a[href*=#]:not([href=#]).smooth-scroll').click(function() {
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
	
	buildChart();
	
});

function loadValues(){
	
	$.ajax({
		type	: 	"GET",
		url		:	"../../php/database.manipulation.php",
		data	:	'action=getvalues&roi=' + getUrlVars()['roi'],
		success	:	function( values ) {
				
			// Parse returned values
			values = $.parseJSON( values );
				
			// Place returned values into CurrentValues[] array
			CurrentValues = [];
			for( i=0; i<values.length; i++ ) {
				
				var entryCell = $('[name="' + values[i]['entryid'] + '"]');
				var entryValue = values[i]['value'];
				
				entryCell.each(function() {
					
					$(this).val( entryValue );
					
					try{
						$('#wrapper').calx( 'getCell', $(this).data('cell') ).setValue( entryValue ).renderComputedValue();
					} catch(e){ }					
				});
				
				$('.chosen-selector').trigger('chosen:updated');
	
				$('select').each(function() {

					hideSelectChildren($(this));		
				});
				
				$('.chosen-container:visible').each(function() {
					
					showSelectChildren($(this).parent().find('select'));
				});

			}
			
			$('#wrapper').calx('getSheet').calculate();
		}
	});	
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

function buildChart() {
	
	$('.ROICalcElemID').each(function() {

		var roiElemId = $(this).attr('data-id');

		$.getJSON('../3/construct/charting/chartoptions.php?ROICalcElemID=' + roiElemId)
			.done(function(json) {
				//console.log(JSON.stringify(json));
				chart = new Highcharts.Chart(
					json
				);
				updateChart();
			}).fail( function( jqxhr, textStatus, error ){
				var err = textStatus + ", " + error ;
				console.log( "Request Failed: " + err );
				console.log(jqxhr);
			});
	});
}

function hideSelectChildren(select) {
		
	// Determine if any options have a show-map data tags
		
	select.find('option').each(function() {
			
		if($(this).data('show-map')) {
			
			var showMap = $(this).data('show-map').split(',');
			$.each( showMap, function( i, val ) {

				$(val).closest('.form-group').hide();
				hideSelectChildren($(val));
			});
		}
	});
}
	
function showSelectChildren(select) {
		
	var selectedOption = select.find('option:selected');
		
	if( selectedOption.data('show-map') ) {
			
		var showMap = selectedOption.data('show-map').split(',');
			
		$.each( showMap, function( i, val ) {
						
			$(val).closest('.form-group').show();
			showSelectChildren($(val));
		});				
	}
}

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