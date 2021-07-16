$(document).ready(function (){
	
	new Clipboard('.clipboard-btn');
	
	$( '.chosen-selector' ).chosen({
		width: '100%',
		disable_search_threshold: 10
	});
	
	$('.currency-selector').on('change', function() {
		
		var currency_symbol = $(this).val();

		$.ajax({	
			type	: 	"POST",
			url		:	"ajax/dashboard.post.php",
			data	:	{
				action: 'changecurreny',
				symbol: currency_symbol,
				roi: getUrlVars()['roi']
			},
			success	: function(currency_changed){
					
				// Show user a notification that the verification link was changed
				noty({
					text: 'ROI Currency Updated',
					type: 'success',
					timeout: 2000
				});
			}
		});

	});
		
	$('#verification_code').on('click keydown', function(e){
		e.preventDefault();
		$('#copy-button').click();
	});
	
	$('.modal').on('click', '.reset-ver-link-confirm', function(){
			
		$.ajax({	
			type	: 	"GET",
			url		:	"ajax/dashboard.get.php",
			data	:	{
				'action': 'resetverification',
				'roi': getUrlVars()['roi']
			},
			success	: function(verification_code){
				
				// Show user a notification that the verification link was changed
				noty({
					text: 'Verification Link successfully changed',
					type: 'success',
					timeout: 2000
				});

				// Trigger the view verification link button
				$('#verification_code').val('https://www.theroishop.com/enterprise/?roi=' + getUrlVars()['roi'] + '&v=' + verification_code);
			}
		});
	});
	
	$('#table-contributor').delegate('.remove-contributor','click', function() {
		
		var contributor_item = $(this).closest('[data-contributor-id]');
		
		$.ajax({	
			type	: 	"POST",
			url		:	"ajax/dashboard.post.php",
			data	:	{
				'action': 'removecontributor',
				'contributor': contributor_item.data('contributor-id')
			},
			success	: function(contributor_removed){
				
				// Show user a notification that the verification link was changed
				noty({
					text: 'Contributor Removed',
					type: 'success',
					timeout: 2000
				});

				contributor_item.fadeOut();
			}
		});
	});
	
	$('.sf-elements').on('change', function() {
		
		var sfdc_link = $(this).val();
		var sfdc_title = $(this).find('option:selected').text();
		
		$.ajax({
			
			type: "GET",
			url: "ajax/dashboard.get.php",
			data: {
				action: 'getversion',
				roi: getUrlVars()['roi']
			},
			success: function(version_id){
				
				var roi_link;

				if(version_id == 490) {
					roi_link = '{"ROI_Link__c" : "https://www.theroishop.com/enterprise/?roi=' + getUrlVars()['roi'] + '"}';
				} else if (version_id == 508){
					roi_link = '{"Value_Assessment_URL__c" : "' + $('#verification_code').val() + '"}';
				}

				$.ajax({	
					type	: 	"POST",
					url		:	"ajax/dashboard.post.php",
					data	:	{
						action: 'updatesflink',
						sfdclink: encodeURIComponent(sfdc_link),
						sfdctitle: sfdc_title,
						roi: getUrlVars()['roi']
					},
					success	: function(contributor_removed){
						
						console.log('version: ' + version_id);
						if(version_id != 508) {
							
							$.ajax({
								
								type: 'GET',
								url: '../ajax/cloudelement/cloudelement.php',
								data: {
									action: 'updaterecord',
									updated_fields: roi_link,
									roi: getUrlVars()['roi']
								},
								success: function(data){

									noty({
										text: 'Salesforce successfully updated!',
										type: 'success',
										timeout: 2000
									});					
								}
							});
						}
					}
				});
			}
		});

	});
	
	$('.opportunityFiltering').delegate('.newFilter','click', function() {
		
		var vendorOptions = $(this).closest('.filterString').find('.vendorPath').html();
		var vendorPath = '<select class="form-control chosen-selector vendorPath" data-placeholder="Please make a selection below">' + vendorOptions + '</select>';
		
		var whereCondition = 	'<select class="form-control chosen-selector whereCondition" data-placeholder="Please make a selection below">\
									<option value="equals">Equals</option>\
									<option value="contains">Contains</option>\
									<option value="contains">Begins With</option>\
									<option value="contains">Ends With</option>\
									<option value="greater">Greater Than</option>\
									<option value="lesser">Less Than</option>\
									<option value="greaterequal">Greater Than or Equal To</option>\
									<option value="lesserequal">Less Than or Equal To</option>\
								</select>';

		var newFilter = '<div class="form-group filterString">\
							<div class="col-lg-4">' + vendorPath + '</div>\
							<div class="col-lg-2">' + whereCondition + '</div>\
							<div class="col-lg-5">\
								<input class="form-control whereClause" name="sfFilterString" type="text" placeholder="Where Clause">\
							</div>\
							<div class="col-lg-1">\
								<button class="btn btn-info btn-circle newFilter" type="button"><i class="fa fa-plus"></i></button>\
								<button class="btn btn-danger btn-circle removeFilter" type="button"><i class="fa fa-times"></i></button>\
							</div>';
		
		$('.opportunityFiltering').append(newFilter);
		
		$( '.chosen-selector' ).chosen({
			width: '100%',
			disable_search_threshold: 10
		});
	});
	
	$('.opportunityFiltering').delegate('.removeFilter','click', function() {
		
		$(this).closest('.filterString').remove();
	});

});

function getUrlVars() {
			
	var vars = [], hash;
	var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	for(var i = 0; i < hashes.length; i++) {
		hash = hashes[i].split('=');
		vars.push(hash[0]);
		vars[hash[0]] = hash[1].replace('#','');
	}
	return vars;
}

function addContributor() {
	
	$.ajax({	
		type	: 	"POST",
		url		:	"ajax/dashboard.post.php",
		data	:	"action=addcontributor&" + $('#form-add-contributor').serialize() + '&roi=' + getUrlVars()['roi'],
		success	: function(contributor_id){
				
			// Show user a notification that the verification link was changed
			noty({
				text: 'New Contributor Added',
				type: 'success',
				timeout: 2000
			});

			var table_rows = $('#table-contributor tr').length;
			
			var table_row = '<tr data-contributor-id="' + contributor_id + '">\
								<td class="text-center">' + table_rows++ + '</td>\
								<td>' + $('[name="name"]').val() + '</td>\
								<td class="text-center small">Contributor</td>\
								<td class="text-center">Now</td>\
								<td class="text-center"><button type="button" class="btn btn-circle btn-danger remove-contributor"><i class="fa fa-times"></i></button></td>\
							</tr>';
							
			$('#table-contributor tr:last').after(table_row);
		}
	});
}

function changeCurrency() {
	
	$.ajax({	
		type	: 	"POST",
		url		:	"ajax/dashboard.post.php",
		data	:	"action=changecurreny&" + $('#form-change-currency').serialize() + '&roi=' + getUrlVars()['roi'],
		success	: function(currency_changed){
				
			// Show user a notification that the verification link was changed
			noty({
				text: 'ROI Currency Updated',
				type: 'success',
				timeout: 2000
			});
		}
	});
}

function setupSalesforceConnection(){

	window.open('https://localhost/salesforceintegration','The ROI Shop','scrollbars=yes,width=650,height=450');
}

function LinkROItoSalesforce(opportunity, account, lead) {
	
	$('.link-to-opportunity').html('<img alt="Loading" src="../css/img/ajax-loader.gif"> Retrieving Opportunities');
	
	var searchQuery = 'where=';
	
	$('.filterString').each(function(){
	
		var vendorPath = encodeURI($(this).find('.vendorPath').val());
		var whereCondition = encodeURI($(this).find('.whereCondition').val());
		var whereClause = encodeURI($(this).find('.whereClause').val());
		
		if(whereClause){
			
			switch(whereCondition) {
				
				case 'equals':
					whereCondition = '=';
					if(whereClause != 'false' && whereClause != 'true') {
						whereClause = "'" + whereClause + "'";
					}
				break;
				
				case 'contains':
					whereCondition = '%20like%20';
					whereClause = "'%25" + whereClause + "%25'";
				break;
				
				case 'beginswith':
					whereCondition = '%20like%20';
					whereClause = "'%25" + whereClause + "'";
				break;

				case 'endswith':
					whereCondition = '%20like%20';
					whereClause = "'" + whereClause + "'%25";
				break;

				case 'greater':
					whereCondition = '>';
					whereClause = "'" + whereClause + "'";
				break;

				case 'lesser':
					whereCondition = '<';
					whereClause = "'" + whereClause + "'";
				break;

				case 'greaterequal':
					whereCondition = '>=';
					whereClause = "'" + whereClause + "'";
				break;

				case 'lesserequal':
					whereCondition = '<=';
					whereClause = "'" + whereClause + "'";
				break;				
			};

			if(searchQuery == 'where=') {
				searchQuery += vendorPath + whereCondition + whereClause;
			} else {
				searchQuery += '&' + vendorPath + whereCondition + whereClause;
			}
		}
	
	});
	
	var elements_to_import = [];
	var target = $(event.target);
	var filterString = '';
	
	if(searchQuery != 'where=') {
		filterString = searchQuery;
	}

	$.ajax({
		
		type: 'GET',
		url: '../ajax/cloudelement/cloudelement.php',
		data: {
			action: 'getSFelements',
			opportunity: opportunity,
			account: account,
			lead: lead,
			where: filterString
		},
		success: function(elements) {
			
			var connections = $.parseJSON(elements);
			var opportunities = $.parseJSON(connections.Opportunity);

			if( ! $.isEmptyObject(opportunities) ){
				
				$('.sf-elements').html('');
				opportunities = opportunities.sort(function(a,b){ return a.Name > b.Name ? 1 : -1; });
					
				var selectOptions = '<optgroup data-label="opportunities" label="Opportunities">';
					
				for(var i=0; i<opportunities.length; i++) {
					var opp = opportunities[i];
					selectOptions += "<option value='" + opp.Id + "'>" + opp.Name + "</option>";
				}
					
				selectOptions += '</optgroup>';
					
				$('.sf-elements').append(selectOptions);
			}
			
			$('.sf-elements').trigger('chosen:updated');
			$('.sf-elements').trigger('change');
			
			$('.link-to-opportunity').html('Retrieve all Elements');
		}
	});
}

function pushWorkfrontSalesforce() {
		
	$.ajax({	
		type	: 	"GET",
		url		:	"ajax/dashboard.get.php",
		data	:	{
			'action': 'getworkfronttotal',
			'roi': getUrlVars()['roi']
		},
		success	: function(summary_total){
		
			var sfdc_link = $('.sf-elements').val();
			var sfdc_title = $('.sf-elements').find('option:selected').text();
			var roi_link = '{"Value_Assessment_URL__c" : "' + $('#verification_code').val() + '"}';
			var summary_total = '{"Total_Savings_From_Value_Assessment__c" : "' + summary_total + '"}';

			$.ajax({
					
				type: 'GET',
				url: '../ajax/cloudelement/cloudelement.php',
				data: {
					action: 'updaterecord',
					updated_fields: roi_link,
					roi: getUrlVars()['roi']
				},
				success: function(data){
					console.log(data);				
				}
			});
			
			$.ajax({
							
				type: 'GET',
				url: '../ajax/cloudelement/cloudelement.php',
				data: {
					action: 'updaterecord',
					updated_fields: summary_total,
					roi: getUrlVars()['roi']
				},
				success: function(data){
					console.log(data);
					noty({
						text: 'Salesforce successfully updated!',
						type: 'success',
						timeout: 2000
					});					
				}
			});
		}
	});
}