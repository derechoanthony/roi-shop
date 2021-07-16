;(function( $ ){
	
	'use strict';
	$.fn.roishopModal = function( options ) {
		
		var settings = {};
		if ( options ) {
			$.extend( settings, options );
		};
		
		// Empty the modal
		$('.roishop-modal').empty();
		
		// If a title was provided add the title to the modal
		if(options.title) {
			$('.roishop-modal').append('<h1>' + options.title + '</h1><hr style="border: 0.8px solid #ccc">');		
		};
		
		$.each(options.elements, function(index, element){
			
			var $div = $('<div/>');
			$div.roishop(element);
			
			$('.roishop-modal').append($div);
		});
		
		var $buttons = $('<p/>'),
			$this = $(this),
			$edit,
			$cancel;
			
		$('.roishop-modal').append('<hr style="border: 0.8px solid #ccc">');
		

		var $add = $('<button/>').roishop({
					el_type: 'button',
					el_class: 'btn btn-small btn-success',
					el_text: 'Add',
					el_action: 'addTableRow()',
					called_by: $(this)
				});
				
		var $edit = $('<button/>').roishop({
					el_type: 'button',
					el_class: 'btn btn-small btn-primary',
					el_text: 'Update',
					el_action: 'updateTableRow()',
					called_by: $(this)
				});
				
		var $cancel = $('<button/>').roishop({
					el_type: 'button',
					el_class: 'btn btn-small btn-danger',
					el_text: 'Cancel',
					el_action: 'close-modal'
				});
				
		if(options.action == 'update'){
			$buttons.append($edit);
		};
		
		if(options.action == 'add'){
			$buttons.append($add);
		};
		
		$buttons.append($cancel);
		
		$('.roishop-modal').append( $buttons );
		
		$.magnificPopup.open({
			items: {
				src: '.mfp-hide'
			},
			type: 'inline'			
		});
	};
	
})( window.jQuery || window.Zepto );


