
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
	
	function verificationModal() {
		
		/* ========================================================================
		 * Build the Verification Modal
		 * ======================================================================== */

		$.ajax({
			
			type	: 	"GET",
			url		:	"../../php/database.manipulation.php",
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
						content		:	'<textarea class="ver-link-output" spellcheck="false" readonly="readonly">www.theroishop.com/calc-your-roi/?roi=' + getUrlVars()['roi'] + '&v=' + ver + '</textarea>'
					},
					footer		:	{
						content		:	'<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>'
					}
				};

				displayModal(modal);
			}
		});
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

		displayModal( modal );		
	}
	
	function contributorsModal() {
		
		var modal = {
			
			animation	:	'fadeIn',
			header		:	{
				icon		:	'fa-user',
				title		:	'Add Allowable User'
			},
			body		:	{
				content		:	'<div class="row">\
									<label class="control-label col-lg-5 col-md-5 col-sm-12">User\'s Email</label>\
									<div class="col-lg-7 col-md-7 col-sm-12">\
										<input id="contributor" class="form-control" type="text" />\
									</div>\
								</div>'
			},
			footer		:	{
				
				content		:	'<button type="button" class="btn btn-primary add-contributor-initialize">Add User</button>\
								<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>'
			}
		};
		
		displayModal( modal );
	}
	
	function currentContributorsModal() {
		
		$.ajax({
			
			type	: 	"GET",
			url		:	"../../php/database.manipulation.php",
			data	:	'action=getcontributors&roi='+getUrlVars()['roi'],
			success	: function(contributors){
				
				var currentContributors = $.parseJSON(contributors);
					
				// Build the body of the contributor table
				modalBody = '<table class="table table-hover contributor-names-table">\
								<tbody>';
				
				// Loop through each contributor
				$.each(currentContributors, function(index, value) {
					
					modalBody += 	'<tr data-contributor-id="' + value.auto_id + '">\
										<td class="project-title">' + value.email_address + '</td>';
					
					if($('#verificationLevel').val() > 1) {
						
						modalBody +=	'<td class="project-actions">\
											<a class="btn btn-danger btn-sm remove-contributor"><i class="fa fa-times"></i> Remove </a>\
										</td>';
					}
					
					modalBody +=	'</tr>';
				});
				
				// Close out the contributor table
				modalBody +=	'</tbody></table>';
				
				// Build the modal
				var modal = {
					
					animation	:	'fadeIn',
					header		:	{
						icon		:	'fa-user',
						title		:	'Allowable Users'
					},
					body		:	{
						content		:	modalBody
					},
					footer		:	{
						content		:	'<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>'
					}
				};
				
				displayModal(modal);
				
			}
		});
	}
	
	$('.add-new-section').on('click', function() {
		
		// Build the modal
		var modal = {
				
			size		:	'modal-lg',
			animation	:	'fadeIn',
			header		:	{
				icon		:	'fa-file-text',
				title		:	'Add a New Section'
			},
			body		:	{
				content		:	'<div class="row">\
									<label class="control-label col-lg-5 col-md-5 col-sm-12">Section Name</label>\
									<div class="col-lg-7 col-md-7 col-sm-12">\
										<input class="form-control new-section-title" />\
									</div>\
								</div>'
			},
			footer		:	{
				content		:	'<button type="button" class="btn btn-primary create-new-section">Create Section</button>\
								<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>'
			}
		};
				
		displayModal(modal);
	});
	
	$(document).ready(function() {
	
		
		$('.modal').on('click', '.reset-ver-link-confirm', function(){
			
			$.ajax({
				
				type	: 	"GET",
				url		:	"../../php/database.manipulation.php",
				data	:	'action=resetver&roi='+getUrlVars()['roi'],
				success	: function(){
					
					// Show user a notification that the verification link was changed
					noty({
						text: 'Verification Link successfully changed',
						type: 'success',
						timeout: 2000
					});

					// Trigger the view verification link button
					$('.ver-link').click();
					
				}
			});
		});
		
		$('.modal').on('click', '.ver-link-output', function(){
			$(this).select();
		});
		
		$('.modal').on('click', '.add-contributor-initialize', function(){
			
			// Get the new Contributor Name
			var newContributorName = $('#contributor').val();
			
			if(newContributorName) {
				
				$.ajax({
					
					type	: 	"GET",
					url		:	"../../php/database.manipulation.php",
					data	:	'action=addcont&cont=' + newContributorName + '&roi=' + getUrlVars()['roi'],
					success	: function(){
						
						// Empty the contributor name input
						$('#contributor').val('');
						
						// Show user a notification alerting them to contributor added
						noty({
							text: newContributorName + ' successfully added',
							type: 'success',
							timeout: 2000
						});
						
					}
				});
			}
		});
		
		$('.modal').on('click', '.remove-contributor', function() {
			
			var contributorId = $(this).closest('tr').data('contributor-id');

			$.ajax({
				type	: 	"GET",
				url		:	"../../php/database.manipulation.php",
				data	:	'action=delcont&id=' + contributorId + '&roi=' + getUrlVars()['roi'],
				success	: function(){
					$('[data-contributor-id="' + contributorId +'"]').fadeOut("slow");
				}
			});
			
		});
		
		$('label.editable').on('click', function() {
			
			var inputId = $(this).parent().find('input').attr('id');
			var discoveryItem = $(this).hasClass('discovery');	
			var action = discoveryItem ? 'getdiscoveryspecs' : 'getinputspecs';
			
			if(discoveryItem) {
				
				$.ajax({
					
					type		:	"GET",
					url			:	"../../php/cloudelement.php",
					data		:	"action=getaccountobjects",
					dataType	:	"json",
					success		:	function(objects) {

						var selectedoptions = '';
						
						$.each(objects.fields, function(index, value) {
							selectedoptions += '<option value="' + value.vendorPath + '">' + value.vendorPath + '</option>';
						});
						
					}
				});
			}
			
			$.ajax({
					
				type	: 	"GET",
				url		:	"../../php/database.manipulation.php",
				data	:	'action=' + action + '&id=' + inputId,
				success	: 	function(inputSpecs){
						
					input = $.parseJSON(inputSpecs);

					// Build the modal
					var modal = {
								
						size		:	'modal-lg',
						animation	:	'fadeIn',
						header		:	{
							title		:	input.Title
						},
						body		:	{
							content		:	'<form class="form-horizontal" data-input-id="' + inputId + '">\
												<div class="form-group">\
													<label class="control-label col-sm-5">Title</label>\
													<div class="col-sm-7">\
														<textarea class="form-control change-input-title">' + input.Title + '</textarea>\
													</div>\
												</div>\
												<div class="form-group">\
													<label class="control-label col-sm-5">Type</label>\
													<div class="col-sm-7">\
														<select class="input-editor-chosen type change-input-type">\
															<option value="0">Input</option>\
															<option value="1">Output</option>\
														</select>\
													</div>\
												</div>\
												<div class="form-group">\
													<label class="control-label col-sm-5">Format</label>\
													<div class="col-sm-7">\
														<select class="input-editor-chosen format change-input-format">\
															<option value="0">Text</option>\
															<option value="1">Currency</option>\
															<option value="2">Percent</option>\
														</select>\
													</div>\
												</div>\
												<div class="form-group">\
													<label class="control-label col-sm-5">Pop-up</label>\
													<div class="col-sm-7">\
														<textarea class="form-control change-input-tip">' + input.Tip + '</textarea>\
													</div>\
												</div>' +
												
												( discoveryItem ? 
												
												'<div class="form-group">\
													<label class="control-label col-sm-5">Salesforce Object Link</label>\
													<div class="col-sm-7">\
														<textarea class="form-control change-input-salesforce">' + input.Tip + '</textarea>\
													</div>\
												</div>' : '' ) +
												
												'</form>'
						},
						footer		:	{
							content		:	'<button type="button" class="btn btn-success change-input" data-form="' + ( discoveryItem ? 'discovery' : 'section' ) + '">Change</button>\
											<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>'
						}
					};				
						
					displayModal(modal);
					
					$('select.type').val(input.Type);
					$('select.format').val(input.Format);
					
					$('.input-editor-chosen').chosen({
						width: '100%',
						disable_search_threshold: 10
					});
			
				}
			});

		});
		
		$('.calculator-popup').on('click', function() {
			
			// Get the cell that the popup is in reference to
			var cellIdentifier =  $(this).data('cell-identifier');
			
			var currentFormula = $('#'+cellIdentifier.replace(/[A-Z]/g,'')).data('formula');
			var originalFormula = $('#'+cellIdentifier.replace(/[A-Z]/g,'')).data('original-equation');
			
			$('#page-wrapper').calx('getCell', cellIdentifier).setFormula( originalFormula );
			
			// Get all the dependencies of the calculation clicked
			var cellDependencies = $('#page-wrapper').calx('getSheet').getCell(cellIdentifier).buildDependency();
			
			// Get the label of the calculation popup that was clicked
			var calculationLabel = $('#'+cellIdentifier).closest('.form-group').find('label').html();
			
			// Get the value of the calculation popup that was clicked
			var inputValue = $('#'+cellIdentifier).closest('.form-group').find('input').val() || '0';
			
			// Get input add on if there is one
			var appendValue = $('#'+cellIdentifier.replace(/[A-Z]/g,'')).closest('.form-group').find('.input-group-addon').find('select').find(':selected').text() || $('#'+cellIdentifier.replace(/[A-Z]/g,'')).closest('.form-group').find('.input-group-addon').html() || '';

			// Empty the panel script in order to build it
			var panel = $('#page-wrapper').calx('getSheet').getCell(cellIdentifier).buildInputPanels();

			var cellFormula = $('#page-wrapper').calx('getSheet').getCell(cellIdentifier).getFormula();
			
			var cellFormat = $('#page-wrapper').calx('getSheet').getCell(cellIdentifier).getFormat();
			
			for(a in $('#page-wrapper').calx('getSheet').getCell(cellIdentifier).dependencies) {
				var inputReference = a.replace(/[A-Z]/g,'');
				var inputCellType = a.replace(inputReference,'');
				var inputLabel = $('#'+a).closest('.form-group').find('label').html() + ( inputCellType == 'U' ? ' (unit factor)' : '' );
				var re = new RegExp(a, 'g');
				cellFormula = cellFormula.replace(re,inputLabel);
			}
			
			$('#page-wrapper').calx('getCell', cellIdentifier).setFormula( currentFormula );
		
			// Build the modal
			var modal = {
						
				size		:	'modal-lg',
				animation	:	'fadeIn',
				header		:	{
					title		:	calculationLabel
				},
				body		:	{
					content		:	'<div class="form-group" style="margin-bottom: 0;"><h4>Inputs Used</h4></div>\
									<div class="form-group"><div class="panel panel-default">' + panel + '</div></div>\
									<div class="form-group" style="margin-bottom: 0;"><h4>Equation</h4></div>\
									<div class="form-group">\
										<label class="control-label col-lg-12 col-md-12 col-sm12">' + cellFormula + ' </label>\
									</div>\
									<div class="form-group" style="margin-bottom: 0; padding-top: 10px;"><h4>Value</h4></div>\
									<div class="form-group">\
										<label class="control-label col-lg-12 col-md-12 col-sm12 pull-right input-value">' + inputValue + ' ' + appendValue + ' </label>\
									</div>\
									<hr>\
									<div class="form-group">\
										<label class="control-label col-lg-5 col-md-5 col-sm-12">Change value to:</label>\
										<div class="col-lg-7 col-md-7 col-sm-12">\
											<input id="overridden-value" class="form-control overridden-value" data-format="' + cellFormat + '"/>\
										</div>\
									</div>'
				},
				footer		:	{
					content		:	'<button type="button" class="btn btn-primary original-equation" data-output="' + cellIdentifier + '">Reset to Original Equation</button>\
									<button type="button" class="btn btn-success override-output-confirm" data-output="' + cellIdentifier + '">Change Value</button>\
									<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>'
				}
			};
				
			displayModal(modal);
			
			$('.overridden-value').on('blur', function() {

				$(this).val( numeral( $(this).val() ).format( $(this).data('format') ) );				
			});
			

		});
		
		$('.modal').on('click', '.override-output-confirm', function() {
			
			var entryId = $(this).data('output');
			var output = $( '#' + $(this).data('output') );
			var cellIdentifier = output.data('cell');
			var overriddenValue = numeral().unformat( $('.overridden-value').val() );
			
			$('#page-wrapper').calx('getCell', cellIdentifier).setFormula( overriddenValue + ' * 1' );
			$('#page-wrapper').calx('getSheet').calculate();

			$.ajax({
				type	: 	"POST",
				url		:	"../../php/database.manipulation.php",
				data	:	'action=overrideoutput&roi='+getUrlVars()['roi']+'&entry='+entryId+'&value='+overriddenValue,
				success	: function(){
					
					output.css('color', 'rgb(165,42,42)');
					
					// Show user a notification alerting them to contributor added
					noty({
						text: 'Output value changed',
						type: 'success',
						timeout: 2000
					});
				}
			});			
		});
		
		$('.modal').on('click', '.original-equation', function() {
			
			var entryId = $(this).data('output');
			var output = $( '#' + $(this).data('output') );
			var originalEquation = output.data('original-equation');
			var cellIdentifier = output.data('cell');
			
			$('#page-wrapper').calx('getCell', cellIdentifier).setFormula( originalEquation );
			$('#page-wrapper').calx('getSheet').calculate();

			$.ajax({
				type	: 	"POST",
				url		:	"../../php/database.manipulation.php",
				data	:	'action=deleteoutputvalue&roi='+getUrlVars()['roi']+'&entry='+entryId,
				success	: function(){
					
					output.css('color', 'rgb(103,106,108)');
					
					// Show user a notification alerting them to contributor added
					noty({
						text: 'Output reset to original equation',
						type: 'success',
						timeout: 2000
					});
				}
			});
		});
		
		$('.modal').on('click', '.change-input', function() {
			
			var title = $('.change-input-title').val();
			var type = $('.change-input-type').val();
			var format = $('.change-input-format').val();
			var tip = $('.change-input-tip').val();
			var id = $(this).closest('.modal-content').find('form').data('input-id');
			var action = $(this).data('form');
			
			$.ajax({
				type	: 	"POST",
				url		:	"../../php/database.manipulation.php",
				data	:	'action=change' + action + 'input&id=' + id + '&title=' + title + '&format=' + format + '&tip=' + tip + '&type=' + type,
				success	: function(){
					
					// Show user a notification alerting them to contributor added
					noty({
						text: 'Input succesfully changed',
						type: 'success',
						timeout: 2000
					});
				}
			});			
			
		});
		
		$('.modal').on('click', '.create-new-section', function() {
			
			var title = $('.new-section-title').val();
			
			$.ajax({
				type	: 	"POST",
				url		:	"../../php/database.manipulation.php",
				data	:	'action=addsection&title=' + title + '&comp=' + getUrlVars()['comp'],
				success	: function(lastId){
					
					$('.nav-sections').find(' > li:nth-last-child(1)')
						.before('<li><a href="#section' + lastId + '" class="section-navigator" data-section-type="section">' + title + '</a></li>');
						
					$('.nav-sections').find(' > li:nth-last-child(1)').hide().fadeIn('slow');
						
					$('.sortable-list').find(' > div:nth-last-child(1)')
						.before('<div class="col-lg-3">\
									<div class="widget white-bg">\
										<div class="p-m row">\
											<div class="row">\
												<h2 class="col-lg-10 section-title" data-section-id="' + lastId + '">' + title + '</h2>\
												<div class="col-lg-2 ibox-tools no-padding" style="margin-top: 10px;">\
													<a class="dropdown-toggle" href="#" data-toggle="dropdown">\
														<i class="fa fa-wrench"></i>\
													</a>\
													<ul class="dropdown-menu dropdown-user">\
														<li>\
															<a class="change-section-title" data-section-id="' + lastId + '">Change Section Title</a>\
														</li>\
													</ul>\
													<a class="close-link section-inactive" data-section-id="' + lastId + '">\
														<i class="fa fa-times"></i>\
													</a>\
												</div>\
											</div>\
										</div>\
									</div>\
								</div>');
								
					$('.sortable-list').find(' > div:nth-last-child(1)').hide().fadeIn('slow');
					
					// Show user a notification alerting them to contributor added
					noty({
						text: 'Section successfully added',
						type: 'success',
						timeout: 2000
					});

					$('.modal').modal('hide');
				}
			});			
			
		});
		
	});