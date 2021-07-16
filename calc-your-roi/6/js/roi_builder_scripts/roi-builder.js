$(document).ready(function() {
	
	$.ajax({
					
		type		:	'GET',
		url			:	'ajax/calculator.get.php',
		data		:	{
			action		:	'getroiarray',
			roi			:	getUrlVars()['roi']
		},
		success		:	function(arrayinfo) {
			
			console.log(arrayinfo);
			testInputs = JSON.parse(arrayinfo);
			//console.log(testInputs.sidebar);
			//console.log(testInputs.main_content.elements);
			var section_build = '';
			$.each(testInputs.main_content.elements, function( index, value ){

				var section_content = __getElementType(value);
				if(section_content){
					section_build += section_content;
				}
			});

			$('#roiContent').append(section_build);	
	
			leftSidebarBuild = __buildLeftSidebar(testInputs.sidebar);
			$('.navbar-default').append(leftSidebarBuild);	

			$('.wrapper').setupPlugins({
				videoHolder: '.player',
				calxAction: 'blur'
			});
		
			setTimeout(function(){
				$('body').addClass('loaded');
				$('.entry-header').hide();
			}, 500);
			//$('#wrapper').calx('refresh');			
		}
	});
	
});