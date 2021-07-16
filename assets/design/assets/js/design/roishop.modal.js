;(function($, window, document, undefined) {
	
    'use strict';
	var rsModal = function(element, options) {
        this.options = options;
        this.el = $(element);
        this.el_ = this.el.clone();
        this.timeoutId_ = 0;
        this.timeoutFooter_ = 0;

        this.init();	
	};
	
	rsModal.DEFAULTS = {
		size: 'modal-lg'
	};	
	
	var defaults = {};
	
    rsModal.EVENTS = {
        'all.rs.dashboard': 'onAll',
        'create-roi.rs.dashboard' : 'onCreateRoi'
    };

	rsModal.prototype.init = function () {
		this.initContainer();
		this.initDialog();
    }
	
	rsModal.prototype.initContainer = function () {
		this.$container = $([
			'<div class="modal inmodal">',
			'<div class="modal-dialog">',
			'<div class="modal-content">',
			'<div class="modal-header">',
			'</div>',
			'<div class="modal-body">',
			'</div>',
			'<div class="modal-footer">',
			'</div>',
			'</div>',
			'</div>',
			'</div>'
		].join(''));
			
		this.$modalDialog = this.$container.find('.modal-dialog');
		this.$modalContent = this.$container.find('.modal-content');
		this.$modalHeader = this.$container.find('.modal-header');
		this.$modalBody = this.$container.find('.modal-body');
		this.$modalFooter = this.$container.find('.modal-footer');
	}
	
	rsModal.prototype.initDialog = function() {
		
	}
	
	rsModal.prototype.trigger = function(name) {
		var args = Array.prototype.slice.call(arguments, 1);

		name += '.rs.modal';
		this.options[rsModal.EVENTS[name]].apply(this.options, args);
		this.el.trigger($.Event(name), args);

		this.options.onAll(name, args);
		this.el.trigger($.Event('all.rs.modal'), [name, args]);			
	}	
	
	var allowedMethods = [];
	 
	$.fn.rsModal = function(option) {

        var value,
            args = Array.prototype.slice.call(arguments, 1);

        this.each(function () {
            var $this = $(this),
                data = $this.data('rs.modal'),
                options = $.extend({}, rsModal.DEFAULTS, $this.data(),
                    typeof option === 'object' && option);

            if (typeof option === 'string') {
                console.log(option);
				if ($.inArray(option, allowedMethods) < 0) {
                    throw new Error("Unknown method: " + option);
                }

                if (!data) {
                    return;
                }

                value = data[option].apply(data, args);

                if (option === 'destroy') {
                    $this.removeData('rs.modal');
                }
            }

            if (!data) {
                $this.data('rs.modal', (data = new rsModal(this, options)));
            }
        });

        return typeof value === 'undefined' ? this : value;
    };
	
    $.fn.rsModal.Constructor = rsModal;
    $.fn.rsModal.defaults = rsModal.DEFAULTS;
    $.fn.rsModal.methods = allowedMethods;

})(window.jQuery || window.Zepto, window, document);
