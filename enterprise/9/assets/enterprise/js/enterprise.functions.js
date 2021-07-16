if (typeof(numeral) === 'undefined') {
	numeral = undefined;
}

if(typeof(moment) == 'undefined'){
	moment = undefined;
}

;(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
    typeof define === 'function' && define.amd ? define(factory) :
    global.roishop = factory();
}(this, (function () {

	'use strict';
	var roishop = (function(el, options) {
		var calc = {};

		var defaults = {
			currencies: {
				label: 'Choose by currency',
				currency: [
					{ value: 'aud', text: 'Australian Dollar' },
					{ value: 'eur', text: 'Euro (English Notation)' },
					{ value: 'eur-si', text: 'Euro (SI Notation)' },
					{ value: 'ja', text: 'Japanese Yen' },
					{ value: 'gbp', text: 'Pound Sterling' },
					{ value: 'usd', text: 'United States Dollar' },
					{ value: 'da-dk', text: 'Swedish Krona' },
					{ value: 'fr-ch', text: 'Swiss Franc' },
					{ value: 'da-dk', text: 'Danish Krona' },
					{ value: 'da-dk', text: 'Norwegian Krona' }
				]
			},
			equalizer: 'equalize',
			navigationItems: [{
				verification: 1,
				text: 'Reset Verification Link',
				actions: { click: function(){ roishop.resetVerification(); } }
			},{
				verification: 1,
				text: 'Change ROI Currency',
				actions: { click: function(){ roishop.changeCurrency(); } }
			},{
				verification: 1,
				text: 'Add Allower Users',
				actions: { click: function(){ roishop.addContributor(); } }
			},{
				verification: 1,
				text: 'View Allowed Users',
				actions: { click: function(){ roishop.viewAllowedUsers(); } }
			}]
		};

		calc.options = $.extend(true, options, defaults);

		calc.el = $(el);

		calc.init = function(){
			roishop.current = calc;

			calc.mergeValues();
			calc.prepareCalculator();
			calc.sheet = new sheet(calc.options.cells);
			calc.attachEvents();
			calc.equalizeHeights();
			calc.updateGraphs();
		}

		calc.mergeValues = function(){
			if(!calc.options.values[0]) return false;

			var parsedValues;
			$.each(calc.options.values, function(i, v){
				try {
					parsedValues = parsedValues ? parsedValues : JSON.parse(calc.options.values[i]);
				} catch(e){ 
					if(i === 0){
						toastr.error('An unexpected error occurred while loading the latest values for this calculator. The most recent values available have been loaded instead.', 'Loading Error', {
							timeOut: 7000
						});
					}
				}
			});

			if(!parsedValues) return false;

			var values = [];
			$.each(parsedValues, function(){
				values[this.address] = this;
			});

			if(calc.options.cells && calc.options.cells.length){
				$.each(calc.options.cells, function(){
					if(values[this.address]){
						this.value = values[this.address].value;
						this.forcedValue = values[this.address].forcedValue;
					}
				});
			}
		}

		calc.setValue = function(cell, value, el){
			if(! calc.sheet) return false;

			calc.sheet.cells[cell].setValue(value);
			calc.sheet.cells[cell].clearProcessedFlag();
			calc.sheet.cells[cell].calculate();

			calc.sheet.renderComputedValue(el);
			calc.storeValues();
		}

		calc.prepareCalculator = function(){
			calc.$container = calc.el;			
			calc.prepareNavigation();

			var link = {
				address: "ROILINK1",
				format: null,
				label: "Link to Calculator",
				type: "text",
				value: "www.theroishop.com/enterprise/9/?roi=" + calc.options.specs.roi_id + '&v=' + calc.options.specs.verification_code
			}

			calc.options.cells.push(link);
		}

		calc.prepareNavigation = function(){
			calc.$topNavigation = $('<div class="row bottom-border"></div>');
			calc.el.append(calc.$topNavigation);

			var actions = [];
			for (var item in calc.options.navigationItems){
				var navigation = calc.options.navigationItems[item];

				if(calc.options.verification > navigation.verification){
					var $action = $(sprintf('<li><a>%s</a></li>', navigation.text));
					actions.push($action);

					if(navigation.actions){
						var keys = Object.keys(navigation.actions);
						
						for (var i = 0; i < keys.length; i++) {
							$action.off(keys[i]).on(keys[i], navigation.actions[keys[i]]);
						}
					}
				}
			}

			var $navigation = $([
				'<nav class="navbar navbar-fixed-top" role="navigation">',
					'<div class="navbar-header">',
						sprintf('<h3>%s</h3>', calc.options.specs.roi_title),
					'</div>',
					'<ul class="nav navbar-top-links navbar-right">',
					'<li style="padding-right: 15px;"><button class="btn btn-block btn-primary share-calculator" type="button"><i class="fa fa-share"></i> Share Calculator</button></li>',	
					'<li>',
							'<span class="m-r-sm text-muted welcome-message">Powered by <a href="https://theroishop.com/" target="_blank" style="padding-left: 0;">The ROI Shop</a></span>',
						'</li>',
						'<li class="dropdown myactions-dropdown"></li>',
						'<li>',
							'<a href="../../assets/logout.php">',
								'<i class="fa fa-sign-out"></i> Log Out',
							'</a>',
						'</li>',
					'</ul>',
				'</nav>'
			].join(''));

			calc.$topNavigation.append($navigation);

			if(actions.length){
				var $actions = [];
				$actions.push(
					'<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">',
						'My Actions <i class="fa fa-caret-down"></i>',
					'</a>',
					'<ul class="dropdown-menu dropdown-alerts"></ul>');

				$navigation.find('.myactions-dropdown').append($actions.join(''));
				
				$.each(actions, function(){
					$navigation.find('.myactions-dropdown > ul').append(this);
				});
			}

			if(calc.options.verification > 1){
				$.each(calc.options.integrations, function(){
					if(this.type == "sfdc"){
						var $action = $(sprintf('<li><a%s>%s</a></li>', calc.options.integration.code ? '' : ' href="/dashboard/account.php"', calc.options.integration.code ? 'Export to Salesforce' : 'Connect to Salesforce'));
							$navigation.find('.myactions-dropdown > ul').append($action);

						$action.off('click').on('click', function(){
							if(calc.options.integration.code){
								roishop.exportToSalesforce();
							} else {
								
							}
						});
					}
				})

				if(calc.options.versionSpecs && calc.options.versionSpecs.options){
					var version_opts = JSON.parse(calc.options.versionSpecs.options);

					if(version_opts.includes){
						var $action = $(sprintf('<li><a>%s</a></li>', 'Show / Hide Sections'));
							$navigation.find('.myactions-dropdown > ul').append($action);

						$action.off('click').on('click', function(){
							var $includes = $('<div></div>');

							$includes.rsmodal({
								header: {
									content: [{
										tag: 'h1',
										children: [{
											html: 'Show / Hide Sections'
										}]
									}]
								},
								body: {
									content: version_opts.includes
								}
							});
						});
					}
				}
			}

			calc.renderCalculator();
		}

		calc.renderCalculator = function(){
			if(! calc.options.build.build_array) return;
			var elements = $.parseJSON(calc.options.build.build_array);
			
			numeral.language(calc.options.specs.currency);
			
			$.each(elements, function(){
				this.$parent = calc.$container;
				builder.build(this);
			});
		}

		calc.storeValues = function(){
			var cells = calc.sheet.cells,
				values = [];

			for(var a in cells){
				cell = {};
				cell.address = cells[a].address;
				cell.formattedValue = cells[a].formattedValue;
				cell.value = cells[a].value;
				cell.forcedValue = cells[a].forcedValue ? cells[a].forcedValue : '';
	
				values.push(cell);
			}

			$.ajax({
				type: "POST",
				url: "/assets/api/",
				data: {
					action: "storeValues",
					roi: calc.options.specs.roi_id,
					values: JSON.stringify(values)
				},
				success: function(returned){

				}
			});

			calc.updateGraphs();
		}

		calc.updateGraphs = function(){
			$('.bar-chart').each(function(){
				var chart =  $(this).highcharts(),
					series = chart.userOptions.series;
				
				if(chart){
					var seriesLength = chart.series.length;
					for(var i = seriesLength -1; i > -1; i--) {
						chart.series[i].remove();
					}

					if(series){
						$.each(series, function(){
							if(this.visibility){
								var value = this.visibility.cell ? calc.sheet.cells[(this.visibility.cell+'')].getValue() : 0;
								var operator = this.visibility.operator ? this.visibility.operator : '==',
									evaluator = value + operator + this.visibility.value;

								var evaluation = eval(evaluator);
								if(! evaluation) return;
							}

							var equations = this.equations,
								datum = [];

							$.each(equations, function(){
								datum.push(calc.sheet.cells[(this+'')].getValue());
							});

							this.data = datum;
							chart.addSeries(this);
						});
					}

					if(chart.userOptions.xAxis && chart.userOptions.xAxis.categories){
						var xCategories = [];

						$.each(chart.userOptions.xAxis.categories, function(){
							if(typeof this === "object"){
								if(this.equation){
									xCategories.push(calc.sheet.cells[(this.equation+'')].getValue());
								}
							} else if(typeof this === "string"){
								xCategories.push(this);
							}
						});

						chart.xAxis[0].update({categories: xCategories}, false);
					}

					chart.redraw();
				}
			});
		}

		calc.calculationBreakdown = function(cell){
			var cell = calc.sheet.cells[cell],
				dependencies = cell.dependencies,
				html = [];

			html.push('<div class="panel panel-default">');

			var inputDependencies = function(html, dependencies){
				for(var dependent in dependencies){
					var element = dependencies[dependent],
						has_dependencies = ! $.isEmptyObject(element.dependencies),
						current_value = element.formattedValue ? element.formattedValue : element.value;

					html.push(
						'<div class="panel-heading">',
							sprintf('<h5 class="panel-title collapsed" href="#panel%s" data-toggle="collapse">', dependent),
								sprintf('<%s style="margin-bottom: 0;">%s %s%s', has_dependencies ? 'a' : 'p' , has_dependencies ? '<i class="fa fa-plus-circle" style="color: blue;"></i>' : '', element.label ? element.label : element.address, element.forcedValue ? ' <span style="color: red;">(Current value is overriding this equation)</span>' : ''),
								sprintf('<span class="pull-right">%s</span>', current_value),
								sprintf('</%s>', has_dependencies ? 'a' : 'p'),
							'</h5>',
						'</div>'
					);
					
					if(has_dependencies){
						html.push(sprintf('<div id="panel%s" class="panel-collapse collapse" style="height: 0px; margin: 0 0 0 15px; padding: 10px; background-color: #eee; border-left: 3px solid blue;">', dependent));
	
						var dependentDependencies = element.dependencies,
							formula_txt = String(element.formula);

						html.push(inputDependencies(html, dependentDependencies));
							
						for (dependent in dependentDependencies){
							var dependent_opts = dependentDependencies[dependent];
							if (formula_txt) formula_txt = formula_txt.replace(dependent, dependent_opts.label);
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
							sprintf('<h1 class="rsmodal-title">%s</h1><hr/>', cell.label || cell.address),
							'</div>',
						'</div>',
					'</div>',	
				'</div>'
			].join(''));
	
			html.push('</div>');
	
			$modal.find('.modal-body').append($(html.join('')));

			var formula_text = String(cell.formula);
			for(var dependent in dependencies){
				if(formula_text) formula_text = formula_text.replace(dependent, dependencies[dependent].label ? dependencies[dependent].label : dependencies[dependent].address )
			}

			$modal.find('.modal-body').append(
				sprintf('<div class="form-group" style="margin-bottom: 0;"><h4 id="equation">Equation%s</h4></div>', cell.forcedValue ? ' <span id="overriding-message" style="color: red;">(Current value is overriding this equation)</span>' : '') +
					'<div class="form-group">\
					<label class="control-label col-lg-12 col-md-12 col-sm12">' + formula_text + ' </label>\
				</div>'
			);

			$modal.find('.modal-body').append(
				'<div class="form-group" style="margin-bottom: 0;"><h4>Value</h4></div>\
					<div class="form-group">\
					<label id="current-value" class="control-label col-lg-12 col-md-12 col-sm12 pull-right">' + cell.formattedValue || numeral(0).format(cell.format) + ' </label>\
				</div>'
			);

			$modal.find('.modal-body').append(
				'<hr/>\
				<div class="form-group">\
					<label class="control-label col-lg-5 col-md-5 col-sm-12">Change value to:</label>\
					<div class="col-lg-7 col-md-7 col-sm-12">\
						<div class="input-group">\
							<input id="override" class="form-control" data-format="' + cell.format + '">\
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
				cell.forcedValue = null;

				if(cell.sheet.affectedCell.indexOf(cell.address) == -1){
					cell.sheet.affectedCell.push(cell.address);
				}

				cell.sheet.clearProcessedFlag();
				cell.calculate();
				cell.sheet.renderComputedValue();

				$modal.find('#current-value').html(cell.formattedValue);
				$('#overriding-message').remove();

				calc.storeValues();
			});

			$modal.find('.override-value').on('click', function(){
				var newVal = $('#override').val(),
					format = $('#override').data('format');
	
				if(format) newVal = numeral().unformat(newVal);

				cell.forcedValue = (newVal+'');

				if(cell.sheet.affectedCell.indexOf(cell.address) == -1){
					cell.sheet.affectedCell.push(cell.address);
				}

				cell.sheet.clearProcessedFlag();
				cell.calculate();
				cell.sheet.renderComputedValue();

				$modal.find('#current-value').html(cell.formattedValue);
				
				if(! $('#equation').find('#overriding-message').length){
					$('#equation').append(' <span id="overriding-message" style="color: red;">(Current value is overriding this equation)</span>');
				}

				calc.storeValues();
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

		calc.equalizeHeights = function(){
			var equalizers = [];

			var resize = function() {
				$('[class*="' + calc.options.equalizer + '"]').each(function(){
					var cls = $(this).attr('class').split(' ');
					$.each(cls, function(){
						if (this.indexOf(calc.options.equalizer) > -1){
							equalizers.push(this.replace(calc.options.equalizer, ''));
						}
					});
				});
				
				equalizers = $.unique(equalizers);
				
				$.each(equalizers, function(){
					var maxHeight = 0;
					$('.' + calc.options.equalizer + this).each(function(){
						maxHeight = $(this).height() > maxHeight ? $(this).height() : maxHeight;
					}).each(function(){
						$(this).height(maxHeight);
					});
				});
			}
			
			$(window).off('resize').on('resize', resize).resize();	
		}

		calc.attachEvents = function(){
			this.el.on('change', '[calc-id]', function(){
				if($(this).prop('tagName').toLowerCase() !== 'td') $(this).trigger('calc.setValue');
			});

			this.el.on('calc.setValue', '[calc-id]', function(){
				var address = $(this).attr('calc-id'),
					cell = calc.sheet.cells[address],
					oldVal = cell.getValue(),
					newVal = $(this).val();

				if($(this).attr('type') == 'checkbox'){
					newVal = ( $(this).attr('value') == 0 ? 1 : 0 ) * $(this).attr('toggle-value');
				};

				cell.setValue(newVal);

				if(oldVal !== newVal){
					cell.setAffected(true);
				}
				
				cell.sheet.clearProcessedFlag();
				cell.calculate();
				cell.sheet.renderComputedValue();

				calc.storeValues();
			});

			$('.sticky').each(function(){
				new StickySidebar(this, {
					topSpacing: 85,
					bottomSpacing: 20
				});
			});

			var theme = {
				theme: 'tooltipster-light',
				maxWidth: 300,
				animation: 'grow',
				position: 'right',
				arrow: false,
				interactive: true,
				contentAsHTML: true						
			}

			$('.tooltipstered').tooltipster(theme);

			$('[calculation-modal]').off('click').on('click', function(){
				calc.calculationBreakdown($(this).attr('calculation-modal'));
			});

			$('.smooth-scroll a[href*=#]:not([href=#]), a[href*=#]:not([href=#]).smooth-scroll').off('click').on('click', function() {			
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

			$('.revolving-html').quovolver({
				autoPlaySpeed : 8000,
				transitionSpeed : 500,
				equalHeight: true
			});

			$('.share-calculator').on('click', function(){
				roishop.showVerification();
			});

			$('.renderPdf').on('click', function(){
				
				waitingDialog.show('Rendering PDF...');

				var reportId = $(this).attr('reportId');
				calc.storeValues();
				
				$.ajax({
					type: 'GET',
					url: '/enterprise/9/assets/ajax/calculator.get.php',
					data: {
						action: 'RetrievePDFGraph',
						roi: getQueryVariable('roi'),
						id: reportId
					},
					success: function(graph){
						if(graph){
							
							graph = $.parseJSON(graph);

							if(graph){
								$.each(graph, function(){
									if(this && this.graph_build){
										var graph = $.parseJSON(this.graph_build),
											id = this.id;
								
										$.each(graph.series, function(){
											var equations = this.equations,
												datum = [];
					
											$.each(equations, function(){
												datum.push(calc.sheet.cells[(this+'')].getValue());
											});
					
											this.data = datum;
										});
	
										$.post("//export.highcharts.com",{
											options: JSON.stringify(graph),
											type: "png",
											width: graph.width || 700,
											async: false
										}, function(img){

											$.post("/enterprise/9/assets/ajax/calculator.post.php", {
												action: "storePdf",
												roi: getQueryVariable('roi'),
												id: id,
												image: encodeURIComponent('http://export.highcharts.com/' + img)
											}, function(returned){ })							
										})
									}
								});
							}
						}
					}
				});

				setTimeout(function(){
					$.ajax({
						type: "GET",
						url: "/enterprise/9/assets/ajax/pdf.creation.php",
						data: {
							action: "createpdf",
							roi: getQueryVariable('roi'),
							reportId: reportId,
							roiPath: window.location.href + '&v=' + calc.options.specs.verification_code
						},
						success: function(returned){
							waitingDialog.hide();
							$('<a href="/webapps/assets/customwb/10016/pdf/' + calc.options.specs.roi_title + '.pdf" download>')[0].click();
						},
						error: function(error){
							
						}
					});
				}, 2500);
			});
		};
		
		calc.init();
	});

	roishop.DEFAULTS = {};

	roishop.changeCurrency = function(){
		var $selector = $('<select data-placeholder="Select Currency"></select>');
			$selector.append('<option></option>');

		var $optgroup = $(sprintf('<optgroup label="%s"></optgroup>', roishop.current.options.currencies.label));
		$.each(roishop.current.options.currencies.currency, function(){
			$optgroup.append($(sprintf('<option value="%s">%s</option>', this.value, this.text)));
		});

		$selector.append($optgroup);

		var $form = $('<div class="form-horizontal"><div class="form-group"><label class="control-label col-lg-8">Currency Symbols for ROI (thousand separators, decimal points and currency symbol):</label><div class="col-lg-4 currency-selector"></div></div></div>')
		$form.find('.currency-selector').append($selector);

		$selector.chosen({
			width: '100%',
			disable_search_threshold: 10
		}).val(roishop.current.options.specs.currency).trigger('chosen:updated');

		var modal = {
			animation: 'fadeIn',
			size: 'modal-lg',
			header: {
				title: 'Change ROI Currency'
			},
			body: {
				domElement: $form
			},
			footer: {
				content: '<button type="button" class="btn btn-primary update-currency">Update Currency and Reload ROI</button><button type="button" class="btn btn-white" data-dismiss="modal">Close</button>'
			}
		};

		roishop.displayModal(modal);

		$('.update-currency').off('click').on('click', function(){
			var language = $selector.val();
			roishop.current.options.specs.currency = language;

			$.ajax({
				type: 'POST',
				url: '/enterprise/9/assets/ajax/calculator.post.php',
				data: {
					action: 'updateCurrency',
					roi: roishop.current.options.specs.roi_id,
					language: language
				},
				success: function() {
					location.reload();
				}
			});			
		});
	}

	roishop.resetVerification = function(){
		var modal = {
			animation: 'fadeIn',
			header: {
				icon: 'fa-shield',
				title: 'Reset Verification Link?'
			},
			body: {
				content: '<p>Would you like to reset the verification link for this ROI? Once the link is reset it <b>cannot</b> be undone and no prospects will be able to view the ROI without the new link.</p>'
			},
			footer: {
				content: '<button type="button" class="btn btn-primary reset-ver-link-confirm" data-dismiss="modal">Reset</button><button type="button" class="btn btn-white" data-dismiss="modal">Close</button>'
			}
		};

		roishop.displayModal(modal);
		
		$('.reset-ver-link-confirm').on('click', function(){
			$.ajax({
				type: "POST",
				url: "/enterprise/9/assets/ajax/calculator.post.php",
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
					
					roishop.current.options.specs.verification_code = verification;
					roishop.showVerification();
				}
			});
		});
	}

	roishop.connectToSF = function(){
		var $export = $('<div></div>'),
			opporunity,
			link_id,
			link_title;

		roishop.current.options.specs.changeOpp = false;

		$export.rsmodal({
			header: {
				content: [{
					tag: 'h1',
					children: [{
						html: 'Connect to Opportunity'
					}]
				}]
			},
			body: {
				content: [{
					tag: 'h1',
					children:[{
						html: sprintf('This ROI is currently linked to: <strong>%s</strong>', roishop.current.options.specs.linked_title ? roishop.current.options.specs.linked_title : 'Unattached' )
					}] 
				},{
					html: '<br/>'
				},{
					attributes: {
						class: 'form-group'
					},
					children: [{
						html: '<label class="col-lg-5">Enter Opportunity to Search For</label>'
					},{
						attributes:{
							class: 'col-lg-7 input-holder'
						},
						children:[{
							tag: 'input',
							attributes: {
								class: 'form-control'
							},
							actions: {
								keyup: function(){
									opporunity = $(this).val();
								},
								keypress: function(e){
									if(e.which == 13) $export.find('#search_opp').click();
								}
							}
						}]
					}]
				},{
					attributes: {
						class: 'form-group'
					},
					children: [{
						html: '<label class="col-lg-5">Select Opportunity</label>'
					},{
						attributes:{
							class: 'col-lg-7 input-holder'
						},
						children:[{
							tag: 'select',
							type: 'select',
							attributes: {
								class: 'form-control',
								id: 'linked_opportunity'
							},
							actions: {
								change: function(){
									link_id = $(this).val();
									link_title = $(this).find('option:selected').text();
								}
							}
						}]
					}]
				},{
					html: '<hr/>'
				},{
					attributes: {
						class: 'form-group'
					},
					children: [{
						tag: 'a',
						attributes:{
							class: 'btn btn-success col-lg-12',
							id: 'search_opp'
						},
						children:[{
							html: 'Search for Opportunity'
						}],
						actions: {
							click: function(){
								$(this).html('Searching for opportunities...');

								$.ajax({
									type: 'GET',
									url: '/enterprise/9/assets/ajax/calculator.get.php',
									data: {
										action: 'getSFOpportunities',
										user_id: roishop.current.options.integration.code,
										opp_name: encodeURI(opporunity)
									},
									success: function(elements){console.log(elements);
										var opportunities = $.parseJSON(elements);
										if(! $.isEmptyObject(opportunities)){
											$export.find('#linked_opportunity').html('');
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
								})
							}
						}
					}]
				},{
					html: '<hr/>'
				},{
					attributes: {
						style: "float:right;"
					},
					children:[{
						tag: "a",
						attributes: {
							type: "button",
							class: "btn btn-primary",
						},
						actions: {
							click: function(){
								$.ajax({
									type: "POST",
									url: "/enterprise/9/assets/ajax/calculator.post.php",
									data: {
										action: 'linkToOpportunity',
										linked_id: link_id,
										linked_title: link_title,
										roi_id: roishop.current.options.specs.roi_id
									},
									success: function(){
										roishop.current.options.specs.sfdc_link = link_id;
										roishop.current.options.specs.linked_title = link_title;
										roishop.exportToSalesforce();
									}
								})
							}
						},
						children:[{
							html: "Connect"
						}]
					},{
						tag: "a",
						attributes: {
							type: "button",
							class: "btn btn-white",
						},
						actions: {
							click: function(){
								$export.rsmodal('close');
							}
						},
						children:[{
							html: "Close"
						}]
					}]
				}]
			}
		})
	}

	roishop.exportToSalesforce = function(){
		if(! roishop.current.options.specs.sfdc_link || roishop.current.options.specs.changeOpp){
			roishop.connectToSF();
		} else {
			if(roishop.current.options.integrations){
				$.each(roishop.current.options.integrations, function(){
					if(this.type == "sfdc") {
						var elements = JSON.parse(this.elements),
							$export = $('<div></div>');

						var body = [{
							tag: 'h1',
							children:[{
								html: 'This ROI is currently linked to: '
							},{
								tag: 'a',
								children: [{
									html: sprintf('<strong>%s</strong>', roishop.current.options.specs.linked_title ? roishop.current.options.specs.linked_title : 'Unattached')
								}],
								actions: {
									click: function(){
										roishop.current.options.specs.changeOpp = true;
										roishop.exportToSalesforce();
									}
								}
							}]
						},{
							html: '<hr/>'
						}];

						var buttons = [{
							html: '<hr/>'
						},{
							attributes: {
								style: "float:right;"
							},
							children:[{
								tag: "a",
								attributes: {
									type: "button",
									class: "btn btn-success",
								},
								actions: {
									click: function(){
										var $fields = $export.find('[integration-id]');

										$fields.each(function(){
											var field = '{"' + $(this).attr('integration-id') + '":"' + $(this).val() + '"}',
												field_name = $(this).attr('integration-id');

												$.ajax({
													type	: 	"POST",
													url		:	"/enterprise/9/assets/ajax/calculator.post.php",
													data: {
														action: 'updateSFRecord',
														fields: field,
														user_id: roishop.current.options.integration.code,
														linked_id: roishop.current.options.specs.sfdc_link,					
													},
													success: function(data){
														toastr.info(field_name + ' was successfully integrated');
													}
												});												
										});
									}
								},
								children:[{
									html: "Export to Salesforce"
								}]
							}]
						}]

						$export.rsmodal({
							header: {
								content: [{
									tag: 'h1',
									children: [{
										html: 'Salesforce Integration'
									}]
								}]
							},
							body: {
								content: $.merge($.merge(body, elements), buttons)
							}
						})
					}
				});
			}
		}
			
	}

	roishop.addContributor = function(){
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

		roishop.displayModal(modal);
		
		$('.add-contributor-initialize').off('click').on('click', function(){
			var newContributorName = $('#contributor').val();
			if(newContributorName) {			
				$.ajax({
					type: "POST",
					url: "/enterprise/9/assets/ajax/calculator.post.php",
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
	}

	roishop.viewAllowedUsers = function(){
		$.ajax({
			type: 'GET',
			url: '/enterprise/9/assets/ajax/calculator.get.php',
			data: {
				action: 'getcontributor',
				roi: getQueryVariable('roi')
			},
			success: function(contributors){
				var currentContributors = $.parseJSON(contributors);
				var modalBody = '<table class="table table-hover contributor-names-table">\
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
				
				roishop.displayModal(modal);

				$('.remove-contributor').off('click').on('click', function() {
					var contributorId = $(this).closest('tr').data('contributor-id');
		
					$.ajax({
						type	: 	"POST",
						url		:	"/enterprise/9/assets/ajax/calculator.post.php",
						data: {
							action: 'delcont',
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

	roishop.showVerification = function(){
		if(roishop.current && roishop.current.options){
			var opts = roishop.current.options;

			var modal = {
				animation: 'fadeIn',
				header:	{
					icon: 'fa-shield',
					title: 'ROI Verification Link',
					subtitle: 'The following link can be used to give anyone access to this ROI.'
				},
				body: {
					content: '<textarea class="ver-link-output" spellcheck="false" readonly="readonly">www.theroishop.com/enterprise/9/?roi=' + opts.specs.roi_id + '&v=' + opts.specs.verification_code + '</textarea>'
				},
				footer: {
					content: '<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>'
				}
			};
	
			roishop.displayModal(modal);
		}
	}

	roishop.displayModal = function(opts){
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
			modalBody += opts.body.content;
		}
		modalBody += '</div>';
		
		if(opts.footer) {
			var modalFooter = '<div class="modal-footer">';
			if(opts.footer && opts.footer.content) {
				modalFooter +=	opts.footer.content;
			}	
			modalFooter +=	'</div>';
		} else { var modalFooter = ''; }
		
		var modal = '<div class="modal-dialog ' + (opts.size ? opts.size : '') + '"><div class="modal-content animated ' + (opts.animation ? opts.animation : 'slideInDown') + '">';
			modal += modalHeader + modalBody + modalFooter + '</div></div>';
				
		var $modal = $('<div class="modal inmodal" id="modal-shell" tabindex="-1" role="dialog" aria-hidden="true"></div>');		

		$modal
			.html(modal)
			.modal('show');

		if(opts.body.domElement) $modal.find('.modal-body').append(opts.body.domElement);
	}

    if (typeof(jQuery) != 'undefined') {
        (function($){
            $.fn.roishop = function(method) {
                var spreadsheetContainer = $(this).get(0);
                if (! spreadsheetContainer.roishop) {
                    return roishop($(this).get(0), arguments[0]);
                } else {
                    return spreadsheetContainer.roishop[method].apply(this, Array.prototype.slice.call( arguments, 1 ));
                }
            };
    
        })(jQuery);
    }
	
	// Ajax call to return the build of the current ROI
	var current_roi	= getQueryVariable('roi'),
		verification = getQueryVariable('v'),
		action 		= 'RetrieveRoi',
		ajax_url 	= 'assets/ajax/calculator.get.php';
		
	$.get( ajax_url, { action: action, verification: verification, roi: current_roi } ).done(function(elements){
			elements = JSON.parse(elements);
			var options = {
				specs: elements.roiSpecs,
				integration: elements.integration,
				integrations: elements.integrations,
				cells: elements.cells,
				build: elements.versionBuild,
				values: elements.storedValues,
				verification: elements.verification,
				versionSpecs: elements.versionSpecs
			};

			$('#wrapper').roishop(options);
	});

	return roishop;
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
			roishop.current.sheet.calculate();
		}
		
		modal.close = function(){
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

		builder.options = $.extend(true, defaults, opts);
		builder.holder();
		builder.render();
		if(builder.options.type && typeof(builder[builder.options.type]) == 'function'){		
			builder[builder.options.type]();
		}
		builder.attributes();
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
        var html;
        builder.options.$container = $(sprintf('<%s%s></%s>', builder.options.tag, builder.options.class ? sprintf(' class="%s"', builder.options.class) : '', builder.options.tag));
		
        if(builder.options.html){
            if(typeof builder.options.html === "object"){
                html = builder.options.html.join('');
            } else {
                html = builder.options.html;
            };
            builder.options.$container = builder.options.jqElement || ( builder.options.children && builder.options.children.length ) ? $(html) : html;
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
		var selectCount = 1;
		
		if(builder.options.choices){
			$.each(builder.options.choices, function(){
				builder.options.$container.append(sprintf('<option value="%s">%s</option>', this.value != null ? this.value : selectCount, this.text ? this.text : this.value));
				selectCount++;
			});		
		};

		builder.options.$container.chosen({
			width: '100%',
			disable_search_threshold: 10,
			inherit_select_classes: true,
			custom_classes: builder.options.customClass || null
		});
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
		builder.options.$container.jexcel(builder.options);
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
				var current = 0;

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
        if(builder.options.parent){
            var $parent,
                parent = builder.options.parent;
                //console.log(builder.options);
            if(typeof parent === "object"){
                if(parent.local){
                    $parent = builder.options.$parent.find(parent.selector);
                } else {
                    $parent = $(selector);
                }
            } else {
                $parent = $(parent);
            }

            $parent.append(builder.options.$container);
        } else {
            builder.options.$parent.append(builder.options.$container);
        }
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
			builder.options.$container.off(event).on(event, action);
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