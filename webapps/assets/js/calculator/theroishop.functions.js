var idleTime = 0;
	
$(document).ready(function() {
			
	/*****************************************
	 ** THE ROI FUNCTIONS
	 ** 
	 ** 1. Load any stored values ( function loadValues() )
	 **
	 ** 2. Fit Vids ( function fitVids() )
	 *****************************************/
	 
	// Add loading icon while functions are performed
	$('body').prepend('<div id="loadvalues-overlay"><div class="loading-spinner"><img alt="Loading" src="../img/ajax_loader.GIF"><br><br>Please wait while the ROI is loading...</div></div>');
	
	
	$('input[data-table-with]').each(function() {
		
		var tableWith = $(this).data('table-with');
		var tableColumns = $('#' + tableWith).find('tr').find('th').length;
		var tableName = $(this).attr('name');
		
		var tableRow = '<tr><td>' + $(this).data('row-name') + '</td>';
		
		for( var i=1; i<tableColumns; i++) {
			
			tableRow += '<td data-cell-name="' + String.fromCharCode(64 + i) + $(this).attr('name') + '">' + $(this).prop('outerHTML') + '</td>';
		}
		
		tableRow += '</tr>';
		
		$(this).remove();
		
		$('#' + tableWith + ' tbody').append(tableRow);
		
		$('[name="' + tableName + '"]').each(function() {
			
			$(this).attr('data-cell', $(this).parent().data('cell-name'));
			$(this).attr('name', $(this).parent().data('cell-name'));
		});
	});	
	
	/**********************************************
	 ** 1. SET UP CALX TO HANDLE ROI CALCUALTIONS
	 **********************************************/
	 
	$('.savingsTable').each(function(){
					
		var returnPeriod = $('#retPeriod').val();
		var tableHTML = '<thead><tr><th></th>';
			
		for( i=1; i<=returnPeriod; i++ ) {
			tableHTML += '<th>Year ' + i + '</th>';
		}
					
		tableHTML += '<th>Total</th></tr></thead><tbody>';
		
		$('.section-equations').each(function(){
			
			tableHTML += '<tr><th>' + $(this).data('section-name') + '</th>';
			for( i=1; i<=returnPeriod; i++ ) {
				tableHTML += '<td><span data-format="($0,0)" data-formula="SECTIONTOTAL(' + $(this).data('formula') + ', ' + i + ', ' + $(this).data('section-id') + ')"></span></td>';
			}
			tableHTML += '<td><span data-format="($0,0)" data-formula="SECTIONTOTAL(' + $(this).data('formula') + ', \'total\', ' + $(this).data('section-id') + ')"></span></td>';
		});
		
		tableHTML += '<tr><th>Cost</th>';
		
		for( i=1; i<=returnPeriod; i++ ) {
			tableHTML += '<td><span data-format="($0,0)" data-formula="ANNUALCOST(' + i + ')"></span></td>';
		}

		tableHTML += '<td><span data-format="($0,0)" data-formula="ANNUALCOST(\'total\')"></span></td></tr>';
		
		tableHTML += '<tr><th>Total</th>';
		
		for( i=1; i<=returnPeriod; i++ ) {
		
			tableHTML += '<td><span data-format="($0,0)" data-formula="ANNUALTOTAL(' + i + ')"></span></td>';
		}
		
		tableHTML += '<td><span data-format="($0,0)" data-formula="GRANDTOTAL(\'true\')"></span></td></tr></tbody>';
					
		$(this).html( tableHTML );
				
	});
	
	$('#page-wrapper').calx({
		'autoCalculateTrigger'	:	'keyup',
		'defaultFormat'			:	'0,0[.]00',
		'onAfterCalculate'		:	function() {
			styleOutputs();
		}
	});
	
	$('#page-wrapper').calx('registerFunction', 'SECTIONTOTAL', function(eq, yr, id, ex){
		
		// Get section conservative factor
		var confactor = ( 100 - $('#currentValueCon'+id).val() ) / 100;
		
		// Get implementation period
		var impperiod = $('#impPeriod').val();
		
		// Get the return period of the ROI
		var returnperiod = $('#retPeriod').val();
		
		// Determine if section is included or not
		var included = $('#check'+id).val();

		if(yr == "total"){
			
			// Calculate percentage of ROI savings after implementation period
			var totalpercentage = ( returnperiod * 12 - impperiod ) / ( returnperiod * 12 );
		} else {
			
			// If year is defined then calculate total for that year only
			var totalpercentage = 1;
			if(impperiod >= yr * 12){ totalpercentage = 0; }
			else if(impperiod < yr * 12 && impperiod >= ( yr - 1 ) * 12) { totalpercentage = ( yr * 12 -impperiod ) / 12; }
			returnperiod = 1;
		}

		var sectiontotal = eq * confactor * returnperiod * totalpercentage;
		
		if(!ex) { return sectiontotal } else { return sectiontotal * included };
		
	});
	
	$('#page-wrapper').calx('registerFunction', 'SECTIONGRAND', function(id){
		
		// Get section conservative factor
		var confactor = ( 100 - $('#currentValueCon'+id).val() ) / 100 || 1;
		
		// Get implementation period
		var impperiod = $('#impPeriod').val();
		
		// Get the return period of the ROI
		var returnperiod = $('#retPeriod').val();
		
		// Determine if section is included or not
		var included = $('#check'+id).val();
		
		var eq = $('#page-wrapper').calx('evaluate', $('.section-equations[data-section-id="'+id+'"]').data('formula') );

		// Calculate percentage of ROI savings after implementation period
		var totalpercentage = ( returnperiod * 12 - impperiod ) / ( returnperiod * 12 );
		
		var sectiontotal = eq * confactor * returnperiod * totalpercentage;

		return sectiontotal;
		
	});
	
	$('#page-wrapper').calx('registerFunction', 'TOTALSAVINGS', function(ex){
		
		var grandTotal = 0;
		$('.section-equations').each(function(){

			grandTotal += $('#page-wrapper').calx('evaluate','SECTIONTOTAL('+$(this).data('formula')+', "total", '+$(this).data('section-id')+','+ex+')');
		});
		
		return grandTotal;
		
	});
	
	$('#page-wrapper').calx('registerFunction', 'NETPV', function(ex){
		
		// Get the return period of the ROI
		var returnperiod = $('#retPeriod').val();
		
		var npv_string = '';
		
		for( i=1; i<=returnperiod; i++ ){
			
			var annualTotal = 0;
			
			$('.section-equations').each(function(){

				annualTotal += $('#page-wrapper').calx('evaluate','SECTIONTOTAL('+$(this).data('formula')+', ' + i + ', '+$(this).data('section-id')+',"true")');
			});
			
			annualTotal += $('#page-wrapper').calx('evaluate','ANNUALCOST(' + i + ')');
			
			if( npv_string ) { npv_string += ', ' + annualTotal } else { npv_string += annualTotal };
		
		}
		
		return $('#page-wrapper').calx('evaluate','NPV(0.02, ' + npv_string + ')');
		
	});
	
	$('#page-wrapper').calx('registerFunction', 'GRANDTOTAL', function(ex){
	
		grandtotal = $('#page-wrapper').calx('evaluate','TOTALSAVINGS('+ex+')') + $('#page-wrapper').calx('evaluate','ANNUALCOST("total")');
		
		return grandtotal;
	});
	
	$('#page-wrapper').calx('registerFunction', 'ANNUALCOST', function(yr){
		
		var totalcost = 0;
		
		if(yr==1) {
		
			$('[data-cost-yr="0"]').each(function(e){
				totalcost -= numeral().unformat( $(this).val() );
			});
			
			$('[data-cost-yr="1"]').each(function(e){
				totalcost -= numeral().unformat( $(this).val() );
				//totalcost -= $('#page-wrapper').calx('getCell', $(this).data('cell')).getValue();
			});
		} else {
			
			$('[data-cost-yr="'+yr+'"]').each(function(e){
				totalcost -= numeral().unformat( $(this).val() );
				//totalcost -= $('#page-wrapper').calx('getCell', $(this).data('cell')).getValue();
			});			
		}
		
		if(yr=="total") {
			
			// Loop through all costs
			$('[data-cost-yr]').each(function(e){
				totalcost -= numeral().unformat( $(this).val() );
			});
		}
		
		return totalcost;
	});
	
	$('#page-wrapper').calx('registerFunction', 'ANNUALTOTAL', function(yr){
		
		var annualTotal = 0;
		
		$('.section-equations').each(function(){
			
			annualTotal += $('#page-wrapper').calx('evaluate','SECTIONTOTAL('+$(this).data('formula')+', '+yr+', '+$(this).data('section-id')+',"true")');
		});
		
		return annualTotal -= $('#page-wrapper').calx('evaluate','ABS(ANNUALCOST(' + yr + '))');
	});
	
	$('#page-wrapper').calx('registerFunction', 'ROI', function(){
		
		var totalSavings = $('#page-wrapper').calx('evaluate','TOTALSAVINGS("true")');

		var annualCost = $('#page-wrapper').calx('evaluate','ABS(ANNUALCOST("total"))');

		if( totalSavings / annualCost == 'Infinity' ) {
			return "N/A";
		} else {
			return totalSavings / annualCost;
		}
		
	});
	
	$('#page-wrapper').calx('registerFunction', 'PAYBACK', function(){
		
		var retPeriod = $('#retPeriod').val();
		
		var impPeriod = $('#impPeriod').val();
		
		var totalCost = $('#page-wrapper').calx('evaluate','ABS(ANNUALCOST("total"))');
		
		var currentSavings = -totalCost;
		
		var savingsReturned = false;
		
		// Loop through each year of ROI
		for( var yr = 1; yr <= retPeriod; yr++ ) 
		{

			var annualSavings = 0;
			
			$('.section-equations').each(function(){

				annualSavings += $('#page-wrapper').calx('evaluate','SECTIONTOTAL('+$(this).data('formula')+', '+yr+', '+$(this).data('section-id')+',"true")');
			});
			
			currentSavings += annualSavings;
			
			if(currentSavings > 0 && !savingsReturned) {
				return ( annualSavings - currentSavings ) / annualSavings * ( yr * 12 ) + impPeriod * 1;
				savingsReturned = true;
			}
			
		}
		
		if(currentSavings<=0 && !savingsReturned) {
			return retPeriod * 12 + impPeriod * 1;
		}
		
	});
	
	setUpSliders();
	loadValues();
	
	$("input").on( 'keyup', function(e) {

		if( $(this).data('formula') ) {
			
			if( $(this).val() == '' ) {
				
				$('#page-wrapper').calx( 'getCell', $(this).data('cell') ).setFormula( $(this).data('original-equation') );
			} else {
				
				$('#page-wrapper').calx( 'getCell', $(this).data('cell') ).setFormula( $(this).val() );
			}	
		}
		var cellValue = numeral().unformat($(this).val());
		$('#page-wrapper').calx( 'getCell', $(this).data('cell') ).setValue( cellValue );	
		calculateTotals();
	});
	
	$("input, textarea, select").on( 'blur', function(e) {
		
		
		if( $(this).val() == '' ) {
			
			if( $(this).data('placeholder-value') ) {
				
				$(this).val( $(this).data('placeholder-value') );
			}
		}
		
		//grab inputs value
		var inputVal = $(this).val();
		
		if(	$(this).data('input-type') != 'alphanumeric' ) {
		
			//change all inputs with the same name to the value that was just changed
			$('[name="'+$(this).attr('name')+'"]').each( function(i) {
				if( $(this).hasClass('pdf-input') ) {
					$('#page-wrapper').calx('getCell', $(this).data('cell')).setValue(inputVal).renderComputedValue();
				} else {
					try {
						$('#page-wrapper').calx('getCell', $(this).data('cell')).setValue(inputVal).renderComputedValue();
					} catch(e) {}
					
					$(this).val( inputVal );
				}
			});
			
		}
		
		//store values 
		storeValues( $(this) );
		
	});
	
	$('.section-navigator').on('click', function(){

		// Get section id to show and section type
		var sectionId = $(this).attr('href');
		var sectionType = $(this).data('section-type');
		
		$('[data-show-with]').each(function(){
			
			// Hide all sections then show any that share the same show type
			$(this).hide();
			if( $(this).data('show-with') == sectionType ) {
				$(this).show();
			}
			
		});
		
		// Show the section that was clicked
		$('[data-section-holder-id="' + sectionId + '"]').show();
	
	});
	
	/**********************************************
	 ** RESIZE WIDGETS TO MATCH LARGEST
	 **********************************************/
	// Set Max Widget Height to 0
	var maxHeight = 0;	 
	 
	// Loop through all widgets to determine max height
	$('.widget').each(function(){
		if( $(this).height() > maxHeight ) { maxHeight = $(this).height(); } 
	});
	
	// Set all widget heights to match max height
	$('.widget').each(function(){
		$(this).height( maxHeight );		
	});
	
	$('#pdf_reset').on( 'click', function() {
		$.ajax({
			type	: 	"POST",
			url		:	"../../php/database.manipulation.php",
			data	:	'action=resetpdf&roi='+getUrlVars()['roi'],
			success	: function() {
				location.reload();
			}
		});	
	});
	
	$('#pdf_create_document').on( 'click', function() {
		var htmlescape = escape( encodeURIComponent( $('#roiContent').html() ) );
		$.ajax({
			type	: 	"POST",
			url		:	"../../php/database.manipulation.php",
			data	:	'action=storepdf&html='+htmlescape+'&roi='+getUrlVars()['roi'],
			success	: function() {
				$('#pdf_output')[0].click();
			}
		});	
	});

	$('#create-pdf').on('click', function() {
		
		$('#pdf_save')[0].click();
	});
	
	$('#pdf_save').on( 'click', function() {
		
		$('#pdf-progress-overlay').show();
		
		$.ajax({
			type	: 	"POST",
			url		:	"../php/database.manipulation.php",
			data	:	'action=deletepdf&roi='+getUrlVars()['roi'],
			success	:	function() {
				createCharts(0);
			}
		});
						
		
		function createCharts( chrt ) {
			
			var totalElementsToCreate = $('[data-pdf-element]').length + $('.pdf-chart').length;
			var currentChart = chrt + 1;
			var currentProgress = currentChart / totalElementsToCreate * 100;

			$('.pdf-progress-alert').html('Creating charts for PDFs (' + currentChart + ' of ' + $('.pdf-chart').length + ')');
			$('.pdf-progress-bar').width(currentProgress + '%');
			
			if(currentProgress > 66) {
				$('.pdf-progress-bar').removeClass('progress-bar-warning').removeClass('progress-bar-danger').addClass('progress-bar-success');
			} else if (currentProgress > 33) {
				$('.pdf-progress-bar').removeClass('progress-bar-success').removeClass('progress-bar-danger').addClass('progress-bar-warning');
			}
			
			var chart = $('.pdf-chart:eq('+chrt+')').highcharts();
			var pdf_type = $('.pdf-chart:eq('+chrt+')').data('chart-type');
			var opts = chart.options;        // retrieving current options of the chart
			opts = $.extend(true, {}, opts); // making a copy of the options for further modification
			delete opts.chart.renderTo;      // removing the possible circular reference
							
			if( $('.pdf-chart:eq('+chrt+')').hasClass('pie-chart') )
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
					$.post("save-graph.php?roi="+getUrlVars()['roi']+"&chart="+pdf_type, {
						imageData: encodeURIComponent(imgUrl)
					});
					
					chrt += 1;
					if(chrt < $('.pdf-chart').length) {
						
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
					
			var totalElementsToCreate = $('[data-pdf-element]').length + $('.pdf-chart').length;
			var currentElement = element + 1;
			var currentProgress = (currentElement + $('.pdf-chart').length) / totalElementsToCreate * 100;
			
			$('.pdf-progress-alert').html('Saving PDF Elements (' + currentElement + ' of ' + $('[data-pdf-element]').length + ')');
			$('.pdf-progress-bar').width(currentProgress + '%');
			
			if(currentProgress > 66) {
				$('.pdf-progress-bar').removeClass('progress-bar-warning').removeClass('progress-bar-danger').addClass('progress-bar-success');
			} else if (currentProgress > 33) {
				$('.pdf-progress-bar').removeClass('progress-bar-success').removeClass('progress-bar-danger').addClass('progress-bar-warning');
			}
			
			if( $('[data-pdf-element]:eq('+element+')').data('content-type') == "chart" )
			{
				pdf_html = '<img src="../../../company_specific_files/' + $('#companyid').val() + '/pdfs/' + $('[data-pdf-element]:eq('+element+')').find('.pdf-chart').data('chart-type') + getUrlVars()['roi'] + '.png">'
			} else {
						
				pdf_html = encodeURIComponent( $('[data-pdf-element]:eq('+element+')').html() );
			}
			var pdf_element = $('[data-pdf-element]:eq('+element+')').data('pdf-element');
			var pdf_element_x = $('[data-pdf-element]:eq('+element+')').data('pos-x');
			var pdf_element_y = $('[data-pdf-element]:eq('+element+')').data('pos-y');
			var pdf_page = $('[data-pdf-element]:eq('+element+')').closest('[data-page]').data('page');
					
			$.ajax({
				type	: 	"POST",
				url		:	"../php/database.manipulation.php",
				data	:	'action=changepdf&element='+pdf_element+'&html='+pdf_html+'&posx='+pdf_element_x+'&page='+pdf_page+'&posy='+pdf_element_y+'&roi='+getUrlVars()['roi'],
				success	:	function() {
					element += 1;
					if(element < $('[data-pdf-element]').length) {
						savePdfElements(element);
					} else {
						$('#pdf-progress-overlay').hide();
						$('.pdf-progress-alert').html('Beginning to build the PDF');
						$('.pdf-progress-bar').removeClass('progress-bar-warning').removeClass('progress-bar-success').addClass('progress-bar-danger');
						$('#pdf_crete_new_template')[0].click();
					}					
				}
			});
					
		}
				
	});
	
	$('[data-growler]').on('focus', function(e){
		
		toastr.options = {
			positionClass: 'toast-top-right',
			closeButton: true,
            progressBar: true,
            showMethod: 'slideDown',
            timeOut: 15000,
			extendedTimeOut: 2000
        };
        toastr.success($(this).data('growler'));

	});
	
	$(".player").fitVids();
	
	$('.check-all').on('click', function() {
		
		$('.integration-key').each(function() {
			
			$(this).iCheck('check');
		});
	});
	
	$('.uncheck-all').on('click', function() {
		
		$('.integration-key').each(function() {
			
			$(this).iCheck('uncheck');
		});
	});
	
	/*****************************************
	 ** 3. SET UP POPUP-IFRAME
	 *****************************************/
	 
	$('.popup-iframe').magnificPopup({
		disableOn: 700,
		type: 'iframe',
		preloader: false,
		fixedContentPos: false
	});
	
	$('.quotes').quovolver({
		autoPlaySpeed : 8000,
		transitionSpeed : 500
	});
	
	$('.btn-section-notes').on('click', function(e){
		
		var section_id = $(this).data("section-id");
		var current_note = $(this);
		
		$.ajax({
			type	: 	"GET",
			url		:	"../../php/database.manipulation.php",
			data	:	'action=getnotes&roi='+getUrlVars()['roi']+'&section='+section_id,
			success	:	function( values ) {

				values = $.parseJSON(values);
				
				note_popup = '<div id="section-notes" data-section-note="' + section_id + '" class="white-popup-block"><h2>Section Notes</h2><hr/><div class="inspinia-timeline"><div class="timeline-items">';
				
				$.each(values, function( index, value ){
					note_popup += createNote( value.id, value.dt, value.note_title, value.note );
				});
				
				note_popup += '</div><hr/><div class="clearfix"></div><div class="mail-box-header">\
					<h2>\
						Add New Note\
					</h2>\
				</div>\
                <div class="mail-box">\
					<div class="mail-body">\
						<form class="form-horizontal" method="get">\
                        	<div class="form-group"><label class="col-sm-2 control-label">Title:</label>\
							<div class="col-sm-10"><input type="text" class="form-control note-title"></div>\
                        </div>\
                        </form>\
					</div>\
					<div class="mail-text h-200">\
						<div class="summernote">\
						</div>\
					<div class="clearfix"></div>\
                        </div>\
                    <div class="mail-body text-right tooltip-demo">\
                        <a href="#" class="btn btn-sm btn-primary save-note" data-toggle="tooltip" data-placement="top" title="Save Note" data-section="' + section_id + '"><i class="fa fa-reply"></i> Save Note</a>\
                        <a href="#" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Discard email"><i class="fa fa-times"></i> Discard</a>\
                    </div>\
                    <div class="clearfix"></div></div></div>';
				 
				$.magnificPopup.open({
					items: {
						src: note_popup
					},
					type: 'inline'
				});
				
				$('.summernote').summernote({
					toolbar:[
						['font', ['bold','underline','italic','clear']],
						['para',['ul','paragraph']],
						['insert', ['link']]
					]
				});
				
				$('.save-note').on('click', function(e){
					saveSectionNote( $(this).data('section'), $.htmlClean($('.summernote').code()), $('.note-title').val(), current_note );					
					//console.log( $('.summernote').code().substring( $('.summernote').code().indexOf('</style>') ).replace('</style>','').replace('<![endif]-->','') );
					e.preventDefault();
				});
				
				/*$('.summernote-note').on('click', function() {
					$(this).summernote({
						airMode: true,
						toolbar:[
							['font', ['bold','underline','italic','clear']],
							['para',['ul','paragraph']],
							['insert', ['link']]
						]
					});
				});*/

				setUpRemoveNote();
			}
		});

	});
	
	$('.fa-question-circle').tooltipster({
		theme: 'tooltipster-light',
		maxWidth: 300,
		animation: 'grow',
		position: 'right',
		arrow: false,
		interactive: true,
		contentAsHTML: true
	});
	
	$('.fa-calculator').tooltipster({
		theme: 'tooltipster-light',
		maxWidth: 300,
		animation: 'grow',
		position: 'top-right',
		arrow: false,
		content: 'Click here to view the calculation breakdown'
	});
	
	var idleInterval = setInterval(timerIncrement, 60000);
	
	$(this).mousemove(function(e) {
		
		idleTime = 0;
	});
	
	$(this).keypress(function(e) {
		
		idleTime = 0;
	});
	
	numeral.language( $('#userCurrency').data('user-currency') == 'usd' ? '' : $('#userCurrency').data('user-currency') );
	
});

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

function setUpSliders(){
		
	/*******************************************
	
			SET UP IMPLEMENTATION SLIDERS
				
	 *******************************************/
		
	var roiMonths = $('#retPeriod').val() * 12;
	
	$(".implementation_period").noUiSlider({
			
		// Define Implementation Slider Set Up
		start: 0,
		connect: "lower",
		step: 1,
		range: {
			"min":  0,
			"max":  roiMonths
		},
		format: {
			to: function ( value ) { return value; },
			from: function ( value ) { return value; }
		}
		
	});
	
	$(".implementation_period").Link('lower').to( function(value) {
			
		// Change Implementation Period to match the Slider
		$(this).closest('.faq-item').find('.pull-right').html( value + ( value == 1 ? ' month' : ' months' ) );
		
	});
		
	$(".implementation_period").on({
			
		slide: function(){
				
			// Get Current Slider Value
			var sliderValue = $(this).val();

			// Change Implementation Input to Match Slider
			$("#impPeriod").val(sliderValue);
			
			// Calculate the ROI as user changes conservative factor
			calculateTotals();			
				
		},
			
		// Set Up Implementation Change Procedure
		change: function(){
				
			// Get Current Slider Value
			var sliderValue = $(this).val();
			
			// Change Each Slider to Match Current Value
			$(".implementation_period").each(function(e){
				$(this).val(sliderValue);
			});
				
			// Store Values
			storeValues( $("#impPeriod") );
		}
	});
		
	/***************************************** 
		
			SET UP CONSERVATIVE SLIDERS
				
	 *****************************************/
		 
	$(".conservative_slider").noUiSlider({
			
		// Define Conservative Slider Set Up
		start: 0,
		connect: "lower",
		step: 5,
		range: {
			"min":  0,
			"max":  100
		},
		format: {
			to: function ( value ) { return value; },
			from: function ( value ) { return value; }
		}
		
	});

	$(".conservative_slider").Link('lower').to( function(value) {
			
		// Change Conservative Value to match the Slider
		$(this).closest('.value-holder').find('.pull-right').html( value + '%' );
		
	});

	$(".conservative_slider").on({
			
		// Set Up Conservative Slider Slide Procedure
		slide: function(){
			
			// Get Current Slider Value
			var sliderValue = $(this).val();
			var conservativeSection = $(this).data("conservative-section-id");
			
			// Change Conservative Input to Match Slider
			$("#currentValueCon" + conservativeSection).val( sliderValue );

			// Calculate the ROI as user changes conservative factor
			calculateTotals();			

		},
			
		// Set Up Conservative Slider Change Procedure
		change: function(){
				
			// Get Current Slider Value
			var sliderValue = $(this).val();
			var conservativeSection = $(this).data("conservative-section-id");
			
			// Change Each Slider to Match Current Value
			$("[data-conservative-section-id='" + conservativeSection + "']").each(function(e){
				$(this).val(sliderValue);
			});
				
			// Store Values
			storeValues( $("#currentValueCon" + conservativeSection) );
				
		}
			
	});
		
	/************************************** 
		
			SET UP ALL OTHER SLIDERS
				
	 **************************************/

	$(".slider").noUiSlider({
		start: 0,
		connect: "lower",
		step: 1,
		range: {
			"min":  0,
			"max":  100
		},
		format: {
			to: function ( value ) { return value; },
			from: function ( value ) { return value; }
		}
	});

	$(".slider").Link('lower').to( function(value) {
			
		//Get slider name
		var sliderName = $(this).closest('.form-group').find('.slider-input').attr('name');

		//Set slider Input to equal the slider
		try{ $('#page-wrapper').calx('getSheet').getCell('A'+ sliderName).setValue(value).calculate(); } catch(e) { }
		
		$(this).closest('.form-group').find('.slider-input').val(value);
	
	});
		
	$(".slider").on({			
			
		slide: function(){
		
			calculateTotals();
		},
		
		change: function(){
				
			// Get Current Slider Value
			var sliderValue = $(this).val();
			var sliderName = $(this).closest('.form-group').find('.slider-input').attr('name');
			
			// Change all other inputs that share the same name / are linked
			$('[name="' + sliderName + '"]').each(function(){
				$(this).closest('.form-group').find('.slider').val( sliderValue );
			});
			
			// Store ROI Values
			storeValues( $('[name="' + sliderName + '"]') );
			
		}
			
	});
				
	$('.slider-input').on('change', function(e){
		$(this).closest('.form-group').find('.slider').val( $(this).val() );
	});		
		
	/************************************** 
		
			SET UP DECIMAL SLIDER
				
	 **************************************/

	$(".decimal-slider").noUiSlider({
		start: 0,
		connect: "lower",
		step: 1,
		range: {
			"min":  0,
			"max":  5
		},
		format: {
			to: function ( value ) { return value; },
			from: function ( value ) { return value; }
		}
	});

	$(".decimal-slider").Link('lower').to( function(value) {
		$(this).closest('.form-group').find('.decimal-slider-input').val( value );
	});
		
	$(".decimal-slider").on({			
			
		change: function(){
				
			// Get Current Slider Value
			var sliderValue = $(this).val();
			var sliderName = $(this).closest('.form-group').find('.decimal-slider-input').attr('name');
			
			$('[name="' + $('#inputSettings').data( 'input-id' ) + '"]').each(function(){
				$(this).data('input-precision', sliderValue );
			});

			// Store ROI Values
			storeValues( $('[name="' + $('#inputSettings').data( 'input-id' ) + '"]') );
				
		}
			
	});
				
	$('.decimal-slider-input').on('change', function(e){
		$(this).closest('.form-group').find('.decimal-slider').val( $(this).val() );
	});	
		
}

function loadValues() {

			
	$.ajax({
		type	: 	"GET",
		url		:	"../../php/database.manipulation.php",
		data	:	'action=getoverriddenoutput&roi=' + getUrlVars()['roi'],
		success	:	function( values ) {
							
			// Parse returned values
			values = $.parseJSON( values );

			for( i=0; i<values.length; i++ ) {
						
				var entryCell = $('[data-cell="' + values[i]['entryid'] + '"]');
				var entryValue = values[i]['value'];

				entryCell.each(function() {
						
					var cellIdentifier = $(this).data('cell');
					$('#page-wrapper').calx('getCell', cellIdentifier).setFormula( entryValue + ' * 1' );
					$(this).css('color', 'rgb(165,42,42)');
				});
			}
			$('#page-wrapper').calx('getSheet').calculate();
		}
	});
			
	// Ajax call for getting stored values
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
						$('#page-wrapper').calx( 'getCell', $(this).data('cell') ).setValue( entryValue ).renderComputedValue();
					} catch(e){ }					
				});

			}

			// Loop through each input on screen and place value if CurrentValues[] array contains a value
			/*$( 'input, textarea, select' ).each( function() {
				if( $(this).attr('id') !== 'retPeriod' ) {
					if( CurrentValues[ this.name ] ) {
						try {
							this.value = CurrentValues[ this.name ];
							$('#page-wrapper').calx('getCell', $(this).data('cell')).setValue(CurrentValues[ this.name ]).renderComputedValue();
						} catch(e){ }
					}
				}
			});*/
			
			$( '.pdf-input' ).each( function() {
				if( CurrentValues[ $(this).attr('name') ] ) {
					try {
						$('#page-wrapper').calx('getCell', $(this).data('cell')).setValue(CurrentValues[ $(this).attr('name') ] || 0).renderComputedValue();
					} catch(e){ }
				}
			});
			
			$('[data-input-type="conservative"]').each(function(){
				var conFactor = $("#currentValueCon" + $(this).closest('#fullpage').data('section-id') ).val() || 0;
				$(this).html( conFactor + '%' );
			});
				
			$('[data-input-type="implementation"]').each(function(){
				var impPeriod = $("#impPeriod").val() || 0;
				$(this).html( impPeriod + ( impPeriod == 1 ? ' month' : ' months' ) );
			});	
		
			// Set slider values
			$('.slider-input').each( function(e){
				
				var inputValue = $(this).val();
				var inputValue = inputValue.replace(/\D/g,'');
				
				$(this).closest('.form-group').find('.slider').val( inputValue );
			});
			
			// Set up implementation sliders
			$(".implementation_period").each(function(e){
				$(this).val( $("#impPeriod").val() );
			});

			// Set up implementation sliders
			$(".conservative_slider").each(function(e){
				$(this).val( $("#currentValueCon" + $(this).data('conservative-section-id') ).val() );
			});				

			//Set up chosen selector
			$('.chosen-selector').chosen({
				width: '100%',
				disable_search_threshold: 10
			}).change(function(){
				try{ $('#page-wrapper').calx('getSheet').getCell($(this).data('cell')).setValue(value).calculate(); } catch(e) { }
				storeValues( $(this) );
			});
				
			// Change all empty section include inputs to 1
			$('.section-include').each(function(e){
				if( $(this).val().length == 0 ) { $(this).val(1); }
			});
			
			setupIncludeButtons();
			
			$('.pdf-grand-total').each(function(){

				var totalFormula = '';
				
				$('.section-equations').each(function(){
					
					if(totalFormula) {
					
						totalFormula += '+'
					}
					
					totalFormula += "SECTIONTOTAL(" + $(this).data('formula') + ", 'total', " + $(this).data('section-id') + ", true)";
				});
				
				totalFormula += "+ANNUALCOST('total')";
				
				var someTotal = $('#page-wrapper').calx("evaluate", totalFormula);
				
				$('#page-wrapper').calx('getCell', $(this).data('cell')).setFormula(totalFormula);	
				
			});	

			$('#loadvalues-overlay').remove();
			
			$('select').each(function() {

				hideSelectChildren($(this));		
			});
			
			$('.chosen-container:visible').each(function() {
				
				showSelectChildren($(this).parent().find('select'));
			});

			$('select').on('change', function() {
				
				hideSelectChildren($(this));
				showSelectChildren($(this));
			});			

			var widgets = $('.widget');
			var ctr = 0;
				
			var fadeWidgets = function() {
				$(widgets[ctr]).css('visibility','visible').hide().fadeIn(1000);
				
				ctr++;
				if (widgets[ctr]) {
					setTimeout(fadeWidgets, 200);
				}
			};
			
			calculateTotals();
			
			fadeWidgets();
			
			__constructBarChart();
			
			__buildStakeholderGraph();
			
		}
	});

}

/**********************************************
 ** MISCELLANEOUS FUNCTIONS
 **********************************************/
	
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

function storeValues( element ) {

	try {
		
		var entryValue = element.val();
	
		if(	element.data('input-type') != 'alphanumeric' ) {
			if( element.data('format') ) {
				if( element.data('format').indexOf('%') === -1 ) {
					entryValue = numeral().unformat(entryValue);
				}
			}
		}
		
		$.ajax({
			type	: 	"POST",
			url		:	"../../php/database.manipulation.php",
			data	:	'action=storevalues&roi='+getUrlVars()['roi']+'&entry='+element.attr('name')+'&val='+encodeURIComponent( entryValue ),
			success :	function(e) {

			}
		});
		
	} catch(e) { }
		
}

function setupIncludeButtons(){
		
	$('.btn-include').each(function(){
			
		var sectionId = $(this).data('included-section-id');
		if( $('#check' + sectionId).val() == 0 ) {
			$(this).html('<i class="fa fa-times"></i> Excluded')
				   .data('checked-state', 0)
				   .removeClass('btn-primary')
				   .addClass('btn-danger');				
		} else {
			$(this).html('<i class="fa fa-check"></i> Included')
				   .data('checked-state', 1)
				   .removeClass('btn-danger')
				   .addClass('btn-primary');				
		}
			
	});
		
	$('.btn-include').on('click', function(){
			
		//Get current button state and section id
		var currentState = $(this).data('checked-state');
		var sectionId = $(this).data('included-section-id');
		
		if( currentState ){
			
			// If currentState exists then section is currently included, so exclude it
			$('button[data-included-section-id="' + sectionId + '"]').each(function(){
				$(this).html('<i class="fa fa-times"></i> Excluded')
					   .data('checked-state', 0)
					   .removeClass('btn-primary')
					   .addClass('btn-danger');
				$('#check' + sectionId).val(0);					
			});
			
		} else {
				
			// If currentState doesn't exist then it is excluded, so include it
			$('button[data-included-section-id="' + sectionId + '"]').each(function(){
				$(this).html('<i class="fa fa-check"></i> Included')
					   .data('checked-state', 1)
					   .removeClass('btn-danger')
					   .addClass('btn-primary');
				$('#check' + sectionId).val(1);
			});
		}
		
		calculateTotals();		
		storeValues( $('#check' + sectionId) );
		
	});
}

function calculateTotals() {
	
	$('#page-wrapper').calx('getSheet').calculate();
	__updateBarChart();
}

function numberWithCommas(value) {
		
	var parts = value.toString().split(".");
	parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	return parts.join(".");
	
}
	
function __constructBarChart() {
		
	// Get Return Period
	var returnPeriod = $('#retPeriod').val();
	
	// Define Graph Data
	var graphData = [];
	
	$('.section-equations').each(function(){
		
		var annualTotals = [];
		for( i=1; i<=returnPeriod; i++ ) {
			annualTotals.push( $('#page-wrapper').calx("evaluate", "SECTIONTOTAL(" + $(this).data('formula') + ", " + i + ", " + $(this).data('section-id') + ", true)") );
		}
		
		graphData.push( { name: $(this).data('section-name'), data: annualTotals } );
	});
		
	var categories = [], costs = [];
	
	for( i=1; i<=returnPeriod; i++ )
	{
			
		// Define years in array for charting
		categories.push( 'Year ' + i );
		
		costs.push( $('#page-wrapper').calx("evaluate", "ABS(ANNUALCOST(" + i + "))") );
	}
		
	graphData.push( { name: 'Cost', data: costs } );
	
	Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function (color) {
		return {
			radialGradient: { cx: 0.5, cy: 0.3, r: 0.7 },
			stops: [
				[0, color],
				[1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
			]
		};
	});	
	
    Highcharts.wrap(Highcharts, 'numberFormat', function (proceed) {
        var ret = proceed.apply(0, [].slice.call(arguments, 1));
        return numeral(ret).format('$0,0');
    });

	var pdf_options = {
		
		chart: {
            type: 'column',
			height: '280',
            options3d: {
                enabled: true,
                alpha: 0,
                beta: 0,
                depth: 60,
                viewDistance: 10
            },
			backgroundColor: 'transparent',
			marginTop: 5
        },
		credits: {
			enabled: false
		},
		exporting: {
			enabled: false
		},
		title: {
			text: ''
		},
		xAxis: {
			categories: categories
		},
		yAxis: {
			min: 0,
			style: {
				color: '#333',
				fontWeight: 'bold',
				fontSize: '12px',
				fontFamily: 'Trebuchet MS, Verdana, sans-serif'
			},				
			title: {
				text: 'Money'
			}
		},
		tooltip: {
			headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
				'<td style="color:{series.color};padding:0;padding-left:10px;"><b> {point.y:,.0f}</b></td></tr>',
			footerFormat: '</table>',
			shared: true,
			useHTML: true
		},
		plotOptions: {
			column: {
				pointPadding: 0.2,
				borderWidth: 0
			}
		},
		series: graphData
	}
	
	var options = {
		
        chart: {
            type: 'column',
            margin: 75,
            options3d: {
                enabled: true,
                alpha: 0,
                beta: 0,
                depth: 60,
                viewDistance: 10
            }
        },
		title: {
			text: 'Your Potential Return on Investment'
		},
		xAxis: {
			categories: categories
		},
		yAxis: {
			min: 0,
			style: {
				color: '#333',
				fontWeight: 'bold',
				fontSize: '12px',
				fontFamily: 'Trebuchet MS, Verdana, sans-serif'
			},				
			title: {
				text: 'Money'
			}
		},
		tooltip: {
			headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
				'<td style="color:{series.color};padding:0;padding-left:10px;"><b> {point.y:,.0f}</b></td></tr>',
			footerFormat: '</table>',
			shared: true,
			useHTML: true
		},
		plotOptions: {
			column: {
				pointPadding: 0.2,
				borderWidth: 0
			}
		},
		series: graphData
		
	}
	
	var demandware_options = {
		
        chart: {
            zoomType: 'xy'
        },
        title: {
            text: 'Demandware Annual Sales & Costs'
        },
        xAxis: [{
            categories: ['Year 1','Year 2','Year 3'],
            crosshair: true
        }],
        yAxis: [{ // Primary yAxis
            min: -5000,
			labels: {
                format: '$ {value}',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            title: {
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            }
        }, { // Secondary yAxis
            title: {
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            labels: {
                format: '{value} %',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            opposite: true
        }],
        tooltip: {
            shared: true
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            x: 120,
            verticalAlign: 'top',
            y: 100,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
		plotOptions: {
			column: {
				stacking: 'normal'
			}
		},
        series: [{
            name: 'Sales',
            type: 'column',
            data: [35000,36407,43688]

        }, {
			name: 'Cost',
			type: 'column',
			data: [-1249, -1413, -1587]
		}, {
            name: 'Cost as % of Sales',
            type: 'spline',
			yAxis: 1,
            data: [4.12, 3.88, 3.63],
            tooltip: {
                valueSuffix: '%'
            }
        }]	
	}
	
	var demandware_spline_options = {
		
        title: {
            text: 'Total Sales (in 000s)',
            x: -20 //center
        },
        xAxis: {
            categories: ['Year 1', 'Year 2', 'Year 3']
        },
        yAxis: {
            title: {
                text: 'Currency'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
            name: 'Demandware',
            data: [30339, 36407, 43668]
        }, {
            name: 'Alternative Platform',
            data: [26000, 31200, 37440]
        }]
	}	
	
	$('.bar-chart').each(function(){
		if( $(this).hasClass('pdf-chart') ) {
			$(this).highcharts(pdf_options);
		} else {
			$(this).highcharts(options);
		}
	});
	
	$('.demandware-graph-1').each(function() {
			
		$(this).highcharts(demandware_options);
	});
	
	$('.demandware-graph-2').each(function() {
		
		$(this).highcharts(demandware_spline_options);
	});
	
	$('.pie-chart').each(function(){
		
		var pieChartData = [];
		var sectionid = $(this).data('section-id');
		
		$('.section-equations').each(function(){
			
			if( sectionid == $(this).data('section-id') ) {
				pieChartData.push( { name: $(this).data('section-name'), y: $('#page-wrapper').calx("evaluate", "SECTIONTOTAL(" + $(this).data('formula') + ", 'total', " + $(this).data('section-id') + ", true)"), sliced: true } );
			} else {
				pieChartData.push( { name: $(this).data('section-name'), y: $('#page-wrapper').calx("evaluate", "SECTIONTOTAL(" + $(this).data('formula') + ", 'total', " + $(this).data('section-id') + ", true)"), sliced: false } );
			}
		});
		
		pieChartData.push( { name: 'Cost', y: $('#page-wrapper').calx("evaluate", "ABS( ANNUALCOST('total') )"), sliced: false } );

		var piechart_options = {
			
			chart: {
				type: 'pie',
				options3d: {
					enabled: true,
					alpha: 45,
					beta: 0
				},
				backgroundColor: 'transparent',
				marginTop: 0
			},
			credits: {
				enabled: false
			},
			exporting: {
				enabled: false
			},
			title: {
				text: ''
			},
			tooltip: {
				pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					slicedOffset: 30,
					cursor: 'pointer',
					depth: 35,
					showInLegend: ( $('#companyid').val() == 37 ||  $('#companyid').val() == 1 || $('#companyid').val() == 9 ||  $('#companyid').val() == 91 ||  $('#companyid').val() == 33 || $('#companyid').val() == 81 ||  $('#companyid').val() == 47 ||  $('#companyid').val() == 141 ? true : false ),
					dataLabels: {
						enabled: ( $('#companyid').val() == 37 ||  $('#companyid').val() == 1 || $('#companyid').val() == 9 ||  $('#companyid').val() == 91 ||  $('#companyid').val() == 33 || $('#companyid').val() == 81  ||  $('#companyid').val() == 47 ||  $('#companyid').val() == 141 ? false : true ),
						borderRadius: 1,
						backgroundColor: 'rgba(252, 255, 197, 0.7)',
						color: 'black',
						borderWidth: 1,
						borderColor: '#AAA',
						distance: -15,
						format: '{point.name}',
						formatter: function(){
							if(this.y > 10 )
								return this.value;
							else	
								return null;
						}
					}
				}
			},
			legend: {
				enabled: true,
				layout: 'vertical',
				align: ( $('#companyid').val() == 81 ? 'center' : 'right' ),
				verticalAlign: ( $('#companyid').val() == 81 ? 'bottom' : 'middle' ),
				labelFormatter: function() {
					return this.name;
				}
			},
			series: [{
				type: 'pie',
				name: 'Browser share',
				data: pieChartData
			}]
			
		}
	
		$(this).highcharts(piechart_options);
	
	});
	
	// Function to resize chart if the size of the window changed
	$(window).resize(function() {
		
		try{
			
			// Get current chart height
			height = chart.height;
			
			// Get width of parent container which will match what our chart will be
			width = $(".bar-chart-container").width();
			
			// Set Size of Chart to calculated height, width
			chart.setSize(width, height, doAnimation = true);
			
		} catch(e) {}
	
	});
	
	__updateBarChart();
}

function __updateBarChart() {		

	$('.bar-chart').each(function(){

		// Get the Bar Chart
		var chart =  $(this).highcharts();

		// Get Return Period
		var returnPeriod = $('#retPeriod').val();
			
		// Set up sections added
		var sectionsAdded = 0;
			
		if( chart ) {
				
			// Build Chart Savings Total Array
			$('.section-equations').each(function(){

				var annualTotals = [];
				for( i=1; i<=returnPeriod; i++ ) {
					annualTotals.push( $('#page-wrapper').calx("evaluate", "SECTIONTOTAL(" + $(this).data('formula') + ", " + i + ", " + $(this).data('section-id') + ", true)") );
				}
					
				chart.series[sectionsAdded].setData( annualTotals, false );
					
				sectionsAdded ++;
				
			});
				
			var costs = [];
			for( i=1; i<=returnPeriod; i++ )
			{

				costs.push( $('#page-wrapper').calx("evaluate", "ABS(ANNUALCOST(" + i + "))") );
			}
					
			chart.series[sectionsAdded].setData( costs );
			//exportChart();
				
		}
	
	});
	
	$('.pie-chart').each(function(){
		
		try {
			
			// Get the Bar Chart
			var chart =  $(this).highcharts();
				
			var pieChartData = [];
			var sectionid = $(this).data('section-id');
			
			$('.section-equations').each(function(){
				
				if( sectionid == $(this).data('section-id') ) {
					pieChartData.push( { name: $(this).data('section-name'), y: $('#page-wrapper').calx("evaluate", "SECTIONTOTAL(" + $(this).data('formula') + ", 'total', " + $(this).data('section-id') + ", true)"), sliced: true } );
				} else {
					pieChartData.push( { name: $(this).data('section-name'), y: $('#page-wrapper').calx("evaluate", "SECTIONTOTAL(" + $(this).data('formula') + ", 'total', " + $(this).data('section-id') + ", true)"), sliced: false } );
				}
			});
			
			pieChartData.push( { name: 'Cost', y: $('#page-wrapper').calx("evaluate", "ABS( ANNUALCOST('total') )"), sliced: false } );
			
			chart.series[0].setData(pieChartData, true);

			
		} catch(e) { }
		
	});
	
	$('.demandware-graph-1').each(function() {
		
		try {
			
			var chart = $(this).highcharts();
			
			var totalsalesyr1 = $('#page-wrapper').calx('getCell', 'A14784').getValue() / 1000;
			var totalsalesyr2 = $('#page-wrapper').calx('getCell', 'A14785').getValue() / 1000;
			var totalsalesyr3 = $('#page-wrapper').calx('getCell', 'A14786').getValue() / 1000;

			var costyr1 = ( $('#page-wrapper').calx('getCell', 'A16821').getValue() + $('#page-wrapper').calx('getCell', 'A16901').getValue() + $('#page-wrapper').calx('getCell', 'A11741').getValue() ) / -1000;
			var costyr2 = ( $('#page-wrapper').calx('getCell', 'A16831').getValue() + $('#page-wrapper').calx('getCell', 'A16901').getValue() + $('#page-wrapper').calx('getCell', 'A11741').getValue() ) / -1000;
			var costyr3 = ( $('#page-wrapper').calx('getCell', 'A16841').getValue() + $('#page-wrapper').calx('getCell', 'A16901').getValue() + $('#page-wrapper').calx('getCell', 'A11741').getValue() ) / -1000;
			
			var percentage1 = ( $('#page-wrapper').calx('getCell', 'A16821').getValue() + $('#page-wrapper').calx('getCell', 'A16901').getValue() + $('#page-wrapper').calx('getCell', 'A11741').getValue() ) / $('#page-wrapper').calx('getCell', 'A14784').getValue() * 100;
			var percentage2 = ( $('#page-wrapper').calx('getCell', 'A16831').getValue() + $('#page-wrapper').calx('getCell', 'A16901').getValue() + $('#page-wrapper').calx('getCell', 'A11741').getValue() ) / $('#page-wrapper').calx('getCell', 'A14785').getValue() * 100;
			var percentage3 = ( $('#page-wrapper').calx('getCell', 'A16841').getValue() + $('#page-wrapper').calx('getCell', 'A16901').getValue() + $('#page-wrapper').calx('getCell', 'A11741').getValue() ) / $('#page-wrapper').calx('getCell', 'A14786').getValue() * 100;
			
			totalsales = [];
			
			totalsales.push(totalsalesyr1);
			totalsales.push(totalsalesyr2);
			totalsales.push(totalsalesyr3);
			
			totalcosts = [];
			
			totalcosts.push(costyr1);
			totalcosts.push(costyr2);
			totalcosts.push(costyr3);
			
			totalpercentage = [];
			
			totalpercentage.push(percentage1);
			totalpercentage.push(percentage2);
			totalpercentage.push(percentage3);

			chart.series[0].setData(totalsales, true);
			chart.series[1].setData(totalcosts, true);
			chart.series[2].setData(totalpercentage, true);
			
		} catch(e) { }

	});
	
	$('.demandware-graph-2').each(function() {
		
		try {
			
			var chart = $(this).highcharts();
			
			var totalsalesyr1 = $('#page-wrapper').calx('getCell', 'A14784').getValue() / 1000;
			var totalsalesyr2 = $('#page-wrapper').calx('getCell', 'A14785').getValue() / 1000;
			var totalsalesyr3 = $('#page-wrapper').calx('getCell', 'A14786').getValue() / 1000;
			
			totalsales = [];
			
			totalsales.push(totalsalesyr1);
			totalsales.push(totalsalesyr2);
			totalsales.push(totalsalesyr3);
			
			var alternatesalesyr1 = $('#page-wrapper').calx('getCell', 'A14781').getValue() / 1000;
			var alternatesalesyr2 = $('#page-wrapper').calx('getCell', 'A14782').getValue() / 1000;
			var alternatesalesyr3 = $('#page-wrapper').calx('getCell', 'A14783').getValue() / 1000;
			
			alternatesales = [];
			
			alternatesales.push(alternatesalesyr1);
			alternatesales.push(alternatesalesyr2);
			alternatesales.push(alternatesalesyr3);			
			
			chart.series[0].setData(totalsales, true);
			chart.series[1].setData(alternatesales, true);
			
		} catch(e) { }

	});
	
}

function styleOutputs() {
	
	$('.section-total').each(function(){
		
		$(this).removeClass('txt-removed').addClass('txt-money');
		if( $(this).data('section-id') ) {
		
			included = $('#check'+$(this).data('section-id')).val();
			if(!included || included == 0) { $(this).removeClass('txt-money').addClass('txt-removed'); }
		}
		
		if( $(this).data('cell') ) {
			
			if( $('#page-wrapper').calx('getCell', $(this).data('cell')).getValue() < 0 ) {
			
				$(this).removeClass('txt-money').addClass('txt-removed');
			}
		}
	});
	
	$('.cost').each(function(){
		
		$(this).removeClass('txt-money').addClass('txt-removed');
	});
	
	$('.section-percentage').each(function(){
			
		var sectionTotal = $('#page-wrapper').calx('evaluate','SECTIONTOTAL('+$(this).data('equation')+', "total", '+$(this).data('section-id')+', "true")');
		var totalSavings = $('#page-wrapper').calx('evaluate','TOTALSAVINGS("true")');
		var totalCost = $('#page-wrapper').calx('evaluate','ANNUALCOST("total")');
		
		$(this).css( 'width', ( totalSavings ? sectionTotal / (totalSavings - totalCost) * 100 : 0 ) + '%' );

	});
}

	/**********************************************
	 ** FUNCTIONS TO HANDLE NOTES
	 **********************************************/
	 
	function createNote(id, dt, title, note)
	{
		var t = dt.split(/[- :]/);
		var d = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
		var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
					
		var note_item = '<div class="timeline-item" data-note-id="' + id + '">\
							<div class="row">\
								<div class="col-xs-3 date">\
									<i class="remove-note fa fa-times"></i><i class="fa fa-pencil"></i>' + d.getDay() + ' ' +
									 months[d.getMonth()] + ', ' + d.getFullYear() +
									'<br/>\
									<small class="text-navy">' + d.getHours() + ':';
		
		if( d.getMinutes() < 10 ) { var minutes = '0' + d.getMinutes() } else { var minutes = d.getMinutes() }
			
		note_item += minutes + '	</small>\
								</div>\
								<div class="col-xs-9 content">\
									<p class="m-b-xs">\
										<h3>' + title + '</h3>\
									</p>\
									<div class="summernote-note">\
										<p>' + note + '</p>\
									</div>\
								</div>\
							</div>\
						</div>';

		return note_item;
	}	
	
	function setUpRemoveNote()
	{
		$('.remove-note').off().on('click', function(e){
					
			var timelineItem = $(this).closest('.timeline-item');
			var noteId = timelineItem.data('note-id');
			var section = $(this).closest('#section-notes').data('section-note');
			
			var n = noty({
				text        : 'Are you sure you\'d like to delete this note? Once deleted it cannot be recovered!',
				type        : 'alert',
				dismissQueue: true,
				closeWith   : ['click', 'backdrop'],
				modal       : true,
				layout      : 'center',
				theme       : 'relax',
				maxVisible  : 10,
				buttons : [
					{
						addClass: 'btn btn-primary',
						text:'Keep Note',
						onClick: function($noty) {
							$noty.close();
						}
					},
					{
						addClass: 'btn btn-danger',
						text: 'Delete Note',
						onClick: function($noty) {
							$.ajax({
								type	: 	"POST",
								url		:	"../../php/database.manipulation.php",
								data	:	'action=deletenote&noteid='+noteId,
								success	:	function() {
									$noty.close();
									noty({
										text: 'Note successfully deleted',
										type: 'error',
										timeout: 2000
									});
									timelineItem.slideUp(1200, function() {
										timelineItem.remove();
									});
									$('.btn-section-notes[data-section-id="' + section + '"]').find('.badge-info').html( CleanNumber( $('.btn-section-notes[data-section-id="' + section + '"]').find('.badge-info').html() ) - 1 );
								}
							});
						}
					}
				]
			});
		})
	}
	
	function saveSectionNote(section, note, title, currentNote)
	{

		$.ajax({
			type	: 	"POST",
			url		:	"../../php/database.manipulation.php",
			data	:	'action=savesectionnotes&roi='+getUrlVars()['roi']+'&section='+section+'&note='+note+'&title='+title,
			success	:	function( id ) {
				
				// Create current date string
				date = new Date();
				dateString = date.getFullYear() + "-" + ( date.getMonth() + 1 ) + "-" + date.getDate() + " " + date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();
				
				//Add newly create note to the note list
				$( createNote(id, dateString, title, note ) ).appendTo( $('.timeline-items') ).hide().show('slow');
				setUpRemoveNote();
				
				//Remove content from add new note form
				$('input.note-title').val('');
				$('.summernote').code('');
				
				currentNote.find('.badge-info').html( CleanNumber( currentNote.find('.badge-info').html() ) + 1 );
				
				//Create an alert to let the user know saving the note was successful
				noty({
					text: 'Note successfully added',
					type: 'success',
					timeout: 2000
				});			
			}
		});
		
	}
	
	/*$('.input-context-menu').on('click', function(e) {
		
		if( $(this).parent().find('.form-control').attr('disabled') )
		{
			
			var inputId = $(this).parent().find('.form-control').attr('id');
			$('#inputSettings').data( 'input-id', inputId );
			$('.decimal-slider').val( $(this).parent().find('.form-control').data('input-precision') );
			$('.stakeholder').val( $(this).parent().find('.form-control').data('stakeholder') ).trigger("chosen:updated");
			$('.savings-type').val( $(this).parent().find('.form-control').data('savings-type') )
			$('#inputSettings').modal('show');
		}
		
	});*/
	
	$('.savings-type').on('change', function(){
			
		var savings = $(this).val();
		$('[name="' + $('#inputSettings').data( 'input-id' ) + '"]').each(function(){
			$(this).data('savings-type', savings );
		});		

	});
	
	$('.stakeholder').on('change', function(){
			
		var stakeholder = $(this).val();
		$('[name="' + $('#inputSettings').data( 'input-id' ) + '"]').each(function(){
			$(this).data('stakeholder', stakeholder );
		});	

	});

	/*$('.stakeholder-graph').on('click', function(){

		var dataArray = [];
		var sectionsAdded = 0;
		var chart =  $('#stakeholder-graph').highcharts();
		$('.stakeholder > option').each(function(){
			var stakeholderTotal = 0
			var optionValue = $(this).val();
			$('[data-stakeholder]').each(function(){
				if($(this).data('stakeholder')==optionValue) {
					stakeholderTotal += $('#page-wrapper').calx('getCell', $(this).data('cell')).getValue();
				}
			});
			dataArray.push( [$(this).html(), stakeholderTotal] );
		});
		chart.series[0].setData( dataArray );
		
	});*/
	
	function __buildStakeholderGraph(){
		
		var dataArray = [];
		$('.stakeholder > option').each(function(){
			var stakeholderTotal = 0
			var optionValue = $(this).val();
			$('[data-stakeholder]').each(function(){
				if($(this).data('stakeholder')==optionValue) {
					stakeholderTotal += $('#page-wrapper').calx('getCell', $(this).data('cell')).getValue();
				}
			});
			dataArray.push( [$(this).html(), stakeholderTotal] );
		});
		
		// Radialize the colors
		Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function (color) {
			return {
				radialGradient: { cx: 0.5, cy: 0.3, r: 0.7 },
				stops: [
					[0, color],
					[1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
				]
			};
		});

		// Build the chart
		$('#stakeholder-graph').highcharts({
			chart: {
				type: 'pie',
				options3d: {
					enabled: true,
					alpha: 35,
					beta: 0
				}
			},
			title: {
				text: 'Browser market shares at a specific website, 2014'
			},
			tooltip: {
				pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					depth: 35,
					dataLabels: {
						enabled: true,
						format: '{point.name}'
					}
				}
			},
			series: [{
				type: 'pie',
				name: 'Browser share',
				data: dataArray
			}]
		});	
	}
