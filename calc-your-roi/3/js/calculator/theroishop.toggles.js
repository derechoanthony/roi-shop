$(document).ready(function(){
	
	/***************************************************/
	/************* JQUERY TOGGLE FUNCTIONS *************/
	/***************************************************/
	
	// Style Toggle Buttons when Document is Loaded
	styleToggleButtons();
	
	$('.btn-toggle').on('click', function(){

		var toggleReference = $(this).data('cell-reference'),
			toggleInput = $('input[data-cell="' + toggleReference + '"]'),
			toggleOnValue = $(this).data('on-value'),
			toggleOffValue = $(this).data('off-value'),
			currentValue = toggleInput.val(),
			inputCell = toggleInput.data('cell');
			
		if(currentValue == toggleOnValue){
			
			$('#wrapper').calx('getSheet').getCell(inputCell).setValue(toggleOffValue).calculate();
		} else {
			$('#wrapper').calx('getSheet').getCell(inputCell).setValue(toggleOnValue).calculate();
		}
		
		styleToggleButtons();
		
	});
	
});

function styleToggleButtons() {
	
	$('.btn-toggle').each(function(){

		var toggleReference = $(this).data('cell-reference'),
			toggleInput = $('input[data-cell="' + toggleReference + '"]'),
			toggleOnValue = $(this).data('on-value'),
			toggleOffValue = $(this).data('off-value'),
			toggleOnText = $(this).data('on-text'),
			toggleOffText = $(this).data('off-text'),
			toggleOnClass = $(this).data('on-class'),
			toggleOffClass = $(this).data('off-class');
				
		var currentValue = toggleInput.val(),
			inputCell = toggleInput.data('cell');

		if(inputCell){
			
			if(currentValue == toggleOnValue) {
						
				$('#wrapper').calx('getSheet').getCell(inputCell).setValue(toggleOnValue).calculate();
				$('button[data-cell-reference="' + toggleReference + '"').each(function(){

					$(this).html(toggleOnText);
					$(this).removeClass(toggleOffClass).addClass(toggleOnClass);
				});	
			} else {
						
				$('#wrapper').calx('getSheet').getCell(inputCell).setValue(toggleOffValue).calculate();
				$('button[data-cell-reference="' + toggleReference + '"').each(function(){

					$(this).html(toggleOffText);
					$(this).removeClass(toggleOnClass).addClass(toggleOffClass);
				});						
			}			
		}

	});	
}