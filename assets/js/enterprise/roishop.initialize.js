;(function( $ ){
	
	$.ajax({

		type : 'GET',
		url : '/assets/ajax/calculator.get.php',
		data : {
			action : 'getroiarray',
			roi : getUrlVars()['roi']
		},
		success	: function( roi_structure ) {

			var roi_structure  = JSON.parse(roi_structure),
				roi_elements   = roi_structure.elements;
				roi_navigation = roi_structure.navigation;

			$('#roiContent').roiBuild({
				elements: roi_elements,
				navigation: roi_navigation,
				currency: 'usd',
				stylesheets: ['/assets/css/bootstrap/bootstrap.min.css','/assets/css/enterprise/style.css']
			});
			
			$('#wrapper').setupPlugins();

			/*setTimeout(function(){
				$('body').addClass('loaded');
				$('.entry-header').hide();
			}, 500); */
			//$('#wrapper').calx('refresh'); */			
		}
	});
	
})( window.jQuery || window.Zepto );