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
		
		Highcharts.setOptions({
			lang: {
				decimalPoint: '.',
				thousandsSep: ','
			}
		});


		$( settings.equalizeHeights ).equalizeHeights();

		cleanCalxCells( $('[data-cell') );
		
		$('input').on('change', function(){
			var input_cell_id = $(this).data('cell');
			$('#wrapper').calx('getSheet').calculate();
			//$('#wrapper').calx('getCell', input_cell_id).calculateAllDependents();
		});
		
		$( settings.calxSelector ).calx('getSheet').calculate();
		
		$('.input-addon').focus(function(){
			
			// Add border styling to add on portion of input when input is focused.
			$(this).parent().find('.helper').toggleClass('input-addon-border');
		}).blur(function(){
			
			// Remove border styling from add on potion of input when input loses fous.
			$(this).parent().find('.helper').toggleClass('input-addon-border');
		});
		
		$('.change-currency').on('click', function() {
			
			$('#change-currency').modal('show');
			$('.current-language').chosen();			
		});
		
		$('.update-currency').on('click', function(){
			$.post("/php/ajax/enterprise/enterprise.post.php",{
				action: 'changecurrency',
				roi: getQueryVariable('roi'),
				currency: $('.current-language').val()
			}, function(callback){});
			
			$('#change-currency').modal('hide');
			
			numeral.language($('.current-language').val() || 'usd');
			
			$('#wrapper').calx({
				
				// Define when the calculator will actually perform the calculations.
				autoCalculate			:	false,
				onAfterCalculate		:	function() {
					updateCharts();
				}
			});
			
			$('#wrapper').calx('getSheet').calculate();
		});
	
		$(window).resize();
		
		$('[href="#pdf"]').on('click', function(e){
			var grandtotal = $('#wrapper').calx('evaluate', '( ( A5 + A10 + A11 * A16 + A17 * A22 + A26 ) + ( A35 ) + A42 + ( A44 * A45 + A56 ) ) * 2' );
				grandtotal = numeral(grandtotal).format('$0,0');
				
			var roitotal = $('#wrapper').calx('evaluate', '( ( ( A5 + A10 + A11 * A16 + A17 * A22 + A26 ) + ( A35 ) + A42 + ( A44 * A45 + A56 ) ) * 2 ) / ( A46 +  A47 + A48 )');
				roitotal = numeral(roitotal).format('0,0%');
				
			var npv = $('#wrapper').calx('evaluate', '( NPV( 0.02, ( ( A5 + A10 + A11 * A16 + A17 * A22 + A26 ) + ( A35 ) + A42 + ( A44 * A45 + A56 ) ) - ( A46 + A47 ), ( ( A5 + A10 + A11 * A16 + A17 * A22 + A26 ) + ( A35 ) + A42 + ( A44 * A45 + A56 ) ) - A48 ) )');
				npv = numeral(npv).format('$0,0');
				
			var payback = $('#wrapper').calx('evaluate', '( A46 + A47 ) / ( ( A5 + A10 + A11 * A16 + A17 * A22 + A26 ) + ( A35 ) + A42 + ( A44 * A45 + A56 ) ) * 12' );
				payback = numeral(payback).format('0,0[.]00');
				
			var s1 = numeral($('#wrapper').calx('evaluate', '( A5 + A10 + A11 * A16 + A17 * A22 + A26 )' )).format('$0,0');
			var s2 = numeral($('#wrapper').calx('evaluate', '( A5 + A10 + A11 * A16 + A17 * A22 + A26 ) * 2' )).format('$0,0');
			var s3 = numeral($('#wrapper').calx('evaluate', 'A35' )).format('$0,0');
			var s4 = numeral($('#wrapper').calx('evaluate', 'A35 * 2' )).format('$0,0');
			var s5 = numeral($('#wrapper').calx('evaluate', 'A42' )).format('$0,0');
			var s6 = numeral($('#wrapper').calx('evaluate', 'A42 * 2' )).format('$0,0');
			var s7 = numeral($('#wrapper').calx('evaluate', '( A44 * A45 + A56 )' )).format('$0,0');
			var s8 = numeral($('#wrapper').calx('evaluate', '( A44 * A45 + A56 ) * 2' )).format('$0,0');
			var s9 = numeral($('#wrapper').calx('evaluate', '( A5 + A10 + A11 * A16 + A17 * A22 + A26 ) + ( A35 ) + A42 + ( A44 * A45 + A56 )' )).format('$0,0');
			var s10 = numeral($('#wrapper').calx('evaluate', '( A5 + A10 + A11 * A16 + A17 * A22 + A26 ) * 2 + ( A35 ) + A42 + ( A44 * A45 + A56 ) * 2' )).format('$0,0');
			var s11 = numeral($('#wrapper').calx('evaluate', '( 0 - A46 - A47 )' )).format('$(0,0)');
			var s12 = numeral($('#wrapper').calx('evaluate', '( A5 + A10 + A11 * A16 + A17 * A22 + A26 ) * 2' )).format('$(0,0)');
			var s13 = numeral($('#wrapper').calx('evaluate', '( A5 + A10 + A11 * A16 + A17 * A22 + A26 ) + ( A35 ) + A42 + ( A44 * A45 + A56 ) + ( 0 - A46 - A47 )' )).format('$0,0');
			var s14 = numeral($('#wrapper').calx('evaluate', '( A5 + A10 + A11 * A16 + A17 * A22 + A26 ) + ( A35 ) + A42 + ( A44 * A45 + A56 ) + ( 0 - A48 )' )).format('$0,0');
			var s15 = numeral($('#wrapper').calx('evaluate', '( ( A5 + A10 + A11 * A16 + A17 * A22 + A26 ) + ( A35 ) + A42 + ( A44 * A45 + A56 ) ) * 2 + ( 0 - A46 - A47 - A48 )' )).format('$0,0');
			
			var s16 = numeral($('#wrapper').calx('evaluate', 'A5 + A10 + A11 * A16 + A17 * A22 + A26' )).format('$0,0');
			var s17 = numeral($('#wrapper').calx('evaluate', 'A5' )).format('$0,0');
			var s18 = numeral($('#wrapper').calx('evaluate', 'A10' )).format('$0,0');
			var s19 = numeral($('#wrapper').calx('evaluate', 'A16' )).format('$0,0');
			var s20 = numeral($('#wrapper').calx('evaluate', 'A22' )).format('$0,0');
			var s21 = numeral($('#wrapper').calx('evaluate', 'A34 * A29 * A31' )).format('$0,0');
			var s22 = numeral($('#wrapper').calx('evaluate', 'A28' )).format('0,0');
			var s23 = numeral($('#wrapper').calx('evaluate', 'A33' )).format('0,0');
			var s24 = numeral($('#wrapper').calx('evaluate', 'A34' )).format('0,0');
			var s25 = numeral($('#wrapper').calx('evaluate', 'A35' )).format('$0,0');
			var s26 = numeral($('#wrapper').calx('evaluate', 'A42' )).format('$0,0');
			var s27 = numeral($('#wrapper').calx('evaluate', 'A40' )).format('0,0');
			var s28 = numeral($('#wrapper').calx('evaluate', 'A41' )).format('0,0');
			var s29 = numeral($('#wrapper').calx('evaluate', 'A42' )).format('$0,0');
			var s30 = numeral($('#wrapper').calx('evaluate', '( A44 * A45 + A56 )' )).format('$0,0');
			var s31 = $('select[data-cell="A44"] option:selected').text();
			var s32 = numeral($('#wrapper').calx('evaluate', 'A45' )).format('$0,0');
			var s33 = numeral($('#wrapper').calx('evaluate', 'A53' )).format('0,0');
			var s34 = numeral($('#wrapper').calx('evaluate', 'A54' )).format('$0,0');
			var s35 = numeral($('#wrapper').calx('evaluate', 'A55' )).format('$0,0');
			var s36 = numeral($('#wrapper').calx('evaluate', 'A52' )).format('0,0%');
			var s37 = numeral($('#wrapper').calx('evaluate', 'A56' )).format('$0,0');
			
			var s38 = numeral($('#wrapper').calx('evaluate', '( 0 - A48 )' )).format('$(0,0)');
			var s39 = numeral($('#wrapper').calx('evaluate', '( 0 - A46 - A47 - A48 )' )).format('$(0,0)');
				
			window.location.href = 'pdf?companyname=' + $('.navbar-header').find('h3').html() + '&roi=' + getQueryVariable('roi') + '&grandtotal=' + grandtotal + '&totalroi=' + roitotal + '&npv=' + npv + '&payback=' + payback + '&s1=' + s1 + '&s2=' + s2 + '&s3=' + s3 + '&s4=' + s4 + '&s5=' + s5 + '&s6=' + s6 + '&s7=' + s7 + '&s8=' + s8 + '&s9=' + s9 + '&s10=' + s10 + '&s11=' + s11 + '&s12=' + s12 + '&s13=' + s13 + '&s14=' + s14 + '&s15=' + s15 + '&s16=' + s16 + '&s17=' + s17 + '&s18=' + s18 + '&s19=' + s19 + '&s20=' + s20 + '&s21=' + s21 + '&s22=' + s22 + '&s23=' + s23 + '&s24=' + s24 + '&s25=' + s25 + '&s26=' + s26 + '&s27=' + s27 + '&s28=' + s28 + '&s29=' + s29 + '&s30=' + s30 + '&s31=' + s31 + '&s32=' + s32 + '&s33=' + s33 + '&s34=' + s34 + '&s35=' + s35 + '&s36=' + s36 + '&s37=' + s37 + '&s38=' + s38 + '&s39=' + s39;
			e.stopPropagation();
			return false;
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
	
})( window.jQuery || window.Zepto );

