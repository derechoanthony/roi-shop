/*!
 * The ROI Shop ROI Build script
 */
;(function($, window, document, undefined) {

    var defaults = {

    };

	function RoiShop(element, options) {
        this.w  = $(document);
        this.el = $(element);
        this.options = $.extend({}, defaults, options);
		this.elements = {};
        this.init();
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
	
    var processActions = function(actions) {

		$.each(actions, function(action, elements){
			elements = JSON.stringify(elements);
			eval(action + '(' + elements + ')');
		});
		
		storeRoiArray();
    };
	
	var showElements = function(elements) {
		$.each(elements, function(){
			$('[element-id="' + this + '"').roishop('toggleVisibility', 1);
		});
	};
	
	var hideElements = function(elements) {
		$.each(elements, function(){
			$('[element-id="' + this + '"').roishop('toggleVisibility', 0);
		});
	};
	
	var moveTableRow = function(elements) {

		var element = $('[element-id="' + elements[0] + '"]');
		var options = element.closest(':data(roi-element)').data('roi-element').options;
		element.find('[name="btSelectItem"]').attr('checked', true).triggerHandler('click');
 		var bootstrapTable = element.closest(':data(bootstrap.table)');
		bootstrapTable.bootstrapTable('deleteRecord');
		
		var newElement = $('[element-id="' + elements[1] + '"]');
		var newBootstrapTable = newElement.find(':data(bootstrap.table)');
		newBootstrapTable.data('bootstrap.table').options.rows.push(options);
		newBootstrapTable.bootstrapTable('reload');
	};
	
	var storeRoiArray = function(){
		var serialized 		= $('#roiContent').children(':data(roi-element)').roishop('serialize'),
			roiArray		= JSON.stringify(serialized),
			values 			= $('#roiContent').children(':data(roi-element)').roishop('getValues'),
			valueArray		= JSON.stringify(values);
		
		$.post("/assets/ajax/calculator.post.php",{
			action: 'storeRoiArray',
			roi: getQueryVariable('roi'),
			array: roiArray,
			values: valueArray
		}, function(callback){});	
	};
	
	var serializeTable = function(element){
		var $bsTable = element.find(':data(bootstrap.table)'),
			tableOpts = element.data('roi-element').options;
		
		var $rows = $bsTable.find('tbody>tr:data(roi-element)');
		var rows_ = [];
		
		$.each($rows, function(){
			var $row = $(this),
				opts = $row.data('roi-element').options;
				
			rows_.push(opts);
			var $cells = $row.find('>td');
			var cells_ =[];
			
			$.each($cells, function(){
				var $cell = $(this).data('roi-element');
				if($cell){
					cells_.push($(this).roishop('serialize')[0]);
				};
			});
			rows_[rows_.length - 1].cells = cells_;
		});
		
		tableOpts.rows = rows_;
		return tableOpts;
	};

    RoiShop.prototype = {
		
		init: function() {

			this.options.el_cell = this.options.el_letter + this.options.el_name;

			switch(this.options.el_type){
				
				case 'holder':
					this.holder();
					break;
					
				case 'input':
					this.input();
					break;
				
				case 'checkbox':
					this.checkbox();
					break;
				
				case 'text':
					this.htmltext();
					break;
					
				case 'textarea':
					this.textarea();
					break;
					
				case 'slider':
					this.slider();
					break;
					
				case 'dropdown':
					this.select();
					break;
					
				case 'button':
					this.button();
					break;
					
				case 'video':
					this.video();
					break;
					
				case 'tabholder':
					this.tabholder();
					break;
					
				case 'tabgroup':
					this.tabs();
					break;
					
				case 'tab':
					this.tabpane();
					break;
					
				case 'tblcell':
					this.tablecell();
					break;
					
				case 'tblrow':
				case 'tblheaders':
					this.tablerow();
					break;
					
				case 'table':
					this.table();
					break;
					
				case 'graph':
					this.graph();
					break;
			}
        },

		holder: function(){

			// Holder's are just divs used to organize the content. They do not need
			// any of their own divs. Just modify the div calling the function.
			this.el.attr({
				class: this.options.el_class
			});
			
			if(this.options.el_visibility == 0){
				this.el.hide();
			};
		},
		
		input: function() {
			this.$container = $([
				'<div class="form-horizontal">',
				'<div class="form-group">',
				sprintf('<label class="%s">%s</label>', this.options.el_label_class, this.options.el_text),
				sprintf('<div class="%s input-holder">', this.options.el_class),
				this.options.el_tooltip || this.options.el_append ?
					'<div class="input-group">' : '',
					sprintf('<input class="form-control' + ( this.options.el_tooltip ? ' input-addon' : '' ) + '" name="%s"%s%s>', this.options.el_field_name, this.options.el_field_name ? ' data-cell="' + this.options.el_field_name + '"' : '', this.options.el_format ? ' data-format="' + this.options.el_format + '"' : ''),
				this.options.el_append ?
					sprintf('<span class="input-group-addon right append">%s</span>', this.options.el_append) : '',
				this.options.el_tooltip || this.options.el_append ?
					'</div>' : '',
				'</div>',
				'</div>',
				'</div>'
			].join(''));
			
			this.el.append(this.$container);
			this.$form = this.$container.find('.form-horizontal');
			this.$group = this.$container.find('.form-group');
			this.$label = this.$container.find('label');
			this.$input = this.$container.find('input');
			
			if(this.options.el_enabled == 0) this.$input.prop('disabled', 'disabled');
			if(this.options.el_formula) this.$input.attr('data-formula', this.options.el_formula);
			if(this.options.el_tooltip) this.tooltip(this.$input);
			if(this.options.el_value) this.$input.val(this.options.el_value);
			
			this.$input.off('focus').on('focus', $.proxy(this.inputFocus, this));
			this.$input.off('change').on('change', $.proxy(this.inputChange, this));
			//this.$label.off('click').on('click', $.proxy(this.editLabel, this));
			
			if(this.options.el_visibility == 0){
				this.el.hide();
			};
		},
		
		inputFocus: function(){
			this.$input.select();
		},
		
		inputChange: function(){
			this.setValue(this.$input.val());
			storeRoiArray();
		},
		
		editLabel: function(){
			var $modal = $('.bs-modal');
				$modal.empty();
				$modal.append('<h1>Change Label</h1>');
				
			var input = {};
				input.el_text = 'Something Here';
				input.el_type = 'input';
			
			var elements = [];
				elements.push(input);
			console.log(elements);
			$modal.append($('<div/>').roishop(elements));
			$modal.append('<hr style="border: 0.8px solid #ccc">');
			
			var $edit = $('<button class="btn btn-small btn-success" style="margin: 7px 7px 7px 0">Edit</button>');
			var $cancel = $('<button class="btn btn-small btn-danger">Cancel</button></div>');

			$modal.append($edit).append($cancel);
		},
		
		htmltext: function(){
			this.el.append(this.options.el_text);
		},
		
		textarea: function(){
			this.$container = $([
				'<div class="form-horizontal">',
				'<div class="form-group">',
				sprintf('<label class="%s">%s</label>', this.options.el_label_class, this.options.el_text),
				sprintf('<div class="%s">', this.options.el_class),
				sprintf('<textarea style="width: 100%; resize: vertical" rows="4">%s</textarea>', this.options.el_value || ''),
				'</div>',
				'</div>',
				'</div>'
			].join(''));

			this.el.append(this.$container);
			this.$textarea = this.$container.find('textarea');

			this.$textarea.off('change').on('change', $.proxy(this.textareaChange, this));			
		},
		
		textareaChange: function(){
			this.setValue(this.$textarea.val());
			storeRoiArray();
		},		
		
		button: function(){
			
			// Create the button element
			var $button = this.el;
			
			// Add button type and class to button
			$button.addClass(this.options.el_class);
			$button.attr({type:'button'});
			
			if(this.options.el_action){
				$button.data('action', this.options.el_action);
			};
			
			if(this.options.el_called_element){
				$button.data('called-element', this.options.el_called_element);
			};
			
			// Add button html
			$button.html(this.options.el_text);
			
			$button.on('click', function(){
				
				var $button = $(this),
					bOpts = $button.closest(':data(roi-element)').data('roi-element').options;
					
				$(this).roishopActions(bOpts);
			});			
			
			this.elements.button = $button;
		},
		
		tabpane: function(){		
			
			if(this.options.el_value == 1 ){
					
				this.el.addClass('active');
			};
					
			if(this.options.children) {
				
				this.el.roiBuild({
					elements : this.options.children
				});						
			};			
		},
		
		tabs: function(){
			this.$container = $([
				'<div class="panel blank-panel">',
				'<div class="panel-heading">',
				'<div class="panel-options">',
				'<ul class="nav nav-tabs">',
				'</ul>',
				'</div>',
				'</div>',
				'<div class="panel-body">',
				'<div class="tab-content">',
				'</div>',
				'</div>',
				'</div>'
			].join(''));
			
			this.el.append(this.$container);
			this.$tabnavs = this.$container.find('.nav-tabs');
			this.$tabcontent = this.$container.find('.tab-content');

			var that = this,
				tabs = this.options.tabs;
				
			if(tabs){
				$.each(tabs, function(count, tab){
					var $listitem = $(sprintf('<li class="nav-item %s"/>', that.options.el_value ? ( that.options.el_value == count ? 'active' : '' ) : ( count == 0 ? 'active' : '' ))),
						$ahref = $(sprintf('<a class="nav-link" data-toggle="tab" href="#tab-%s">%s</a>', tab.el_id, tab.el_text));
					
					that.$tabnavs.append( $listitem.append($ahref) );
					
					var $tabpane = $(sprintf('<div class="tab-pane" id="tab-%s"/>', tab.el_id));
					
					that.$tabcontent.append($tabpane);
					$tabpane.roishop(tab);
				});				
			};
		},
		
		rebuild: function($opts){

			this.el.empty();
			this.el.removeData('roi-element');
			
			this.el.roishop($opts[0]);
		},
		
		graph: function(){
			this.$container = $([
				'<div class="graph-holder">',
				'</div>'
			].join(''));
			
			this.el.append(this.$container);

			if(this.options.highchart){
				this.options.highchart.chart = {};
				this.options.highchart.chart.renderTo = this.$container[0];
				this.$highchart = new Highcharts.Chart(this.options.highchart);				
			};
		},
		
		tablecell: function(){

			var opts = this.options,
				$cell = this.el;
				
			this.elements.cell = $cell;

			if($.isEmptyObject(this.options.children)){
				var type = this.options.choices ? 'select' : 'text';
				var choices = this.options.choices;
				var value = this.options.el_text;
				$a = $('<a/>').attr({'data-type' : type});
				$a = $a.append(opts.el_text);
						
				$cell.append($a);
				
				/* $a.editable({
					source: function(){
						var $source = [];
						
						$.each(choices, function(){
							var $value = {value: this.ch_text, text: this.ch_text};
							$source.push($value);
						});
						
						return $source;
					},
					value: value,
					success: function(response, newValue){
						var $el = $(this).closest(':data(roi-element)'),
							currentText = $el.data('roi-element').options.el_text,
							choices = $el.data('roi-element').options.choices;
						
						// Find elements containing current text
						var elements = $(':data(roi-element)').filter(function(){
							if($(this).data('roi-element').options.el_text){
								return $(this).data('roi-element').options.el_text.indexOf(currentText) !== -1	
							}
						});
						
						if($el.data('roi-element').options.el_group){
							$.each(elements, function(){
								$(this).data('roi-element').options.el_text = $(this).data('roi-element').options.el_text.replace(new RegExp(currentText, 'g'), newValue);
								$(this).roishop('redraw');
							});							
						};
						
						if(choices){
							$.each(choices, function(){
								if(this.ch_text == newValue) var actions = JSON.parse(this.ch_action);
								if(actions) processActions(actions);
							});
						};
						
						$el.data('roi-element').options.el_text = newValue;
						storeRoiArray();
					}
				});	 */					
			}

			if(this.options.children) {
				
				this.el.roiBuild({
					elements : this.options.children
				});						
			};

			if(opts.el_formula) {
				
				$cell.attr({
					'data-formula': opts.el_formula
				});
			};
			
			if(opts.el_letter && opts.el_name){
				
				var identity = opts.el_letter + opts.el_name;
				$cell.attr({
					'data-cell':identity
				});
			}
		},
		
		tablerow: function(){
			
			var opts = this.options,
				$row = this.el;

			if(this.options.el_visibility == 0){
				this.el.hide();
			};

			if(opts.cells) {
				
				$.each(opts.cells, function(i, $cell){

					$cell.index = i;
					$cell.rowIndex = opts.index;
					if(opts.el_type == 'tblheaders'){
						var $row_cell = $('<th data-sortable="true"/>');
					} else {
						var $row_cell = $('<td/>');
					};					
					$row_cell.roishop($cell);
					$row.append($row_cell);
				});
			}			
		},
		
		table: function(){
			
			var $table = $('<table/>');

			var that= this,
				rows = this.options.rows,
				rows_ = [];

			this.options.headers = $.extend(true, [], this.options.headers);
			this.options.rows = [];
				
			$.each(rows, function(){
				opts = this;
				if(this.el_type == "tblheaders"){
					that.options.headers.push(opts);
				} else {
					that.options.rows.push(opts);
				}
			});

			this.el.append($table);
			
			this.options.editable = true;
			this.options.addRecord = true;
			this.options.deleteRecord = true;
			this.options.editRecord = true;

			
			$table.RoiShopElement(this.options);
		},
		
		redraw: function(){
			
			var opts = this.options;
			this.el.empty();
			
			this.el.removeData('roiElement');
			
			this.el.roishop(opts);
		},
		
		slider: function(){
			
			// Create element's cell attribute.
			$cell = this.options.el_field_name;
			
			// If the slider is stacked label class must be adjusted
			if(this.options.el_stacked == 1){
				this.options.el_label_class = 'control-label col-lg-12';
			};
			
			// Create slider label
			$label = this.label();
			
			// Create the slider
			var $slider = $('<div/>').addClass('slider');
			
			this.elements.slider = $slider;
			
			/* Use this section to initialize slider functions */
			this.initializeSlider($slider);
			
			// Wrap slider in a holder
			if(this.options.el_stacked == 1){
				$slider = $('<div/>').addClass('input-slider col-lg-12').append($slider);
			};
			
			// Create the slider input
			var $input;
			
			if(this.options.el_stacked == 1){
				
				$input = $('<span/>').addClass('pull-right');
				
				$inputSpan = $('<span/>').addClass('slider-input').attr({
					'data-format' : this.options.el_format,
					'data-cell'   :	$cell
				});
				
				$input = $input.append($inputSpan);
				
				$slider = $('<div/>').addClass('form-group').append( $label.append($input).add($slider) );
				
				$slider = $slider.wrapAll( $('<div/>').addClass('form-horizontal') );
				
				this.el.append($slider);
			} else {
				
				$input = $('<input/>');
				this.elements.input = $input;
				
				// Add input attributes
				$input.attr({
					name			:	$cell,
					'data-cell'		:	$cell,
					'data-format'	:	this.options.el_format,
					'value'			:	this.options.el_value,
					'data-group-id'	:	this.options.el_group,
					class			:	'form-control'
				});
				
				// If the input has a formula add the data-formula attribute.
				if(this.options.el_formula){
					$input.attr('data-formula', this.options.el_formula);
				};
				
				// If the input isn't enabled add the disabled prop to the element.
				if(this.options.el_enabled == 0){
					$input.prop('disabled','disabled');
				};
				
				this.initializeSliderInput($input);
				
				$input = $('<div/>').addClass('input-group col-lg-6').append($input);
				$slider = $('<div/>').addClass('input-slider col-lg-6').append($slider);
				
				$slider = $slider.add($input);
				
				$slider = $('<div/>').addClass(this.options.el_class).append($slider);
				$group = $('<div/>').addClass('form-group').append($label);
				$slider = $group.append($slider);
				
				this.el.append($slider);
				
				$slider = $slider.wrapAll( $('<div/>').addClass('form-horizontal') );
			}
		},
		
		checkbox: function(){
			this.$container = $([
				'<div class="pretty p-icon p-toggle">',
				'<input type="checkbox"',
				this.options.el_value == 1 ? ' checked="checked">' : '>',
				'</div>'
			].join(''));
			
			this.el.append(this.$container);
			this.$checkbox = this.$container.find('input');

			var $choices = [];
			$.each(this.options.choices, function(count, choice){
				$choices.push(sprintf('<div class="%s">%s</div>', choice.ch_class, choice.ch_text));
			});
			$choices.join('');
			
			this.$container.append($choices);
			
			this.$checkbox.off('change').on('change', $.proxy(this.toggleCheckbox, this));
		},
		
		toggleCheckbox: function(){
			var that = this,
				opts = this.options,
				isChecked = this.$checkbox.is(':checked') ? 1 : 0,
				choices = opts.choices;
				
			var actions = JSON.parse(choices[isChecked].ch_action);
			if(actions){
				processActions(actions);
			};
			
			this.setValue(isChecked);
			storeRoiArray();
		},
		
		select: function(){
			var that = this;
			this.$container = $([
				'<div class="form-horizontal">',
				'<div class="form-group">',
				sprintf('<label class="%s">%s</label>', this.options.el_label_class, this.options.el_text),
				sprintf('<div class="%s">', this.options.el_class),
					'<select data-cell="' + this.options.el_field_name + '" class="form-control">',
				'</div>',
				'</div>',
				'</div>'
			].join(''));
			
			this.el.append(this.$container);
			this.$form = this.$container.find('.form-horizontal');
			this.$group = this.$container.find('.form-group');
			this.$label = this.$container.find('label');
			this.$select = this.$container.find('select');		

			if(this.options.choices){
				var choices = this.options.choices,
					options = [];
					
				$.each(choices, function(count, choice){
					options.push(sprintf('<option value="%s"%s>%s</option>', choice.ch_value ? choice.ch_value : choice.ch_text, ( that.options.selectedIndex == count ? ' selected="selected"' : '' ), choice.ch_text));
				});
				options = options.join('');
				
				this.$select.append($(options));
			};
			
			this.$select.chosen({width: '100%', disable_search_threshold: 10});
			
			this.$select.off('change').on('change', $.proxy(this.onSelectChange, this));			
		},
		
		onSelectChange: function(){
			var that = this,
			$select = this.$select,
			selectedIndex = $select.prop('selectedIndex');

			var selectedOption = this.options.choices[selectedIndex];
			this.options.el_value = selectedOption.el_text;
			this.options.selectedIndex = selectedIndex;
			
			$('#wrapper').calx('getCell', this.options.el_field_name).setValue(selectedOption.ch_value).calculateAllDependents();

			var show_elements = selectedOption.ch_show.split(',');
			var hide_elements = selectedOption.ch_hide.split(',');
				
			$.each(show_elements, function(count, element){
				$('[element-id="' + element + '"]').roishop('toggleVisibility', 1);
			});
				
			$.each(hide_elements, function(count, element){
				$('[element-id="' + element + '"]').roishop('toggleVisibility', 0);
			});				

			storeRoiArray();
		},
		
		video: function(){
			this.$container = $([
				'<div class="player">',
				sprintf('<a class="popup-iframe" href="%s"></a>', this.options.el_src),
				sprintf('<iframe style="margin-left: 5px;" width="425" height="239" src="%s" frameborder="0"/>', this.options.el_src),
				'</div>'
			].join(''));
			
			this.el.append(this.$container);
			this.$container.fitVids();
		},
		
		tooltip: function($el){
			this.$tooltip = $([
				sprintf('<span class="input-group-addon input right helper %s">', this.options.el_enabled == 1 ? '' : 'output'),
				sprintf('<i class="fa fa-question-circle tooltipstered" data-placement="right" title="%s"/>', this.options.el_tooltip),
				'</span>'
			].join(''));
			
			$el.after(this.$tooltip);
			
			this.$tooltip.find('.tooltipstered').tooltipster({
				theme: 'tooltipster-light',
				maxWidth: 300,
				animation: 'grow',
				position: 'right',
				arrow: false,
				interactive: true,
				contentAsHTML: true				
			});
		},
		
		label: function(){
			
			// Create the label element
			$label = $('<label/>');
			
			// Add the html to the label
			$label.html(this.options.el_text);
			
			// Add label class to the label
			$label.addClass(this.options.el_label_class);
			
			// Store the label in the elements array
			this.elements.label = $label;
			
			return $label;
		},
		
		initializeSlider: function($el){

			noUiSlider.create($el[0],{
				start: 0,
				connect: "lower",
				step: parseInt(this.options.el_step),
				range: {
					"min":  parseInt(this.options.el_min),
					"max":  parseInt(this.options.el_max)
				},
				format: {
					to: function ( value ) { return value; },
					from: function ( value ) { return value; }
				}				
			});
			
			var onSlideMove = function( $value ){
console.log($value);
				var $this     = $(this.target),
					$parent   = $this.closest(':data(roi-element)'),
					$plugin   = $parent.data('roi-element'),
					$opts     = $plugin.options,
					$elements = $plugin.elements;

				var $cell  = $opts.el_cell;
					$input = $elements.input;
				
				$parent.roishop('setValue', $value);

				$(':data(roi-element)').not($parent).each(function(){
					
					var $opts 	 = $(this).data('roi-element').options;
					var $el_cell = $opts.el_cell;
					
					if($el_cell == $cell){
						$opts.el_value = $value;
					};
				});
				
				$('#wrapper').calx('getCell', $cell).calculateAllDependents();
			};
			
			$el[0].noUiSlider.on('slide', onSlideMove);
		},
		
		initializeSliderInput: function($el){
			
			var onInputFocus = function(e){
				this.select();
			};
			
			$el.on('focus', onInputFocus);			
		},
		
		toggleVisibility: function(state){
			this.options.el_visibility = state;
			var id = this.options.el_id;
			if(this.options.el_type == 'tblrow'){
				var table = $(this.el).closest(':data(bootstrap.table)').data('bootstrap.table');
				if(table.options.rows){
					$.each(table.options.rows, function(){
						if(this.el_id == id) this.el_visibility = state;
					});
				};
			};
			if(state == 1) this.el.show();
			if(state == 0) this.el.hide();
		},
		
		setValue: function($value){

			var $plugin   = $(this)[0],
				$opts     = $plugin.options,
				$elements = $plugin.elements;

			// Set the value of the plugin
			$opts.el_value = $value;
			
			// Loop through each element of this function and set the value to the new value.
			$.each($elements, function(){
				
				$(this).val($value);
				
				// Check to see if this element has a data-cell attribute. If so, then set
				// value using the calx method.
				/* var $calxCell = $(this).data('cell');
				if($calxCell){
					$('#wrapper').calx('getCell',$calxCell).setValue($opts.el_value).calculate();
				}; */
			});
		},
		
		updateLabel: function($html){
			
			var $plugin   = $(this)[0],
				$opts     = $plugin.options,
				$elements = $plugin.elements;
				
			$opts.el_text = $html;
			
			$elements.label.replaceWith(this.label());
		},
		
		getValues: function(){
			
			var $plugin    = $(this)[0],
				$element   = $plugin.el,
				values	   = [];
				
			var $elements = $element.find(':data(roi-element)');
			$elements.each(function(){
				
				var $this = $(this),
					opts = $this.data('roi-element').options;
					
				values.push(opts);				
			});

			return values;
		},
		
		serialize: function(){

			var $plugin    = $(this)[0],
				$element   = $plugin.el,
				$structure = [];

			if($plugin.options.el_type == "table"){
				$structure.push(serializeTable($(this)));
			} else {
				$structure.push($plugin.options);
			}

			step = function(level) {

				var $childArray 	= 	[],
					$elements 		= 	level.children(':data(roi-element)');

				$elements.each(function() {

					var $child		= $(this),
						$options	= $child.data('roi-element').options,
						$subChild  	= $child.children(':data(roi-element)');
						
					if($options.el_type == "tabgroup"){
						$child		=	$(this).children('.panel').children('.panel-body').children('.tab-content');
						$options.tabs = step($child);
					};

					if ($subChild.length) {
						$options.children = step($child);
					};

					if($options.el_type == "table"){
						$childArray.push(serializeTable($child));
					} else {
						$childArray.push($options);
					};
				});

				return $childArray;
			};

			var $children = $element.children(':data(roi-element)');
			if($children){
				$structure[0].children = step($element);
			};
			
			if($plugin.options.el_type == "tabgroup"){
				$child		=	$(this).children('.panel').children('.panel-body').children('.tab-content');
				$options.tabs = step($child);				
			};
	
			return $structure;			
		},
		
		serialise: function(){
			
			return this.serialize();
		},
		
		getOptions: function(){		
			
			return this.options;
		}
		
	}

    $.fn.roishop = function(params) {
        var inputs  = this,
            retval = this,
			args = arguments;

        inputs.each(function() {
			
            var plugin = $(this).data("roi-element");

            if (!plugin) {
                $(this).data("roi-element", new RoiShop(this, params));
                $(this).data("roi-element-id", params.el_id);
				$(this).attr('element-id',params.el_id);
            } else {
                if (typeof params === 'string' && typeof plugin[params] === 'function') {
                    retval = plugin[params]( Array.prototype.slice.call( args, 1 ) );
                }
            }
        });

        return retval || inputs;
    };

})(window.jQuery || window.Zepto, window, document);
