$(document).ready(function () {

	$('.roi-folder-list').on('click', '.open-roi', function(){
			
		var roi_link = $(this).data('link');
		var roi_open = $(this).closest('.roi-element').data('roi-id');
			
		sessionStorage.setItem( 'currentroi', roi_open );

		window.location.href = "../" + roi_link + "?roi=" + roi_open;
	});
	
	$('.create-roi').on( 'click', function(e) {

		var version_id = $('#roi-type option:selected').val();
		var roi_name = $('.new-roi-name').val();

		$.ajax({
			type: 'POST',
			url: 'ajax/dashboard.post.php',
			data: {
				action: 'CreateNewROI',
				roi_name: roi_name,
				version: version_id
			},
			success: function(response) {
				
				created_roi = $.parseJSON(response);
				sessionStorage.setItem('currentroi', created_roi.roi)
				window.location.href = "../" + created_roi.path + "?roi=" + created_roi.roi;
			}
		});
		
	});
	
	$('.row').on('click', '.close-folder', function(e){
			
		e.preventDefault();
			
		var totalFoldersOpen = parseInt( $('div[data-folder-id]:visible').length ) - 1;
			
		$(this).closest('.roi-folder-list').hide();
		$('div[data-folder-id]').each(function(){
				
			$(this).removeClass('col-lg-12').removeClass('col-lg-6');
			$(this).addClass( totalFoldersOpen == 1 ? 'col-lg-12' : 'col-lg-6' );
		});
			
		var visibleFolders = [];
			
		$('div[data-folder-id]:visible').each(function(){
				
			visibleFolders.push( $(this).data('folder-id') );
		});
			
		$.ajax({
			type: "POST",
			url: "ajax/dashboard.post.php",
			data: {
				action: 'visiblefolders',
				folders: JSON.stringify( visibleFolders )
			}
		});
					
	});
	
	$('.file-manager').on('click', 'li[data-folder-id] a', function(e) {
			
		e.preventDefault();
			
		var newFolder = $(this).parent().data('folder-id');
		var totalFoldersOpen = parseInt( $('div[data-folder-id]:visible').length ) + 1;
			
		if( $('div[data-folder-id="' + newFolder + '"]:visible').length == 0 ) {
				
			$('div[data-folder-id]').each(function(){
					
				$(this).removeClass('col-lg-12').removeClass('col-lg-6');
				$(this).addClass( totalFoldersOpen == 1 ? 'col-lg-12' : 'col-lg-6' );
					
				if($(this).data('folder-id') == newFolder){
					$(this).show();
				}
			});
		}
			
		var visibleFolders = [];
			
		$('div[data-folder-id]:visible').each(function(){
				
			visibleFolders.push( $(this).data('folder-id') );
		});
			
		$.ajax({
			type: "POST",
			url: "ajax/dashboard.post.php",
			data: {
				action: 'visiblefolders',
				folders: JSON.stringify( visibleFolders )
			}
		});
		
	});
	
	$(window).unload(function() {
	
		$.ajax({
			type:  "POST",
			async: false,
			url: "ajax/dashboard.post.php",
			data: {
				action: 'logoutUser'
			}
		});				
	});
	
	$('.wrapper').on('click', '.transfer-roi', function() {
			
		var roiId = $(this).closest('.roi-element').data('roi-id');
		var roiTitle = $(this).closest('.roi-element').find('.roi-name').html();			
			
		$.ajax({

			type: 'GET',
			url: "ajax/dashboard.get.php",
			data: {
				action: 'companyusers'
			},
			success: function(users) {

				var companyUsers = $.parseJSON(users);
				var userSelect = '<select class="new-user form-control">';

				$.each(companyUsers, function(index, value) {
					userSelect += '<option value="' + value.user_id + '">' + value.username + '</option>';
				});
				
				userSelect += '</select>';
				
				// Build the modal
				var modal = {

					animation	:	'fadeIn',
					header		:	{
						title		:	'Transfer ' + roiTitle
					},
					body		:	{
						content		:	'<form class="form-horizontal">\
											<div class="form-group">\
												<label class="control-label col-lg-5 col-md-5 col-sm-12">Transfer the ROI to: </label>\
												<div class="col-lg-7 col-md-7 col-sm-12">'
													+ userSelect +
												'</div>\
											</div>\
										</form>'
					},
					footer		:	{
						content		:	'<button type="button" data-roi-id="' + roiId + '" class="btn btn-primary transfer-roi-to">Transfer</button>\
										<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>'
					}
				};
										
				displayModal(modal);
				
				$('.new-user').chosen({
					width: '100%',
					disable_search_threshold: 10
				});
				
			}
		});

	});
	
	$('.modal').on('click', '.transfer-roi-to', function(){
						
		var roiId = $(this).data('roi-id');
		var newUser = $('.new-user').val();
			
		$.ajax({
			type:  "POST",
			url: "ajax/dashboard.post.php",
			data: {
				action: 'transferroi',
				roi: roiId,
				user: newUser
			},
			success	:	function() {
				$('[data-roi-id="' + roiId + '"]').fadeOut(1000);
				$('.modal').modal('hide');
			}
		});
			
	});
	
	$('.modal').on('click', '.confirm-delete-roi', function(){
						
		var roiId = $(this).data('roi-id');
		var newUser = $('.new-user').val();
			
		$.ajax({
			type: "POST",
			url: "ajax/dashboard.post.php",
			data: {
				action: 'deleteroi',
				roi: roiId
			},
			success: function() {
				$('[data-roi-id="' + roiId + '"]').fadeOut(1000);
				$('.modal').modal('hide');
			}
		});
			
	});
	
	$('.modal').on('click', '.add-new-folder', function(){
						
		var foldername = $('.new-folder-name').val();
					
		$.ajax({

			type: 'POST',
			url: "ajax/dashboard.post.php",
			data: {
				action: 'addnewfolder',
				foldername: foldername
			},
			success: function(lastfolderid) {
				
				$('.folder-list').append('<li data-folder-id="' + lastfolderid + '"><a href=""><i class="fa fa-folder"></i> ' + foldername + ' (<span class="category-total-rois">0</span>)</a></li>');
				$('.folder-list > li:last-child').hide().fadeIn(1000);
						
				location.reload();
			}
		});	
	});
	
	$('.modal').on('click', '.change-roi-name', function(){
							
		var roiId = $(this).data('roi-id');
		var roiName = $('.changed-roi-name').val();
			
		$.ajax({
			type: 'POST',
			url: "ajax/dashboard.post.php",
			data: {
				action: 'renameroi',
				roi: roiId,
				name: roiName
			},
			success	:	function() {
				$('[data-roi-id="' + roiId + '"]').find('.roi-name').html(roiName);
				$('.modal').modal('hide');
			}
		});
	});

});

