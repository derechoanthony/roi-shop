
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
		var $modal = $('#roishop-modal');		
		
		// Add the html to the modal then show the modal
		$modal
			.html(modal)
			.modal('show');
	}
	
	function resetVerificationModal() {
		
		/* ========================================================================
		 * Reset Verification Link Modal
		 * ======================================================================== */
				
		var modal = {
					
			animation	:	'fadeIn',
			header		:	{
				icon		:	'fa-shield',
				title		:	'Reset Verification Link?'
			},
			body		:	{
				content		:	'<p>Would you like to reset the verification link for this ROI? Once the link is reset it <b>cannot</b> be undone and no prospects will be able to view the ROI without the new link.</p>'
			},
			footer		:	{
				content		:	'<button type="button" class="btn btn-primary reset-ver-link-confirm" data-dismiss="modal">Reset</button>\
								<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>'
			}
		};

		displayModal(modal);		
	}
	
	function verificationModal() {

		// Ajax call to return the build of the current ROI
		var current_roi	= getQueryVariable('roi'),
			action 		= 'getverification',
			ajax_url 	= '/php/ajax/enterprise/calculator.get.php';
	
		$.get( ajax_url, { action: action, roi: current_roi } )
			.done(function(verification){

				var modal = {
					
					animation: 'fadeIn',
					header: {
						icon: 'fa-shield',
						title: 'ROI Verification Link'
					},
					body: {
						content: '<textarea class="ver-link-output" spellcheck="false" readonly="readonly">www.theroishop.com/enterprise/2/?roi=' + current_roi + '&v=' + verification + '</textarea>'
					},
					footer: {
						content: '<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>'
					}
				};
				
				displayModal(modal);							
			});
	}