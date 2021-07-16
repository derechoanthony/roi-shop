$(document).ready(function() {
	
	$('td').dblclick(function(){
	
		var el = $(this).find('span');
		var $datacell = el.attr('data-cell');
		var $value = $('#wrapper').calx('getSheet').getCell($datacell).getValue();
		var $format = el.attr('data-format');
		var $formula = el.attr('data-formula');
		var $originalformula = el.attr('data-original-formula');
		
		var $input = '<input id="' + $datacell + '" \
						type="text" \
						class="form-control" \
						value="' + numeral($value).format($format) + '" \
						name="' + $datacell + '" \
						data-cell-reference="' + $datacell + '" \
						data-original-formula="' + $originalformula + '" \
						data-format="' + $format + '" \
						data-cell="' + $datacell + '">';
						
		var $inputgroup = 	'<div class="input-group override-output">'
								+ $input +
								'<span class="input-group-addon right output">\
									<i class="fa fa-check override-table-value" style="color: green"></i>\
									<i class="fa fa-times reset-original" style="color: red"></i>\
								</span>\
							</div>';
		
		$(this).html($inputgroup);
		
		$('#wrapper').calx('getSheet').refresh();
		$('input#' + $datacell).select();
	});
	
	$('td').delegate('.reset-original', 'click', function(){

		var el = $(this).closest('.override-output').find('input');
		var $datacell = el.attr('data-cell');
		var $format = el.attr('data-format');
		var $formula = el.attr('data-original-formula');

		$.ajax({
			type	: 	"POST",
			url		:	"ajax/calculator.post.php",
			data	:	'action=deleteoutputvalue&roi='+getUrlVars()['roi']+'&entry='+$datacell,
			success	: function(){ }
		});
		
		var $span = '<span \
						data-cell="' + $datacell + '" \
						data-format="' + $format + '" \
						data-formula="' + $formula + '" \
						data-original-formula="' + $formula + '"></span>';
						
		$('[data-cell="' + $datacell + '"]').each(function(){
			
			$(this).closest('td').html($span);
		});
		
		$('#wrapper').calx('getSheet').refresh();
		$('#wrapper').calx('getSheet').calculate();
	});	
	
	$('td').delegate('.override-table-value', 'click', function(){
		
		var el = $(this).closest('.override-output').find('input');
		var $datacell = el.attr('data-cell');
		var $value = $('#wrapper').calx('getSheet').getCell($datacell).getValue();
		var $format = el.attr('data-format');
		var $formula = el.attr('data-original-formula');
		
		$value = numeral($value).format($format);
		
		var $span = '<span \
						style="color: rgb(165,42,42)" \
						data-cell="' + $datacell + '" \
						data-format="' + $format + '" \
						data-original-formula="' + $formula + '">' + $value + '</span>';
						
		$('[data-cell="' + $datacell + '"]').each(function(){
			
			$(this).closest('td').html($span);
		});

		$.ajax({
			type	: 	"POST",
			url		:	"ajax/calculator.post.php",
			data	:	'action=overrideoutput&roi='+getUrlVars()['roi']+'&entry='+$datacell+'&value='+numeral().unformat($value),
			success	: function(){ }
		});			
		
		$('#wrapper').calx('getSheet').refresh();
	});
	
	$('input').on('focus', function(){
		
		$(this).select();
	});
	
	$('#side-menu').metisMenu({
		toggle: false
	});	
	
	var cells = new Array();
	$('[data-cell]').each(function(){
			
		cellId = $(this).data('cell');
		
		if($.inArray(cellId, cells) != -1){
			$(this).removeAttr('data-cell');
		} else {
			cells.push($(this).data('cell'));
		}	
	});
	
	numeral.language( $('#userCurrency').data('user-currency') == 'usd' ? '' : $('#userCurrency').data('user-currency') );
	
	$('#wrapper').calx({
		'autoCalculateTrigger'	:	'keyup',
		'defaultFormat'			:	'0,0[.]00',
		'onAfterCalculate'		:	function() {
			updateChart();
			styleCurrency();
		}
	});
	
	$('#wrapper').calx('registerFunction', 'REGUR', function(text1){
		var celltext = $('[data-cell="' + text1 + '"]').val();
		return celltext;
	});	

	$('.btn-toggle').each(function(){

		var toggleReference = $(this).data('cell-reference');
		var toggleInput = $('input[data-cell="' + toggleReference + '"]');
		var toggleOnValue = $(this).data('on-value');
		var toggleOffValue = $(this).data('off-value');
		var toggleOnText = $(this).data('on-text');
		var toggleOffText = $(this).data('off-text');
		var toggleOnClass = $(this).data('on-class');
		var toggleOffClass = $(this).data('off-class');
				
		var currentValue = toggleInput.val();
		var inputCell = toggleInput.data('cell');
			
		if(currentValue == toggleOnValue) {
					
			$('#wrapper').calx('getSheet').getCell(inputCell).setValue(toggleOffValue).calculate();
			$('button[data-cell-reference="' + toggleReference + '"').each(function(){

				$(this).html(toggleOffText);
				$(this).removeClass(toggleOnClass).addClass(toggleOffClass);
			});	
		} else {
					
			$('#wrapper').calx('getSheet').getCell(inputCell).setValue(toggleOnValue).calculate();
			$('button[data-cell-reference="' + toggleReference + '"').each(function(){

				$(this).html(toggleOnText);
				$(this).removeClass(toggleOffClass).addClass(toggleOnClass);
			});						
		}
	});
	
	$('.tooltipstered').tooltipster({
		theme: 'tooltipster-light',
		maxWidth: 300,
		animation: 'grow',
		position: 'right',
		arrow: false,
		interactive: true,
		contentAsHTML: true
	});
			
	$('.input-addon').focus(function(){
		$(this).parent().find('.helper').toggleClass('input-addon-border');
	}).blur(function(){
		$(this).parent().find('.helper').toggleClass('input-addon-border');
	});
			
			$('.slider').each(function(){
				
				var sliderMin = $(this).data('min');
				var sliderMax = $(this).data('max');
				var sliderStep = $(this).data('step');
				var sliderStart = $(this).data('start') || 0;
				
				$(this).noUiSlider({
					start: sliderStart,
					connect: "lower",
					step: sliderStep,
					range: {
						"min":  sliderMin,
						"max":  sliderMax
					},
					format: {
						to: function ( value ) { return value; },
						from: function ( value ) { return value; }
					}
				});				
				
			});
			
			$(".slider").Link('lower').to( function(value) {
					
				//Get slider name
				var sliderCell = $(this).closest('.form-group').find('.slider-input').attr('data-cell');

				//Set slider Input to equal the slider
				$('#wrapper').calx('getSheet').getCell(sliderCell).setValue(value).calculate();
				$('#wrapper').calx('getSheet').calculate();
			});
			
			$('.slider').on('change', function(){
				
				var datacell = $(this).closest('.form-group').find('.slider-input').attr('data-cell');
				var cellDependents = $('#wrapper').calx('getCell', datacell).getAllDependents();
				var cellValues = [];
				
				cellValues.push(getCellValueArray($(this).closest('.form-group').find('.slider-input')));
				
				var me = $(this);
				var myvalue = me.val();
				var reference = me.attr('data-cell-reference');
				
				$('[data-cell-reference="' + reference + '"').not(me).each(function(){

					$(this).val(myvalue);
					var datacell = $(this).attr('data-cell');
					try {
						$('#wrapper').calx('getSheet').getCell(datacell).setValue(myvalue).calculate();
					} catch(e) {}
				});
				
				/*$.each(cellDependents, function(index, value) {
					
					$('[data-cell="' + value + '"]').each(function(){
						
						cellValues.push(getCellValueArray($(this)));
					});
				});*/
				
				storeChangedValues(cellValues);
			});
			
			$('.slider-input').on('change', function(e){
				$(this).closest('.row').find('.slider').val( $(this).val() );
			});				
			
			$(".player").fitVids();
			
			$('.i-checks').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green',
			});
			
			$('.chosen-selector').chosen({
				width: '100%',
				disable_search_threshold: 10
			});
			
			$('.modal').on('change', '.current-language', function() {
					
				var currentLanguage = $(this).val();
				var exchangeRate = $(this).find('option:selected').data('currency-option');

				$('.current-currency').val(exchangeRate);
				$('.current-currency').trigger('chosen:updated');
				$('.current-currency').change();
			});
				
			$('.modal').on('click', '.update-currency', function() {
					
				var currency = $('.current-currency').val();
				var currentLanguage = $('.current-language').val();

				$.ajax({
						
					type		:	'POST',
					url			:	'ajax/calculator.post.php',
					data		:	{
										action		:	'storecurrency',
										roi			:	getUrlVars()['roi'],
										currency	:	currency,
										language	:	currentLanguage
					},
					success		:	function() {
						location.reload();
					}
				});
					
			});
			

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
			
			$('.modal').on('click', '.show-hide-sections', function() {
		
				$.ajax({
					type	: 	"POST",
					url		:	"ajax/calculator.post.php",
					data	:	{
									action	:	'removehiddensections',
									roi		:	getUrlVars()['roi']
					},
					async	:	'false',
					success	:	function() {
						
						$('.section-to-show').each(function(){
							
							if(!$(this).prop('checked')){
								
								var sectionid = $(this).attr('data-section-id');
								$.ajax({
									type	:	"POST",
									url		:	"ajax/calculator.post.php",
									data	:	{
													action	:	'hidesection',
													roi		:	getUrlVars()['roi'],
													section	:	sectionid
									}
								});
							}
						});
						
						$(document).ajaxStop(function() {
								
							location.reload();
						});
					}
				});
			
			});
			
			$('.btn-toggle').on('click', function(){
				
				var toggleReference = $(this).data('cell-reference');
				var toggleInput = $('input[data-cell="' + toggleReference + '"]');
				var toggleOnValue = $(this).data('on-value');
				var toggleOffValue = $(this).data('off-value');
				var toggleOnText = $(this).data('on-text');
				var toggleOffText = $(this).data('off-text');
				var toggleOnClass = $(this).data('on-class');
				var toggleOffClass = $(this).data('off-class');
				
				var currentValue = toggleInput.val();
				var inputCell = toggleInput.data('cell');
				
				if(currentValue == toggleOnValue) {
					
					$('#wrapper').calx('getSheet').getCell(inputCell).setValue(toggleOffValue).calculate();
					$('button[data-cell-reference="' + toggleReference + '"').each(function(){

						$(this).html(toggleOffText);
						$(this).removeClass(toggleOnClass).addClass(toggleOffClass);
					});	
				} else {
					
					$('#wrapper').calx('getSheet').getCell(inputCell).setValue(toggleOnValue).calculate();
					$('button[data-cell-reference="' + toggleReference + '"').each(function(){

						$(this).html(toggleOnText);
						$(this).removeClass(toggleOffClass).addClass(toggleOnClass);
					});						
				}
				
				$('#wrapper').calx('getSheet').calculate();
				
				var cellValues = [];
				cellValues.push(getCellValueArray(toggleInput));
				
				/*$.each(cellDependents, function(index, value) {
					
					$('[data-cell="' + value + '"]').each(function(){
						
						cellValues.push(getCellValueArray($(this)));
					});
				});*/
				
				storeChangedValues(cellValues);
			});
			
			$('.quotes').each(function(){
				
				var playSpeed = $(this).attr('data-auto-play-speed');
				var transitionSpeed = $(this).attr('data-transition-speed');

				$(this).quovolver({
					autoPlaySpeed : playSpeed,
					transitionSpeed : transitionSpeed
				});
			});
			
			$('input[data-cell-reference]').on('blur', function(){
			
				var me = $(this);
				var myvalue = me.val();
				var reference = me.attr('data-cell-reference');
				
				$('[data-cell-reference="' + reference + '"').not(me).each(function(){

					var datacell = $(this).attr('data-cell');
					$('#wrapper').calx('getSheet').getCell(datacell).setValue(myvalue);
				});
				
				$('#wrapper').calx('getSheet').calculate();
				
				var datacell = $(this).data('cell');
				var cellDependents = $('#wrapper').calx('getCell', datacell).getAllDependents();
				var cellValues = [];
				
				cellValues.push(getCellValueArray($(this)));
				
				/*$.each(cellDependents, function(index, value) {
					
					$('[data-cell="' + value + '"]').each(function(){
						
						cellValues.push(getCellValueArray($(this)));
					});
				});*/
				
				storeChangedValues(cellValues);
			});
			
			$('textarea').on('blur', function(){
				
				var cellValues = [];
				cellValues.push(getCellValueArray($(this)));
				
				/*$.each(cellDependents, function(index, value) {
					
					$('[data-cell="' + value + '"]').each(function(){
						
						cellValues.push(getCellValueArray($(this)));
					});
				});*/
				
				storeChangedValues(cellValues);
			});
			
			$('textarea').on('keyup', function(){
				
				$('#wrapper').calx('getSheet').calculate();
			});
			
			$('.tab-toggle').on('click', function(){
				
				$($(this).closest('.tabs-container').find('.tab-pane').find('.tab-active-input')).each(function(){
					
					var datacell = $(this).attr('data-cell');
					$('#wrapper').calx('getSheet').getCell(datacell).setValue(0).calculate();
				});
				
				var datacell = $($(this).attr('href')).find('.tab-active-input').attr('data-cell');
				var activevalue = $($(this).attr('href')).find('.tab-active-input').attr('data-active-value');
				$('#wrapper').calx('getSheet').getCell(datacell).setValue(activevalue).calculate();
				
				$($(this).closest('.tabs-container').find('.tab-pane').find('.tab-active-input')).each(function(){
					
					var datacell = $(this).data('cell');
					var cellDependents = $('#wrapper').calx('getCell', datacell).getAllDependents();
					var cellValues = [];
					
					cellValues.push(getCellValueArray($(this)));
					
					/*$.each(cellDependents, function(index, value) {
						
						$('[data-cell="' + value + '"]').each(function(){
							
							cellValues.push(getCellValueArray($(this)));
						});
					});*/
					
					storeChangedValues(cellValues);
				});
				
				$('#wrapper').calx('getSheet').calculate();
			});

			$('.popup-iframe').visibile;
			
			$('select').each(function() {

				console.log($(this));
				hideSelectChildren($(this));		
			});
			
			$('.chosen-container:visible').each(function() {
				
				showSelectChildren($(this).parent().find('select'));
			});

			$('select').on('change', function() {
				
				hideSelectChildren($(this));
				showSelectChildren($(this));
			});
			
			$('.tab-content').each(function(){
				
				var maxTabHeight = 0;
				
				$(this).find('.tab-pane').each(function(){
					if($(this).height() > maxTabHeight){
						
						maxTabHeight = $(this).height();
					}
				});
				
				$(this).find('.tab-pane.tab-resize').each(function(){
					
					$(this).css('height', maxTabHeight + 'px');
				});
			});
			
			// Set Max Widget Height to 0
			var maxHeight = 0;	 
			 
			// Loop through all widgets to determine max height
			$('.section-pod').each(function(){
				if( $(this).height() > maxHeight ) { maxHeight = $(this).height(); } 
			});
			
			// Set all widget heights to match max height
			$('.section-pod').each(function(){
				$(this).height( maxHeight );		
			});
			
			// Set Max Widget Height to 0
			var maxHeight = 0;	 
			 
			// Loop through all widgets to determine max height
			$('.section-header').each(function(){
				if( $(this).height() > maxHeight ) { maxHeight = $(this).height(); } 
			});
			
			// Set all widget heights to match max height
			$('.section-header').each(function(){
				$(this).height( maxHeight );		
			});
			
			$('select').on('change', function(){
				
				var cellValues = [];
				cellValues.push(getCellValueArray($(this)));
				
				/*$.each(cellDependents, function(index, value) {
					
					$('[data-cell="' + value + '"]').each(function(){
						
						cellValues.push(getCellValueArray($(this)));
					});
				});*/
				
				storeChangedValues(cellValues);	
			});
			
			$('.showHideSections').on('click', function() {
				
				$('#showHideSections').modal('show');	
			});
			
	$('.change-currency').on('click', function() {
		
		$('#change-currency').modal('show');	
	});
	
			Highcharts.setOptions({
				
				lang: {
					thousandsSep: ','
				}
			});
			
			Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function (color) {
				return {
					radialGradient: {
						cx: 0.5,
						cy: 0.3,
						r: 0.7
					},
					stops: [
						[0, color],
						[1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
					]
				};
			});
			
			
			
			
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
				pdf_html = '<img src="../../../company_specific_files/71/pdfs/' + getUrlVars()['roi'] + 'chart' + $('[data-pdf-element]:eq('+element+')').html() + '.png">'
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
	
	
	$('.add-table-row').on('click',function(){
		
		$rowid = $(this).closest('tr').attr('data-row-id');
		
		var modal = {
					
			animation	:	'fadeIn',
			header		:	{
				icon		:	'fa-pencil-square',
				title		:	'Add Table Row',
				subtitle	:	'Add a new row to the table.'
			},
			body		:	{
				content		:	'<div class="row">\
									<label class="control-label col-lg-5 col-md-5 col-sm-12">Table row label</label>\
									<div class="col-lg-7 col-md-7 col-sm-12">\
										<input id="contributor" class="form-control new-row-label" type="text" />\
									</div>\
								</div>'
			},
			footer		:	{
				content		:	'<button type="button" class="btn btn-primary add-new-row" data-row-id="' + $rowid + '">Add Table Row</button>\
								<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>'
			}
		};

		displayModal(modal);		

	});
	
	$('.remove-table-row').on('click',function(){
		
		$rowid = $(this).attr('data-custom-row-id');
		
		var modal = {
					
			animation	:	'fadeIn',
			header		:	{
				icon		:	'fa-pencil-square',
				title		:	'Remove Table Row?',
				subtitle	:	'Are you sure you\'d like to remove this custom row? This action cannot be undone.'
			},
			body		:	{
				content		:	''
			},
			footer		:	{
				content		:	'<button type="button" class="btn btn-primary remove-row" data-custom-row-id="' + $rowid + '">Remove Row</button>\
								<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>'
			}
		};

		displayModal(modal);		

	});
	
	$('.modal').on('click', '.add-new-row', function(){
		
		$rowid = $(this).attr('data-row-id');
		$newrowname = $('.new-row-label').val();
		$roi = getUrlVars()['roi'];
		
		$.ajax({
			url			: 'ajax/calculator.get.php',
			data		: {
				action		:	'getrowdata',
				rowid		:	$rowid,
				rowname		:	$newrowname,
				roi			:	$roi
			},
			dataType	: 'JSON',
			type		: 'GET',
			success		: function(data){
				console.log(data);
			}
		});
	});
	
	$('.modal').on('click', '.remove-row', function(){
		
		$rowid = $(this).attr('data-custom-row-id');
		$roi = getUrlVars()['roi'];
		console.log($roi + ' ' + $rowid);
		$.ajax({
			url			: 'ajax/calculator.post.php',
			data		: {
				action		:	'removerow',
				rowid		:	$rowid,
				roi			:	$roi
			},
			dataType	: 'JSON',
			type		: 'POST',
			error		: function(e){
				console.log(e);
				location.reload();
			},
			success		: function(data){
				console.log(data);
				location.reload();
			}
		});
	});
			
});

function getUrlVars() {
		
	var vars = [], hash;
	var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	for(var i = 0; i < hashes.length; i++)
	{
		hash = hashes[i].split('=');
		vars.push(hash[0]);
		vars[hash[0]] = hash[1].replace('#','');
	}
	return vars;
	
}

function getCellValueArray(el){

	var formTags = ['input', 'select', 'textarea', 'button'];

	var $address = el ? el.attr('data-cell') : '',
		$formula = el ? el.attr('data-formula') : '',
		$format  = el ? el.attr('data-format') : false,
		$value   = el ? el.val() : null,
		$name	 = el.attr('name');
		tagName  = el.prop('tagName') ? el.prop('tagName').toLowerCase() : null;

	if(formTags.indexOf(tagName) == -1){
		$value = el.text();
	}

	if($format) {
		$value = numeral().unformat($value);
	}

	if( $format && $format.indexOf('%') != -1 ) {
		$value = $value * 100;
	}
	
	cellValue = [$name, $value];

	return cellValue;
}

function storeChangedValues($values) {

	$roi = getUrlVars()['roi'];
	
	$.ajax({
		url: 'ajax/calculator.post.php',
		data: {
			action		:	'storevalues',
			roi			:	$roi,
			storevalues	:	JSON.stringify($values)
		},
		dataType: 'JSON',
		type: 'POST',
		success: function(){
			console.log('data inserted');
		},
		error: function(){
			console.log('error');
		}
	});
		
}

function hideSelectChildren(select) {
		
	// Determine if any options have a show-map data tags
		
	select.find('option').each(function() {
			
		if($(this).data('show-map')) {
			
			var showMap = $(this).data('show-map').split(',');
			$.each( showMap, function( i, val ) {
				console.log(val);
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
	
	try{
		
		$('.chart-holder').each(function(){

			var chartHolder = $(this);
			$(this).find('.ROICalcElemID').each(function(){
				
				var chart = $(this).highcharts();
				var totalSeries = 0;
				
				chartHolder.find('.series-holder').each(function(){
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
					
					chart.series[totalSeries].update({ name: newSeriesName }, false);
					chart.series[totalSeries].update({data: seriesData}, false);
					totalSeries++;
				});
				
				chart.redraw();
			});
			
		});
	}catch(e){ }

}

function styleCurrency() {
	
	$('[data-conditional-format]').each(function() {
		
		var conditionalForumla = $(this).attr('data-conditional-format');

		if(conditionalForumla){
			var evaluatedFormula = $('#wrapper').calx('evaluate', conditionalForumla);
			$(this).css('color', evaluatedFormula);			
		}

	});
}