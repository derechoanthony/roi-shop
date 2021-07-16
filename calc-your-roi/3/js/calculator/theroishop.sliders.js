$(document).ready(function(){
	
	/***************************************************/
	/************* JQUERY SLIDER FUNCTIONS *************/
	/***************************************************/
	
	// Initialize each slider
	$('.slider').each(function(){
	
		var sliderMin = $(this).data('min'),
			sliderMax = $(this).data('max'),
			sliderStep = $(this).data('step'),
			sliderStart = $(this).data('start') || 0;
				
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
	
	// Link the slider to its corresponding data cell so it can be used within calculations
	$(".slider").Link('lower').to(function(value) {
					
		//Get slider name
		var sliderCell = $(this).closest('.form-group').find('.slider-input').attr('data-cell');

		//Set slider Input to equal the slider
		$('#wrapper').calx('getSheet').getCell(sliderCell).setValue(value).calculate();
		$('#wrapper').calx('getSheet').calculate();
	});
	
	$('.slider').on('change', function(){
	
		// Get ID of the current slider that was changed
		var me = $(this);
		var sliderId = me.closest('.form-group').find('.slider-input').attr('data-cell');
			
		// Get current slider value
		var myvalue = me.val();
		var reference = me.attr('data-cell-reference');

		// Loop through each cell that has the same data cell
		$('[data-cell-reference="' + reference + '"').not(me).each(function(){

			$(this).val(myvalue);
			var datacell = $(this).attr('data-cell');
			if(datacell){
				$('#wrapper').calx('getSheet').getCell(datacell).setValue(myvalue).calculate();
			}
		});
	});
	
	$('.slider-input').on('change', function(e){
	
		// If the input changed has a slider associated with it, the slider also needs to
		// change.
		$(this).closest('.row').find('.slider').val( $(this).val() );
	});	
	
});