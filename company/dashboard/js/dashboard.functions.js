$(document).ready(function() {
	
	$('.new-roi-template').on('click', function() {
		
		var modal = {
					
			animation:	'fadeIn',
			header:	{
				icon: 'fa-shield',
				title: 'Add New Template',
				subtitle: 'Enter a title for the new template.'
			},
			body: {
				content: '<input class="form-control new-structure-title" />'
					},
			footer: {
				content: 	'<button type="button" class="btn btn-primary create-new-template">Create New Template</button>\
							<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>'
			}
		};

		displayModal(modal);
	});
	
	$('.reset-username').on('click', function() {

		var user_id = $(this).closest('tr').find('td [data-user-id]').data('user-id');
		var username = $(this).closest('tr').find('td a').text();
		
		var modal = {
					
			animation:	'fadeIn',
			header:	{
				icon: 'fa-shield',
				title: 'Reset User Login Credentials',
				subtitle: 'Change the username if a new user will be using this account or change the password if user needs password reset.'
			},
			body: {
				content: 	'<div class="panel-body">\
								<form>\
									<div class="form-group">\
										<label class="control-label">Username</label>\
										<input type="text" class="form-control new-username" value="' + username + '"/>\
									</div>\
									<hr/>\
									<div class="form-group">\
										<label class="control-label">Password</label>\
										<input type="password" class="form-control new-password" />\
										<span class="help-block">If password is omitted a random password will be generated.</span>\
									</div>\
								</form>\
							</div>\
							<div class="alert-holder"></div>'
					},
			footer: {
				content: 	'<button type="button" class="btn btn-success reset-username" data-user-id="' + user_id + '">Reset Username</button>\
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>'
			}
		};

		displayModal(modal);
	});
	
	$('.transfer-user-rois').on('click', function() {

		var user_id = $(this).closest('tr').find('td [data-user-id]').data('user-id');
		var username = $(this).closest('tr').find('td a').text();
		
		$.ajax({
			type: 'GET',
			data: {
				action: 'getUsers',
				companyid: getUrlVars()['companyid']
			},
			url: 'ajax/dashboard.get.php',
			dataType: 'json',
			success: function(result){
				
				var transfer_select = '<select class="transfer-rois">';

				var i;
				
				for(i=0; i<result.length; i++) {
					
					transfer_select += '<option value="' + result[i].user_id + '">' + result[i].username + '</option>';
				};
				
				transfer_select += '</select>';

				
				var modal = {
							
					animation:	'fadeIn',
					header:	{
						icon: 'fa-shield',
						title: 'Reset User Login Credentials',
						subtitle: 'Change the username if a new user will be using this account or change the password if user needs password reset.'
					},
					body: {
						content: 	'<div class="panel-body">\
										<form>\
											<div class="form-group">\
												<label class="control-label col-lg-4">Transfer ROIs to:</label>\
												<div class="col-lg-8">' + transfer_select + '</div>\
											</div>\
										</form>\
									</div>\
									<div class="alert-holder"></div>'
							},
					footer: {
						content: 	'<button type="button" class="btn btn-success execute-transfer-roi" data-user-id="' + user_id + '">Transfer All ROIs</button>\
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>'
					}
				};

				displayModal(modal);
				
				$('.transfer-rois').chosen({
					width: '100%',
					disable_search_threshold: 10
				});
			}
		});
	});
	
	$('.modal').delegate('.create-new-template', 'click', function(){
		
		var newTemplateName = $('.new-structure-title').val();

		$.ajax({
			
			type	: 	"POST",
			url		:	"ajax/dashboard.post.php",
			data	:	{
				action: 'createnewstructure',
				template: newTemplateName,
				company: getUrlVars()['companyid']
			},
			success	: function(){
				
			}
		});
	});
	
	$('.modal').delegate('.reset-username', 'click', function(){
		
		var userId = $(this).data('user-id');
		var username = $.trim($('.new-username').val());
		var password = $.trim($('.new-password').val());

		if(username) {
			$.ajax({
				
				type	: 	"POST",
				url		:	"ajax/dashboard.post.php",
				data	:	{
					action: 'resetusername',
					userid: userId,
					username: username,
					password: password
				},
				success	: function(returned_state){
					
					switch(returned_state){
						
						case 'user exists':
							$('.alert-holder').hide().html('<div class="notice red">\
														<p>User already exists. Please enter a different username.</p>\
													</div>').fadeIn();
							break;
							
						default:
							var user_table = $('[data-user-id="' + userId + '"');
							user_table.hide().html(username).fadeIn();
							user_table.closest('td').effect('highlight', {}, 3000);
							
							$('.modal').modal('toggle');
							break;
					};
				}
			});
		}

	});
	
	$('.modal').delegate('.execute-transfer-roi', 'click', function(){
		
		var userId = $(this).data('user-id');
		var transferTo = $('.transfer-rois').val();

		$.ajax({
				
			type	: 	"POST",
			url		:	"ajax/dashboard.post.php",
			data	:	{
				action: 'transferrois',
				userid: userId,
				transferto: transferTo
			},
			success	: function(returned_state){

				switch(returned_state){
						
					case 'rois transferred':
						alertify.success("ROIs successfully transferred.");
						$('.modal').modal('toggle');
						break;

				};
			}
		});

	});
	
	$('.toggle-activity').on('click', function() {
		
		var user_id = $(this).closest('tr').find('td [data-user-id]').data('user-id');
		var username = $(this).closest('tr').find('[data-username]').text();

		var current_activity = $(this).text();
		
		if(current_activity == 'Active') {
			var cancel_alert = 'Deactivate User',
				alert_text = 'Are you sure you want to deactivate ',
				status = 0;
		} else {
			var cancel_alert = 'Activate User',
				alert_text = 'Are you sure you want to activate ',
				status = 1;			
		};
		
		alertify.set({
			labels: {
				ok: 'Cancel',
				cancel: cancel_alert
			}
		});
		
		alertify.confirm(alert_text + username + "?", function (e) {
			if (e) {

			} else {
				$.ajax({
					type	: 	"POST",
					url		:	"ajax/dashboard.post.php",
					data	:	{
						action: 'statuschange',
						userid: user_id,
						status: status
					},
					success	: function(returned_state){

						switch(returned_state){
									
							case 0:
								alertify.success("User is now inactive");
								break;
								
							case 1:
								alertify.success("User is not active");
								break;
						};
					}
				});
			}
		});
	});
	
	$('body').on('click', '.delete-user', function() {
		
		var user_id = $(this).closest('tr').find('td [data-user-id]').data('user-id');
		var username = $(this).closest('tr').find('[data-username]').text();

		var table = $('#basicExample').DataTable();
		var table_row = table.row( $(this).parents('tr') );
		
		alertify.set({
			labels: {
				ok: 'Cancel',
				cancel: 'Delete User'
			}
		});
		
		alertify.confirm("Are you sure you want to delete " + username, function (e) {
			if (e) {

			} else {
				$.ajax({
						
					type	: 	"POST",
					url		:	"ajax/dashboard.post.php",
					data	:	{
						action: 'statuschange',
						userid: user_id,
						status: 99
					},
					success	: function(returned_state){
						switch(returned_state){
								
							case '99':
								$('.users-remaining').html( parseInt($('.users-remaining').html()) + 1 );
								table_row.remove().draw();
								alertify.success("User successfully deleted");
								break;
						};
					}
				});				
			}
		});		
	});	

	
	$('.add-users').on('click', function(){
		
		var table = $('#usersToAdd').DataTable();
		
		table.rows().every(function(rowIndex, tableLoop, rowLoop) {
			
			var user = this.data();
			if(user[4] != '<span class="badge red-bg">Insufficient Licenses</span>') {
				
				$.ajax({
							
					type	: 	"POST",
					url		:	"ajax/dashboard.post.php",
					data	:	{
						action: 'adduser',
						company: getUrlVars()['companyid'],
						username: user[3],
						first: user[1],
						last: user[2]							
					},
					success	: function(returned_data){
						alertify.success("Users successfully added. Please refresh page to show in user table above.");
						table.clear().draw();
					}
				});				
			}

		});
		
		/*$.ajax({

			url: 'company_specific_files/1/users.csv?timestamp=' + new Date().getTime(),
			async: false,
			success	: function(csv){
				
				var lines = csv.split("\n");
				var result = [];
				
				var headers = lines[0].split(",");
				
				for( var i=1; i<lines.length; i++ ) {
					
					var obj = {};
					var currentline = lines[i].split(",");
					
					for( var j=0; j<headers.length; j++ ){
						obj[headers[j]] = currentline[j];
					};

					$.ajax({
						
						type	: 	"POST",
						url		:	"ajax/dashboard.post.php",
						data	:	{
							action: 'adduser',
							username: obj['Username'],
							first: obj['First Name'],
							last: obj['Last Name']							
						},
						success	: function(returned_data){
							console.log(returned_data);
						}
					});
				}
			},
			dataType: 'text',
			complete: function() {
				console.log('completed');
			}
		});*/
	});
});

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