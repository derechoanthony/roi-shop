$(document).ready(function(){
	
	/***************************************************/
	/************ SET UP INITIAL CALCULATOR ************/
	/***************************************************/
	
	// Remove duplicate data-cell definitions and allow calx to assign default data-cell
	// definitions to them. Their values will match the other inputs with the same cell name
	// through the data-cell-reference attribute.
	
	$('input[data-cell-reference], textarea').on('blur', function(){

		var datacell = $(this).data('cell');
		var cellDependents = $('#wrapper').calx('getCell', datacell).getAllDependents();

		var cellValues = [];
			
		cellValues.push(getCellValueArray($(this)));

		storeChangedValues(cellValues);
		
		$('#wrapper').calx('getSheet').calculate();
	});
	
	$('select').on('change', function() {
		
		var cellValues = [];
			
		cellValues.push(getCellValueArray($(this)));

		storeChangedValues(cellValues);		
		hideSelectChildren($(this));
		showSelectChildren($(this));

		$('#wrapper').calx('getSheet').calculate();
	});
	
	$('.quotes').quovolver({
		autoPlaySpeed : 8000,
		transitionSpeed : 500
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
	
$('#create-pdf').on('click', function() {
		
		$('#pdf_save')[0].click();
	});
	
	$('#pdf_save').on( 'click', function() {
		
		$('#pdf-progress-overlay').show();
		
		$.ajax({
			type	: 	"POST",
			url		:	"ajax/calculator.post.php",
			data	:	'action=deletepdf&roi='+getUrlVars()['roi'],
			success	:	function() {
				if($('.ROICalcElemID').length > 0 ){
					createCharts(0);
				} else {
					savePdfElements(0);
				}
				
			}
		});

		function createCharts( chrt ) {
			
			var totalElementsToCreate = $('[data-pdf-element]').length + $('.ROICalcElemID').length;
			var currentChart = chrt + 1;
			var currentProgress = currentChart / totalElementsToCreate * 100;

			$('.pdf-progress-alert').html('Creating charts for PDFs (' + currentChart + ' of ' + $('.ROICalcElemID').length + ')');
			$('.pdf-progress-bar').width(currentProgress + '%');
			
			if(currentProgress > 66) {
				$('.pdf-progress-bar').removeClass('progress-bar-warning').removeClass('progress-bar-danger').addClass('progress-bar-success');
			} else if (currentProgress > 33) {
				$('.pdf-progress-bar').removeClass('progress-bar-success').removeClass('progress-bar-danger').addClass('progress-bar-warning');
			}
			
			var chart = $('.ROICalcElemID:eq('+chrt+')').highcharts();
			var chartnumber = $('.ROICalcElemID:eq('+chrt+')').attr('data-id');
			var opts = chart.options;        // retrieving current options of the chart
			opts = $.extend(true, {}, opts); // making a copy of the options for further modification
			delete opts.chart.renderTo;      // removing the possible circular reference

			$('.ROICalcElemID:eq('+chrt+')').each(function(){
				
				var chart = $(this).highcharts();
				var totalSeries = 0;
				
				var seriesNumber = 0;
				$(this).closest('.chart-holder').find('.series-holder').each(function(){
					
					var seriesName = $(this).find('.series-name').attr('data-cell');
					var newSeriesName = $('#wrapper').calx('getCell', seriesName).getValue();					
					var seriesData = [];
					$(this).find('input.graph-formula').each(function(){
						
						var cell = $(this).attr('data-cell');
						var inputValue = $('#wrapper').calx('getCell', cell).getValue();
						if(chart.options.chart.type == 'pie'){
							seriesData.push({ name: $(this).attr('data-series-name'), y: inputValue, sliced: $(this).attr('data-sliced') == 1 ? true : false });
						} else {
							seriesData.push(inputValue);
						}
					});
					opts.series[seriesNumber].data = seriesData;
					opts.series[seriesNumber].name = newSeriesName;
					seriesNumber++;
				});
			});
			
			if( $('.ROICalcElemID:eq('+chrt+')').hasClass('pie-chart') )
			{
				var width = 600;
			} else {
				var width = 700;
			}

			/* Here we can modify the options to make the printed chart appear */
			/* different from the screen one                                   */

			var strOpts = JSON.stringify(opts);

			$.post(
				'//export.highcharts.com/',
				{
					options: strOpts ,
					type:    'png',
					width:	 width,
					async:   true
				},
				function(data){
					var imgUrl = 'http://export.highcharts.com/' + data;
					/* Here you can send the image url to your server  */
					/* to make a PDF of it.                            */
					/* The url should be valid for at least 30 seconds */
					$.post("save-graph.php?roi="+getUrlVars()['roi']+'&chrt='+chartnumber,{
						imageData: encodeURIComponent(imgUrl)
					});
					
					chrt += 1;
					if(chrt < $('.ROICalcElemID').length) {
						
						createCharts(chrt);
					} else {
						
						savePdfElements(0);
					}			
				}
			).fail(function(){
				
				savePdfElements(0);
			});
				
			
		}
				
		function savePdfElements( element ) {
					
			var totalElementsToCreate = $('[data-pdf-element]').length + $('.ROICalcElemID').length;
			var currentElement = element + 1;
			var currentProgress = (currentElement + $('.ROICalcElemID').length) / totalElementsToCreate * 100;
			
			$('.pdf-progress-alert').html('Saving PDF Elements (' + currentElement + ' of ' + $('[data-pdf-element]').length + ')');
			$('.pdf-progress-bar').width(currentProgress + '%');
			
			if(currentProgress > 66) {
				$('.pdf-progress-bar').removeClass('progress-bar-warning').removeClass('progress-bar-danger').addClass('progress-bar-success');
			} else if (currentProgress > 33) {
				$('.pdf-progress-bar').removeClass('progress-bar-success').removeClass('progress-bar-danger').addClass('progress-bar-warning');
			}
			
			if( $('[data-pdf-element]:eq('+element+')').data('content-type') == "chart" )
			{
				pdf_html = '<img src="../../../company_specific_files/73/pdfs/' + getUrlVars()['roi'] + 'chart' + $('[data-pdf-element]:eq('+element+')').html() + '.png">'
			} else {	
				pdf_html = encodeURIComponent( $('[data-pdf-element]:eq('+element+')').html() );
			}
			var pdf_element = $('[data-pdf-element]:eq('+element+')').data('pdf-element');
			var pdf_element_x = $('[data-pdf-element]:eq('+element+')').data('pos-x');
			var pdf_element_y = $('[data-pdf-element]:eq('+element+')').data('pos-y');
			var pdf_page = $('[data-pdf-element]:eq('+element+')').closest('[data-page]').data('page');

			
			$.ajax({
				type	: 	"POST",
				url		:	"ajax/calculator.post.php",
				data	:	'action=changepdf&element='+pdf_element+'&html='+pdf_html+'&posx='+pdf_element_x+'&page='+pdf_page+'&posy='+pdf_element_y+'&roi='+getUrlVars()['roi'],
				success	:	function() {
					element += 1;
					if(element < $('[data-pdf-element]').length) {
						savePdfElements(element);
					} else {
						$('#pdf-progress-overlay').hide();
						$('.pdf-progress-alert').html('Beginning to build the PDF');
						$('.pdf-progress-bar').removeClass('progress-bar-warning').removeClass('progress-bar-success').addClass('progress-bar-danger');
						$('#pdf_create_new_template')[0].click();
					}					
				}
			});
					
		}
				
	});
	
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

		$.getJSON('../4/construct/charting/chartoptions.php?ROICalcElemID=' + roiElemId)
			.done(function(json) {
				//console.log(JSON.stringify(json));
				chart = new Highcharts.Chart(
					json
				);
				updateChart();
			}).fail( function( jqxhr, textStatus, error ){
				var err = textStatus + ", " + error ;

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