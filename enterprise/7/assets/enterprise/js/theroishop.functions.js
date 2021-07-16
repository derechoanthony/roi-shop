;(function($, window, document, undefined) {

	var sideNavigation = function(RSCalc){
		var sideNav = this,
			options = RSCalc.options,
			$container = sideNav.$container = $('<nav class="navbar-default navbar-static-side" role="navigation"></nav>');

		sideNav.rsCalculator = RSCalc;

		RSCalc.el.append($container);

		var $navigation = $([
			'<div class="sidebar-collapse sidebar-navigation" style="overflow: hidden; width: auto; height: 100%;">',
				'<ul class="nav" id="side-menu">',
					'<li class="nav-header">',
						'<div class="dropdown profile-element">',
							'<span>',
								sprintf('<img id="company_logo" class="some-button" alt="image" src="../../company_specific_files/%s/logo/logo.png">', options.compSpecs.compID),
							'</span>',
						'</div>',
					'</li>',	
				'</ul>',
			'</div>'		
		].join(''));
		
		sideNav.$sideMenu = $navigation.find('#side-menu');

		if(options.verification > 1 && options.discovery.length > 0) {
			var $discovery = $([
				'<li id="discovery" class="smooth-scroll">',
					'<a href="index.html">',
						'<i class="fa fa-binoculars"></i>',
						'<span class="nav-label">Discovery Document</span>',
						'<span class="fa arrow"></span>',
					'</a>',
					'<ul class="nav nav-second-level collapse in">',
					'</ul>',
				'</li>'		
			].join(''));

			$.each(options.discovery, function(){
				var $discoveryNav = $('<li><a href="#disc_' +  this.id + '" class="discovery-document section-navigator">' + this.title + '</a></li>');

				$discovery.find('ul').append($discoveryNav);
			});

			sideNav.$sideMenu.append($discovery);
		}

		var $sections = $([
			'<li id="sections" class="smooth-scroll">',
				'<a href="#"><i class="fa fa-calculator"></i> <span class="nav-label">ROI Sections</span><span class="fa arrow"></span></a>',
				'<ul class="nav nav-second-level collapse in">',
					'<li>',
						'<a href="#dash" class="roi-section section-navigator"> Dashboard</a>',
					'</li>',
				'</ul>',
			'</li>'
		].join(''));

		$.each(options.sections, function(){
			if(this.visible == 1){
				var $sectionNav = $('<li><a href="#section' + this.ID + '"class="roi-section section-navigator">' +  this.Title + '</a></li>');

				$sections.find('ul').append($sectionNav);
			};
		});		

		sideNav.$sideMenu.append($sections);

		if(options.pdfs.length){
			var $pdf = ['<li id="pdf" class="smooth-scroll">',
							'<a href="#"><i class="fa fa-file-pdf-o"></i> <span class="nav-label">Your PDFs</span> <span class="fa arrow"></span></a>',
							'<ul class="nav nav-second-level collapse in">',
							'</ul>',
						'</li>'];

			$pdf = $($pdf.join(''));

			$.each(options.pdfs, function(){
				var pdf = this;

				$pdf_template = $('<li><a class="create-pdf" data-pdf-id="' + this.pdf_template + '">' + this.pdf_name + '</a></li>');
				$pdf.find('ul').append($pdf_template);

				$pdf_template.off('click').on('click', function(){

					RSCalc.options.renderingGraphs = true;
					RSCalc.renderGraphsToImage();
					
					setTimeout(function(){
						$.ajax({
							type: "GET",
							url: "/enterprise/7/assets/ajax/calculator.get.php",
							data: {
								action: "createpdf",
								roi: getQueryVariable('roi'),
								reportId: pdf.pdf_template,
								roiPath: window.location.href + '&v=' + RSCalc.options.specs.verification_code
							},
							success: function(returned){
								$('<a href="/webapps/assets/customwb/10016/pdf/preview-preview2.pdf" download>')[0].click();
							},
							error: function(error){
								
							}
						});
					}, 1000);
				});
			});

			sideNav.$sideMenu.append($pdf);
		};

		if(options.verification > 1){
			sideNav.$sideMenu.append($('<li><a href="../../dashboard"><i class="fa fa-globe"></i> <span class="nav-label">My ROIs</span><span class="label label-info pull-right">' + options.rois.length + '</span></a></li>'));
		}

		$container.append($navigation);		
	}

	var topNavigation = function(RSCalc){

		var topNav = this,
			options = RSCalc.options,
			$container = this.$container = $('<div class="row bottom-border"></div>');

		topNav.rsCalculator = RSCalc;

		RSCalc.el.append(RSCalc.$container);
		RSCalc.$container.append($container);

		var allowedActions = [];
		if(options.verification > 1){
			allowedActions.push('<li><a class="showVerificationLink">Show Verification Link</a></li>');
			allowedActions.push('<li><a class="resetVerification">Reset Verification Link</a></li>');
			allowedActions.push('<li><a class="showHideSections">Show/Hide Sections</a></li>');
			allowedActions.push('<li><a class="changeCurrency">Change ROI Currency</a></li>');
		}

		if(options.compSpecs.sf_integration > 0 && options.verification > 1){
			if(options.integration){
				allowedActions.push('<li><a class="sfIntegration">Salesforce Integration</a></li>');
			} else {
				allowedActions.push('<li><a href="/dashboard/account.php">Setup Your Salesforce Connection</li>');
			}
		}
		
		if(options.verification > 1){
			allowedActions.push('<li class="divider"></li><li><a href="/dashboard/account.php"><i class="fa fa-user"></i> &nbsp; &nbsp;  View Your Profile</a></li>');
			allowedActions.push('<li><a href="../../assets/logout.php"><i class="fa fa-power-off"></i> &nbsp; &nbsp; Log Out</a></li>');
		};

		var $navigation = $([
			'<nav class="navbar navbar-fixed-top" role="navigation" style="margin-bottom: 0; left: 220px;">',
				'<div class="navbar-header">',
					sprintf('<h3>%s</h3>', options.specs.roi_title),
				'</div>',
				'<ul class="nav navbar-top-links navbar-right">',
					'<li>',
						'<span class="m-r-sm text-muted welcome-message">Powered by <a href="https:\\www.theroishop.com" style="padding-left: 0;">The ROI Shop</a></span>',
					'</li>',
					'<li class="dropdown myactions-dropdown">',
					'</li>',
					'<li>',
						'<a href="../../assets/logout.php">',
							'<i class="fa fa-sign-out"></i> Log Out',
						'</a>',
					'</li>',
				'</ul>',
			'</nav>'
		].join(''));

		$container.append($navigation);

		var $dropdownAlerts = $container.find('.myactions-dropdown');
		if (allowedActions.length){
			var $myActionsDropdown = [];
			$myActionsDropdown.push(
				'<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">',
					'My Actions <i class="fa fa-caret-down"></i>',
				'</a>',
				'<ul class="dropdown-menu dropdown-alerts">');
				
			$.each(allowedActions, function(){
				$myActionsDropdown.push(this);
			});
			
			$myActionsDropdown.push('</ul>');
			$dropdownAlerts.append($($myActionsDropdown.join('')));
		}

		$container.find('.showVerificationLink').off('click').on('click', function(){ RSCalc.showVerificationLink(); });
		$container.find('.resetVerification').off('click').on('click', function(){ RSCalc.resetVerificationLink(); });
		$container.find('.showHideSections').off('click').on('click', function(){ RSCalc.showHideSections(); });
		$container.find('.changeCurrency').off('click').on('click', function(){ RSCalc.changeCurrency(); });
		$container.find('.addContributor').off('click').on('click', function(){ RSCalc.addContributor(); });
		$container.find('.viewAllowedUsers').off('click').on('click', function(){ RSCalc.viewAllowedUsers(); });
		$container.find('.sfIntegration').off('click').on('click', function(){ RSCalc.sfIntegration(); });
	};

	var Discovery = function(options, RSCalc){
		var discovery = this
		discovery.options = {
			inForm: true
		};
		
		discovery.options = $.extend(discovery.options, options);
		discovery.$container = $('<div id="disc_' + discovery.options.id + '" class="discovery-page" style="display: none;"></div>');

		RSCalc.$container.append(discovery.$container);
		discovery.rsCalculator = RSCalc;

		this.init();
	};

	Discovery.fx = Discovery.prototype;

	Discovery.fx.init = function(){
		var discovery = this;

		discovery.build();
	}

	Discovery.fx.build = function(){
		var discovery = this,
			options = discovery.options,
			RSCalc = discovery.rsCalculator;

		var $discovery = $(['<div class="row border-bottom white-bg dashboard-header">',		
								'<div class="col-lg-12">',
									'<h1 style="margin-bottom: 20px; width: 100%;">' + options.title + '</h1>',
								'</div>',
							'</div>',
							'<div class="row border-bottom gray-bg dashboard-header">',
								'<div class="col-md-12 col-sm-12 col-xs-12">',
									'<div class="ibox float-e-margins">',
										'<div class="ibox-title">',
											'<h5>' + options.title + '</h5>',
										'</div>',
										'<div class="ibox-content">',
											'<form class="form-horizontal">',
											'</form>',
										'</div>',
									'</div>',
								'</div>',
							'</div>'
						].join(''));

		if(RSCalc.options.integration){
			$discovery.find('h1').append('<small class="pull-right">This ROI is currently linked to: <span class="sfdc-link text-info" data-sfdc-link="' +  RSCalc.options.specs.sfdc_link + '">' + ( RSCalc.options.specs.linked_title ? RSCalc.options.specs.linked_title : 'Click Here to Link' ) + '</span></small>')
		}

		discovery.$elements = $discovery.find('form.form-horizontal');
		$.each(options.elements, function(){
			if (this.precision) {
				var decimals = this.precision;
				this.precision = '.';
				for(var i=0; i < decimals; i++) {
					this.precision += '0';
				}
			}

			if(this.Type != 13){
				switch(this.Format){
					case '0': this.Format = '0,0' + ( this.precision ? this.precision : '' ); break;
					case '1': this.Format = '$0,0' + ( this.precision ? this.precision : '' ); break;
					case '2': this.Format = '0,0' + ( this.precision ? this.precision : '' ) + '%'; break;
					case '3': this.Format = ''; break;				
				}
			};

			for( $yr=0; $yr <= (RSCalc.options.compSpecs.retPeriod - 1) * (this.annual ? this.annual : 0); $yr++ ) {
				this.field_name = "disc_" + this.ID;
				this.cell_name = "A" + this.ID;
				this.$container = discovery.$elements;
				this.yr = $yr + 1;

				switch(this.Type){
					case '0':
					case '1':
						new Input(this, RSCalc);
						break;

					case '2':
						new Textarea(this, RSCalc);
						break;

					case '3':
						new Dropdown(this, RSCalc);
						break;

					case '11':
						new Slider(this, RSCalc);
						break;

					case '13':
						new Header(this, RSCalc);
						break;

					case 'text':
						new Htmltext(this, RSCalc);
						break;
				};
			};
		});

		discovery.$container.append($discovery);

		if(RSCalc.options.compSpecs.sf_integration){
			if(RSCalc.options.integration.code){
				var $integration = $([
					'<div class="text-center">',
						sprintf('<button type="button" class="btn btn-primary export-salesforce" data-instance-link="%s">Export to Salesforce</button>', (RSCalc.options.specs.instance ? RSCalc.options.specs.instance : '')),
						'<button type="button" class="btn btn-primary">Import from Salesforce</button>',
					'</div>'
				].join(''));
			} else {
				var $integration = $([
					'<div class="text-center">',
						'<button type="button" class="btn btn-primary" onclick="setupSalesforceConnection()"">Setup Salesforce Integration</button>',
					'</div>'				
				].join(''));
			}

			discovery.$container.append($integration);
		}
	};

	var Holder = function(options, RSCalc){
		var holder = this;
		holder.options = {};

		holder.options = $.extend(holder.options, options);
		if(options.options) holder.options = $.extend(true, holder.options, JSON.parse(options.options));

		holder.$container = $(sprintf('<div%s></div>', holder.options.class ? sprintf(' class="%s"', holder.options.class) : ''));
		holder.options.$container.append(holder.$container);

		if (holder.options.data){
			$.each(holder.options.data, function(){
				holder.$container.attr('data-' + this.attribute, this.value);
			});
		}

		if (holder.options.children && holder.options.children.length > 0) {
			$.each(holder.options.children, function(i, options){
				options.$container = holder.$container
				RSCalc.renderElement(options);
			})
		}
	}

	var Htmltext = function(options, RSCalc){
		var text = this;
		text.options = {};

		text.options = $.extend(text.options, options);
		if(text.options.options){
			text.options = $.extend(true, text.options, JSON.parse(text.options.options));
		}

		text.options.$container.append(text.options.text);
	}

	var Input = function(options, RSCalc){
		var input = this;
		input.options = {
			inForm: true
		};

		input.options = $.extend(input.options, options);
		if(options.options) input.options = $.extend(true, input.options, JSON.parse(options.options));
		input.$container = $('<div/>');
		input.rsCalculator = RSCalc;

		options.$container.append(input.$container);

		this.init();
	}

	Input.fx = Input.prototype;

	Input.fx.init = function(){
		var input = this;
		RSCalc.elements.inputs.push(input);

		if(input.options.cell_name){
			if(!RSCalc.elements[input.options.cell_name]) RSCalc.elements[input.options.cell_name] = [];
			RSCalc.elements[input.options.cell_name].push(input);
		}

		input.build();
	}

	Input.fx.build = function(){
		var input = this,
			options = input.options;

		input.$container.attr('id', options.ID);
		input.$container.data('options', input);
		
		if(options.inForm){
			input.$container.addClass('form-horizontal');
		};

		var $container = $([
			'<div class="form-group">',
			sprintf('<label class="%s">%s</label>', 'col-lg-7 col-md-7 col-sm-7', options.Title + ( options.cost == 1 ? ' - Year ' + options.yr : '' ) ),
			sprintf('<div class="%s input-holder">', 'col-lg-5 col-md-5 col-sm-5'),
			options.prepend || options.append || options.Tip || options.Type == 1 ?
				'<div class="input-group">' : '',
				sprintf('<input class="form-control' + ( options.Tip || options.formula ? ' input-addon' : '' ) + '" %s%s%s%s>', options.cost == 1 ? ' data-cost-yr="' + options.yr + '"' : '' , options.ID ? ' name="' + options.ID + '"' : '', options.ID ? ' data-cell="' + ( options.cell_name ? options.cell_name : options.field_name ) + '"' : '', options.Format ? ' data-format="' + options.Format + '"' : ''),
			options.append ?
				sprintf('<span class="input-group-addon right append">%s</span>', options.append) : '',
			options.Tip || options.append || options.formula ?
				'</div>' : '',
			'</div>',
			'</div>'
		].join(''));

		input.$container.append($container);
		input.$input = input.$container.find('input');
		input.$calx = input.$container.find('[data-cell]');

		if(options.data){
			for(var i in options.data){
				var data_id = Object.keys(options.data[i]);
				var data_value = options.data[i][data_id];

				input.$input.attr('data-' + data_id, data_value);
			}
		}

		if(options.Type == 1 || options.disabled == true) input.$input.prop('disabled', 'disabled');
		if(options.formula) input.$input.attr('data-formula', '( ' + options.formula + ' )');

		if(options.Type == 1 || options.Tip){
			input.$tooltip = new Tooltip(options, input);
			input.$container.find('.input-group').append(input.$tooltip.$tooltip);
		}
	};

	var Tooltip = function(options, element){
		var tooltip = this,
			theme = {
				theme: 'tooltipster-light',
				maxWidth: 300,
				animation: 'grow',
				position: 'right',
				arrow: false,
				interactive: true,
				contentAsHTML: true	
			}

		$.extend(theme, options.theme);

		tooltip.options = options;
		tooltip.options.theme = theme;
		tooltip.parent = element;

		tooltip.init();
	}

	Tooltip.fx = Tooltip.prototype;

	Tooltip.fx.init = function(){
		var tooltip = this;

		tooltip.build();
	}

	Tooltip.fx.build = function(){
		var tooltip = this,
			options = tooltip.options,
			html = [];

		html.push(
			sprintf('<span class="input-group-addon right helper%s">', options.Type != 1 ? ' input' : ''));

		if (options.Type == 1){
			html.push('<i class="fa fa-calculator calculator-popup" data-placement="right" title="Click here to view the calculation breakdown" calculation/>');
		}

		if (options.Tip){
			html.push(
				sprintf('<i style="margin-left: 5px;" class="fa fa-question-circle tooltipstered" data-placement="right" title="%s"/>', options.Tip));
		}
		
		html.push('<span/>');
		tooltip.$tooltip = $(html.join(''));

		var calculationBreakdown = function(){
			var element = this.parent,
				cell = element.options.cell_name;

			RSCalc.showCalculationModal(cell);
		}
		
		tooltip.$calculator = tooltip.$tooltip.find('[calculation]');
		tooltip.$calculator.off('click').on('click', $.proxy(calculationBreakdown, tooltip));
	};

	var Textarea = function(options, RSCalc){
		var textarea = this;
		textarea.options = {
			inForm: true
		};
		
		textarea.options = $.extend(textarea.options, options);
		textarea.$container = $('<div/>');

		options.$container.append(textarea.$container);

		this.init();
	};

	Textarea.fx = Textarea.prototype;

	Textarea.fx.init = function(){
		var textarea = this;
		RSCalc.elements.textarea.push(textarea);

		if(textarea.options.cell_name){
			if(!RSCalc.elements[textarea.options.cell_name]) RSCalc.elements[textarea.options.cell_name] = [];
			RSCalc.elements[textarea.options.cell_name].push(textarea);
		}

		textarea.build();
	};

	Textarea.fx.build = function(){
		var textarea = this,
			options = textarea.options;

		textarea.$container.attr('id', options.ID);
		textarea.$container.data('options', textarea);

		var $container = $([
			'<div class="form-group">',
				sprintf('<label class="control-label col-lg-7 col-md-7 col-sm-7">%s</label>', options.Title),
				'<div class="col-lg-5 col-md-5 col-sm-5">',
					sprintf('<div class="textarea-input"><textarea data-element-type="textarea" class="form-control" name="%s" style="width: 100%; resize: vertical;" rows="%s"></textarea></div>', options.field_name, options.choices),
				'</div>',
			'</div>'
		].join(''));

		textarea.$container.append($container);
		textarea.$textarea = $container.find('textarea');

		textarea.$textarea.on('blur', function(){
			options.value = textarea.$textarea.val();
			RSCalc.storeValues();
		});
	};
	
	var Dropdown = function(options, RSCalc){
		var dropdown = this;
		dropdown.options = {
			inForm: true
		};

		dropdown.RSCalc = RSCalc;
		
		dropdown.options = $.extend(dropdown.options, options);
		if(dropdown.options) dropdown.options = $.extend(true, dropdown.options, JSON.parse(options.options));
		dropdown.$container = $('<div/>');

		options.$container.append(dropdown.$container);

		this.init();
	};

	Dropdown.fx = Dropdown.prototype;

	Dropdown.fx.init = function(){
		var dropdown = this;
		RSCalc.elements.selects.push(dropdown);

		if(dropdown.options.cell_name){
			if(!RSCalc.elements[dropdown.options.cell_name]) RSCalc.elements[dropdown.options.cell_name] = [];
			RSCalc.elements[dropdown.options.cell_name].push(dropdown);
		}

		dropdown.build();
	};

	Dropdown.fx.build = function(){
		var dropdown = this,
			options = dropdown.options;

		dropdown.$container.attr('id', options.ID);
		dropdown.$container.data('options', dropdown);

		var $container = $([
			'<div class="form-group">',
				sprintf('<label class="control-label col-lg-7 col-md-7 col-sm-7">%s</label>', options.Title),
				'<div class="col-lg-5 col-md-5 col-sm-5">',
					sprintf('<select data-cell="%s" class="form-control chosen-selector" name="%s">', options.cell_name, options.ID),
					'</select>',
				'</div>',
			'</div>'
		].join(''));

		dropdown.$select = $container.find('select');
		dropdown.$calx = $container.find('[data-cell]');

		var selectValue = 1;
		if(options.choices){
			$.each(options.choices, function(){
				dropdown.$select.append(sprintf('<option data-choice-id="%s" value="%s">%s</option>', this.id, this.val ? this.val : selectValue, this.text ? this.text : this.value));
				selectValue++;
			});
				
		};

		dropdown.$select.chosen({
			width: '100%',
			disable_search_threshold: 10
		});

		dropdown.$container.append($container);
		dropdown.$container.find('.form-group').data('options', dropdown);
		
		dropdown.$select.on('change', function(){
			var cell = $(RSCalc.options.calcHolder).calx('getCell', $(dropdown.$select).data('cell')),
				$select = $(this);

			cell.value = $select.val();
			cell.formattedValue = $select.find('option:selected').text();

			RSCalc.storeValues();
			RSCalc.toggleVisibility(dropdown);
		});
	};

	var Checkbox = function(options, RSCalc){
		var checkbox = this;

		checkbox.options = {};
		checkbox.options = $.extend(checkbox.options, options);
		if(options.options) checkbox.options = $.extend(true, checkbox.options, JSON.parse(options.options));

		checkbox.$container = $(sprintf('<div id="%s" class="form-horizontal"></div>', options.ID));
		checkbox.$container.data('options', options);
		options.$container.append(checkbox.$container);

		this.init();
	}

	Checkbox.fx = Checkbox.prototype;

	Checkbox.fx.init = function(){
		var checkbox = this;
		RSCalc.elements.checkbox.push(checkbox);

		if(checkbox.options.cell_name){
			if(!RSCalc.elements[checkbox.options.cell_name]) RSCalc.elements[checkbox.options.cell_name] = [];
			RSCalc.elements[checkbox.options.cell_name].push(checkbox);
		}

		checkbox.build();	
	}

	Checkbox.fx.build = function(){
		var checkbox = this,
			options = checkbox.options,
			values;
		
		$.each(RSCalc.options.values[0], function(){
			if(this.address == options.cell_name) values = this.value;
		});

		var $container = $([
			'<div class="form-group">',
				sprintf('<label class="control-label %s">%s</label>', options.label_class ? options.label_class : 'col-lg-7', options.Title),
				sprintf('<div class="%s checkbox-options">', options.class ? options.class : 'col-lg-5'),
				'</div>',
			'</div>'
		].join(''));

		checkbox.$container.append($container);

		checkbox.$checkbox = $container.find('.checkbox-options');
		checkbox.$calx = $container.find('[data-cell]');

		if(options.choices){
			$.each(options.choices, function(i, choice){
				checkbox.$checkbox.append(sprintf('<div class="checkbox i-checks"><label><input type="checkbox" value="%s"%s><i></i> %s</label></div>', choice.value, values && values[i] == '1' ? ' checked="checked"' : '', choice.text));
			});
		};

		checkbox.$container.find('.i-checks').iCheck({
			checkboxClass: 'icheckbox_square-green',
			radioClass: 'iradio_square-green',
		}).on('ifToggled', function(){
			var chk = checkbox.$checkbox;
			var chk_opts = chk.find('input');

			var value = [];
			$.each(chk_opts, function(){
				if($(this).is(':checked')) {
					value.push(1);
				} else { value.push(0) }
			});

			checkbox.options.value = value;
			RSCalc.storeValues();
		});
	}

	var Slider = function(options, RSCalc){
		var slider = this;
		slider.options = {
			inForm: true
		};
		
		slider.options = $.extend(slider.options, options);
		slider.$container = $(sprintf('<div id="%s" class="form-group"><div/>', options.ID));
		slider.$container.data('options', slider);

		options.$container.append(slider.$container);

		this.init();
	};

	Slider.fx = Slider.prototype;

	Slider.fx.init = function(){
		var slider = this;
		RSCalc.elements.sliders.push(slider);

		if(slider.options.cell_name){
			if(!RSCalc.elements[slider.options.cell_name]) RSCalc.elements[slider.options.cell_name] = [];
			RSCalc.elements[slider.options.cell_name].push(slider);
		}

		slider.build();
	};

	Slider.fx.build = function(){
		var slider = this,
			options = slider.options;

		var $container = $([
			sprintf('<label class="control-label col-lg-7 col-md-7 col-sm-7">%s</label>', options.Title),
			'<div class="col-lg-5 col-md-5 col-sm-5 element-slider">',
				'<div class="row">',
					'<div class="col-lg-6 input-slider">',
						'<div id="drag-fixed" class="slider slider_red"></div>',
					'</div>',
					'<div class="col-lg-6">',
					options.prepend || options.append || options.Tip || options.Type == 1 ?
					'<div class="input-group">' : '',
						sprintf('<input class="slider-input form-control' + ( options.Tip || options.formula ? ' input-addon' : '' ) + '" %s%s%s>', options.ID ? ' name="' + options.ID + '"' : '', options.ID ? ' data-cell="' + options.field_name + '"' : '', options.Format ? ' data-format="' + options.Format + '"' : ''),
					options.append ?
						sprintf('<span class="input-group-addon right append">%s</span>', options.append) : '',
					options.Tip || options.append || options.formula ?
						'</div>' : '',
					'</div>',
				'</div>',
			'</div>'
		].join(''));

		if(options.Type == 1 || options.Tip){
			slider.$tooltip = new Tooltip(options, slider);
			$container.find('.input-group').append(slider.$tooltip.$tooltip);
		}

		slider.$slider = $container.find('.slider');
		slider.$input = $container.find('input');
		slider.$calx = $container.find('[data-cell]');

		slider.$container.append($container);
	};

	var Header = function(options, RSCalc){
		var header = this;
		header.options = {
			inForm: true
		};
		
		header.options = $.extend(header.options, options);

		header.$container = $([
			sprintf('<div id="%s" class="form-group">',  options.ID),,
				sprintf('<div class="col-md-12 col-lg-11 subsection-header"%s>', (header.options.Format == 3 ? ' style="border:none;"' : '' )),
					sprintf('<h5>%s</h5>', header.options.Title),
				'</div>',
			'</div>'
		].join(''));

		options.$container.append(header.$container);
	};

	var SavingsTable = function(options, $container){
		var $savingsTable = $([
			'<div class="ibox-content">',
				'<div class="table-responsive" style="border: 2px solid #ddd;">',
					'<table id="summary-table" class="table table-hover" style="margin-bottom: 0;">',
					'</table>',
				'</div>',
			'</div>'
		].join(''));

		$table = [];
		$table.push('<thead><tr><th></th>');

		for(var i=1; i <= options.compSpecs.retPeriod; i++){
			$table.push(sprintf('<th>Year %s</th>', i));
		}

		$table.push('<th>Total</th></tr></thead><tbody>');

		$.each(options.sections, function(i){
			if(this.visible == 1 && this.formula){
				$table.push(sprintf('<tr class="value-holder" data-section-name="%s">', this.Title));
				$table.push(sprintf('<th class="section-navigation"><a class="section-navigator smooth-scroll table-scroll" href="#section%s">%s</a></th>', this.ID, this.Title));

				for(var yr=1; yr <= options.compSpecs.retPeriod; yr++){
					$table.push(sprintf('<td class="section-total" data-section-id="%s" data-cell="SECTOT%s%s" data-format="($0,0)" data-formula="SECTIONTOTAL( %s, %s, %s, true )"></td>', this.ID, String.fromCharCode(64 + yr), i + 1, this.formula, yr, this.ID));
				};
				
				$table.push(sprintf('<td class="section-total" data-section-id="%s" data-cell="SECTOT%s" data-format="($0,0)" data-formula="SECTIONTOTAL(%s, \'total\', %s, true)"> 0</td>', this.ID, i, this.formula, this.ID));
				$table.push('</tr>');
			};
		});

		$table.push('<tr class="value-holder" data-section-name="Cost"><th class="class-row">Cost</th>');

		for(var yr=1; yr <= options.compSpecs.retPeriod; yr++){
			$table.push(sprintf('<td class="cost txt-removed" data-cell="COST%s" data-format="($0,0)" data-formula="ANNUALCOST(%s)"></td>', yr, yr));
		};		

		$table.push('<td class="cost txt-removed" data-cell="COSTTOT1" data-format="($0,0)" data-formula="ANNUALCOST(\'total\')"></td></tr><tr class="value-holder" data-section-name="Total"><th class="annual-total-row">Total</th>');

		for(var yr=1; yr <= options.compSpecs.retPeriod; yr++){
			$table.push(sprintf('<td class="annual-total" data-cell="ANNTOT%s" data-formula="SUM( SECTOT%s1:SECTOT%s%s ) + COST%s" data-format="($0,0)">$0</td>', yr, String.fromCharCode(64 + yr), String.fromCharCode(64 + yr), options.sections.length, yr));
		};

		$table.push(sprintf('<td class="annual-total" data-cell="GRANDTOTAL1" data-formula="SUM( ANNTOT1:ANNTOT%s )" data-format="($0,0)">$0</td></tr>', options.compSpecs.retPeriod));

		$table.push('</tbody>');
		$savingsTable.find('#summary-table').append($($table.join('')));

		$container.append($savingsTable);
	};

	var Dashboard = function(RSCalc){
		var dashboard = this,
			options = RSCalc.options;

		var $container = $([
			'<div id="dash" class="row border-bottom white-bg dashboard-header">',
				'<div class="col-lg-12">',
					sprintf('<h1 style="margin-bottom: 20px;">ROI Dashboard | %s Year Projection <span class="pull-right pod-total grand-total" data-format="($0,0)" data-formula="( GRANDTOTAL1 )"</span></h1>', options.compSpecs.retPeriod),
				'</div>',
			'</div>',
			'<div class="row border-bottom gray-bg dashboard-header">',
				'<div class="dashboard-pods">',
				options.dashboard.writeup,
				'</div>',
			'</div>'
		].join(''));

		dashboard.$pods = $container.find('.dashboard-pods');

		if(options.dashboard && options.dashboard.pods){
			$.each(options.dashboard.pods, function(){
				this.$container = dashboard.$pods;
				RSCalc.renderElement(this);
			});			
		} else {
			dashboard.pods = [];
			$.each(RSCalc.options.sections, function(){
				dashboard.pods.push(new Pod(this, dashboard.$pods));
			});
		}

		if(options.dashboard.appended){
			dashboard.$pods.append(options.dashboard.appended);
		}

		RSCalc.$calculator.append($container);
	};

	var Pod = function(options, $container){
		var pod = this;
		pod.options = {
			inForm: true
		};
		
		pod.options = $.extend(pod.options, options);
		pod.$container = $container;

		this.init();
	};

	Pod.fx = Pod.prototype;

	Pod.fx.init = function(){
		var pod = this;

		pod.build();
	};

	Pod.fx.build = function(){
		var pod = this,
			options = pod.options;

		if(options.visible == 1){
			var $container = $([
				'<div class="col-lg-3">',
					'<div class="widget white-bg">',
						'<div class="p-m row">',
							'<div class="equalize-pods pod-content row">',
								'<h2 class="col-md-12 font-bold no-margins pod-header">',
									sprintf('<a class="smooth-scroll section-navigator" href="#section%s">%s</a>', options.ID, options.Title),
								'</h2>',
							'</div>',
						'</div>',
					'</div>',
				'</div>'
			].join(''));

			pod.$content = $container.find('.pod-content');
			
			if(options.customformula){
				$podcontent.append(sprintf('<h1 class="txt-right pod-total txt-money" data-format="($0,0)" data-formula="%s"></h1>', options.customformula));
			} else if(options.formula && options.grandtotal == 0){
				pod.$content.append(sprintf('<h1 class="txt-right pod-total section-total" data-section-id="%s" data-format="($0,0)" data-formula="SECTIONTOTAL(%s, \'total\', %s)">$1,000,000</h1>', options.ID, options.formula, options.ID));
			} else if(options.grandtotal == 1){
				pod.$content.append('<h1 class="txt-right pod-total grand-total" data-format="($0,0)" data-formula="( GRANDTOTAL1 )"></h1>');
			}

			if((options.formula || options.customformula) && options.grandtotal == 0){
				var $pod = $([
					'<div class="progress progress-small">',
						sprintf('<div class="progress-bar section-percentage progress-bar-success" role="progressbar" aria-valuenow="35" data-progress-formula="SECTIONTOTAL(%s, \'total\', %s, true)" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>', options.formula, options.ID),
					'</div>',
					'<div class="row" style="padding: 0 20px;">',
						'<div class="value-holder" style="padding: 15px 0;">',
							'Conservative Factor: <span class="pull-right">0 %</span>',
							'<div class="row" style="padding-top: 10px;">',
								'<div class="col-lg-12">',
									sprintf('<div id="drag-fixed" class="conservative_slider slider_red" data-conservative-section-id="%s"></div>', options.ID),
								'</div>',
							'</div>',
						'</div>',
						sprintf('<button class="btn btn-block btn-primary btn-include" data-included-section-id="%s" data-checked-state="1" type="button">', options.ID),
							'<i class="fa fa-check"></i>',
							'Included',
						'</button>',
					'</div>'
				].join(''));

				pod.$content.append($pod);

				pod.$slider = pod.$content.find('.conservative_slider');

				RSCalc.intializeSlider(pod);			
			}

			pod.$container.append($container);
		};
	};

	var Section = function(options, RSCalc){
		var section = this
		section.options = {
			inForm: true,
			mainPanel: {
				class: sprintf('%s col-sm-12 col-xs-12',  options.formula || options.statistics == 1 ? 'col-lg-9 col-md-9' : 'col-lg-12 col-lg-12')
			},
			sidebar: {
				class: 'col-lg-3 col-md-3 col-sm-12 col-xs-12',
				display: true,
				customId: null
			}
		};

		section.options = $.extend(section.options, options);
		if(options.options) section.options = $.extend(true, section.options, JSON.parse(options.options));
		section.$container = $(sprintf('<div id="section%s" style="%s"></div>', section.options.ID, (section.options.visible == 1 ? 'display:block;' : 'display:none;')));

		if(section.options.sidebar.display == false) section.options.mainPanel.class = 'col-lg-12 col-lg-12 col-sm-12 col-xs-12';
		RSCalc.$calculator.append(section.$container);
		section.rsCalculator = RSCalc;
		this.init();
	};

	Section.fx = Section.prototype;

	Section.fx.init = function(){
		var section = this;
		section.build();
	}

	Section.fx.build = function(){
		var section = this;
		section.header();
		section.body();
		section.elements();
		if(section.options.sidebar.display) section.sidebar();
		section.graphs();
	};

	Section.fx.header = function(){
		var section = this,
			options = section.options;

		section.$header = $([
			'<div class="row border-bottom white-bg dashboard-header">',
				'<div class="col-lg-12">',
					sprintf('<h1 style="margin-bottom: 20px;">%s</h1>', options.Title),
				'</div>',
			'</div>'
		].join(''));

		if(options.formula || options.customformula || options.grandtotal == 1){
			var $formula = $('<span class="pull-right pod-total txt-money section-total" data-format="($0,0)">$0</span>');
			if(options.customformula){
				$formula.attr('data-formula', options.customformula);
			} else if (options.grandtotal == 1){
				$formula.attr('data-formula', '( GRANDTOTAL1 )');
			} else {
				$formula.attr('data-formula', sprintf('SECTIONTOTAL( %s, \'total\', %s )', options.formula, options.ID));
			}

			section.$header.find('h1').append($formula);
		}

		section.$container.append(section.$header);
	};

	Section.fx.body = function(){
		var section = this,
			options = section.options;

		section.$body = $([
			'<div class="row border-bottom gray-bg dashboard-header">',
			'</div>'
		].join(''));

		if(options.Caption){
			section.$writeup = $([
				'<div class="section-body col-lg-12">',
					'<div class="row">',
						'<div class="col-md-12 col-sm-12 col-xs-12">',
							'<div class="ibox float-e-margins">',
								'<div class="ibox-title">',
									sprintf('<h5>%s</h5>', options.Title),
								'</div>',
								'<div class="ibox-content" style="padding-left: 30px;">',
									'<div class="row">',
										sprintf('<div class="%s section-writeup" role="alert">', options.Video ? 'col-md-7' : 'col-md-12'),
											sprintf('<p class="caption-text">%s</p>', options.Caption),
										'</div>',
									'</div>',
								'</div>',
							'</div>',
						'</div>',
					'</div>',
				'</div>'
			].join(''));

			if(options.testimonials > 0){
				var $testimonials = [
					'<div class="quotes">',
				];

				$.each(section.rsCalculator.options.testimonials, function(i){
					$testimonials = $.merge($testimonials, [
						sprintf('<div id="blockquote" class="row" style="min-height: 220px; margin: 0;%s">', ( i != 0 ? ' display:none;' : '' )),
							sprintf('<blockquote %s>', ( this.author == 'twitter' ? 'class="twitter-tweet lang="en" data-conversation="none"' : '' )),
								sprintf('<p>%s</p>', this.testimonial),
								( this.author && this.author != 'twitter' ? sprintf('<p>— %s</p>', this.author) : '' ),
							'</blockquote>',
						'</div>'
					]);
				});

				$testimonials.push('</div>');
				$testimonials = $($testimonials.join(''));

				section.$writeup.find('.section-writeup').append('<hr/>').append($testimonials);

				$testimonials.quovolver({
					autoPlaySpeed : 8000,
					transitionSpeed : 500
				});
			}

			section.$body.append(section.$writeup);
		};

		if(options.Video){
			section.$video = $([
				'<div class="col-md-5 player">',
					sprintf('<a class="popup-iframe" href="%s"></a>', options.Video),
					sprintf('<iframe width="425" height="239" style="margin-left: 5px;" src="%s?rel=0&wmode=transparent" frameborder="0" allowfullscreen></iframe>', options.Video),
				'</div>'
			].join(''));

			section.$video.fitVids();
			section.$video.find('.popup-iframe').magnificPopup({
				disableOn: 700,
				type: 'iframe',
				preloader: false,
				fixedContentPos: false
			});

			if(section.$writeup) section.$writeup.find('.section-writeup').after(section.$video);
		};

		section.$container.append(section.$body);
	};

	Section.fx.elements = function(){
		var section = this,
			options = section.options;
			
		var $elements = $([
			'<div class="col-md-12 col-sm-12 col-xs-12">',
				'<div class="section-content row">',
					sprintf('<div class="%s">', options.mainPanel.class),
						'<div class="ibox float-e-margins">',
							'<div class="ibox-content" style="border-top: none;">',
								'<form class="form-horizontal">',
								'</form>',
							'</div>',
						'</div>',
					'</div>',
				'</div>',
			'</div>'
		].join(''));

		section.$body.append($elements);

		section.$elements = $elements.find('form.form-horizontal');
		if(options.elements){
			$.each(options.elements, function(){
				if (this.precision) {
					var decimals = this.precision;
					this.precision = '.';
					for(var i=0; i < decimals; i++) {
						this.precision += '0';
					}
				}
	
				if(this.Type != 13){
					switch(this.Format){
						case '0': this.Format = '0,0' + ( this.precision ? this.precision : '' ); break;
						case '1': this.Format = '$0,0' + ( this.precision ? this.precision : '' ); break;
						case '2': this.Format = '0,0' + ( this.precision ? this.precision : '' ) + '%'; break;
						case '3': this.Format = ''; break;				
					}
				};
	
				for( $yr=0; $yr <= (RSCalc.options.compSpecs.retPeriod - 1) * (this.annual ? this.annual : 0); $yr++ ) {
					this.field_name = String.fromCharCode(65 + $yr) + this.ID;
					this.cell_name = this.field_name;
					this.$container = section.$elements;
					this.yr = $yr + 1;		

					switch(this.Type){
						case '0':
						case '1':
						case 'input':
							new Input(this, RSCalc);
							break;
	
						case '2':
							new Textarea(this, RSCalc);
							break;
	
						case '3':
						case 'dropdown':
							new Dropdown(this, RSCalc);
							break;
	
						case '11':
						case 'slider':
							new Slider(this, RSCalc);
							break;
	
						case '13':
							new Header(this, RSCalc);
							break;
	
						case '14':
							new SavingsTable(section.rsCalculator.options, section.$elements);
							break;

						case 'text':
							new Htmltext(this, RSCalc);
							break;

						case 'checkbox':
							new Checkbox(this, RSCalc);
							break;

						case 'holder':
							new Holder(this, RSCalc);
							break;
					};
				};
			});
		};		
	};

	Section.fx.sidebar = function(){
		var section = this,
			options = section.options;

		if(options.sidebar.customId) {
			var customOpts = section.rsCalculator.customElements['CE' + options.sidebar.customId],
				$sidebar = $(sprintf('<div class="%s"></div>', options.sidebar.class));
			
				if(customOpts && customOpts.options){
				elementOpts = JSON.parse(customOpts.options);

				$.each(elementOpts, function(){
					this.$container = $sidebar;
					section.rsCalculator.renderElement(this);
				});
			}
		} else if(options.statistics == 1) {
			var $sidebar = $([
				sprintf('<div class="%s col-lg-3 col-md-3 col-sm-12 col-xs-12">', options.sidebar.customId ? 'custom' : ''),
					'<div class="ibox float-e-margins">',
						'<div class="ibox-title">',
							'<h5 class="col-lg-12">ROI Statistics</h5>',
						'</div>',
						'<div class="faq-item">',
							'<div class="row">',
								'<div class="col-lg-8">',
									'<a class="faq-question collapsed nohover">Return on Investment</a>',
								'</div>',
								'<div class="col-lg-4">',
									'<div class="pull-right return-on-investment" data-format="(0,0%)" data-formula="IF( COSTTOT1 = 0, \'No Investment\', ( GRANDTOTAL1 / ABS(COSTTOT1) ) )"></div>',
								'</div>',
							'</div>',
						'</div>',
						'<div class="faq-item">',
							'<div class="row">',
								'<div class="col-lg-8">',
									'<a class="faq-question collapsed nohover">Net Present Value</a>',
								'</div>',
								'<div class="col-lg-4">',
									sprintf('<div class="pull-right net-present-value" data-format="($0,0)" data-formula="( NPV(0.02, ANNTOT1:ANNTOT%s) )"></div>', section.rsCalculator.options.compSpecs.retPeriod),
								'</div>',
							'</div>',
						'</div>',
						'<div class="faq-item">',
							'<div class="row">',
								'<div class="col-lg-7">',
									'<a class="faq-question collapsed nohover">Payback Period</a>',
								'</div>',
								'<div class="col-lg-5">',
									'<div class="pull-right"><span data-format="0,0[.]00" data-formula="PAYBACK()"></span> months</div>',
								'</div>',
							'</div>',
						'</div>',
					'</div>',
					'<div class="ibox float-e-margins">',
						'<div class="faq-item">',
							'Implementation Period: <span class="pull-right">0 months</span>',
							'<div class="row" style="padding-top: 15px;">',
								'<div class="col-lg-12">',
									'<div id="drag-fixed" class="slider_red implementation_period"></div>',
								'</div>',
							'</div>',
						'</div>',
					'</div>',
				'</div>'
			].join(''));

			var implementationSlider = {};
			implementationSlider.$slider = $sidebar.find('.implementation_period'); 

			RSCalc.intializeSlider(implementationSlider);
		} else if(options.formula) {
			var $sidebar = [];
			$sidebar.push(sprintf('<div class="%s col-lg-3 col-md-3 col-sm-12 col-xs-12">', options.sidebar.customId ? 'custom' : ''));

			$.each(section.rsCalculator.options.sections, function(){
				if(this.formula && this.ID == options.ID){
					$sidebar.push('<div class="ibox float-e-margins">\
										<div class="ibox-title">\
											<h5 class="col-lg-12">\
												Baseline Totals\
											</h5>\
										</div>\
										<div class="faq-item">\
											<div class="row">\
												<div class="col-lg-8 col-md-12">' +
													sprintf('<a class="faq-question collapsed" href="%sfaq%s" data-toggle="collapse" aria-expanded="false">%s</a>', this.ID, this.ID, this.Title) +
												'</div>\
												<div class="col-lg-4 col-md-12">\
													<div class="pull-right">' +
														sprintf('<span class="section-total" data-section-id="%s" data-format="($0,0)" data-formula="SECTIONTOTAL(%s, \'total\', %s)" style="white-space: no wrap;"></span>', this.ID, this.formula, this.ID) +
													'</div>\
												</div>\
												<div class="row">\
													<div class="col-lg-12 annual-totals">' +
														sprintf('<div class="%sfaq%s panel-collapse faq-answer collapse in" aria-expanded="false" style="">', this.ID, this.ID) +
															'<ul>'
					);

					for(var i=1; i<=section.rsCalculator.options.compSpecs.retPeriod; i++){
						$sidebar.push(sprintf('<li class="value-holder">Year %s: <span class="pull-right section-total" data-yr="%s" data-section-id="%s" data-format="($0,0)" data-formula="SECTIONTOTAL(%s, %s, %s)"></span></li>', i, i, this.ID, this.formula, i, this.ID));
					};

					$sidebar.push('<hr class="calculation-divider">\
													</li>\
													<li class="value-holder">\
														Section Total:' +
														sprintf('<span class="pull-right section-total" data-section-id="%s" data-format="($0,0)" data-formula="SECTIONTOTAL( %s, \'total\',  %s)"></span>', this.ID, this.formula, this.ID) +
													'</li>\
													<li class="value-holder" style="padding-top: 10px;">\
														Conservative Factor: <span class="pull-right">35 %</span>\
														<div class="row" style="padding-top: 10px;">\
															<div class="col-lg-12">' +
																sprintf('<div id="drag-fixed" class="conservative_slider slider_red" data-conservative-section-id="%s"></div>', this.ID) +
															'</div>\
														</div>\
													</li>\
												</ul>'+
												sprintf('<button class="btn btn-block btn-primary btn-include" data-included-section-id="%s" data-checked-state="1" type="button">', this.ID) +
													'<i class="fa fa-check"></i>\
													Included\
												</button>\
											</div>\
										</div>\
									</div>\
								</div>\
							</div>\
						</div>' +
						sprintf('<div class="ibox float-e-margins"%s>', section.rsCalculator.options.implementation != 0 ? '' : ' style="display: none;"') +
							'<div class="faq-item">\
								Implementation Period: <span class="pull-right">0 months</span>\
								<div class="row" style="padding-top: 15px;">\
									<div class="col-lg-12">\
										<div id="drag-fixed" class="slider_red implementation_period"></div>\
									</div>\
								</div>\
							</div>\
						</div>'
					);
				}
			});

			$sidebar = $($sidebar.join(''));

			var conservativeSlider = {};
			conservativeSlider.$slider = $sidebar.find('.conservative_slider');

			var implementationSlider = {};
			implementationSlider.$slider = $sidebar.find('.implementation_period'); 

			RSCalc.intializeSlider(conservativeSlider);
			RSCalc.intializeSlider(implementationSlider);
		}

		if($sidebar){
			section.$body.find('.section-content').append($sidebar);
		}
	};

	Section.fx.graphs = function(){
		var section = this,
			options = section.options;

		$.each(section.rsCalculator.options.graphs, function(){
			if(this.sectionid == options.ID){
				 var $graph = $([
					'<div class="row border-bottom gray-bg dashboard-header">',
						'<div class="col-lg-12">',
							'<div class="row">',
								'<div class="col-md-12 col-sm-12 col-xs-12">',
									'<div class="ibox float-e-margins">',
										sprintf('<div class="ibox-content" style="padding-left: 30px;">%s</div>', this.html),
									'</div>',
								'</div>',
							'</div>',
						'</div>',
					'</div>',
				 ].join(''));
			};

			section.$container.append($graph);
		});
	}

	var RoiShopCalculator = function(element, options){
		this.options = options;
		this.el = $(element);
		this.$container = $('<div id="page-wrapper" class="gray-bg"></div>');
		this.elements = [];
		this.elements.inputs =[];
		this.elements.sliders = [];
		this.elements.selects = [];
		this.elements.textarea = [];
		this.elements.checkbox = [];
		
		this.init();
	};

	RoiShopCalculator.DEFAULTS = {
		calcHolder: 'body',
		equalizer: 'equalize',
		colors: ['#72ABE2', '#2E8B57', '#7DDA6A', '#EC9851', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'],
		dashboard: {
			writeup: '<div class="col-lg-12"><div class="ibox-content"><h3 style="font-size: 18px; font-weight: 700;">Select a section below to review your ROI</h3><p style="font-size: 16px;">To calculate your return on investment, begin with the first section below. The information entered therein will automatically populate corresponding fields in the other sections. You will be able to move from section to section to add and/or adjust values to best reflect your organization and process. To return to this screen, click the ROI Dashboard button to the left.</p></div></div>'
		}
	};

	var RSCalc = RoiShopCalculator.prototype;

	RSCalc.init = function(){
		RSCalc = this;

		if(RSCalc.options.customElements){
			RSCalc.customElements = [];
			$.each(RSCalc.options.customElements, function(){
				RSCalc.customElements['CE' + this.auto_id] = this;
			});
		}

		if(RSCalc.options.versionSpecs.options) RSCalc.options = $.extend(true, RSCalc.options, JSON.parse(RSCalc.options.versionSpecs.options));

		RSCalc.sideNavigation = new sideNavigation(RSCalc);
		RSCalc.topNavigation = new topNavigation(RSCalc);
		
		if(RSCalc.options.discovery.length){
			RSCalc.discoveryDocuments = [];
			$.each(RSCalc.options.discovery, function(){
				RSCalc.discoveryDocuments.push(new Discovery(this, RSCalc));
			});
		};

		RSCalc.$calculator = $('<div class="roi-calculator"></div>');
		RSCalc.$container.append(RSCalc.$calculator);

		RSCalc.dashboard = new Dashboard(RSCalc);

		RSCalc.sections = [];
		$.each(RSCalc.options.sections, function(){
			RSCalc.sections.push(new Section(this, RSCalc));
		});

		RSCalc.setupActions();
	};

	RSCalc.setupActions = function(){
		RSCalc = this;
		RSCalc.initializeCalculator();
		RSCalc.initializeSliders();
		RSCalc.assignValues();
		RSCalc.elementVisibility();
		RSCalc.initializeScrolling();
		RSCalc.setupGraphs();
		RSCalc.equalizeHeights();
		RSCalc.includeButtons();
		RSCalc.setupTooltips();
		RSCalc.calculateRoi();

		RSCalc.storeValues();
	};

	RSCalc.elementVisibility = function(){
		var sections = this.sections;

		$.each(sections, function(){
			if(this.options.visible == 0){
				$('[data-section-id="' + this.options.ID + '"]').each(function(){
					$(this).hide();
				});
			}
		});
		
		$element = RSCalc.elements;

		if($element){
			for(var element in $element){
				RSCalc.toggleVisibility($element[element][0]);
			}
		};	
	}

	RSCalc.setupTooltips = function(){
		RSCalc = this;

		theme = {
			theme: 'tooltipster-light',
			maxWidth: 300,
			animation: 'grow',
			position: 'right',
			arrow: false,
			interactive: true,
			contentAsHTML: true	
		}

		var $tooltips = $('.tooltipstered, [calculation]');
		$tooltips.each(function(){
			$(this).tooltipster(theme);
		});
	}

	RSCalc.includeButtons = function(){
		$('.btn-include').off('clikc').on('click', function(){
			var section = $(this).data('included-section-id');

			if(RSCalc.sectionIncludes['INC' + section] == 0){
				$('button[data-included-section-id="' + section + '"]').each(function(){
					$(this).html('<i class="fa fa-check"></i> Included').removeClass('btn-danger').addClass('btn-primary');
					RSCalc.sectionIncludes['INC' + section] = 1;
				});
			} else {
				$('button[data-included-section-id="' + section + '"]').each(function(){
					$(this).html('<i class="fa fa-times"></i> Excluded').removeClass('btn-primary').addClass('btn-danger');
					RSCalc.sectionIncludes['INC' + section] = 0;
				});
			}

			RSCalc.storeValues();
		});
	};	

	RSCalc.initializeCalculator = function(){
		RSCalc = this;
		RSCalc.registerFunctions();
	
		numeral.language(RSCalc.options.specs.currency);
		
		$(RSCalc.options.calcHolder).calx({
			'autoCalculate': false
		});

		$('input[data-cell]').off('blur').on('blur', function(){
			RSCalc.storeValues();
		});
	};

	RSCalc.assignValues = function(){
		RSCalc = this;
		RSCalc.conservativeFactors = [],
		RSCalc.sectionIncludes = [],
		RSCalc.implementationPeriod = 0;

		var currentValues;
		$.each(RSCalc.options.values, function(i, v){
			if(this != false){
				try{
					currentValues = currentValues ? currentValues : JSON.parse(v);
				} catch(e){
					currentValues = currentValues ? currentValues : v
				}
			};
		});
		
		if(currentValues){
			$.each(currentValues, function(){
				var values = this,
					value = values.value ? ( (values.value+'').indexOf('%') > 0 ? parseInt(values.value) / 100 : isNaN(values.value) ? 0 : values.value ) : 0;
	
				if(values.entryid == 'impPeriod' || values.address == 'IMP1'){
					RSCalc.implementationPeriod = parseInt(value);
					$(".implementation_period").each(function(){
						$(this).val(RSCalc.implementationPeriod);
					});
				};
	
				if(values.entryid && values.entryid.includes('currentValueCon') || values.address && values.address.includes('CON')){
					var section;
					
					if(values.entryid){
						section = values.entryid.replace('currentValueCon', '');
					} else {
						section = values.address.replace('CON', '');
					};
	
					RSCalc.conservativeFactors['CON' + section] = parseInt(value);
	
					$('[data-conservative-section-id="' + section + '"]').each(function(){
						$(this).val(RSCalc.conservativeFactors['CON' + section]);
					});
				}
	
				if(values.entryid && values.entryid.includes('check') || values.address && values.address.includes('INC')){
					var section;
	
					if(values.entryid){
						section = values.entryid.replace('check', '');
					} else {
						section = values.address.replace('INC', '');
					};
					
					RSCalc.sectionIncludes['INC' + section] = values.value;
	
					$('button[data-included-section-id="' + section + '"]').each(function(){
						var section = $(this).data('included-section-id');
	
						if(RSCalc.sectionIncludes['INC' + section] && RSCalc.sectionIncludes['INC' + section] == 0){
							$(this).html('<i class="fa fa-times"></i> Excluded').removeClass('btn-primary').addClass('btn-danger');
						} else {
							$(this).html('<i class="fa fa-check"></i> Included').removeClass('btn-danger').addClass('btn-primary');
						}
					});			
				}
	
				if(values.entryid){
					if(values.entryid.includes('yr')){
						var split = values.entryid.split('yr');
						var year = values.entryid.split('yr').pop();
						var letter = String.fromCharCode(64 + parseInt(year));
	
						values.address = letter + split[0];
					} else {
						values.address = "A" + values.entryid;
					}
				}
	
				if(values.address){
					$element = RSCalc.elements[values.address];
	
					if($element){
						$.each($element, function(){
							if(this.constructor.name == "Textarea"){
								this.$textarea.val(values.value);
								this.options.value = values.value;
							} else if(this.constructor.name == "Checkbox"){
	
							} else {
								var value = (values.value+'').indexOf('%') > 0 ? parseInt(values.value) / 100 : isNaN(values.value) ? 0 : values.value,
									formattedValue = value;
	
								if(this.options.Format){
									var formattedValue = numeral(value).format(this.options.Format);
								};
	
								if(this.$input) $(this.$input).val(formattedValue);
								if(this.$slider) $(this.$slider).val(parseInt(formattedValue));
								if(this.$select) $(this.$select).val(value).trigger('chosen:updated');
								if(this.$calx) {
									var calx = $(RSCalc.options.calcHolder).calx('getCell', $(this.$calx).data('cell'));
									
									if(values.customformula){
										calx.formula = values.customformula;
										calx.customformula = values.customformula;
									};
	
									calx.setValue(formattedValue).renderComputedValue();
								}
							}
	
							RSCalc.toggleVisibility(this);
						});
					};	
				};
			});
		}

		$(RSCalc.options.calcHolder).calx('getSheet').calculate();
	};

	RSCalc.renderElement = function(options){
		var RSCalc = this;

		switch(options.type){
			case 'holder':
				new Holder(options, RSCalc);
				break;

			case 'text':
				new Htmltext(options, RSCalc);
				break;

			case 'input':
				new Input(options, RSCalc);
				break;

			case 'textarea':
				new Textarea(options, RSCalc);
				break;
		
			case 'dropdown':
				new Dropdown(options, RSCalc);
				break;
		
			case 'slider':
				new Slider(options, RSCalc);
				break;

			case 'checkbox':
				new Checkbox(options, RSCalc);
				break;
		};
	}

	RSCalc.intializeSlider = function(slider){
		var RSCalc = this,
			$slider = slider.$slider;

		$slider.noUiSlider({
			start: 0,
			connect: 'lower',
			step: slider.options && slider.options.step ? slider.options.step : $slider.hasClass('implementation_period') ? 1 : ( $slider.hasClass('conservative_slider') ? 5 : 1 ),
			range: {
				min: 0,
				max: $slider.hasClass('implementation_period') ? RSCalc.options.compSpecs.retPeriod * 12 : 100
			},
			format: {
				to: function(value) { return value; },
				from: function(value) {return value; }
			}
		});

		$slider.Link('lower').to( function(value) {
			if($slider.hasClass('implementation_period')){
				$slider.closest('.faq-item').find('.pull-right').html( value + ( value == 1 ? ' month' : ' months' ) );
			} else if($slider.hasClass('conservative_slider')) {
				$slider.closest('.value-holder').find('.pull-right').html( value + '%' );
			} else {
				var sliderName = slider.options.field_name;
				$(RSCalc.options.calcHolder).calx('getSheet').getCell(sliderName).setValue(value).calculate();
				$slider.closest('.form-group').find('.slider-input').val(value);
			}
		});

		$slider.on({
			slide: function(){
				var sliderValue = $slider.val();
				if($slider.hasClass('implementation_period')) RSCalc.implementationPeriod = sliderValue;
				if($slider.hasClass('conservative_slider')) RSCalc.conservativeFactors['CON' + $slider.data('conservative-section-id')] = sliderValue;			
			},
				
			change: function(){
				var sliderValue = $slider.val();
				if($slider.hasClass('implementation_period')){
					$(".implementation_period").each(function(e){
						$(this).val(sliderValue);
					});
				} else if($slider.hasClass('conservative_slider')) {
					var conservativeSection = $slider.data("conservative-section-id");
					$("[data-conservative-section-id='" + conservativeSection + "']").each(function(e){
						$(this).val(sliderValue);
					});	
				} else {
					var cell = $slider.closest('.form-group').find('.slider-input').data('cell');
					$('[data-cell="' + cell + '"]').each(function(){
						$(RSCalc.options.calcHolder).calx('getSheet').getCell($(this).data('cell')).setValue(sliderValue).renderComputedValue();
						$(RSCalc.options.calcHolder).calx('getSheet').calculate();
					});					
				}
				RSCalc.storeValues();
			}
		});
	}

	RSCalc.initializeSliders = function(){
		var RSCalc = this;

		$.each(RSCalc.elements.sliders, function(){
			var slider = this;

			RSCalc.intializeSlider(slider);
		});
		
		$('.slider-input').off('change').on('change', function(){
			$(this).closest('.form-group').find('.slider').val($(this).val());
		})		
	};

	RSCalc.equalizeHeights = function(){
		RSCalc = this,
		equalizers = [];

		var resize = function() {
			$('[class*="' + RSCalc.options.equalizer + '"]').each(function(){
				var cls = $(this).attr('class').split(' ');
				$.each(cls, function(){
					if (this.indexOf(RSCalc.options.equalizer) > -1){
						equalizers.push(this.replace(RSCalc.options.equalizer, ''));
					}
				});
			});
			
			equalizers = $.unique(equalizers);
			
			$.each(equalizers, function(){
				var maxHeight = 0;
				$('.' + RSCalc.options.equalizer + this).each(function(){
					maxHeight = $(this).height() > maxHeight ? $(this).height() : maxHeight;
				}).each(function(){
					$(this).height(maxHeight);
				});
			});
		}
		
		$(window).off('resize').on('resize', resize).resize();	
	}

	RSCalc.calculateRoi = function(){
		var RSCalc = this,
			calx = $(RSCalc.options.calcHolder).calx('getSheet');

			calx.calculate();

		var totalSavings = 0;
		$.each(RSCalc.options.sections, function(){
			if(this.formula) totalSavings += $(RSCalc.options.calcHolder).calx('evaluate', 'SECTIONTOTAL(' + this.formula + ", 'total', " + this.ID + ', true)');
		});
		
		$('.section-percentage').each(function(){
			if(totalSavings == 0) {
				$(this).css('width', '0%');
			} else {
				var sectionTotal = $(RSCalc.options.calcHolder).calx('evaluate', $(this).data('progress-formula'));
				var totalPercentage = sectionTotal / totalSavings * 100;
	
				$(this).css('width', totalPercentage + '%');
			}
		});
		
		RSCalc.styleRoi();
		RSCalc.updateGraphs();
	};

	RSCalc.setupGraphs = function(){
		var RSCalc = this,
			seriesData = [],
			returnPeriod = RSCalc.options.compSpecs.retPeriod,
			categories = [],
			costs = [],
			totalSections = 0;

		var colors = RSCalc.options.colors;

		for(var i=1; i<=returnPeriod; i++){
			categories.push('Year ' + i);
			costs.push($(RSCalc.options.calcHolder).calx('evaluate', 'ABS(ANNUALCOST(' + i + '))'))
		}
		
		$.each(RSCalc.sections, function(){
			var section = this,
				options = section.options;

			if(options.visible == 0) return;

			if(options.formula){
				var data = [];
				for(var i=1; i<=returnPeriod; i++){
					data.push($(RSCalc.options.calcHolder).calx('evaluate', 'SECTIONTOTAL(' + options.formula + ', ' + i + ', ' + options.ID + ')'));
				}

				var seriesDatum = {
					name: options.Title,
					type: 'column',
					data: data,
					color: colors[totalSections]
				};

				totalSections++;

				seriesData.push(seriesDatum);
			}
		});

		seriesData.push({
			name: 'Cost',
			type: 'column',
			data: costs,
			color: 'rgb(235, 54, 54)'
		});

		var chart_options = {
			chart: {
				type: 'column',
				margin: 75,
				options3d: {
					enabled: true,
					alpha: 0,
					beta: 0,
					depth: 60,
					viewDistance: 10
				}
			},
			title: {
				text: 'Your Potential Return on Investment'
			},
			xAxis:{
				categories: categories
			},
			yAxis: {
				min: 0,
				style: {
					color: '#333',
					fontWeight: 'bold',
					fontSize: '12px',
					fontFamily: 'Trebuchet MS, Verdana, sans-serif'
				},				
				title: {
					text: 'Money'
				}
			},
			tooltip: {
				headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
				pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
					'<td style="color:{series.color};padding:0;padding-left:10px;"><b> {point.y:,.0f}</b></td></tr>',
				footerFormat: '</table>',
				shared: true,
				useHTML: true
			},
			plotOptions: {
				column: {
					pointPadding: 0.2,
					borderWidth: 0
				}
			},
			series: seriesData
		};

		$('.bar-chart').each(function(){
			$(this).highcharts(chart_options);
		});
	};

	RSCalc.renderGraphsToImage = function(){
		var RSCalc = this;

		$('.bar-chart').each(function(){
			var highchart = $(this).highcharts();
			var options = highchart.options;

			var opts = $.extend(true, {}, options);
			delete opts.chart.renderTo;
			delete opts.plotOptions.column.animation.duration;

			opts.credits.enabled = false;

			pdfOpts = {
				chart: {
					type: 'column',
					height: 280,
					backgroundColor: 'transparent',
					marginTop: 5
				},
				credits: {
					enabled: false
				},
				exporting: {
					enabled: false
				},
				title: {
					text: ''
				},
				yAxis: {
					min: 0,				
					title: {
						text: 'Money'
					}
				},
				plotOptions: {
					column: {
						pointPadding: 0.2,
						borderWidth: 0
					}
				}
			};

			var opts = $.extend(true, opts, pdfOpts);

			$.each(highchart.series, function(index, value){
				opts.series[index].data = highchart.series[index].yData;
			});

			$.post("//export.highcharts.com",{
				options: JSON.stringify(opts),
				type: "png",
				width: 700,
				async: false
			}, function(img){

				$.post("/enterprise/7/assets/ajax/calculator.post.php", {
					action: "storePdf",
					roi: getQueryVariable('roi'),
					company: RSCalc.options.versionSpecs.version_id,
					section: 'summary',
					type: 'barchart',
					image: encodeURIComponent('http://export.highcharts.com/' + img)
				}, function(){ })
			});
			
		});
	}

	RSCalc.updateGraphs = function(){
		var RSCalc = this,
			costs = [],
			totalSections = 0;

		var colors = RSCalc.options.colors;

		$('.bar-chart').each(function(){
			var chart =  $(this).highcharts(),
				returnPeriod = RSCalc.options.compSpecs.retPeriod;

			if(chart){
				var seriesLength = chart.series.length;
				for(var i = seriesLength -1; i > -1; i--) {
					chart.series[i].remove();
				}

				$.each(RSCalc.sections, function(){
					var section = this,
						options = section.options;
	
					if(options.visible == 0) return;

					if(options.formula){
						var data = [];
						for(var i=1; i<=returnPeriod; i++){
							data.push($(RSCalc.options.calcHolder).calx('evaluate', 'SECTIONTOTAL(' + options.formula + ', ' + i + ', ' + options.ID + ')'));
						}

						var seriesDatum = {
							name: options.Title,
							type: 'column',
							data: data,
							color: colors[totalSections]
						};

						totalSections++;

						chart.addSeries(seriesDatum);
					}
				});

				for(var i=1; i<=returnPeriod; i++){
					costs.push($(RSCalc.options.calcHolder).calx('evaluate', 'ABS(ANNUALCOST(' + i + '))'))
				};

				chart.addSeries({
					name: 'Cost',
					type: 'column',
					data: costs,
					color: 'rgb(235, 54, 54)'
				});

				chart.redraw();
			};
		});
	};

	RSCalc.showVerificationLink = function(){
		var modal = {
			animation: 'fadeIn',
			header:	{
				icon: 'fa-shield',
				title: 'ROI Verification Link',
				subtitle: 'The following link can be used to give anyone access to this ROI.'
			},
			body: {
				content: '<textarea class="ver-link-output" spellcheck="false" readonly="readonly">www.theroishop.com/enterprise/7/?roi=' + this.options.specs.roi_id + '&v=' + this.options.specs.verification_code + '</textarea>'
			},
			footer: {
				content: '<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>'
			}
		};

		this.displayModal(modal);	
	};

	RSCalc.resetVerificationLink = function(){
		var RSCalc = this;
		
		var modal = {
			animation	:	'fadeIn',
			header		:	{
				icon		:	'fa-shield',
				title		:	'Reset Verification Link?'
			},
			body		:	{
				content		:	'<p>Would you like to reset the verification link for this ROI? Once the link is reset it <b>cannot</b> be undone and no prospects will be able to view the ROI without the new link.</p>'
			},
			footer		:	{
				content		:	'<button type="button" class="btn btn-primary reset-ver-link-confirm" data-dismiss="modal">Reset</button>\
								<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>'
			}
		};

		this.displayModal(modal);
		
		$('.reset-ver-link-confirm').on('click', function(){
			$.ajax({
				type: "POST",
				url: "/enterprise/7/assets/ajax/calculator.post.php",
				data: {
					action: "resetVerification",
					roi: getQueryVariable('roi')
				},
				success: function(verification){
					noty({
						text: 'Verification Link successfully changed',
						type: 'success',
						timeout: 2000
					});
					
					RSCalc.options.specs.verification_code = verification;
					RSCalc.showVerificationLink();
				}
			});
		});
	};

	RSCalc.changeCurrency = function(){
		var RSCalc = this,
			$changeCurrency = [];

		$changeCurrency.push(
			'<div class="modal inmodal" id="change-currency" tabindex="-1" role="dialog" aria-hidden="true">',
				'<div class="modal-dialog modal-lg">',
					'<div class="modal-content animated bounceInRight">',
						'<div class="modal-header">\
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>\
							<h4 class="modal-title">Change ROI Currency</h4>\
						</div>\
						<div class="modal-body">\
							<div class="form-horizontal">\
								<div class="form-group">\
									<label class="control-label col-lg-8 col-md-8 col-sm-12">Currency Symbols for ROI (thousand separators, decimal points and currency symbol): </label>\
									<div class="col-lg-4 col-md-4 col-sm-12">\
										<select class="current-language chosen-select" data-placeholder="Select Your Language/Country">\
											<option></option>\
											<optgroup data-label="currency" label="Choose by currency">\
												<option class="language-option" data-currency-option="AUD" value="aud">Australian Dollar</option>\
												<option class="language-option" data-currency-option="EUR" value="eur">Euro (English Notation)</option>\
												<option class="language-option" data-currency-option="EUR" value="eur-si">Euro (SI Notation)</option>\
												<option class="language-option" data-currency-option="GBP" value="gbp">Pound Sterling</option>\
												<option class="language-option" data-currency-option="USD" value="usd">United States Dollar</option>\
											</optgroup>\
											<optgroup data-label="country" label="Choose by country">\
												<option class="language-option" data-currency-option="EUR" value="eur-si">Euro (SI Notation)</option>\
												<option class="language-option" data-currency-option="EUR" value="be-nl">Belgium-Dutch</option>\
												<option class="language-option" data-currency-option="CAD" value="can">Canada (Dollar)</option>\
												<option class="language-option" data-currency-option="CNY" value="chs">Chinese (Simplified)</option>\
												<option class="language-option" data-currency-option="CZK" value="cs">Czech</option>\
												<option class="language-option" data-currency-option="DKK" value="da-dk">Danish Denmark</option>\
												<option class="language-option" data-currency-option="EUR" value="nl-nl">Dutch (Netherlands)</option>\
												<option class="language-option" data-currency-option="EUR" value="et">Estonian</option>\
												<option class="language-option" data-currency-option="EUR" value="fi">Finnish</option>\
												<option class="language-option" data-currency-option="EUR" value="fr">French</option>\
												<option class="language-option" data-currency-option="CAD" value="fr-CA">French (Canadian)</option>\
												<option class="language-option" data-currency-option="EUR" value="de">German</option>\
												<option class="language-option" data-currency-option="CHF" value="de-ch">German (Switzerland)</option>\
												<option class="language-option" data-currency-option="HUF" value="hu">Hungarian</option>\
												<option class="language-option" data-currency-option="EUR" value="it">Italian</option>\
												<option class="language-option" data-currency-option="JPY" value="ja">Japanese</option>\
												<option class="language-option" data-currency-option="PLN" value="pl">Polish</option>\
												<option class="language-option" data-currency-option="BRL" value="pt-br">Portuguese (Brazil)</option>\
												<option class="language-option" data-currency-option="EUR" value="pt-pt">Portuguese</option>\
												<option class="language-option" data-currency-option="RUB" value="ru">Russian</option>\
												<option class="language-option" data-currency-option="UAH" value="ru-UA">Russian (Ukraine)</option>\
												<option class="language-option" data-currency-option="EUR" value="sk">Slovak</option>\
												<option class="language-option" data-currency-option="EUR" value="es">Spanish</option>\
												<option class="language-option" data-currency-option="EUR" value="en-es">Spanish (Dollar)</option>\
												<option class="language-option" data-currency-option="THB" value="th">Thai</option>\
												<option class="language-option" data-currency-option="TRY" value="tr">Turkish</option>\
												<option class="language-option" data-currency-option="UAH" value="uk-UA">Ukrainian</option>\
											</optgroup>\
										</select>\
									</div>\
								</div>\
							</div>\
						</div>\
						<div class="modal-footer">\
							<button type="button" class="btn btn-primary update-currency">Update Currency and Reload ROI</button>\
							<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>\
						</div>\
					</div>\
				</div>\
			</div>');
			
		$changeCurrency = $($changeCurrency.join(''));
		$changeCurrency.modal('show');

		$changeCurrency.find('.chosen-select').chosen({
			width: '100%',
			disable_search_threshold: 10
		}).val(RSCalc.options.specs.currency).trigger('chosen:updated');

		$changeCurrency.find('.update-currency').off('click').on('click', function(){
			var language = $('.current-language').val();
			RSCalc.options.specs.currency = language;

			$.ajax({
				type: 'POST',
				url: '/enterprise/7/assets/ajax/calculator.post.php',
				data: {
					action: 'updateCurrency',
					roi: RSCalc.options.specs.roi_id,
					language: language
				},
				success: function() {
					location.reload();
				}
			});			
		});
	};

	RSCalc.sfIntegration = function(){
		var RSCalc = this;

		if(!RSCalc.options.specs.sfdc_link || RSCalc.options.specs.changeOpp){
			RSCalc.connectToSF();
		} else {
			var modal = {		
				animation: 'fadeIn',
				size: 'modal-xl',
				header: {
					title: 'Salesforce Integration'
				},
				footer:	{
					content: '<button id="export" type="button" class="btn btn-success" data-dismiss="modal">Export to Salesforce</button><button type="button" class="btn btn-white" data-dismiss="modal">Close</button>'
				}
			};
			
			RSCalc.displayModal(modal);

			$modalBody = $('.modal-body');

			$modalBody.append($(sprintf('<h1>This ROI is currently linked to: <a id="change_link">%s</strong></a></h1><hr>', RSCalc.options.specs.linked_title ? RSCalc.options.specs.linked_title : 'Unattached')));

			var elements = JSON.parse(RSCalc.options.integrationElements.integration_elements);

			$.each(elements, function(){
				this.$container = $modalBody;
				RSCalc.renderElement(this);
			});

			$modalBody.find('[data-roi-link]').each(function(){
				$(this).val(window.location.href + '&v=' + RSCalc.options.specs.verification_code);
				$(this).attr('data-integration-value', window.location.href + '&v=' + RSCalc.options.specs.verification_code);
			});

			$modalBody.find('[data-roi-created-by]').each(function(){
				var input = $(this);

				$.ajax({
					type: "GET",
					url: "/enterprise/7/assets/ajax/calculator.get.php",
					data: {
						action: "roiOwner",
						roi: getQueryVariable('roi')
					},
					success: function(returned){
						owner = $.parseJSON(returned);

						input.val(owner.username);
						input.attr('data-integration-value', owner.username);
					},
					error: function(error){
						
					}
				});
			});

			$modalBody.find('[data-dt]').each(function(){
				var date = RSCalc.options.specs.dt;
				date = new Date(date);
				month = date.getMonth() + 1;

				month = month < 10 ? ( "0" + month ) : month;

				if(!isNaN(date.getTime())){
					date = date.getFullYear() + "-" + month + '-' + date.getDate();
				}
				
				$(this).val(date);
				$(this).attr('data-integration-value', date);
			});

			$modalBody.find('[data-formula]').each(function(){
				var formula = $(this).data('formula'),
					value = $(RSCalc.options.calcHolder).calx('evaluate', formula);

				$(this).attr('data-integration-value', value);
				$(this).val($(this).data('format') ? numeral(value).format($(this).data('format')) : value);
			});
		};

		$('#change_link').off('click').on('click', function(){
			RSCalc.options.specs.changeOpp = true;
			RSCalc.sfIntegration();
		});

		$('#export').off('click').on('click', function(){
			var $fields = $('[data-integration-id]');

			$fields.each(function(){
				var field = '{"' + $(this).data('integration-id') + '":"' + $(this).data('integration-value') + '"}',
					field_name = $(this).data('integration-id');

				$.ajax({
					type	: 	"POST",
					url		:	"/enterprise/7/assets/ajax/calculator.post.php",
					data: {
						action: 'updateSFRecord',
						fields: field,
						user_id: RSCalc.options.integration.code,
						linked_id: RSCalc.options.specs.sfdc_link,					
					},
					success: function(data){
						toastr.info(field_name + ' was successfully integrated');
					}
				});				
			});
		});

	};

	RSCalc.connectToSF = function(){
		var RSCalc = this;

		RSCalc.options.specs.changeOpp = false;

		var modalBody = sprintf('<h1>This ROI is currently linked to: <strong>%s</strong><h1>', RSCalc.options.specs.linked_title ? RSCalc.options.specs.linked_title : 'Unattached');
			modalBody += 	'<br>\
							<div class="form-group">\
								<label class="col-lg-5">Enter Opportunity to Search For</label>\
								<div class="col-lg-7 input-holder"><input id="opp_name" class="form-control"></div>\
							</div>\
							<div class="form-group">\
								<label class="col-lg-5">Select Opportunity</label>\
								<div class="col-lg-7 input-holder">\
									<select id="linked_opportunity" class="form-control">\
									</select>\
								</div>\
							</div>\
							<hr/>\
							<div class="form-group">\
								<a id="search_opp" class="btn btn-success col-lg-12">Search for Opportunity</a>\
							</div>';

		var modal = {		
			animation: 'fadeIn',
			size: 'modal-lg',
			header: {
				title: 'Connect to Opportunity'
			},
			body: {
				content: modalBody
			},
			footer:	{
				content: '<button id="connect" type="button" data-dismiss="modal" class="btn btn-success">Connect</button><button type="button" class="btn btn-white" data-dismiss="modal">Close</button>'
			}
		};
		
		RSCalc.displayModal(modal);

		$('#linked_opportunity').chosen({
			width: '100%',
			disable_search_threshold: 10
		});

		$('#opp_name').on('keypress', function(e){
			if(e.which == 13) $('#search_opp').click();
		});

		$('#connect').off('click').on('click', function() {
			var linked_id = $('#linked_opportunity').val(),
				linked_title = $('#linked_opportunity').find('option:selected').text();

			$.ajax({
				type	: 	"POST",
				url		:	"/enterprise/7/assets/ajax/calculator.post.php",
				data: {
					action: 'linkToOpportunity',
					linked_id: linked_id,
					linked_title: linked_title,
					roi_id: RSCalc.options.specs.roi_id
				},
				success	: function(){
					RSCalc.options.specs.sfdc_link = linked_id;
					RSCalc.options.specs.linked_title = linked_title;
					RSCalc.sfIntegration();
				}
			});				
		});

		$('#search_opp').off('click').on('click', function() {
			$('#search_opp').html('Searching for Opportunities...');

			$.ajax({
						
				type: 'GET',
				url: '/enterprise/7/assets/ajax/calculator.get.php',
				data: {
					action: 'getSFOpportunities',
					opportunity: 'test',
					user_id: RSCalc.options.integration.code,
					opp_name: encodeURI($('#opp_name').val())
				},
				success: function(elements){
					var opportunities = $.parseJSON(elements);
		
					if( ! $.isEmptyObject(opportunities) ){
						
						$('#linked_opportunity').html('');

						$('.sf-elements').html('');
						opportunities = opportunities.sort(function(a,b){ return a.Name > b.Name ? 1 : -1; });
							
						var selectOptions = '<optgroup data-label="opportunities" label="Opportunities">';
							
						for(var i=0; i<opportunities.length; i++) {
							var opp = opportunities[i];
							selectOptions += "<option value='" + opp.Id + "'>" + opp.Name + "</option>";
						}
							
						selectOptions += '</optgroup>';
							
						$('#linked_opportunity').append(selectOptions);
					}
					
					$('#linked_opportunity').trigger('chosen:updated');
					$('#linked_opportunity').trigger('change');
					
					$('#search_opp').html('Search for Opportunity');
				}
			});		
		});
	};

	RSCalc.viewAllowedUsers = function(){
		var RSCalc = this;
		
		$.ajax({
			type: 'GET',
			url: '/enterprise/7/assets/ajax/calculator.get.php',
			data: {
				action: 'getcontributor',
				roi: getQueryVariable('roi')
			},
			success: function(contributors){
				var currentContributors = $.parseJSON(contributors);
				modalBody = '<table class="table table-hover contributor-names-table">\
								<tbody>';
				
				$.each(currentContributors, function(index, value) {
					modalBody += 	'<tr data-contributor-id="' + value.auto_id + '">\
										<td class="project-title">' + value.email_address + '</td>';
					//if($('#verificationLevel').val() > 1) {
						modalBody +=	'<td class="project-actions">\
											<a class="btn btn-danger btn-sm remove-contributor"><i class="fa fa-times"></i> Remove </a>\
										</td>';
					//}	
					modalBody +=	'</tr>';
				});
				
				modalBody +=	'</tbody></table>';
				
				var modal = {		
					animation: 'fadeIn',
					header: {
						icon: 'fa-user',
						title: 'Current Contributors'
					},
					body: {
						content: modalBody
					},
					footer:	{
						content: '<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>'
					}
				};
				
				RSCalc.displayModal(modal);

				$('.remove-contributor').off('click').on('click', function() {
					var contributorId = $(this).closest('tr').data('contributor-id');
		
					$.ajax({
						type	: 	"POST",
						url		:	"/enterprise/7/assets/ajax/calculator.post.php",
						data: {
							action: 'delcont',
							roi: getQueryVariable('roi'),
							id: contributorId
						},
						success	: function(){
							$('[data-contributor-id="' + contributorId +'"]').fadeOut("slow");
						}
					});
					
				});
			}
		});			
	}

	RSCalc.addContributor = function(){
		var RSCalc = this;

		var modal = {
			animation:	'fadeIn',
			header:	{
				icon:	'fa-user',
				title:	'Add Contributor'
			},
			body:	{
				content	:	'<div class="row">\
									<label class="control-label col-lg-5 col-md-5 col-sm-12">Contributor Name</label>\
									<div class="col-lg-7 col-md-7 col-sm-12">\
										<input id="contributor" class="form-control" type="text" />\
									</div>\
								</div>'
			},
			footer:	{
				content	:	'<button type="button" class="btn btn-primary add-contributor-initialize">Add Contributor</button>\
							<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>'
			}
		};

		RSCalc.displayModal(modal);
		
		$('.add-contributor-initialize').off('click').on('click', function(){
			var newContributorName = $('#contributor').val();
			if(newContributorName) {			
				$.ajax({
					type: "POST",
					url: "/enterprise/7/assets/ajax/calculator.post.php",
					data: {
						action: 'addcont',
						cont: newContributorName,
						roi: getQueryVariable('roi')
					},
					success: function(verification){
						$('#contributor').val('');
						noty({
							text: newContributorName + ' successfully added',
							type: 'success',
							timeout: 2000
						});
					}
				});
			}
		});
	};

	RSCalc.toggleVisibility = function(element){
		this.hideElements(element);
		this.showElements(element);
	};

	RSCalc.hideElements = function(element){
		if(!element) return false;
		
		var RSCalc = this,
		$el = element.$container,
		options = $el ? $el.data('options').options : false;

		if(options && options.element_visibility) {
			$.each(options.element_visibility, function(i){
				if(this.show){
					$.each(this.show, function(){
						var element_id = $(this+'');
						element_id.hide();
						if(element_id && element_id.data('options')){
							RSCalc.hideElements(element_id.data('options'));
						}
					});
				};
			});
		};
	};

	RSCalc.showElements = function(element){
		if(!element || !element.options) return false;
		var RSCalc = this,
			cell = element.options.cell_name ? $(RSCalc.options.calcHolder).calx('getCell', element.options.cell_name) : false;

		var $el = element.$container;

		if(cell && $el.is(":visible")){
			var cell_value = cell.value,
				options = $el ? $el.data('options').options : false;

			if(options && options.element_visibility) {
				$.each(options.element_visibility, function(i){
					if(this.show && this.value == cell_value){
						$.each(this.show, function(){
							var element_id = $(this+'');
							element_id.show();
							if(element_id && element_id.data('options')){
								RSCalc.showElements(element_id.data('options'));
							}
						});
					};
				});
			}
		}	
	};

	RSCalc.showCalculationModal = function(cell){
		var RSCalc = this,
			html = [],
			cell = $(RSCalc.options.calcHolder).calx('getCell', cell),
			dependencies = cell.dependencies,
			formula_txt,
			el = RSCalc.elements[cell.address] ? RSCalc.elements[cell.address][RSCalc.elements[cell.address].length - 1] : false;

		html.push('<div class="panel panel-default">');

		var inputDependencies = function(html, dependencies){
			for(dependent in dependencies){
				var element = RSCalc.elements[dependent] ? RSCalc.elements[dependent][RSCalc.elements[dependent].length - 1] : false;
				has_dependencies = $.isEmptyObject(dependencies[dependent].dependencies);

				element_opts = $.extend(true, {}, RSCalc.elements[dependent] ? RSCalc.elements[dependent][RSCalc.elements[dependent].length - 1].options : false);
				current_value = $(RSCalc.options.calcHolder).calx('getCell', dependent).getFormattedValue() || numeral(0).format(element.options.Format);

				html.push(
					'<div class="panel-heading">',
						sprintf('<h5 class="panel-title collapsed" href="#panel%s" data-toggle="collapse">', dependent),
							sprintf('<%s style="margin-bottom: 0;">%s %s%s', has_dependencies ? 'p' : 'a' , has_dependencies ? '' : '<i class="fa fa-plus-circle" style="color: blue;"></i>', element.options.Title, $(RSCalc.options.calcHolder).calx('getCell', dependent).customformula ? ' <span style="color: red;">(Current value is overriding this equation)</span>' : ''),
							sprintf('<span class="pull-right">%s</span>', current_value),
							sprintf('</%s>', has_dependencies ? 'p' : 'a'),
						'</h5>',
					'</div>'
				);

				if(!has_dependencies){
					html.push(sprintf('<div id="panel%s" class="panel-collapse collapse" style="height: 0px; margin: 0 0 0 15px; padding: 10px; background-color: #eee; border-left: 3px solid blue;">', dependent));

					var dependentDependencies = dependencies[dependent].dependencies,
						cell = $(RSCalc.options.calcHolder).calx('getCell', dependent);
						formula_txt = element_opts.formula;
							
					html.push(inputDependencies(html, dependentDependencies));
						
					for (dependent in dependentDependencies){
						element_opts = $.extend(true, {}, RSCalc.elements[dependent] ? RSCalc.elements[dependent][RSCalc.elements[dependent].length - 1].options : false);
						if (formula_txt) formula_txt = formula_txt.replace(dependent, element_opts.Title);
					}

					html.push(sprintf('<div style="margin: 15px;"><strong>Equation: %s</strong></div>', formula_txt));
						
					html.push('</div>');
				}
			}

			return html;
		};

		html = inputDependencies(html, dependencies);

		var $modal = $([
			'<div class="modal inmodal fade in">',
				'<div class="modal-dialog modal-lg">',
					'<div class="modal-content animated fadeIn">',
						'<div class="modal-body">',
						sprintf('<h1 class="rsmodal-title">%s</h1><hr/>', el.options.Title),
						'</div>',
					'</div>',
				'</div>',	
			'</div>'
		].join(''));

		html.push('</div>');

		$modal.find('.modal-body').append($(html.join('')));

		var formula_txt = String(el.options.formula);
			
		for(dependent in dependencies) {
			element_opts = $.extend(true, {}, RSCalc.elements[dependent] ? RSCalc.elements[dependent][RSCalc.elements[dependent].length - 1].options : false);
			if (formula_txt) formula_txt = formula_txt.replace(dependent, element_opts.Title);
		}

		$modal.find('.modal-body').append(
			sprintf('<div class="form-group" style="margin-bottom: 0;"><h4>Equation%s</h4></div>', cell.customformula ? ' <span style="color: red;">(Current value is overriding this equation)</span>' : '') +
				'<div class="form-group">\
				<label class="control-label col-lg-12 col-md-12 col-sm12">' + formula_txt + ' </label>\
			</div>'
		);

		$modal.find('.modal-body').append(
			'<div class="form-group" style="margin-bottom: 0;"><h4>Value</h4></div>\
				<div class="form-group">\
				<label class="control-label col-lg-12 col-md-12 col-sm12 pull-right">' + cell.getFormattedValue() || numeral(0).format(el.options.Format) + ' </label>\
			</div>'
		);

		$modal.find('.modal-body').append(
			'<hr/>\
			<div class="form-group">\
				<label class="control-label col-lg-5 col-md-5 col-sm-12">Change value to:</label>\
				<div class="col-lg-7 col-md-7 col-sm-12">\
					<div class="input-group">\
						<input id="override" class="form-control" data-format="' + el.options.Format + '">\
						<span class="input-group-addon right append" style="padding: 0; border:0;"><button class="btn btn-success override-value" style="border-left: 0px; border-color: rgb(197, 198, 199); border-radius: 0 3px 3px 0;">Change Value</button></span>\
					</div>\
				</div>\
			</div>'
		);

		$modal.find('.modal-content').append(
			$([
				'<div class="modal-footer">',
					'<div class="rsmodal-buttonwrapper">',
						'<button type="button" class="btn btn-primary rsmodal-button reset">Reset to Original Equation</button>',
						'<button type="button" class="btn rsmodal-button rsmodal-cancel btn-danger" data-dismiss="modal">Close</button>',
					'</div>',
				'</div>'
			].join(''))
		);

		$modal.find('.reset').on('click', function(){
			cell.formula = el.options.formula;
			cell.customformula = '';

			RSCalc.storeValues();
		});

		$modal.find('.override-value').on('click', function(){
			var newVal = $('#override').val(),
				format = $('#override').data('format');

			if(format) newVal = numeral().unformat(newVal);

			cell.formula = (newVal+'');
			cell.customformula = (newVal+'');
			el.options.customformula = (newVal + '');

			RSCalc.storeValues();
		});

		$modal.find('#override').on('blur', function(){
			var newVal = $(this).val(),
				format = $(this).data('format');

			if(format.indexOf('%') > -1 && (newVal+'').indexOf('%') == -1) newVal = newVal / 100;
		
			$(this).val(numeral(newVal).format(format));
		});

		$modal.modal('show');
		$modal.on('hidden.bs.modal', function(){
			$modal.remove();
		});
	}

	RSCalc.showHideSections = function(){
		var RSCalc = this,
			$showHideModal = [];

		$showHideModal.push(
			'<div class="modal inmodal" id="showHideSections" tabindex="-1" role="dialog" aria-hidden="true" data-input-id="">',
				'<div class="modal-dialog">',
					'<div class="modal-content animated bounceInRight">',
						'<div class="modal-header">',
							'<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>',
							'<h4 class="modal-title">Show/Hide Sections</h4>',
						'</div>',
						'<div class="modal-body">');

		$.each(RSCalc.sections, function(){
			var include = true,
				section = this,
				options = section.options;

			if(options.visible == 0) include = false;

			$showHideModal.push('<div class="form-group">',
									'<label class="checkbox-inline showHideCheckbox">',
										'<input class="section-to-show" data-section-id="' + options.ID + '" type="checkbox"' + ( include == true ? ' checked="checked"' : '' ) + '>',
										'<span class="pull-right" style="margin-left: 45px;">' + options.Title + '</span>',
									'</label>',
								'</div>');
		});
		
		$showHideModal.push(
						'</div>',
						'<div class="modal-footer">',
							'<button type="button" class="btn btn-primary show-hide-sections">Update Sections</button>',
							'<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>',
						'</div>',
					'</div>',
				'</div>',
			'</div>');
			
		$showHideModal = $($showHideModal.join(''));
		$showHideModal.modal('show');

		$('.showHideCheckbox').iCheck({
			checkboxClass: 'icheckbox_square-green',
			radioClass: 'iradio_square-green',
		});
		
		$('.show-hide-sections').on('click', function(){
			$.ajax({		
				type: 'POST',
				url: '/enterprise/7/assets/ajax/calculator.post.php',
				data: {
					action: "removeHiddenSections",
					roi: RSCalc.options.specs.roi_id
				},
				async: 'false',
				success: function() {
					$('.section-to-show').each(function() {
						if($(this).prop('checked')) { } else {
							var sectionId = $(this).data('section-id');
							
							$.ajax({
								type: 'POST',
								url: '/enterprise/7/assets/ajax/calculator.post.php',
								data: {
									action: 'hideSection',
									roi:RSCalc.options.specs.roi_id,
									section: sectionId
								}
							});
						}
					});
					
					$(document).ajaxStop(function() {
						location.reload();
					});	
				}
			});			
		});
	};

	RSCalc.displayModal = function(opts) {
		
		$('.modal').remove();
		
		var modalHeader = '<div class="modal-header">';
		if( opts.header.close ) {
			modalHeader += opts.header.close;
		} else {
			modalHeader += 	'<button type="button" class="close" data-dismiss="modal">\
								<span aria-hidden="true">&times;</span>\
								<span class="sr-only">Close</span>\
							</button>';			
		}

		if( opts.header.icon ) {
			modalHeader += '<i class="fa ' + opts.header.icon + ' modal-icon"></i>';
		}
		modalHeader += '<h4 class="modal-title">' + opts.header.title + '</h4>';
		
		if(opts.header.subtitle) {
			modalHeader += '<small class="font-bold">' + opts.header.subtitle + '</small>';
		}
		modalHeader +=	'</div>'
		
		var modalBody =	'<div class="modal-body">';
		if(opts.body && opts.body.content) {
			modalBody +=	opts.body.content;
		}
		modalBody +=	'</div>';
		
		if(opts.footer) {
			var modalFooter =	'<div class="modal-footer">';
			if(opts.footer && opts.footer.content) {
				modalFooter +=	opts.footer.content;
			}	
			modalFooter +=	'</div>';
		} else { var modalFooter = ''; }
		
		modal = 	'<div class="modal-dialog ' + (opts.size ? opts.size : '') + '">\
						<div class="modal-content animated ' + (opts.animation ? opts.animation : 'slideInDown') + '">';
		modal +=	modalHeader + modalBody + modalFooter + '</div></div>';
				
		var $modal = $('<div class="modal inmodal" id="modal-shell" tabindex="-1" role="dialog" aria-hidden="true"></div>');		

		$modal
			.html(modal)
			.modal('show');
	}

	RSCalc.styleRoi = function(){
		var RSCalc = this;

		$('.section-total').each(function(){
			if(RSCalc.sectionIncludes['INC' + $(this).data('section-id')] == 0){
				$(this).removeClass('txt-money').addClass('txt-removed');
			} else {
				if($(RSCalc.options.calcHolder).calx('evaluate', $(this).data('formula')) >= 0){
					$(this).removeClass('txt-removed').addClass('txt-money');
				} else {
					$(this).removeClass('txt-money').addClass('txt-removed');
				}
			};
		});

		$('.grand-total, .annual-total').each(function(){
			if($(RSCalc.options.calcHolder).calx('evaluate', $(this).data('formula')) >= 0){
				$(this).removeClass('txt-removed').addClass('txt-money');
			} else {
				$(this).removeClass('txt-money').addClass('txt-removed');
			}			
		});
	}

	RSCalc.storeValues = function(){
		var RSCalc = this,
			calx = $(RSCalc.options.calcHolder).calx('getSheet'),
			cells = calx.cells,
			roiValues = [];

		RSCalc.calculateRoi();

		$.each(cells, function(){
			cell = {};
			cell.address = this.address;
			cell.formattedValue = this.formattedValue;
			cell.value = this.value;
			cell.customformula = this.customformula ? this.customformula : '';

			roiValues.push(cell);
		});

		var implementation = {};
		implementation.address = 'IMP1';
		implementation.formattedValue = RSCalc.implementationPeriod;
		implementation.value = RSCalc.implementationPeriod;

		roiValues.push(implementation);
		
		if(RSCalc.conservativeFactors){
			$.each(RSCalc.options.sections, function(){
				var conservativeFactor = {
					address: 'CON' + this.ID,
					formattedValue: numeral(RSCalc.conservativeFactors['CON' + this.ID]) + '%',
					value: RSCalc.conservativeFactors['CON' + this.ID]
				};

				roiValues.push(conservativeFactor);
			});
		}

		if(RSCalc.sectionIncludes){
			$.each(RSCalc.options.sections, function(){
				var includedSections = {
					address: 'INC' + this.ID,
					formattedValue: RSCalc.sectionIncludes['INC' + this.ID],
					value: RSCalc.sectionIncludes['INC' + this.ID]					
				};

				roiValues.push(includedSections);
			});
		}

		$.each(RSCalc.elements.textarea, function(){
			var textarea = {
				address: this.options.field_name,
				formattedValue: this.options.value,
				value: this.options.value
			}
			
			roiValues.push(textarea);
		});

		$.each(RSCalc.elements.checkbox, function(){
			var checkbox = {
				address: this.options.cell_name,
				formattedValue: this.options.value,
				value: this.options.value
			}
			
			roiValues.push(checkbox);
		});

		$.ajax({
			type: "POST",
			url: "/enterprise/7/assets/ajax/calculator.post.php",
			data: {
				action: "storeValues",
				values: JSON.stringify(roiValues),
				roi: RSCalc.options.specs.roi_id
			}
		});
	}

	RSCalc.initializeScrolling = function(){
		$('.smooth-scroll a[href*=#]:not([href=#]), a[href*=#]:not([href=#]).smooth-scroll').off('click').on('click', function() {
			if($(this).hasClass('discovery-document')){
				$('.roi-calculator').hide();
				$('.discovery-page').each(function(){
					$(this).hide();
				});

				$(this.hash).show();
			}
	
			if($(this).hasClass('roi-section')){
				$('.roi-calculator').show();
				$('.discovery-page').each(function(){
					$(this).hide();
				});
			};		
			
			if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
				var target = $(this.hash);
				target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
				if (target.length) {
					$('html,body').animate({
						scrollTop: target.offset().top-62
					}, 1000);
					return false;
				}
			}
		});			
	}

	RSCalc.registerFunctions = function(){
		var RSCalc = this;

		// Register Section Total
		$(RSCalc.options.calcHolder).calx('registerFunction', 'SECTIONTOTAL', function(formula, yr, id, ex){
			if(RSCalc.sectionIncludes['INC' + id] == 0 && ex == true) return 0;

			if(RSCalc.conservativeFactors['CON' + id]){
				var confactor = ( 100 - RSCalc.conservativeFactors['CON' + id] ) / 100;
			} else {
				var confactor = 1;
			}

			var impperiod = RSCalc.implementationPeriod;
			var returnperiod = RSCalc.options.compSpecs.retPeriod;

			if(yr == 'total'){
				var totalpercentage = (returnperiod * 12 - impperiod) / (returnperiod * 12);
			} else {
				var totalpercentage = 1;
				if(impperiod >= yr * 12){ totalpercentage = 0; }
				else if(impperiod < yr * 12 && impperiod >= (yr - 1) * 12){ totalpercentage = (yr * 12 - impperiod) / 12; }

				returnperiod = 1;
			}

			var sectiontotal = formula * confactor * returnperiod * totalpercentage;
			return sectiontotal;
		});

		// Register Net Present Value
		$(RSCalc.options.calcHolder).calx('registerFunction', 'PAYBACK', function() {
			var returnPeriod = RSCalc.options.compSpecs.retPeriod * 12;
			var impPeriod = RSCalc.implementationPeriod;

			var cost = $(RSCalc.options.calcHolder).calx('getCell', 'COSTTOT1').getValue();
			var totalSavings = $(RSCalc.options.calcHolder).calx('getCell', 'GRANDTOTAL1').getValue();

			var totalMonths = Math.abs(cost) / ( ( totalSavings - cost ) / ( returnPeriod - impPeriod ) );
			var payback = totalMonths + impPeriod;

			return payback ? payback < returnPeriod ? payback : returnPeriod + '+' : returnPeriod + '+';
		});

		// Register Annual Cost
		$(RSCalc.options.calcHolder).calx('registerFunction', 'ANNUALCOST', function(yr) {
			var totalcost = 0;

			if(yr==1) {
				$('[data-cost-yr="0"]').each(function(e){
					totalcost -= numeral().unformat( $(RSCalc.options.calcHolder).calx('getCell', $(this).data('cell')).getValue() );
				});
				
				$('[data-cost-yr="1"]').each(function(){
					totalcost -= numeral().unformat( $(RSCalc.options.calcHolder).calx('getCell', $(this).data('cell')).getValue() );
				});
			} else {
				
				$('[data-cost-yr="'+yr+'"]').each(function(e){		
					totalcost -= numeral().unformat( $(RSCalc.options.calcHolder).calx('getCell', $(this).data('cell')).getValue() );
				});			
			}
			
			if(yr=="total") {
				$('[data-cost-yr]').each(function(e){
					if($(this).data('formula')) {
						totalcost -= numeral().unformat( $(RSCalc.options.calcHolder).calx('evaluate', $(this).data('formula') ) );
					} else {
						totalcost -= numeral().unformat( $(RSCalc.options.calcHolder).calx('getCell', $(this).data('cell')).getValue() );
					}
				});
			}

			return totalcost;
		});		
	}

	$.fn.roishopCalculator = function(option) {

        var value,
            args = Array.prototype.slice.call(arguments, 1);

        this.each(function () {
            var $this = $(this),
                data = $this.data('roishop.calculator'),
                options = $.extend({}, RoiShopCalculator.DEFAULTS, $this.data(),
                    typeof option === 'object' && option);

            if (typeof option === 'string') {
				if ($.inArray(option, allowedMethods) < 0) {
                    throw new Error("Unknown method: " + option);
                }

                value = data[option].apply(data, args);

                if (option === 'destroy') {
                    $this.removeData('roishop.calculator');
                }
            }

            if (!data) {
                $this.data('roishop.calculator', (data = new RoiShopCalculator(this, options)));
            }
        });
		
        return typeof value === 'undefined' ? this : value;
	};
	
	// Ajax call to return the build of the current ROI
	var current_roi	= getQueryVariable('roi'),
		verification = getQueryVariable('v'),
		action 		= 'RetrieveRoi',
		ajax_url 	= 'assets/ajax/calculator.get.php';
		
	$.get( ajax_url, { action: action, verification: verification, roi: current_roi } )
		.done(function(elements){
			elements = JSON.parse(elements);
			options = {
				specs: elements.roiSpecs,
				sections: elements.roiSections,
				versionSpecs: elements.versionSpecs,
				compSpecs: elements.compSpecs,
				testimonials: elements.testimonials,
				discovery: elements.discoveryDocuments,
				rois: elements.userRois,
				graphs: elements.graphs,
				integration: elements.sfIntegration,
				integrationElements: elements.integrationElements,
				verification: elements.verification,
				pdfs: elements.pdfs,
				values: elements.values,
				customElements: elements.customElements
			};

			$('#wrapper').roishopCalculator(options);
		});
	
})(window.jQuery || window.Zepto, window, document);

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

function getQueryVariable(variable) {
		
	var query = window.location.search.substring(1),
		vars = query.split("&");

	for (var i=0;i<vars.length;i++) {
		
		var pair = vars[i].split("=");
		if(pair[0] == variable){ return pair[1]; }
	}
	
	return(false);
};