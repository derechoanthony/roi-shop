
	function displayModal( modalShell ) {
		
		/* ========================================================================
		 * Build a Modal and display it
		 * ======================================================================== */
		
		var modalHeader = '<div class="modal-header">';
		
		// Set up the modal dismiss, if a custom dismiss header was passed to the function then
		// use that script, otherwise use the standard script.
		if( modalShell.header.close ) {
			modalHeader += modalShell.header.close;
		} else {
			modalHeader += 	'<button type="button" class="close" data-dismiss="modal">\
								<span aria-hidden="true">&times;</span>\
								<span class="sr-only">Close</span>\
							</button>';			
		}

		// If a modal icon was passed to the function then added it to the header here.
		if( modalShell.header.icon ) {
			modalHeader += '<i class="fa ' + modalShell.header.icon + ' modal-icon"></i>';
		}
		
		// Add the modal title to the header.
		modalHeader += '<h4 class="modal-title">' + modalShell.header.title + '</h4>';
		
		// Add a subtitle below the modal title if one was passed to the function.
		if(modalShell.header.subtitle) {
			modalHeader += '<small class="font-bold">' + modalShell.header.subtitle + '</small>';
		}
		
		// Complete the modal header.
		modalHeader +=	'</div>'
		
		// Build the modal body
		var modalBody =	'<div class="modal-body">';
		
		// Add a modal body if one was passed to the function.
		if(modalShell.body.content) {
			modalBody +=	modalShell.body.content;
		}
		
		modalBody +=	'</div>';
		
		// Build the modal footer
		if(modalShell.footer) {
			var modalFooter =	'<div class="modal-footer">';
			
			// Add a modal footer if one was passed to the function.
			if(modalShell.footer.content) {
				
				modalFooter +=	modalShell.footer.content;
			}
			
			modalFooter +=	'</div>';
		} else { var modalFooter = ''; }
		
		// Assemble the final modal then show it.
		modal = 	'<div class="modal-dialog ' + (modalShell.size ? modalShell.size : '') + '">\
						<div class="modal-content animated ' + (modalShell.animation ? modalShell.animation : 'slideInDown') + '">';
					
		modal +=	modalHeader + modalBody + modalFooter + '</div></div>';
				
		// Get the modal shell to add content to.
		var $modal = $('#modal-shell');		
		
		// Add the html to the modal then show the modal
		$modal
			.html(modal)
			.modal('show');
	}
	
	function CreateModalFormInput( $id, $label, $label_width = 4 ) {
		
		return '<div class="form-group">\
					<label class="col-sm-' + $label_width + ' control-label">' + $label + '</label>\
					<div class="col-sm-' + ( 12 - $label_width ) + '">\
						<input id="' + $id + '" type="text" class="form-control">\
					</div>\
				</div>';
	};
	
	
	function verificationModal() {
		
		/* ========================================================================
		 * Build the Verification Modal
		 * ======================================================================== */

		$.ajax({
			
			type	: 	"GET",
			url		:	"ajax/calculator.get.php",
			data	:	'action=getverification&roi='+getUrlVars()['roi'],
			success	: function(ver){
				
				// Build the modal. The verification modal will be created, this is where the user will have access
				// to their ROIs verification link that can be sent to guests for access to the ROI.
				
				var modal = {
					
					animation	:	'fadeIn',
					header		:	{
						icon		:	'fa-shield',
						title		:	'ROI Verification Link',
						subtitle	:	'The following link can be used to give anyone access to this ROI.'
					},
					body		:	{
						content		:	'<textarea class="ver-link-output" spellcheck="false" readonly="readonly">www.theroishop.com/calc-your-roi/3/?roi=' + getUrlVars()['roi'] + '&v=' + ver + '</textarea>'
					},
					footer		:	{
						content		:	'<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>'
					}
				};

				displayModal(modal);
			}
		});
	}
	
	$('.modal').on('click', '.ver-link-output', function(){
		$(this).select();
	});
		
	
	/*******************************************************************************************************************************/
	/************************************************** MODALS FOR ROI OPERATION ***************************************************/
	/*******************************************************************************************************************************/
	
	$( '.create-input' ).on( 'click', function() {
		
		var $input_holder = $(this).closest('[data-content-type]');
		
		$input_holder.after( '<div class="new-input-holder"></div>');
		
		// Build modal content.
		
		var $modal_body = '<div class="form-group">';
		
		var $input = [];

			$input.push( CreateModalFormInput( 'new-input-label', 'Input Label' ) );
			$input.push( CreateModalFormInput( 'new-input-format', 'Format' ) );
			$input.push( CreateModalFormInput( 'new-input-popup', 'Popup' ) );
			$input.push( CreateModalFormInput( 'new-input-append', 'Appended Value' ) );
			$input.push( CreateModalFormInput( 'new-input-prepend', 'Prepended Value' ) );
		
		var $modal_body;
		
		$.each($input, function( index, value ){
			
			$modal_body += value;
		});

		$modal_body += '</div>';
		
		// Build the modal
		var modal = {
						
			size		:	'modal-lg',
			animation	:	'fadeIn',
			header		:	{
				title		:	'Create New Input'
			},
			body		:	{
				content		:	$modal_body
			},
			footer		:	{
				content		:	'<button type="button" class="btn btn-primary add-new-input">Add New Input</button>\
								<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>'
			}
		};

		displayModal(modal);		
		
	});
	
	$('.modal').on('click', '.add-new-input', function() {
			
		var $inputs = $('[data-content-type]').length;
		var $input_label = $('#new-input-label').val();
		var $input_format = $('#new-input-format').val();
		var $input_popup = $('#new-input-popup').val();
		var $input_append = $('#new-input-append').val();
		var $input_prepend = $('#new-input-prepend').val();
		
		$new_input_html = 	'<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" data-content-type="input">\
								<div class="form-horizontal">\
									<div class="form-group">\
										<label class="control-label col-lg-8 col-md-12 col-sm-12 col-xs-12">';
										
		$new_input_html +=	$input_label;
		
		$new_input_html +=	'</label>';
		
		$new_input_html +=	'<div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">';
		
		if( $input_popup || $input_append || $input_prepend ) {
			
			$new_input_html +=	'<div class="input-group">';
		}
		
		$new_input_html +=	'<input class="form-control' + ( $input_append ? ' input-addon' : '' ) + '" type="text" name="IN' + ( $inputs + 1 ) + '" data-format="' + $input_format + '" data-cell="IN' + ( $inputs + 1 ) + '">';
		
		if( $input_popup ) {
			
			$new_input_html +=	'<span class="input-group-addon right helper">\
									<i class="fa fa-question-circle tooltipstered" data-toggle="tooltip" data-placement="right" title="' + $input_popup + '"></i>\
								</span>';
		}
		
		if( $input_append ) {
			
			$new_input_html +=	'<span class="input-group-addon right append">' + $input_append + '</span>';
		}
								
		if( $input_popup || $input_append || $input_prepend ) {
			
			$new_input_html +=	'</div>';
		}
		
		$new_input_html +=	'</div></div></div></div>';

		$('.new-input-holder').replaceWith( $new_input_html );
		
		$('#wrapper').calx('update');
		
	});