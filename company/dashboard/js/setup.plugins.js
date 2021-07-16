;(function( $ ){
	
	$('#usersToAdd').DataTable();
	
	$.fn.editable.defaults.mode = 'inline';
	
	$.ajax({
		type: 'GET',
		data: {
			action: 'getUsers',
			companyid: getUrlVars()['companyid']
		},
		url: 'ajax/dashboard.get.php',
		dataType: 'json',
		success: function(result){
			
			var i;
			
			for(i=0; i<result.length; i++) {
				result[i] = {
					value: result[i].user_id,
					text: result[i].username,
					name: result[i].username
				};
			}

			$('.user-manager').editable({
				source: result,
				url: 'ajax/updatemanager.php'
			});
		}
	});
	
	function exportTableToCSV($table, filename) {

		var table = $table.DataTable();
		
		csv = table.rows().every(function(index) {
			
			var row = table.row(index);
			
			var data = row.data();
			console.log(data);
		});
		
		/*var $rows = $table.find('tbody').find('tr:has(td)');

		var tmpColDelim = String.fromCharCode(11),
		tmpRowDelim = String.fromCharCode(0),

		colDelim = '","',
		rowDelim = '"\r\n"',

		csv = '"' + $rows.map(function(i, row) {

			var $row = $(row),
			$cols = $row.find('td');

			return $cols.map(function(j, col) {
				var $col = $(col),
				text = $col.text();
			
				return text.replace(/"/g, '""'); // escape double quotes

			}).get().join(tmpColDelim);

		}).get().join(tmpRowDelim)
		.split(tmpRowDelim).join(rowDelim)
		.split(tmpColDelim).join(colDelim) + '"';

		if (false && window.navigator.msSaveBlob) {

			var blob = new Blob([decodeURIComponent(csv)], {
				type: 'text/csv;charset=utf8'
			});
			
			window.navigator.msSaveBlob(blob, filename);

		} else if (window.Blob && window.URL) {
       
			var blob = new Blob([csv], {
				type: 'text/csv;charset=utf-8'
			});
			var csvUrl = URL.createObjectURL(blob);

			$(this).attr({
				'download': filename,
				'href': csvUrl
			});
		} else {

			var csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

			$(this).attr({
				'download': filename,
				'href': csvData,
				'target': '_blank'
			});
		}*/
	}

	$('table').delegate('.remove-table', 'click', function(){
		
		$('.users-remaining').html( parseInt($('.users-remaining').html()) + 1 );
		$('#usersToAdd').DataTable().row( $(this).parents('tr') ).remove().draw();
	});

	$(".export").on('click', function(event) {
		
		var args = [$('#basicExample'), 'export.csv'];
		exportTableToCSV.apply(this, args);
	});
	
	/*Dropzone.autoDiscover = false;
	
	var dropzone = new Dropzone('#roi_users');
	dropzone.on("addedfile", function(file) {
		
		$('#file-upload-status').show();
		
		$.ajax({

			url: 'company_specific_files/' + getUrlVars()['companyid'] + '/users.csv?timestamp=' + new Date().getTime(),
			success	: function(csv){
				
				var lines = csv.split("\n");
				var result = [];
				
				var headers = lines[0].split(",");
				var totallines = lines.length;
				
				for( var i=1; i<totallines; i++ ) {
					
					var obj = [];
					var currentline = lines[i].split(",");
					
					var currentprogress = i / lines.length * 100;
					currentprogress = currentprogress.toFixed(0);
					currentprogress = parseInt(currentprogress);
					
					$('#file-upload-status-bar').css('width', currentprogress + "%").attr('aria-valuenow', currentprogress);
					
					for( var j=0; j<headers.length; j++ ){
						
						switch(headers[j]) {
							
							case 'First Name':
								obj["First Name"] = currentline[j];
								break;
								
							case 'Last Name':
								obj["Last Name"] = currentline[j];
								break;
								
							case 'Email':
								obj["Email"] = currentline[j];
								
								$.ajax({
									
									type	: 	"POST",
									async	:	false,
									url		:	"ajax/dashboard.post.php",
									data	:	{
										action: 'checkavailability',
										username: obj['Email']						
									},
									success	: function(returned_data){
										
										if(returned_data > 0) {
											obj['Exists'] = '<span class="badge green-bg">Existing User</span>'
										} else {
											obj['Exists'] = '<span class="badge blue-bg">New User</span>'
										};
									}
								});								
								break;
						}
					};
					
					if(currentline.length != headers.length) {
						obj["Error"] = '<span class="badge red-bg">!</span>';
					} else {
						obj["Error"] = '&nbsp;';
					}

					if(obj["Email"]) {
						result.push(obj);
					};
					
					/*$.ajax({
						
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
				};
				
				var table_data = [];
				
				for( var i=1; i<result.length; i++ ) {
					
					var row_data = [];
					row_data.push(result[i]['Exists']);
					row_data.push(result[i]['First Name']);
					row_data.push(result[i]['Last Name']);
					row_data.push(result[i]['Email']);
					row_data.push(result[i]['Error']);
					
					table_data.push(row_data);
				}

				$('#usersToAdd').dataTable().fnClearTable();
				$('#usersToAdd').dataTable().fnAddData(table_data);
				
			},
			dataType: 'text'
		});
		
	});*/
	
	$('.add-user-table').on('click', function() {
		
		var username = $('input[name="email"]').val();
		var first_name = $('input[name="first_name"]').val();
		var last_name = $('input[name="last_name"]').val();
		
		var remaining_licenses = $('.users-remaining').html();
		
		var table = $('#usersToAdd').DataTable();
		var new_users = 0;
		var user_in_table = 0;
		
		table.rows().every(function(index) {

			var row = table.row(index);
			var data = row.data();
			
			if( username == data[3] ) {
				alertify.alert("User already added to Add New User table");
				user_in_table++;
			};
			
			if(data[0] != '<span class="badge green-bg">Existing User</span>') {
				
				new_users++;
			}
		});

		var issues = '&nbsp;';
		if( new_users >= remaining_licenses ) {
			issues = '<span class="badge red-bg">Insufficient Licenses</span>';
		};
		
		if(username && user_in_table == 0) {

			$.ajax({
										
				type	: 	"POST",
				async	:	false,
				url		:	"ajax/dashboard.post.php",
				data	:	{
					action: 'checkavailability',
					username: username						
				},
				success	: function(returned_data){
											
					if(returned_data > 0) {
						var exists = '<span class="badge green-bg">Existing User</span>'
					} else {
						var exists = '<span class="badge blue-bg">New User</span>'
					};
		
					var row_data = [];
					row_data.push(exists);
					row_data.push(first_name);
					row_data.push(last_name);
					row_data.push(username);
					row_data.push(issues);
					row_data.push('<span class="badge red-bg"><i class="fa fa-times remove-table"></i></span>');
						
					$('.users-remaining').html( parseInt($('.users-remaining').html()) - 1 );
					$('#usersToAdd').dataTable().fnAddData(row_data);
				}
			});	
		
		}					
		
	});
	
	
})( window.jQuery || window.Zepto );