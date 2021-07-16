;(function( $ ){
	
	var action 		= 'setupdashboard',
		ajax_url 	= '/php/ajax/dashboard/dashboard.get.php';
		
	$.get( ajax_url, { action: action } )
		.done(function(dashboard_setup){
			// console.log("dashboard setup:",dashboard_setup)
			var dashboard_setup = JSON.parse(dashboard_setup),
				user_rois 		= dashboard_setup.rois,
				user_templates 	= dashboard_setup.templates,
				user_info		= dashboard_setup.userinfo,
				user_tags		= dashboard_setup.tags,
				company_users	= dashboard_setup.companyusers,
				permissions 	= dashboard_setup.permissions

		$('#wrapper').roishopDashboard({
			rois		: 	user_rois,
			templates	: 	user_templates,
			userinfo	:	user_info,
			tags		:	user_tags,
			compUsers	:	company_users,
			permissions	: 	permissions
		});

		var timeoutTest = function(){
			$('#wrapper').roishopDashboard('initRoiTable');
		}

		setTimeout(timeoutTest, 0);
	});


	
})( window.jQuery || window.Zepto );