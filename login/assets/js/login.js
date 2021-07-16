;(function( $ ){
	"use strict";

	var	hideClass = 'd-none';
	var	showClass = 'show';

	$(window).on('load', function() {
		$.when($('#page-loader').addClass(hideClass)).done(function() {
			$('#page-container').addClass(showClass);
		});
	});
	
	$('#password-form').off('submit').on('submit', function(e){
		e.preventDefault();

		var username = $('#email-address').val();
		var password = $('#password').val();
		var remember = $('#remember-me').is(':checked');
		
		login(username, password, remember);
	});
	
	var login = function(username, password, remember){

		$.ajax({
			type: "POST",
			url: "assets/ajax/login.actions.php",
			data: {
				action: "signInUser",
				username: username,
				password: password,
				remember: remember
			},
			success: function(loginStatus){console.log(loginStatus);
				var login = JSON.parse(loginStatus);
				var href = getQueryVariable('ref');
				
				if (login.warnings){
					if (login.warnings.no_user_found){
						var alert = '<div class="alert alert-danger">Username or password is incorrect.</div>',
							$alert_holder = $('.alert-holder');
							
						$alert_holder.empty();
						
						$(alert).appendTo($alert_holder).hide().fadeIn(1000);
					}
					
					if (login.warnings.not_registered){
						var alert = '<div class="alert alert-primary">This account hasn\'t been registered.</div>',
							$alert_holder = $('.alert-holder');
							
						$alert_holder.empty();
						
						$(alert).appendTo($alert_holder).hide().fadeIn(1000);					
					}					
				} else {
					
					if (login.session_id && login.token){
						Cookies.set('session', login.session_id, { expires: 7 });
						Cookies.set('token', login.token, { expires: 7 });
					}

					window.location.replace(href ? href : "/dashboard");					
				}		
			}
		})		
	}
	
	$('.register-account').off('click').on('click', function(){
		$('.login-nav-tab').find("a#register-email").trigger('click');
	});

	$('#register-account').off('submit').on('submit', function(e){
		e.preventDefault();
		var username = $('#email-register').val();

		$.ajax({
			type: "POST",
			url: "assets/ajax/login.actions.php",
			data: {
				action: "sendRegisterEmail",
				username: username
			},
			success: function(loginStatus){
				var login = JSON.parse(loginStatus);
				console.log(login);
				if (login.warnings){
					if (login.warnings.account_registed){
						var alert = '<div class="alert alert-primary">Account has already been registered. If you\'ve forgotten your password please click the Forgot Password tab to recover it.</div>',
							
						$alert_holder = $('.registration-alert-holder');	
						$alert_holder.empty();
						
						$(alert).appendTo($alert_holder).hide().fadeIn(1000);
					}
					
					if (login.warnings.not_in_system){
						var alert = '<div class="alert alert-warning">This email isn\'t in our system. If you\'ve been given a login please contact your administrator, otherwise contact us at <a href="emailto:mfarber@theroishop.com">support@theroishop.com</a></div>',
							$alert_holder = $('.registration-alert-holder');
							
						$alert_holder.empty();
						
						$(alert).appendTo($alert_holder).hide().fadeIn(1000);					
					}					
				} else {
					
					var alert = '<div class="alert alert-primary">An email has been sent. Please follow the instructions to complete the registration. If you do not receive an email please check your junk folder. If it doesn\'t arrive then contact us at <a href="emailto:mfarber@theroishop.com">support@theroishop.com</a></div>',
					
					$alert_holder = $('.registration-alert-holder');		
					$alert_holder.empty();
					
					$(alert).appendTo($alert_holder).hide().fadeIn(1000);					
				}
			}
		})
	});
	
	$('#password-reset').off('submit').on('submit', function(e){
		e.preventDefault();
		var username = $('#email-to-reset').val();

		$.ajax({
			type: "POST",
			url: "assets/ajax/login.actions.php",
			data: {
				action: "resetPassword",
				username: username
			},
			success: function(loginStatus){
				var login = JSON.parse(loginStatus);

				if (login.warnings){
					if (login.warnings.not_in_system){
						
						var alert = '<div class="alert alert-warning">This email isn\'t in our system. If you have an account please contact your administrator, otherwise contact us at <a href="emailto:mfarber@theroishop.com">support@theroishop.com</a></div>',
						
						$alert_holder = $('.reset-alert-holder');	
						$alert_holder.empty();
						
						$(alert).appendTo($alert_holder).hide().fadeIn(1000);					
					}				
				} else {
					
					var alert = '<div class="alert alert-primary">An email has been sent. Please follow the instructions to reset your password. If you do not receive an email please check your junk folder. If it doesn\'t arrive then contact us at <a href="emailto:mfarber@theroishop.com">support@theroishop.com</a></div>',
					
					$alert_holder = $('.reset-alert-holder');		
					$alert_holder.empty();
					
					$(alert).appendTo($alert_holder).hide().fadeIn(1000);					
				}
			}
		})
	});

})( window.jQuery || window.Zepto );

	
var getQueryVariable = function(variable){
		
	var query = window.location.search.substring(1),
		vars = query.split("&");

	for (var i=0;i<vars.length;i++) {
			
		var pair = vars[i].split("=");
		if(pair[0] == variable){ 
			var reference = vars[i].replace('?'+variable+'=','');
				reference = reference.replace(variable+'=','');
			return reference
		}
	}
		
	return(false);
}

$(document).ready(function(){
	var cookie = Cookies.get();

	if (cookie.session && cookie.token){
		
		$.ajax({
			type: "POST",
			url: "assets/ajax/login.actions.php",
			data: {
				action: "rememberedSignIn",
				session: cookie.session,
				token: cookie.token
			},
			success: function(loginStatus){
				var login = JSON.parse(loginStatus);
				var href = getQueryVariable('ref');
				
				if (login.username){
					window.location.replace(href ? href : "/dashboard");
				}
			}
		})		
	}	
});