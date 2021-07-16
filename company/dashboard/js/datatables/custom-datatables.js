// Basic DataTable
$(function(){
	
	var $userTable = $('#basicExample');
	
	var table = $userTable.DataTable({
		responsive: false,
		dom: 'Bfrtip',
		buttons: [
            'copy', 'csv'
        ]
	});
	
	$('#basicExample').DataTable().on('page', function() {
		
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
	});
	
	var $userTable = $('#companyROIs');
	
	var table = $userTable.DataTable({
		responsive: true,
		dom: 'Bfrtip',
		buttons: [
            'copy', 'csv'
        ]
	});
});