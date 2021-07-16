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
			calxAction: 'change',
			currencyIdentifier: 'usd',
			equalizeHeights: '.pod-holder'
		};
		
		if ( options ) {
			$.extend( settings, options );
		};
		
		/***
		*
			Create Curreny Plugin for user ROI saved preferences
		*
		*/
		
		numeral.language(settings.currencyIdentifier);
		
		Highcharts.setOptions({
			lang: {
				decimalPoint: '.',
				thousandsSep: ','
			}
		});


		$( settings.equalizeHeights ).equalizeHeights();
		
		/***
		*
			Calculation Plugin Definition 
		*
		*/

		cleanCalxCells( $('[data-cell') );
		
		initializeCheckboxes();
		
		$( '#wrapper' ).calx({
			
			// Define when the calculator will actually perform the calculations.
			autoCalculate			:	false,
			onAfterCalculate		:	function() {
				//updateChart();
			}
		});

		/* $('div:data(roi-element)').each(function(i, v){
			
			var $plugin = $(this).data('roi-element'),
				$opts   = $plugin.options,
				$type   = $opts.el_type;
			
			if($type == 'tab'){
				
				var $choices = $opts.choices;
				if($choices){
					
					$.each($choices, function(i,v){
						var $show = v.ch_show;
						if($show){
							$('[element-id="' + $show + '"').wrap( $('<div/>').addClass('tab-content') );
							$('[element-id="' + $show + '"').wrap( $('<div/>').addClass('tab-pane').attr
						};
					});
				};
			};
		}); */
		
		/***
		*
			Setup the Chosen Plugin
		*
		*/
		
		/* $( settings.chosenSelector ).chosen({
			width: '100%',
			disable_search_threshold: 10
		});	 */	
		
		/***
		*
			Datatable set up 
		*
		*/

		$( settings.datatable ).each(function(){
			
			var table_id = $(this).attr('id');
			var dataSet = $.data( document.body, "table" + table_id );

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
					chartid		:	roi_elem_id,
					roi			:	getUrlVars()['roi']
				},
				dataType: 'json',
				success		:	function(chartarray) {
					console.log(chartarray);
					var chart = new Highcharts.Chart(
						JSON.parse(chartarray)
					);
					updateChart();
				}
				
			});
			
		});
		
		$('.create-pdf').on('click', function() {
			
			if($('.ROICalcElemID').length > 0 ){
				createCharts(0);
			} else {
				console.log('else');
				callPdf();
			}
		});
		
		$('textarea').on('change', function() {

			var cellValues = [];
			cellValues.push(getCellValueArray($(this)));
			storeChangedValues(cellValues);			
		});

		/**
		
			Dropdown Functions
			
		*/
		
		$('select').on('change', function() {
			
			$(this).processSelect( settings );			
		});
		
		$('[data-sourced-data]').each(function() {
			
			$(this).sourceData();			
		});
		
		$('select').each(function(){
			
			$(this).processSelect( settings );
		});
		
		
		$('[data-element-id="32042"]').stick_in_parent({
			offset_top: 70
		});
		
		$('.slider').on('change', function(){

/* 			var data_cell = $(this).attr('data-cell-reference');
			
			if( !data_cell ) {
				data_cell = $(this).closest('.form-group').find('.slider-input').attr('data-cell');
			};

			// Get the ID of the current cell that was changed.
			var sheet = $( settings.calxSelector );
			var oncalc = $(this).data('oncalc');
			
			if(oncalc == "sheet") {
				sheet.calx('getSheet').calculate();
			} else {
				sheet.calx('getCell', data_cell).calculateAllDependents();
			}
			
			var cellValues = [];

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
				
			cellValues.push([reference, myvalue]);

			storeChangedValues(cellValues);
			updateChart(); */
		});
			
/* 		$('input[data-cell-reference]').on(settings.calxAction, function(){

			// Get the ID of the current cell that was changed.
			var data_cell = $(this).data('cell');
			var sheet = $( settings.calxSelector );
			var oncalc = $(this).data('oncalc');
			
			if(oncalc == "sheet") {
				sheet.calx('getSheet').calculate();
			} else {
				sheet.calx('getCell', data_cell).calculateAllDependents();
			}
			
			var cellValues = [];
				
			cellValues.push(getCellValueArray($(this)));

			storeChangedValues(cellValues);
			
			updateChart();
		}); */
		
		function getLowestDependants(address, wasProcessed) {
			
			var sheet = $('#wrapper');
			var cell = sheet.calx('getCell', address);
			
			if(!wasProcessed) {
				var wasProcessed = [];
			}

			var a;

			for(a in cell.dependencies){
				
				if($.inArray(a, wasProcessed) == -1 ) {
					wasProcessed.push(a);
					console.log(a);
					sheet.calx('getCell', a).calculate(false, true);
					
					if(Object.keys(cell.dependencies[a].dependencies).length > 0) {
						getLowestDependants(a, wasProcessed);
					}
				}
			}
		};
		
		$('.input-addon').focus(function(){
			
			// Add border styling to add on portion of input when input is focused.
			$(this).parent().find('.helper').toggleClass('input-addon-border');
		}).blur(function(){
			
			// Remove border styling from add on potion of input when input loses fous.
			$(this).parent().find('.helper').toggleClass('input-addon-border');
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
		
	};
	
	$.fn.toggleElementVisibility = function(options) {

		var settings = $.extend({
			type	:	'select'
		}, options);
		
		var $this 			= $(this),
			$hideElements 	= $this.data('hide-map'),
			$showElements 	= $this.data('show-map');
			
		switch(options.type){
			
			case 'select':
				showElements($showElements);
				hideElements($hideElements);
				break;
			
			case 'checkbox':
				
				var $checked = $this.is(':checked');
				if($checked){
					showElements($showElements);
					hideElements($hideElements);
				}
				
				else
				
				{
					hideElements($showElements);
					showElements($hideElements);						
				}
			
		};
	};
	
	function showElements($elements){
		
		$.each($elements, function(el, val){
				
			var $element = $('[data-element-id="' + val + '"]');
			$element.show();
		});		
	};
	
	function hideElements($elements){

		$.each($elements, function(el, val){
				
			var $element = $('[data-element-id="' + val + '"]');
			$element.hide();
		});	
	}
	
	$.fn.sourceData = function(options){
		
		var settings = $.extend({
			el		:	$(this)	
		}, options);
		
		var	$sourceData	= settings.el.data('sourced-data'),
			$element	= settings.el.closest('[data-element-id]'),
			$opts		= $element.data('roi-element').options,
			$type		= $opts.type;		
		
		switch($type){
			
			case 'dropdown':
				
				$opts.selections = [];
				
				var $srcData = $('[data-group-id="' + $sourceData + '"]');
				$.each($srcData, function(el, val){
					
					var $source = $(val).closest('[data-element-id]').data("roi-element");
					var $srcOptions = $source.options;
					
					var $option = {};
					$option.value = $srcOptions.value;
					$option.text = $srcOptions.label.html;
					
					$opts.selections.push($option);
				});
				
				// Create New DOM element
				var $dropdown = $('<div/>');
					$dropdown.attr('element-type','dropdown');
					
				// Replace current element with new DOM element
				$element.replaceWith($dropdown);
					
				// Create new input with updated options
				$dropdown.dropdown($opts);
			
			break;
		};
	};
	
	$.fn.hideAllChildren = function( options ) {

		var settings = $.extend({
			type: 'select',
			el: $(this)
		}, options );
			
		switch( settings.type ) {
				
			case 'select':
				settings.el.find('option').each(function() {
						
					var showmap = $(this).data('show-map');
					if(showmap) {
							
						var hide_elements = showmap.split(",");
						$.each( hide_elements, function(i, val) {
								
							$('[data-element-id="' + val + '"]').hide();
							$(val).hideAllChildren();
						});								
					}
				});
				break;
				
			case 'checkbox':
				settings.el.find('input').each(function() {

					var showmap = $(this).data('show-map');
					if(showmap) {
							
						var hide_elements = showmap.split(",");
						$.each( hide_elements, function(i, val) {
								
							$('[data-element-id="' + val + '"]').hide();
							$(val).hideAllChildren({
								type: 'checkbox'
							});
						});								
					}					
				});
				
				
		};
	};
	

	$.fn.showSelectedChildren = function( options ) {
			
		var settings = $.extend({
			type: 'select',
			el: $(this)
		}, options );
		
		switch( settings.type ) {
				
			case 'select':
				var showmap = $(this).data('show-map');
				if( showmap ) {
						
					var show_elements = showmap.split(",");
					$.each( show_elements, function(i, val) {
							
						$('[data-element-id="' + val + '"]').show();
						$(val).showSelectedChildren();
					});		
				}
				break;
				
			case 'checkbox':
				settings.el.find('input:checkbox:checked').each(function() {
					var showmap = $(this).data('show-map');
					if( showmap ) {
							
						var show_elements = showmap.split(",");
						$.each( show_elements, function(i, val) {
								
							$('[data-element-id="' + val + '"]').show();
							$(val).showSelectedChildren({
								type: 'checkbox'
							});
						});		
					};
				});
				break;
		}
	};
	
	$.fn.processSelect = function( settings ) {
		
		var el_type = $(this).prop('tagName');
		
		switch(el_type) {
			
			case 'SELECT':

				var dd = $(this);
				var dd_selected = $(this).find('option:selected');
				var cell_reference = dd.data('cell');
				var dd_value = dd.val();			
				
				dd.hideAllChildren();
				dd_selected.showSelectedChildren();
					
				if(cell_reference){
					$( settings.calxSelector ).calx('getCell', cell_reference).setValue(dd_value);
					$( settings.calxSelector ).calx('getSheet').calculate();
						
					var cellValues = [];
					cellValues.push(getCellValueArray($(this)));
					storeChangedValues(cellValues);
						
					updateChart();				
				};
				break;
				
			case 'INPUT':

				var chk = $(this);
				var chk_id = chk.closest('[data-checkbox-id]');
				
				chk_id.hideAllChildren({
					type: 'checkbox'
				});
				
				chk_id.showSelectedChildren({
					type: 'checkbox'
				});

				break;
		}
	};
	
	$('.save-the-roi').on('click', function(){
		storeRoiArray();
	});
	
	
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
	
})( window.jQuery || window.Zepto );

$.fn.equalizeHeights = function() {
	
	var two = $(this).map(function(i, e) {
		return $(e).height();
	});
	
	return this.height(
		Math.max.apply(
			this,two.get()
		)
	);
}



		/*function checkDropdown() {		


			if($('select#25874').val() == 0 ) {
				$('input#A25876').closest('[data-element-type="input"').hide();
				$('input#A25877').closest('[data-element-type="input"').hide();
				$('input#A25878').closest('[data-element-type="input"').hide();
				$('input#A25879').closest('[data-element-type="input"').hide();
				$('input#A25880').closest('[data-element-type="input"').hide();
				$('input#A25881').closest('[data-element-type="input"').hide();
				$('input#A25882').closest('[data-element-type="input"').hide();
				$('input#A25883').closest('[data-element-type="input"').hide();
				$('input#A25884').closest('[data-element-type="slider"').hide();
				$('input#A25886').closest('[data-element-type="input"').hide();
				$('input#A25887').closest('[data-element-type="input"').hide();
				$('input#A25888').closest('[data-element-type="input"').hide();
				$('input#A25889').closest('[data-element-type="input"').hide();
				$('input#A25890').closest('[data-element-type="input"').hide();
				$('input#A25891').closest('[data-element-type="slider"').hide();
				$( "div:contains('Time Spent Searching For Assets')" ).closest('[data-element-type="text"').hide();
			} else {
				$('input#A25876').closest('[data-element-type="input"').show();
				$('input#A25877').closest('[data-element-type="input"').show();
				$('input#A25878').closest('[data-element-type="input"').show();
				$('input#A25879').closest('[data-element-type="input"').show();
				$('input#A25880').closest('[data-element-type="input"').show();
				$('input#A25881').closest('[data-element-type="input"').show();
				$('input#A25882').closest('[data-element-type="input"').show();
				$('input#A25883').closest('[data-element-type="input"').show();
				$('input#A25884').closest('[data-element-type="slider"').show();
				$('input#A25886').closest('[data-element-type="input"').show();
				$('input#A25887').closest('[data-element-type="input"').show();
				$('input#A25888').closest('[data-element-type="input"').show();
				$('input#A25889').closest('[data-element-type="input"').show();
				$('input#A25890').closest('[data-element-type="input"').show();
				$('input#A25891').closest('[data-element-type="slider"').show();
				$( "div:contains('Time Spent Searching For Assets')" ).closest('[data-element-type="text"').show();
			}
		}*/
		
		function createCharts( chrt ) {
			
			var chart = $('.ROICalcElemID:eq('+chrt+')').highcharts();
			var chartnumber = $('.ROICalcElemID:eq('+chrt+')').attr('data-id');
			var opts = chart.options;        // retrieving current options of the chart
			opts = $.extend(true, {}, opts); // making a copy of the options for further modification
			delete opts.chart.renderTo;      // removing the possible circular reference
			
			if( ! opts.plotOptions.series.stacking ) {
				delete opts.plotOptions.series;
			}

			$('.ROICalcElemID:eq('+chrt+')').each(function(){

				var chart = $(this).highcharts();
				var totalSeries = 0;
				
				var seriesNumber = 0;
				$(this).closest('.graph-holder').find('.series-holder').each(function(){

					var newSeriesName = $(this).data('series-name');					
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
					opts.plotOptions.pie.showInLegend = true;
					opts.plotOptions.pie.dataLabels = false;
					opts.credits.enabled = false;
					opts.chart.backgroundColor = 'transparent';
					seriesNumber++;
					
					console.log(opts);
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
			console.log(opts);
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

					}			
				}
			).fail(function(){
				callPdf();
			});
			
			if( $('.ROICalcElemID').length == chrt + 1 ) {
				
				setTimeout(function(){
					callPdf();
				}, 1000);
			}
		}
		
function callPdf(){
	

	$.ajax({
							
		type		:	'GET',
		url			:	'ajax/calculator.get.php',
		data		:	{
			action		:	'getroiversion',
			roi			:	getUrlVars()['roi']
		},
		dataType: 'json',
		success		:	function(roiinfo) {
			
			if(roiinfo.roi_version_id == 490) {
				var tcoTotal = $('#wrapper').calx('evaluate', '( SUMA3 + SUMA4 + SUMA5 + SUMA6 + SUMA7 + SUMA8 ) * 100 / ( SUMB3 + SUMB4 + SUMB5 + SUMB6 + SUMB7 + SUMB8 )' );
				var infrastructure = $('#wrapper').calx('getCell', 'CALX1').getFormattedValue();
				var environmentals = $('#wrapper').calx('getCell', 'CALX2').getFormattedValue();
				var total = $('#wrapper').calx('getCell', 'CALX3').getFormattedValue();

				var calx1 = $('#wrapper').calx('getSheet').getCell('CALX1').getFormula();
				$('#wrapper').calx('getSheet').getCell('CALX1').setFormula('SUMB5 - SUMA5').calculate();
				var upgrades = $('#wrapper').calx('getCell', 'CALX1').getFormattedValue();
				var software = $('#wrapper').calx('getCell', 'SUMB4').getFormattedValue();
				$('#wrapper').calx('getSheet').getCell('CALX1').setFormula('AAG45 + AAG90 - ( TAG26 + TAN26 )').calculate();
				var maintenance = $('#wrapper').calx('getCell', 'CALX1').getFormattedValue();
				
				$('#wrapper').calx('getSheet').getCell('CALX1').setFormula('EXPG8 - EXPG2').calculate();
				var rack = $('#wrapper').calx('getCell', 'CALX1').getFormattedValue();
				$('#wrapper').calx('getSheet').getCell('CALX1').setFormula('EXPG10 - EXPG4').calculate();
				var power = $('#wrapper').calx('getCell', 'CALX1').getFormattedValue();
				$('#wrapper').calx('getSheet').getCell('CALX1').setFormula('EXPG11 - EXPG5').calculate();
				var cooling = $('#wrapper').calx('getCell', 'CALX1').getFormattedValue();
				
				$('#wrapper').calx('getSheet').getCell('CALX1').setFormula(calx1).calculate();
				
				tcoTotal = Math.round(tcoTotal);
				
				window.open('pdf?roi=' + getUrlVars()['roi'] + '&total=' + tcoTotal + '%' + '&infra=' + infrastructure + '&enviro=' + environmentals + '&totalsav=' + total + '&upgrade=' + upgrades + '&software=' + software + '&maintenance=' + maintenance + '&rack=' + rack + '&power=' + power + '&cooling=' + cooling);				
			} else if (roiinfo.roi_version_id == 491) {
				
				var var1 = $('#wrapper').calx('getCell', 'CALX9').getFormattedValue();
				var var2 = $('#wrapper').calx('getCell', 'CALX10').getFormattedValue();
				var var3 = $('#wrapper').calx('getCell', 'CALX11').getFormattedValue();
				$('#wrapper').calx('getSheet').getCell('PDF1').setFormula('A15').calculate();
				var var4 = $('#wrapper').calx('getCell', 'PDF1').getFormattedValue();
				$('#wrapper').calx('getSheet').getCell('PDF1').setFormula('A22').calculate();
				var var6 = $('#wrapper').calx('getCell', 'PDF1').getFormattedValue();
				$('#wrapper').calx('getSheet').getCell('PDF1').setFormula('A28 + A32 + A39 + A44').calculate();
				var var8 = $('#wrapper').calx('getCell', 'PDF1').getFormattedValue();
				$('#wrapper').calx('getSheet').getCell('PDF1').setFormula('A54 + A56').calculate();
				var var10 = $('#wrapper').calx('getCell', 'PDF1').getFormattedValue();
				$('#wrapper').calx('getSheet').getCell('PDF1').setFormula('( A15 + A22 + A28 + A32 + A39 + A44 - ( A54 + A56 ) )').calculate();
				var var13 = $('#wrapper').calx('getCell', 'PDF1').getFormattedValue();

				var var16 = $('#wrapper').calx('getCell', 'A4').getFormattedValue();
				var var17 = $('#wrapper').calx('getCell', 'A7').getFormattedValue();
				var var18 = $('#wrapper').calx('getCell', 'A8').getFormattedValue();
				var var19 = $('#wrapper').calx('getCell', 'A62').getFormattedValue();
				var var29 = $('#wrapper').calx('getCell', 'A10').getFormattedValue();
				var var30 = $('#wrapper').calx('getCell', 'A59').getFormattedValue();
				var var31 = $('#wrapper').calx('getCell', 'A60').getFormattedValue();
				
				var var20 = $('#wrapper').calx('getCell', 'A16').getFormattedValue();
				var var21 = $('#wrapper').calx('getCell', 'A18').getFormattedValue();
				var var22 = $('#wrapper').calx('getCell', 'A19').getFormattedValue();
				var var23 = $('#wrapper').calx('getCell', 'A22').getFormattedValue();
				
				var var24 = $('#wrapper').calx('getCell', 'A28').getFormattedValue();
				var var25 = $('#wrapper').calx('getCell', 'A32').getFormattedValue();
				var var26 = $('#wrapper').calx('getCell', 'A39').getFormattedValue();
				var var27 = $('#wrapper').calx('getCell', 'A44').getFormattedValue();
				var var28 = $('#wrapper').calx('getCell', 'A52').getFormattedValue();
				
				window.location.href = 'pdf?roi=' + getUrlVars()['roi'] + '&1=' + var1 + '&2=' + var2 + '&3=' + var3 + '&4=' + var4 + '&6=' + var6 + '&8=' + var8 + '&10=' + var10 + '&13=' + var13 + '&16=' + var16 + '&17=' + var17 + '&18=' + var18 + '&19=' + var19 + '&20=' + var20 + '&21=' + var21 + '&22=' + var22 + '&23=' + var23 + '&24=' + var24 + '&25=' + var25 + '&26=' + var26 + '&27=' + var27 + '&28=' + var28 + '&29=' + var29 + '&30=' + var30 + '&31=' + var31;
				//window.open('pdf?roi=' + getUrlVars()['roi'] + '&1=' + var1 + '&2=' + var2 + '&3=' + var3 + '&4=' + var4 + '&6=' + var6 + '&8=' + var8 + '&10=' + var10 + '&13=' + var13 + '&16=' + var16 + '&17=' + var17 + '&18=' + var18 + '&19=' + var19 + '&20=' + var20 + '&21=' + var21 + '&22=' + var22 + '&23=' + var23 + '&24=' + var24 + '&25=' + var25 + '&26=' + var26 + '&27=' + var27 + '&28=' + var28 + '&29=' + var29 + '&30=' + var30 + '&31=' + var31);
			} else if (roiinfo.roi_version_id == 506) {
				
				var var1 = $('#wrapper').calx('getCell', 'CALX1').getFormattedValue();
				var var2 = $('#wrapper').calx('getCell', 'CALX79').getFormattedValue();
				var var3 = $('#wrapper').calx('getCell', 'CALX80').getFormattedValue();
				var var4 = $('#wrapper').calx('getCell', 'CALX81').getFormattedValue();
				var var5 = $('#wrapper').calx('getCell', 'CALX57').getFormattedValue();
				var var6 = $('#wrapper').calx('getCell', 'CALX58').getFormattedValue();
				var var7 = $('#wrapper').calx('getCell', 'CALX59').getFormattedValue();
				var var8 = $('#wrapper').calx('getCell', 'STOT1').getFormattedValue();
				var var9 = $('#wrapper').calx('getCell', 'CALX60').getFormattedValue();
				var var10= $('#wrapper').calx('getCell', 'CALX61').getFormattedValue();
				var var11= $('#wrapper').calx('getCell', 'CALX62').getFormattedValue();
				var var12= $('#wrapper').calx('getCell', 'STOT2').getFormattedValue();
				var var13= $('#wrapper').calx('getCell', 'CALX63').getFormattedValue();
				var var14= $('#wrapper').calx('getCell', 'CALX64').getFormattedValue();
				var var15= $('#wrapper').calx('getCell', 'CALX65').getFormattedValue();
				var var16= $('#wrapper').calx('getCell', 'STOT3').getFormattedValue();
				var var17= $('#wrapper').calx('getCell', 'CALX66').getFormattedValue();
				var var18= $('#wrapper').calx('getCell', 'CALX67').getFormattedValue();
				var var19= $('#wrapper').calx('getCell', 'CALX68').getFormattedValue();
				var var20= $('#wrapper').calx('getCell', 'STOT4').getFormattedValue();
				var var21= $('#wrapper').calx('getCell', 'CALX69').getFormattedValue();
				var var22= $('#wrapper').calx('getCell', 'CALX70').getFormattedValue();
				var var23= $('#wrapper').calx('getCell', 'CALX71').getFormattedValue();
				var var24= $('#wrapper').calx('getCell', 'STOT5').getFormattedValue();
				var var25= $('#wrapper').calx('getCell', 'CALX72').getFormattedValue();
				var var26= $('#wrapper').calx('getCell', 'CALX73').getFormattedValue();
				var var27= $('#wrapper').calx('getCell', 'CALX74').getFormattedValue();
				var var28= $('#wrapper').calx('getCell', 'CTOT1').getFormattedValue();
				var var29= $('#wrapper').calx('getCell', 'CALX75').getFormattedValue();
				var var30= $('#wrapper').calx('getCell', 'CALX76').getFormattedValue();
				var var31= $('#wrapper').calx('getCell', 'CALX77').getFormattedValue();
				var var32= $('#wrapper').calx('getCell', 'CALX78').getFormattedValue();
				var var33= $('#wrapper').calx('getCell', 'A25563').getFormattedValue();
				var var34= $('#wrapper').calx('getCell', 'A25564').getFormattedValue();
				var var35= $('#wrapper').calx('getCell', 'A25565').getFormattedValue();
				var var36= $('#wrapper').calx('getCell', 'A25566').getFormattedValue();
				var var37= $('#wrapper').calx('getCell', 'A25567').getFormattedValue();
				var var38= $('#wrapper').calx('getCell', 'A25556').getFormattedValue();
				var var39= $('#wrapper').calx('getCell', 'A25557').getFormattedValue();
				var var40= $('#wrapper').calx('getCell', 'A25558').getFormattedValue();
				var var41= $('#wrapper').calx('getCell', 'A25559').getFormattedValue();
				var var42= $('#wrapper').calx('getCell', 'A25560').getFormattedValue();
				var var43= $('#wrapper').calx('getCell', 'A25561').getFormattedValue();
				var var44= $('#wrapper').calx('getCell', 'A25562').getFormattedValue();
				var var45= $('#wrapper').calx('getCell', 'A25573').getFormattedValue();
				var var46= $('#wrapper').calx('getCell', 'A25771').getFormattedValue();				
				var var47= $('#wrapper').calx('getCell', 'A25772').getFormattedValue();
				var var48= $('#wrapper').calx('getCell', 'A25591').getFormattedValue();
				var var49= $('#wrapper').calx('getCell', 'A25596').getFormattedValue();
				var var50= $('#wrapper').calx('getCell', 'A25773').getFormattedValue();
				var var51= $('#wrapper').calx('getCell', 'A25608').getFormattedValue();
				var var52= $('#wrapper').calx('getCell', 'A25614').getFormattedValue();
				
				window.open('pdf?roi=' + getUrlVars()['roi'] + '&1=' + var1 + '&2=' + var2 + '&3=' + var3 + '&4=' + var4 + '&5=' + var5 +'&6=' + var6 + '&7=' + var7 + '&8=' + var8 + '&9=' + var9 + '&10=' + var10 + '&11=' + var11 + '&12=' + var12 + '&13=' + var13 + '&14=' + var14 + '&15=' + var15 + '&16=' + var16 + '&17=' + var17 + '&18=' + var18 + '&19=' + var19 + '&20=' + var20 + '&21=' + var21 + '&22=' + var22 + '&23=' + var23 + '&24=' + var24 + '&25=' + var25 + '&26=' + var26 + '&27=' + var27 + '&28=' + var28 + '&29=' + var29 + '&30=' + var30 + '&31=' + var31 + '&32=' + var32 + '&33=' + var33 + '&34=' + var34 + '&35=' + var35 + '&36=' + var36 + '&37=' + var37 + '&38=' + var38 + '&39=' + var39 + '&40=' + var40 + '&41=' + var41 + '&42=' + var42 + '&43=' + var43 + '&44=' + var44 + '&45=' + var45 + '&46=' + var46 + '&47=' + var47 + '&48=' + var48 + '&49=' + var49 + '&50=' + var50 + '&51=' + var51 + '&52=' + var52);
			} else if (roiinfo.roi_version_id == 508) {
				
				var var1 = $('#wrapper').calx('getCell', 'POD62').getFormattedValue();
				var var2 = $('#wrapper').calx('getCell', 'STOT1').getFormattedValue();
				var var3 = $('#wrapper').calx('getCell', 'SEC11').getFormattedValue();
				var var4 = $('#wrapper').calx('getCell', 'SEC12').getFormattedValue();
				var var5 = $('#wrapper').calx('getCell', 'SEC13').getFormattedValue();
				var var6 = $('#wrapper').calx('getCell', 'SEC14').getFormattedValue();
				var var7 = $('#wrapper').calx('getCell', 'SEC15').getFormattedValue();
				var var8 = $('#wrapper').calx('getCell', 'STOT3').getFormattedValue();
				var var9 = $('#wrapper').calx('getCell', 'SEC54').getFormattedValue();
				var var10= $('#wrapper').calx('getCell', 'SEC55').getFormattedValue();
				var var11= $('#wrapper').calx('getCell', 'SEC51').getFormattedValue();
				var var12= $('#wrapper').calx('getCell', 'SEC52').getFormattedValue();
				var var13= $('#wrapper').calx('getCell', 'SEC53').getFormattedValue();
				var var14= $('#wrapper').calx('getCell', 'STOT4').getFormattedValue();
				var var15= $('#wrapper').calx('getCell', 'SEC61').getFormattedValue();
				var var16= $('#wrapper').calx('getCell', 'SEC62').getFormattedValue();
				var var17= $('#wrapper').calx('getCell', 'SEC63').getFormattedValue();
				var var18= $('#wrapper').calx('getCell', 'SEC64').getFormattedValue();
				var var19= $('#wrapper').calx('getCell', 'A25632').getFormattedValue();
				var var20= $('#wrapper').calx('getCell', 'A25841').getFormattedValue();
				var var21= $('#wrapper').calx('getCell', 'STOT5').getFormattedValue();
				var var22= $('#wrapper').calx('getCell', 'SEC71').getFormattedValue();
				var var23= $('#wrapper').calx('getCell', 'SEC72').getFormattedValue();
				var var24= $('#wrapper').calx('getCell', 'SECT4').getFormattedValue();				
				
				window.open('pdf?roi=' + getUrlVars()['roi'] + '&1=' + var1 + '&2=' + var2 + '&3=' + var3 + '&4=' + var4 + '&5=' + var5 +'&6=' + var6 + '&7=' + var7 + '&8=' + var8 + '&9=' + var9 + '&10=' + var10 + '&11=' + var11 + '&12=' + var12 + '&13=' + var13 + '&14=' + var14 + '&15=' + var15 + '&16=' + var16 + '&17=' + var17 + '&18=' + var18 + '&19=' + var19 + '&20=' + var20 + '&21=' + var21 + '&22=' + var22 + '&23=' + var23 + '&24=' + var24);				
			}

		}
		
	});	

	/**/	
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

	if($('#wrapper').calx('getCell', 'CALX31')){

		var summary_total = $('#wrapper').calx('getCell', 'CALX31').getValue();
		$values.push(['GT1', summary_total]);
	};	
	
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
		
};

	
	function initializeCheckboxes(){
			
		var elems = Array.prototype.slice.call(document.querySelectorAll('input.js-switch'));
			
		elems.forEach(function(html) {
			var switchery = new Switchery(html);
			if(switchery.markedAsSwitched()){
				console.log('cucked');
			};
			
			html.onchange = function(){
					
				var state = html.checked;
				var action = ( state ? 'showElement' : 'hideElement' );

				$(this).roishopActions({'el_action':action + '( ' + $(this).attr('onclick') + ')'});
			};
		});		
	};

