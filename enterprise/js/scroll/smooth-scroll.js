$(document).ready(function (){
	
		
	/***
	*
		Smooth Scroll 
	*
	*/		
		
	if( $(".smooth-scroll").length>0 ) {
		
		if( $(".header.fixed").length>0 ) {
				
			$('.smooth-scroll a[href*=#]:not([href=#]), a[href*=#]:not([href=#]).smooth-scroll').click( function() {
				if( location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname ) {
					
					var target = $(this.hash);
					target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
					
					if( target.length ) {
							
						$('html,body').animate({
							scrollTop: target.offset().top-85
						}, 1000);
						return false;
					}
				}
			});
			
		} else {
			
			$('.smooth-scroll a[href*=#]:not([href=#]), a[href*=#]:not([href=#]).smooth-scroll').click( function() {
				if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
					
					var target = $(this.hash);
					target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
					
					if (target.length) {
						
						$('html,body').animate({
							scrollTop: target.offset().top - $('.navbar-fixed-top').height()
						}, 1000);
						return false;
					}
				}
			});
		}
	
	}
	
	$('#side-menu').metisMenu({
		toggle: false
	});	

});