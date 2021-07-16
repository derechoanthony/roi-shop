if (typeof(numeral) === 'undefined') {
	numeral = undefined;
}

if(typeof(moment) == 'undefined'){
	moment = undefined;
}

;(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
    typeof define === 'function' && define.amd ? define(factory) :
    global.rstable = factory();
}(this, (function () {

    'use strict';
	var rstable = (function(el, options) {

        var table = {};
            table.options = {};
            table.$el = $(el);

        var defaults = {
            columns: [[]],
            data: [],
        };

        if (! (el instanceof Element || el instanceof HTMLDocument)) {
            console.error('rsmodal: el is not a valid DOM element');
            return false;
        }

        for (var property in defaults){
            if(options && options.hasOwnProperty(property)){
                if(property === 'text'){
                    table.options[property] = defaults[property];
                } else {
                    table.options[property] = options[property];
                }
            } else {
                table.options[property] = defaults[property];
            }
        }

        table.init = function(){
            table.container();
            table.initTable();
            table.header();
        }

        table.container = function(){
            table.$container = $([
                '<table class="roishop-table">',
                    '<colgroup></colgroup>',
                    '<thead></thead>',
                    '<tbody></tbody>',
                '</table>'
            ].join(''));
            
            table.$header = table.$container.find('thead');
            table.$body = table.$container.find('tbody');
            table.$colgroup = table.$container.find('colgroup');

            table.$el.append(table.$container);
        }

        table.initTable = function(){
            var columns = [],
                data = [];

            // if options.data is set, do not process tbody data
            if (table.options.data.length) {
                return;
            }

            table.options.data = data;
        }

        table.header = function(){
            var visibileColumns = {},
                $headerRow;

            table.header = {
                fields: [],
                styles: [],
                classes: [],
                formatters: [],
                events: [],
                sorters: [],
                sortNames: [],
                cellStyles: [],
                searchables: []
            }

            $.each(table.options.columns, function(i, columns){
                $headerRow = $('<tr/>');

                if(i === 0 && !table.options.cardView && table.options.detailView){
                    $headerRow.append($(sprintf('<th class="detail" rowspan="%s"><div class="fht-cell"></div></th>', table.options.columns.length)));
                }

                $.each(columns, function(i, column){
                    var text = '',
                        halign = '',
                        align = '',
                        style = '',
                        class_ = sprintf(' class="%s"', column['class']),
                        order = table.options.sortOrder || column.order,
                        unitWidth = 'px',
                        width = column.width;

                    if (column.width !== undefined && (!table.options.cardView)) {
                        if (typeof column.width === 'string') {
                            if (column.width.indexOf('%') !== -1) {
                                unitWidth = '%';
                            }
                        }
                    }
                    if (column.width && typeof column.width === 'string') {
                        width = column.width.replace('%', '').replace('px', '');
                    }

                    halign = sprintf('text-align: %s; ', column.halign ? column.halign : column.align);
                    align = sprintf('text-align: %s; ', column.align);
                    style = sprintf('vertical-align: %s; ', column.valign);
                    style += sprintf('width: %s; ', (column.checkbox || column.radio) && !width ?
                        '36px' : (width ? width + unitWidth : undefined));

                    if (typeof column.fieldIndex !== 'undefined') {
                        that.header.fields[column.fieldIndex] = column.field;
                        that.header.styles[column.fieldIndex] = align + style;
                        that.header.classes[column.fieldIndex] = class_;
                        that.header.formatters[column.fieldIndex] = column.formatter;
                        that.header.events[column.fieldIndex] = column.events;
                        that.header.sorters[column.fieldIndex] = column.sorter;
                        that.header.sortNames[column.fieldIndex] = column.sortName;
                        that.header.cellStyles[column.fieldIndex] = column.cellStyle;
                        that.header.searchables[column.fieldIndex] = column.searchable;
    
                        if (!column.visible) {
                            return;
                        }
    
                        if (that.options.cardView && (!column.cardVisible)) {
                            return;
                        }
    
                        visibleColumns[column.field] = column;
                    }

                    var $header = $('<th></th>');

                    $headerRow.append($header);
                });

                table.$header.append($headerRow);
            })
        }

        table.init();
    });

    if (typeof(jQuery) != 'undefined') {
        (function($){
            $.fn.rstable = function(method) {
                var rsTable = $(this).get(0);
                if (! rsTable.rstable) {
                    return rstable($(this).get(0), arguments[0]);
                } else {
                    return rsTable.rstable[method].apply(this, Array.prototype.slice.call( arguments, 1 ));
                }
            };
    
        })(jQuery);
    };

    return rstable;
})));

;(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
    typeof define === 'function' && define.amd ? define(factory) :
    global.rsmodal = factory();
}(this, (function () {

	'use strict';
	var rsmodal = (function(el, options) {
        var modal = {};
        var defaults = {
            header: {
                close: true,
                closeIcon: '<div class="close-modal" data-dismiss="modal">&#10006;</div>'
            },
            body: {
                contentType: 'html'
            }
        };

        if (! (el instanceof Element || el instanceof HTMLDocument)) {
            console.error('rsmodal: el is not a valid DOM element');
            return false;
        }
        
        modal.options = $.extend(true, options, defaults);
        modal.el = $(el);

        modal.init = function(){
            rsmodal.current = modal;

			if(! modal.options.stacked) $('.modal').remove();
			
			modal.createModal();
            modal.createHeader();
            modal.createBody();

			modal.container.modal('show');
			modal.container.on('hidden.bs.modal', function(){
				modal.container.remove();
			});
		}
		
		modal.close = function(){
            modal.container.modal('hide');
			modal.container.remove();
		}

        modal.createModal = function(){
            modal.el.addClass('modal').attr('id', 'modal-name');
            modal.container = modal.el;
            modal.box = $('<div class="modal-box"></div>');

            modal.container.append(modal.box); 
        }

        modal.createHeader = function(){
            var opts = modal.options.header;
            modal.header = $('<div></div>').addClass('modal-header').addClass(opts.class);

            if(opts.close){
                modal.header.addClass(opts.closeClass);
                modal.header.html(opts.closeIcon);

                if(opts.content){
                    switch(typeof opts.content){
                        case 'string':
                            modal.header.append(opts.content);
                        break;

                        case 'object':
                            $.each(opts.content, function(){
                                this.$parent = modal.header;
                                builder.build(this);
                            });
                        break;
                    }
                }

                modal.header.append(modal.close);
            }

            modal.box.append(modal.header);
        }

        modal.createBody = function(){
            var opts = modal.options.body;
            modal.body = $('<div class="modal-body"></div>');

            if(opts.content){
                switch(typeof opts.content){
                    case 'string':
                        modal.body.append(opts.content);
                    break;

                    case 'object':
                        $.each(opts.content, function(){
                            this.$parent = modal.body;
                            builder.build(this);
                        });
                    break;
                }
            }

            modal.box.append(modal.body);
		}
		
		el.rsmodal = modal;

		modal.init();
		
		return modal;
    });

    if (typeof(jQuery) != 'undefined') {
        (function($){
            $.fn.rsmodal = function(method) {
				var spreadsheetContainer = $(this).get(0);
                if (! spreadsheetContainer.rsmodal) {
                    return rsmodal($(this).get(0), arguments[0]);
                } else {
                    return spreadsheetContainer.rsmodal[method].apply(this, Array.prototype.slice.call( arguments, 1 ));
                }
            };
    
        })(jQuery);
    };
})));

;(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
    typeof define === 'function' && define.amd ? define(factory) :
    global.builder = factory();
}(this, (function () {

	'use strict';
	var builder = {};

	builder.build = function(opts){
		var defaults = {
			tag: "div"
		}

        if(opts.access){
            if(opts.access > opts.permission) return;
        }
		builder.options = $.extend(true, defaults, opts);
		builder.holder();
        builder.render();
        builder.attributes();
		if(builder.options.type && typeof(builder[builder.options.type]) == 'function'){		
			builder[builder.options.type]();
        }
        builder.actions();
        builder.children();
	}

	builder.chart = function(){
		if(builder.options.currencyFormat){
			var formatter = function(){
				return numeral(this.value).format('($0,0 a)');
			}
	
			builder.options.yAxis.labels = {};
			builder.options.yAxis.labels.formatter = formatter;
		}
			
		builder.options.$container.highcharts(builder.options);
	}

	builder.holder = function(){
		builder.options.$container = $(sprintf('<%s%s></%s>', builder.options.tag, builder.options.class ? sprintf(' class="%s"', builder.options.class) : '', builder.options.tag));
		
		if(builder.options.html){
			builder.options.$container = builder.options.jqElement ? $(builder.options.html) : builder.options.html
		}
	}

	builder.checkbox = function(){
		if(builder.options.choices){
			$.each(builder.options.choices, function(){
				var option = sprintf('<div class="checkbox i-checks"><label><input type="checkbox" %stoggle-value="%s"><i></i> %s</label></div>', this['calc-id'] ? 'calc-id="' + this['calc-id'] + '" ' : '', this.value ? this.value : '1', this.text);
				builder.options.$container.append(option);
			});		
		};
	}

	builder.revolver = function(){
		builder.options.$container.addClass('revolving-html');
	}

	builder.select = function(){
        var selectCount = 1,
            opts = {
                width: '100%',
                disable_search_threshold: 10,
                inherit_select_classes: true,
                custom_classes: builder.options.customClass || null                
            };

        if(builder.options.options){
            opts = $.extend(true, opts, builder.options.options);
        }

		if(builder.options.choices){
            $.each(builder.options.choices, function(){
                var $option = $(sprintf('<option value="%s">%s</option>', this.value != null ? this.value : selectCount, this.text ? this.text : this.value))
                builder.options.$container.append($option);
                if(this.attributes){
                    $.each(this.attributes, function(attribute, id){
                        $option.attr(attribute, id);
                    })
                }
                selectCount++;
			});		
		};

		builder.options.$container.chosen(opts);
	}

	builder.slider = function(){
		var defaults = {
			start: 0,
			step: 1,
			min: 0,
			max: 100
		};

		var slider_options = $.extend(true, {}, defaults, builder.options);

		var $slider = noUiSlider.create(slider_options.$container[0],{
			start: slider_options.start,
			step: slider_options.step,
			connect: [true, false],
			range: {
				'min' : slider_options.min,
				'max' : slider_options.max
			}
		});

		$slider.on('slide', function(){
			if(slider_options.attributes && slider_options.attributes['calc-id']) {
				roishop.current.setValue(slider_options.attributes['calc-id'], $slider.get(), slider_options.$container);
			}
		})
	}

	builder.video = function(){
		builder.options.$container.append(sprintf('<a class="popup-iframe" href="%s"></a>', builder.options.src));
		builder.options.$container.append(sprintf('<iframe width="425" height="239" style="margin-left: 5px;" src="%s?rel=0&wmode=transparent" frameborder="0" allowfullscreen></iframe>', builder.options.src));

		builder.options.$container.fitVids();
		
	}

	builder.table = function(){
		builder.options.$container.bootstrapTable('destroy').bootstrapTable(builder.options.options);
	}

	builder.rating = function(){
		var defaults = {
			importance: 5
		};

		var rating_options = $.extend(true, {}, defaults, builder.options);
		
		rating_options.$container.starrr({
			emptyClass: 'fa fa-star-o large-star',
			fullClass: 'fa fa-star large-star',
			change: function(e, value){
				roishop.current.setValue(rating_options.$container.attr('calc-id'), value, rating_options.$container);
			}			
		});
	}

	builder.toggle = function(){
		var toggle_opts = builder.options;

		builder.options.$container.data('states', toggle_opts.states);

		builder.options.$container.on('click', function(){
			var $container = $(this),
				states = toggle_opts.states,
				value = roishop.current.sheet.cells[$container.attr('calc-id')].value;

			if(states){
				var current;

				$.each(states, function(a, b){
					$container.removeClass(b.class);
					if(b.value == value){
						current = a;
					}
				});

				var next = current + 1 >= states.length ? 0 : current + 1,
					state = states[next];

				roishop.current.setValue($container.attr('calc-id'), state.value, $container);
				$container.addClass(state.class);
				$container.html(state.text);
			}
		})
	}

	builder.render = function(){
		builder.options.$parent.append(builder.options.$container);
	}

	builder.attributes = function(){
		if(!builder.options.attributes) return;

		$.each(builder.options.attributes, function(attribute, id){
			builder.options.$container.attr(attribute, id);
		});
    }
    
    builder.actions = function(){
        if(!builder.options.actions) return;

        $.each(builder.options.actions, function(event, action){
            var index = event.indexOf(' '),
                name = index > 0 ? event.substring(0, index) : event.substring(0),
                el = event.substring(index + 1),
                $el = index > 0 ? builder.options.$container.find(el) : builder.options.$container;

			$el.off(name).on(name, action);
		});
    }

	builder.children = function(){
		var child = $.extend(true, {}, {}, builder.options);

		if(builder.options.children && builder.options.children.length){
			$.each(builder.options.children, function(i, options){
				options.$parent = child.$container;
				builder.build(options);
			})			
		}
	}

	return builder;
})));

var rsAjax = function(options){
    if(! options.data) {
        options.data = {};
    }

    if(! options.type) {
        options.type = 'GET';
    }
    options.method = options.type;

    if(! options.url){
        options.url = '/assets/api/'
    }

    if(options.data){
        var data = [];
        var keys = Object.keys(options.data);

        if (keys.length) {
            for (var i = 0; i < keys.length; i++) {
                if (typeof(options.data[keys[i]]) == 'object') {
                    var o = options.data[keys[i]];
                    for (var j = 0; j < o.length; j++) {
                        if (typeof(o[j]) == 'string') {
                            data.push(keys[i] + '[' + j + ']=' + encodeURIComponent(o[j]));
                        } else {
                            var prop = Object.keys(o[j]);
                            for (var z = 0; z < prop.length; z++) {
                                data.push(keys[i] + '[' + j + '][' + prop[z] + ']=' + encodeURIComponent(o[j][prop[z]]));
                            }
                        }
                    }
                } else {
                    data.push(keys[i] + '=' + encodeURIComponent(options.data[keys[i]]));
                }
            }
        }

        if (options.method == 'GET' && data.length > 0) {
            if (options.url.indexOf('?') < 0) {
                options.url += '?';
            }
            options.url += data.join('&');
        }
    }

    var httpRequest = new XMLHttpRequest();
    httpRequest.open(options.method, options.url, true);

    if (options.method == 'POST') {
        httpRequest.setRequestHeader('Accept', 'application/json');
        httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    } else {
        if (options.dataType == 'json') {
            httpRequest.setRequestHeader('Content-Type', 'text/json');
        }
    }

    // No cache
    if (options.cache == true) {
        httpRequest.setRequestHeader('pragma', 'no-cache');
        httpRequest.setRequestHeader('cache-control', 'no-cache');
    }

    // Authentication
    if (options.withCredentials == true) {
        httpRequest.withCredentials = true
    }

    // Before send
    if (typeof(options.beforeSend) == 'function') {
        options.beforeSend(httpRequest);
    }

    httpRequest.onload = function() {
        if (httpRequest.status === 200) {
            if (options.dataType == 'json') {
                try {
                    var result = JSON.parse(httpRequest.responseText);

                    if (options.success && typeof(options.success) == 'function') {
                        options.success(result);
                    }
                } catch(err) {
                    if (options.error && typeof(options.error) == 'function') {
                        options.error(result);
                    }
                }
            } else {
                var result = httpRequest.responseText;

                if (options.success && typeof(options.success) == 'function') {
                    options.success(result);
                }
            }
        } else {
            if (options.error && typeof(options.error) == 'function') {
                options.error(httpRequest.responseText);
            }
        }
    }

    if (data) {
        httpRequest.send(data.join('&'));
    } else {
        httpRequest.send();
    }

    return httpRequest;
}

var json2csv = function (objArray){
    var array = typeof objArray != 'object' ? JSON.parse(objArray) : objArray;
    var str = '';
    var line = '';

	for (var index in array[0]) {
		line += index + ',';
	}

	line = line.slice(0, -1);
	str += line + '\r\n';

    for (var i = 0; i < array.length; i++) {
        var line = '';

		for (var index in array[i]) {
			line += array[i][index] + ',';
		}

        line = line.slice(0, -1);
        str += line + '\r\n';
    }

    return str;	
}

var setFieldIndex = function (columns) {
    var i, j, k,
        totalCol = 0,
        flag = [];

    for (i = 0; i < columns[0].length; i++) {
        totalCol += columns[0][i].colspan || 1;
    }

    for (i = 0; i < columns.length; i++) {
        flag[i] = [];
        for (j = 0; j < totalCol; j++) {
            flag[i][j] = false;
        }
    }

    for (i = 0; i < columns.length; i++) {
        for (j = 0; j < columns[i].length; j++) {
            var r = columns[i][j],
                rowspan = r.rowspan || 1,
                colspan = r.colspan || 1,
                index = $.inArray(false, flag[i]);

            if (colspan === 1) {
                r.fieldIndex = index;
                // when field is undefined, use index instead
                if (typeof r.field === 'undefined') {
                    r.field = index;
                }
            }

            for (k = 0; k < rowspan; k++) {
                flag[i + k][index] = true;
            }
            for (k = 0; k < colspan; k++) {
                flag[i][index + k] = true;
            }
        }
    }
};

var sprintf = function (str) {
	
	var args = arguments,
		flag = true,
		i = 1;

	str = str.replace(/%s/g, function () {
		var arg = args[i++];

		if (typeof arg === 'undefined') {
			flag = false;
			return '';
		}
		return arg;
	});
	
	return flag ? str : '';
};