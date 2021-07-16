/*!
 * Nestable jQuery Plugin - Copyright (c) 2012 David Bushell - http://dbushell.com/
 * Dual-licensed under the BSD or MIT licenses
 */
;(function($, window, document, undefined) {

    var defaults = {};

	function Plugin(element, options) {
        this.w  = $(document);
        this.el = $(element);
        this.options = $.extend({}, defaults, options);
        this.init();
    }

    Plugin.prototype = {

        init: function() {

			var $build = this;
			var $elements = this.options.elements;

			$.each($elements, function(key,value){

				$build.element(value, $build.el);
			});
			
			var $navigation = this.options.navigation;
			
			if($navigation){
				var $navHolder = $('.sidebar-navigation');
				
				var $sidebar = $('<div/>')
						.addClass('sidebar-collapse');
						
				var $metisMenu = $('<ul/>')
						.addClass('nav metismenu')
						.attr({
							id : 'side-menu'
						});
						
				$sidebar = $sidebar.append($metisMenu);
				
				$navHolder.append($sidebar);
				
				$.each($navigation, function(key,value){

					$build.navigation(value, $metisMenu);
				});
				
				$('#side-menu').metisMenu();				
			}
			
			if(this.options.stylesheets){
				this.stylesheets();
			};
        },
		
		navigation: function($specs, $parent){

			var $build = this;
			var $navItem = $('<li/>')
					.addClass('smooth-scroll');
					
			var $reference = $('<a/>')
					.attr({
						'href' : ( $specs.href ? $specs.href : '#' )
					});
					
			if($specs.icon) {
				
				var $icon = $('<i/>')
					.addClass('fa')
					.addClass($specs.icon);
					
				$reference = $reference.append($icon);
			};
			
			$navItem = $navItem.append($reference);
			
			var $label = $specs.label;
			$reference.append($label);
			
			if($specs.children){
				
				$arrow = $('<span/>')
					.addClass('fa arrow');

				$reference.append($arrow);
				
				var $nextLevel = $('<ul/>')
						.addClass('nav nav-second-level collapse in');
						
				$navItem.append($nextLevel);
				
				$.each($specs.children, function(key, value){
					
					$build.navigation(value, $nextLevel);
				});
			};
			
			$parent.append($navItem);			
		},
		
		stylesheets: function(){
			
			var stylesheets = this.options.stylesheets;

			$.each(stylesheets, function(count, sheet){
				
				$('head').append('<link rel="stylesheet" href="' + sheet + '" type="text/css" />');
			});
		},
		
		element: function($specs, $parent) {

			// Create DOM Div
			var $div = $('<div/>');
			var $build = this;
			
			// Create the element within the div.
			var $element = $div.roishop($specs);
			
			// If element has children loop through and create them within this element.
			if($specs.children && $specs.children.length !=0){
				$.each($specs.children, function($key, $value) {
					$build.element($value, $element);
				});
			};
			
			// Append element to its parent.
			$parent.append($div);
		}

    };

    $.fn.roiBuild = function(params) {
		
        var elements  = this,
            retval = this;

        elements.each(function() {
			
            var plugin = $(this).data("roi");

            if (!plugin) {
                $(this).data("roi", new Plugin(this, params));
                $(this).data("roi-id", new Date().getTime());
            } else {
                if (typeof params === 'string' && typeof plugin[params] === 'function') {
                    retval = plugin[params]();
                }
            }
        });

        return retval || elements;
    };

})(window.jQuery || window.Zepto, window, document);
