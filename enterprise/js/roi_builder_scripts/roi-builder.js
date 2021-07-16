$(document).on('ready', function() {

	/**
		Once Dom is ready fire Ajax to pull ROI builder info.
	*/
	
	$.ajax({

		type		:	'GET',
		url			:	'ajax/calculator.get.php',
		data		:	{
			action		:	'getroiarray',
			roi			:	getUrlVars()['roi']
		},
		success		:	function( roi_builder_array ) {
			
			/**
				Uncomment to see array that was returned.
			*/
			
			 console.log( roi_builder_array );

			/** 
				Build Main Template
			*/
			
			var roi_builder = JSON.parse( roi_builder_array );

			/**
				Uncomment to see the main array and sidebar.
			*/
			
			// console.log( roi_builder.sidebar );
			// console.log( roi_builder.main_content.elements );

			var template_elements = roi_builder.main_content.elements;
			var section_build = '';
			
			// Loop through every element and produce javascript array to be processed.
			$.each(template_elements, function( index, value ){

				var section_content = __getElementType(value);
				if(section_content){
					section_build += section_content;
				}
			});

			$('#roiContent').append(section_build);	
	

			leftSidebarBuild = __buildLeftSidebar(roi_builder.sidebar);
			$('.navbar-default').append(leftSidebarBuild);	
			
			$.ajax({	
				
				type		:	'GET',
				url			:	'ajax/calculator.get.php',
				data		:	{
					action		:	'getroicurrency',
					roi			:	getUrlVars()['roi']
				},
				success		:	function(returnedcurrency) {

					currency = JSON.parse(returnedcurrency);
					
					$('.wrapper').setupPlugins({
						currencyIdentifier: currency.currency_name
					});
				}
			});

			setTimeout(function(){
				$('body').addClass('loaded');
				$('.entry-header').hide();
			}, 500);
			//$('#wrapper').calx('refresh');			
		}
	});	
});