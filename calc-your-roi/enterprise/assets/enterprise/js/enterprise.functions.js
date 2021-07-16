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
			sectionEntries: [],
			cells: [],
			letters: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
			currencies: {
				label: 'Choose by currency',
				currency: [
					{ value: 'usd', text: 'United States Dollar' },
					{ value: 'aud', text: 'Australian Dollar' },
					{ value: 'be-nl', text: 'Belgium-Dutch' },
					{ value: 'can', text: 'Canadian Dollar' },
					{ value: 'da-dk', text: 'Danish Krona' },
					{ value: 'eur', text: 'Euro (English Notation)' },
					{ value: 'eur-si', text: 'Euro (SI Notation)' },
					{ value: 'fr', text: 'French' },
					{ value: 'ja', text: 'Japanese Yen' },
					{ value: 'da-dk', text: 'Norwegian Krona' },
					{ value: 'pt-br', text: 'Portuguese (Brazil)' },
					{ value: 'gbp', text: 'Pound Sterling' },
					{ value: 'ru', text: 'Russian' },
					{ value: 'es', text: 'Spanish' },
					{ value: 'da-dk', text: 'Swedish Krona' },
					{ value: 'fr-ch', text: 'Swiss Franc' }
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

			calc.buildCells();

			calc.mergeValues();
			calc.buildStructure();
			calc.prepareCalculator();
			calc.createElements();
			calc.createDomElements();
			calc.sheet = new sheet(calc.cells);
			calc.attachEvents();
			calc.equalizeHeights();
			calc.updateGraphs();

			console.log(calc);
		}

		calc.buildCells = function(){
			var cells = [],
				entries = [],
				format,
				years = calc.options.compSpecs.retPeriod;
			
			var cell_format = function(cell){
				if(cell.format) return cell.format;

				switch(cell.Format){
					case '1': format = '$0,0'; break;
					case '2': format = '0,0'; break;
					case '4': format = null; break;
					default: format = cell.Format; break;					
				}

				if ( cell.precision > 0 ) {
					format += '.';
					
					for( var i=0; i < cell.precision; i++ ) {
						format += '0';
					}
				}
				
				if( cell.Format == 2 ) {
					format += '%';
				}

				if( cell.Type == 2 || cell.Type == 'textarea' ) format = null;
				if( cell.Type == 1 ) format = '(' + format + ')';
				
				return format;
			}

			if(calc.options.entries){
				var customEntries = 0;
				$.each(calc.options.entries, function(){
					var entry = this;
					years = 1;
					if(this.annual > 0) years = calc.options.compSpecs.retPeriod;

					if(this.Title.includes('data-formula')){
						var $temp = $('<div>' + this.Title + '</div>'),
							$div = $temp.find('[data-formula]');

						$.each($div, function(){
							customEntries++;
							var	formula = $(this).data('formula'),
								format = $(this).data('format') || '0,0',
								address = 'RSX' + customEntries;

								$(this).attr('calc-id', address);

								var cell = {
									address: address,
									format: format,
									formula: formula,
									label: 'Auto Generated calculation: ' + customEntries
								};
		
								entries.push(cell);
						});
						
						this.Title = $temp.html();
					};

					for( var i=0; i<years; i++){
						var cell = {
							address: this.address ? this.address : calc.options.letters[i] + this.ID,
							format: cell_format(this),
							formula: this.formula ? this.formula : null,
							label: this.Title ? this.Title : null,
							value: this.value ? this.value : null
						};

						if(this.Type == 2 || cell.Type == 'textarea') cell.float = 'text';

						var totalChoices = 1,
							visibility = [],
							rules = {};

						$.each(calc.options.entryChoices, function(){
							if(entry.ID == this.entryid){
								var show = {};

								show.operator = "==";
								show.value = totalChoices;

								totalChoices++;

								if(this.show_map) {
									show.show = this.show_map.split(",");
								}

								visibility.push(show);
							}
						});

						rules.visibility = visibility;

						if(rules.visibility.length) cell.rules = rules;

						if(this.options) {
							var custom = $.parseJSON(this.options);
							cell = $.extend(true, cell, custom);
						}

						entries.push(cell);
					}
				});
			}

			var grand_total = '',
				npv = '';

			for( var i=0; i<calc.options.compSpecs.retPeriod; i++){
				var annual_total = '';
				
				$.each(calc.options.sections, function(){
					if(this.formula){
						var a = 'ST' + calc.options.letters[i] + this.ID
						annual_total += ( annual_total == '' ? ( '( ' + a ) : ' + ' + a );
					}
				});

				annual_total += ' + AC' + ( i + 1 ) + ' )';

				npv += ( npv == '' ? ( 'NPV( 0.02, AT' + ( i + 1 ) ) : ', AT' + ( i + 1 ) );

				var cell = {
					address: 'AT' + ( i + 1 ),
					format: '($0,0)',
					formula: annual_total,
					label: 'Annual Total for Year' + i
				};

				entries.push(cell);
			}

			npv += ' )';

			$.each(calc.options.sections, function(i, section){
				var exclude = false;
				$.each(calc.options.hiddenSections, function(){
					if(this.entity_id == section.ID) exclude = true;
				});

				var cell = {
					address: 'INC' + section.ID,
					format: '0,0',
					label: section.Title ? section.Title + 'Include' : null,
					value: exclude ? 0 : 1,
					rules: {
						visibility: [{
							show: ["#" + section.ID + "Navigation", "#" + section.ID + "Pod", "#" + section.ID + "Section", "#" + section.ID + "Summary"],
							operator: "==",
							value: 1
						}]
					}
				};

				entries.push(cell);

				if(this.formula){
					years = calc.options.compSpecs.retPeriod;
				
					var cell = {
						address: 'CON' + this.ID,
						format: '0,0%',
						formula: null,
						label: this.Title ? 'Conservative Factor: ' + this.Title : null,
						value: null
					};
	
					entries.push(cell);
	
					for( var i=0; i<years; i++){
						var cell = {
							address: ( this.address ? this.address : 'ST' ) + calc.options.letters[i] + this.ID,
							format: '($0,0)',
							formula: this.formula ? ( '( IF( INC' + this.ID + '=1,' + this.formula + ', 0 ) ) * ( 1 - CON' + this.ID + ' ) * I' + ( i + 1 ) ): null,
							label: this.Title ? ( this.Title + 'Annual Total for Year ' + i ) : null,
							value: null
						};
	
						entries.push(cell);
					}
	
					var section_address = ( this.address ? this.address : 'ST' ) + this.ID
	
					var cell = {
						address: section_address,
						format: '($0,0)',
						formula: 'SUM( STA' + this.ID + ':ST' + calc.options.letters[years - 1] + this.ID + ' )',
						label: this.Title ? 'Conservative Factor: ' + this.Title : null,
						value: null
					};
	
					grand_total += ( grand_total == '' ? ( '( ' + section_address ) : ' + ' + section_address );
	
					entries.push(cell);
				}
			});

			var totalcost = '';

			for( var i=0; i<calc.options.compSpecs.retPeriod; i++ ){
				var cost = '';

				if(calc.options.entries){
					$.each(calc.options.entries, function(){
						if(this.cost > 0){
							if(i > 0 && this.annual > 0){
								cost += ( cost == '' ? ( '( 0 - ' + calc.options.letters[i] + this.ID ) : ( ' + ' + calc.options.letters[i] + this.ID ) );
							}

							if( i==0 ) cost += ( cost == '' ? ( '( 0 - A' + this.ID ) : ( ' - A' + this.ID ) );
						}
					});

					cost += ' )';

					totalcost += ( totalcost == '' ? ( '( AC' + ( i + 1 ) ) : ( ' + AC' + ( i + 1 ) ) );
					
					var cell = {
						address: 'AC' + ( i + 1 ),
						format: "($0,0)",
						formula: cost
					}

					entries.push(cell);
				}
			}

			totalcost += ' )';

			var cell = {
				address: 'TC1',
				format: "($0,0)",
				formula: totalcost
			}

			entries.push(cell);

			if(grand_total !== ''){
				grand_total += ' + TC1 )';

				var cell = {
					address: 'GT1',
					format: '($0,0)',
					formula: grand_total,
					label: 'Grand Total'
				};

				entries.push(cell);	
			}

			for( var i=1; i<=years; i++){
				var cell = {
					address: 'I' + i,
					format: '0,0',
					formula: 'IF( IMP1 > ( ' + i + ' * 12 ), 0, IF( IMP1 < ( ' + ( i - 1 ) + ' * 12 ), 1, ( ( ' + i + ' * 12 ) - IMP1 ) / 12 ) )',
					label: 'Implementation Period - Year ' + i,
					value: null
				};

				entries.push(cell);				
			}

			var cell = {
				address: 'ROILINK1',
				label: 'Link to the ROI',
				value: window.location.href.match(/^.*\//) + '?roi=' + calc.options.specs.roi_id + '&v=' + calc.options.specs.verification_code
			};

			entries.push(cell);

			var cell = {
				address: 'VISIT1',
				label: 'Number of ROI Visits',
				value: calc.options.specs.visits
			};

			entries.push(cell);

			var cell = {
				address: 'UNIQUE1',
				label: 'Number of Unique ROI Visits',
				value: calc.options.specs.unique_ip
			};

			entries.push(cell);

			var cell = {
				address: "RP1",
				label: "Return Period",
				format: "0,0",
				value: years
			};

			entries.push(cell);
			
			var cell = {
				address: 'IMP1',
				format: '0,0',
				label: 'Implementation Period',
				value: null
			};

			entries.push(cell);

			cell = {
				address: "ROI1",
				label: "Return on Investment",
				format: "0,0%",
				formula: 'IF( ABS(TC1) > 0, GT1 / ABS(TC1), 0 )'
			}

			entries.push(cell);

			cell = {
				address: "NPV1",
				label: "Net Present Value",
				format: '($0,0)',
				formula: npv
			}

			entries.push(cell);

			var payback = 'MIN( ( ( ABS( TC1 ) / ( ( GT1 - TC1 ) / ( ( ' + calc.options.compSpecs.retPeriod + ' * 12 - ROUNDUP( IMP1 / 12, 0 ) * 12 ) / 12 + ( ROUNDUP( IMP1 / 12, 0 ) * 12 - IMP1 ) / 12 ) ) ) * 12 + IMP1 ), ' + calc.options.compSpecs.retPeriod + ' * 12 )';

			cell = {
				address: "PAY1",
				label: "Payback Period",
				format: "0,0[.]0",
				formula: payback
			}

			entries.push(cell);

			var cells = entries.concat(calc.options.roiCells);

			calc.cells = cells;

			$.each(calc.options.overriddenValues, function(){
				var overridden = this;
				$.each(calc.cells, function(){
					if(overridden.entryid == this.address){
						this.forcedValue = overridden.value;
					}
				})
			});
		}

		calc.mergeValues = function(){
			var parsedValues;
			$.each(calc.options.values, function(i, v){
				try {
					parsedValues = parsedValues ? parsedValues : JSON.parse(this.value_array);
				} catch(e){ 
					if(i === 0){
						toastr.error('An unexpected error occurred while loading the latest values for this calculator. The most recent values available have been loaded instead.', 'Loading Error', {
							timeOut: 7000
						});
					}
				}
			});

			var values = [];
			if(calc.options.oldValues){
				$.each(calc.options.oldValues, function(){
					var address,
						included = false;

					this.oldValue = true;

					if(this.entry){
						if(this.entryid.includes('currentValueCon')){
							values['CON' + this.entryid.replace('currentValueCon', '')] = this;
							included = true;
						}

						if(this.entryid.includes('impPeriod')){
							values['IMP1'] = this;
							included = true;
						}

						if(this.entryid.includes('check')){
							values['INC' + this.entryid.replace('check', '')] = this;
							included = true;
						}

						if(this.entryid.includes('yr')){
							var entry = this.entryid.split('yr');
							var letter = calc.options.letters[entry[1] - 1];

							values[letter + entry[0]] = this;
							included = true;
						}
					}

					if(!included){
						if($.isNumeric(this.entryid.slice(0,1))){
							values['A' + this.entryid] = this;
						} else {
							values[this.entryid] = this;
						}
						
					}
				})
			}

			if(parsedValues){
				$.each(parsedValues, function(){
					values[this.address] = this;
				});
			}

			if(calc.cells && calc.cells.length){
				$.each(calc.cells, function(){
					if(values[this.address]){
						this.value = this.format && this.format.includes('%') && values[this.address].oldValue ? ( values[this.address].value.replace('%', '') / 100 ) : values[this.address].value;
						this.forcedValue = values[this.address].forcedValue;
					}

					calc.options.cells[this.address] = this;
				});

				$.each(calc.cells, function(){
					var formula = this.formula;
	
					// check for indirect references in formula
					if(formula && formula.includes("[[") && formula.includes("]]")){
						var begin = formula.lastIndexOf("[[") ? formula.lastIndexOf("[[") + 2 : 0;
						var end = formula.lastIndexOf("]]");
			
						var indirect = formula.substring(begin, end);
						var indirect_value = calc.options.cells[indirect].value || 0;
			
						formula = formula.replace("[[" + indirect + "]]", indirect_value);

						this.formula = formula;
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

		calc.setupStructure = function(){
			calc.build = [];
			calc.build.build = [];
			calc.build.templates = {};

			calc.structureTemplates();
		}

		calc.buildCalculator = function(){
			
			// Create calculator holder
			calc.build.build.push({
				html: "<div class=\"white-bg\" id=\"page-wrapper\"></div>"
			});

			calc.dashboard();
			calc.sections();
		}

		calc.dashboard = function(){
			var dashboard_header = {
				parent: "#page-wrapper",
				html: 	"<div id=\"dashboard\">\
							<div class=\"row border-bottom white-bg dashboard-header\" id=\"dashboard\">\
								<div class=\"col-lg-12\">\
									<h1 style=\"margin-bottom: 20px\">ROI Dashboard | <span calc-id=\"RP1\"></span> Year Projection <span class=\"pull-right pod-total section-total grand-total txt-money\" calc-id=\"GT1\">$0</span></h1>\
									<div class=\"col-lg-12\">\
										<hr>\
										<h3 style=\"font-size: 18px; font-weight: 700;\">Select a section below to review your ROI</h3>\
										<p style=\"font-size: 16px;\">To calculate your return on investment, begin with the first section below. The information entered therein will automatically populate corresponding fields in the other sections. You will be able to move from section to section to add and/or adjust values to best reflect your organization and process. To return to this screen, click the ROI Dashboard button to the left.</p>\
									</div>\
								</div>\
							</div>\
						</div>"
			};

			calc.build.build.push(dashboard_header);

			var builds = [];

			$.each(calc.options.sections, function(){
				if(this.pod > 0){
					var pod = [{
						options: {
							id: this.ID,
							title: this.Title,
							cell: 'ST' + this.ID
						}
					}];

					builds.push({
						template: this.formula || this.statistics > 0 ? this.statistics > 0 ? 'podSavings' : 'pod' : 'podNoSummary',
						builds: pod
					})
				}
			});

			var pod_holder = {
				parent: "#page-wrapper > #dashboard",
				html: [
					"<div id=\"PodHolder\" class=\"row border-bottom gray-bg dashboard-header\">\
					</div>"
				],
				children: builds
			};

			calc.build.build.push(pod_holder);
		}

		calc.sections = function(){

			$.each(calc.options.sections, function(){

				var section_id = this.ID;
				if(calc.options.entries){
					var section_entries = $.map(calc.options.entries, function(n, i){
						if(n.sectionName === section_id) return n;
					});
				}

				calc.options.sectionEntries['entries' + section_id] = section_entries;

				var html = [
					sprintf("<div %sid=\"%s\">", this.pod > 0 ? '' : 'style="display: none;" ', ( this.section_id ? this.section_id : this.ID + 'Section' )),
								sprintf("<div class=\"row border-bottom white-bg dashboard-header\" id=\"section%s\">", this.ID),
									"<div class=\"col-lg-12\">",
										sprintf("<h1 style=\"margin-bottom: 20px\">%s %s</h1>", this.Title, this.customformula == 0 ? '' : this.formula || this.grandtotal == 1 ? sprintf('<span class=\"pull-right pod-total section-total grand-total txt-money\" calc-id=\"%s\">$0</span>', this.grandtotal == 1 ? 'GT1' : ( 'ST' + this.ID ) ) : ''),
									"</div>"
					];

				if(this.Caption){
					html.push("<div caption class=\"col-lg-12\"><hr>");

					html.push(sprintf("<div class=\"%s section-writeup\">%s</div>", this.Video ? "col-lg-7" : "col-lg-12", this.Caption));

					if(this.Video){
						html.push("<div class=\"col-lg-5 video\"></div>");
					}

					html.push("</div>");
				}

				html.push(["</div>",
						"<div class=\"row border-bottom gray-bg dashboard-header\">",
							"<div class=\"col-lg-12\">",
								"<div class=\"row\">",
									sprintf("<div writeup class=\"%s\">", this.formula || this.statistics > 0 ? "col-lg-9 col-md-9 col-sm-12 col-xs-12" : "col-lg-12 col-md-12 col-sm-12 col-xs-12"),
										"<div entries" + section_id +" class=\"ibox-content\">",
										"</div>",
									"</div>",
									"<div sidebar" + section_id +" class=\"col-lg-3 col-md-3 col-sm-12 col-xs-12\">",							
									"</div>",
								"</div>",
							"</div>",
						"</div>",
						this.statistics > 0 ? "<div graph class=\"row border-bottom gray-bg dashboard-header\"></div>" : '',
					"</div>"
				].join(''));

				var dashboard_header = {
					parent: "#page-wrapper"
				};

				var entries = [];

				if(section_entries){
					$.each(section_entries, function(){
						var years = 1;
						if(this.annual > 0) years = calc.options.compSpecs.retPeriod;
	
						for( var i=0; i<years; i++){
	
							var entry = {},
								current_entry = this;
	
							var options = {
								label: years == 1 ? this.Title : this.Title + ' - Year ' + ( i + 1 ),
								cell: this.address ? this.address : calc.options.letters[i] + this.ID
							};
	
							if(this.Tip) options.tooltip = this.Tip;
							if(this.append) options.append = this.append;
	
							var choices = [],
								totalChoices = 1;
	
							$.each(calc.options.entryChoices, function(){
								if(current_entry.ID == this.entryid){
									var choice = {
										text: this.value,
										value: this.dropdown_value ? this.dropdown_value : totalChoices
									};
	
									totalChoices++;
	
									choices.push(choice);
								}
							});
	
							if(this.options){
								var custom = $.parseJSON(this.options);
								options = $.extend(true, options, custom);
							}
	
							switch(this.Type){
								case '0':
								case 'input':
									entry.template = "input";
								break;
	
								case '1':
								case 'output':
									entry.template = "output";
								break;
	
								case '2':
								case 'textarea':
									if(this.Tip){
										entry.template = "textareaTooltip";
									} else {
										entry.template = "textarea";
									}
								break;
	
								case '3':
								case 'dropdown':
									entry = {
										attributes: {
											class: "form-group",
											id: this.address ? this.address : calc.options.letters[i] + this.ID
										},
										children: [{
											tag: "label",
											attributes: {
												class: "control-label col-lg-7 col-md-7 col-sm-7"
											},
											children: [{
												html: this.Title
											}]
										},{
											attributes:{
												class: "col-lg-5 col-md-5 col-sm-5"
											},
											children: [{
												tag: "select",
												type: "select",
												attributes: {
													"calc-id": this.address ? this.address : calc.options.letters[i] + this.ID
												},
												choices: choices
											}]
										}]
									}
								break;
	
								case '11':
								case 'slider':
									if(this.Tip){
										entry.template = "inputSliderTooltip";
									} else {
										entry.template = "inputSlider";
									}
								break;
	
								case '13':
								case 'header':
									entry.template = "subsectionHeader";
									options.text = this.Title;
								break;
	
								case '14':
									entry.template = "savingsSummary";
								break;
	
								case 'text':
									entry.template = "text";
									options.text = this.Title;
								break;
							}
		
							entry.builds = [{
								options: options
							}];
	
							entries.push(entry);
						}
	
					});
				}

				var baseline = [
					"<div class=\"ibox-content\">",
						"<div class=\"row\">",
							"<div class=\"col-lg-12 col-md-12\">",
								sprintf("<h1 class=\section-header\">%s</h1>", this.Title),
								"<div class=\"table-responsive m-t\">",
									"<table class=\"table invoice-table\">",
										"<tbody>"
				]
		
				for(var i=1; i<=calc.options.compSpecs.retPeriod; i++){
						baseline.push(sprintf("<tr><td>Year %s: </td><td calc-id=\"ST%s%s\">$0</td></tr>", i, calc.options.letters[i - 1], this.ID));
				}
						
						baseline.push(sprintf("<tr><td>%s Total: </td><td calc-id=\"ST%s\">$0</td></tr>", this.Title, this.ID));
		
						baseline.push("</tbody>",
									"</table>",
								"</div>",
								"<div class=\"value-holder\">",
									sprintf("Conservative Factor: <span class=\"pull-right\" calc-id=\"CON%s\"></span>", this.ID),
									"<div class=\"row slider-padding\">",
										"<div class=\"col-lg-12\"></div>",
									"</div>",
								"</div>",
							"</div>",
						"</div>",
					"</div>");

				var statistics = [
					"<div class=\"ibox-title\">",
						"<h5 class=\"col-lg-12\">ROI Statistics</h5>",
					"</div>",
					"<div class=\"faq-item\">",
						"<div class=\"row\">",
							"<div class=\"col-lg-8\">",
								"<a class=\"faq-question collapsed nohover\">Return on Investment</a>",
							"</div>",
							"<div class=\"col-lg-4\">",
								"<div class=\"pull-right return-on-investment\" calc-id=\"ROI1\"></div>",
							"</div>",
						"</div>",
					"</div>",
					"<div class=\"faq-item\">",
						"<div class=\"row\">",
							"<div class=\"col-lg-8\">",
								"<a class=\"faq-question collapsed nohover\">Net Present Value</a>",
							"</div>",
							"<div class=\"col-lg-4\">",
								"<div class=\"pull-right return-on-investment\" calc-id=\"NPV1\"></div>",
							"</div>",
						"</div>",
					"</div>",
					"<div class=\"faq-item\">",
						"<div class=\"row\">",
							"<div class=\"col-lg-8\">",
								"<a class=\"faq-question collapsed nohover\">Payback Period</a>",
							"</div>",
							"<div class=\"col-lg-4\">",
								"<div class=\"pull-right return-on-investment\"><span calc-id=\"PAY1\"></span> months</div>",
							"</div>",
						"</div>",
					"</div>"
				];

				var implementation_slider = [{
					html: [
						"<div class=\"faq-item\">",
							"<div class=\"row conservative-slider\">",
								"<div class=\"value-holder\">",
									"Implementation Period: <span class=\"pull-right\"><span calc-id=\"IMP1\"></span> months</span>",
									"<div class=\"row slider-padding\">",
										"<div class=\"col-lg-12\"></div>",
									"</div>",
								"</div>",
							"</div>",
						"</div>"
					],
				},{
					parent:{
						local: true,
						selector: ".value-holder .col-lg-12"
					},
					type:"slider",
					step:1,
					min: 0,
					max: calc.options.compSpecs.retPeriod * 12,
					attributes:{
						"calc-id":"IMP1"
					}
				}];

				var child = [{
					parent: "[entries" + section_id + "]",
					attributes: {
						class: "form-horizontal"
					},
					children: entries
				}]
				
				if(this.formula){
					child.push({
						parent: "[sidebar" + section_id + "]",
						attributes: {
							class: "ibox float-e-margins"
						},
						children: [{
							html: this.statistics > 0 ? statistics : baseline
						},{
							parent:{
								local: true,
								selector: ".value-holder .col-lg-12"
							},
							type:"slider",
							step:5,
							attributes:{
								"calc-id": sprintf("CON%s", this.ID)
							}
						}]
					});
				}

				if(this.statistics > 0){
					child.push({
						parent: "[sidebar" + section_id + "]",
						attributes: {
							class: "ibox float-e-margins"
						},
						children: [{
							html: statistics
						}]
					});
				}
				
				if(this.formula || this.statistics > 0){
					child.push({
						parent: "[sidebar" + section_id + "]",
						attributes: {
							class: "ibox float-e-margins"
						},
						children: implementation_slider
					});
				}

				if(this.testimonials > 0 && calc.options.testimonials.length > 0){
					child.push({
						parent: {
							local: true,
							selector: ".section-writeup"
						},
						html: '<hr>'						
					},{
						parent: {
							local: true,
							selector: ".section-writeup"
						},
						template: "testimonials",
						builds: [{}]
					})
				}

				if(this.Video){
					child.push({
						parent: {
							local: true,
							selector: ".video"
						},
						type: "video",
						src: this.Video
					});
				}

				dashboard_header.html = html;
				dashboard_header.children = child;

				if(this.statistics > 0){
					dashboard_header.children.push({
						parent: "[graph]",
						template: "summaryChart",
						builds: [{}]
					})
				}

				calc.build.build.push(dashboard_header);
			})
		}

		calc.structureTemplates = function(){

			var categories = [],
				series = [],
				colors = ['#265F96', '#114F1C', '#318E1E', '#1aadce', '#492970', '#f28f43', '#77a1e5', '#c42525', '#a6c96a', '#910000'];

			for( var i=1; i<=calc.options.compSpecs.retPeriod; i++ ){
				categories.push("Year " + i);
			}

			$.each(calc.options.sections, function(n, section){
				if(this.formula && this.customformula != 0){
					var entry = {},
						equations = [];
						
					for( var i=1; i<=calc.options.compSpecs.retPeriod; i++ ){
						equations.push('ST'+ calc.options.letters[i - 1] + this.ID);
					}
					
					entry.name = this.Title;
					entry.type = "column";
					entry.color = colors[n - 1];
					entry.equations = equations;

					series.push(entry);
				}
			});

			var equations  = [];
			for( var i=1; i<=calc.options.compSpecs.retPeriod; i++ ){
				equations.push('ABS(AC' + i + ')');
			}

			series.push({
				name: "Cost",
				type: "column",
				color: "#910000",
				equations: equations
			})

			var chart = [{
				type: "chart",
				attributes: {
					class: "bar-chart",
					style: "width: 100%;"
				},
				chart: {
					type: "column",
					margin: 75,
					animation: false
				},
				title: {
					text: "Your Potential Return on Investment"
				},
				xAxis:{
					categories: categories
				},
				yAxis: {
					min: 0,
					style: {
						color: "#333",
						fontWeight: "bold",
						fontSize: "12px",
						fontFamily: "Trebuchet MS, Verdana, sans-serif"
					},				
					title: {
						text: "Money"
					}
				},
				tooltip: {
					headerFormat: "<span style=\"font-size:10px\">{point.key}</span><table>",
					pointFormat: "<tr><td style=\"color:{series.color};padding:0\">{series.name}: </td><td style=\"color:{series.color};padding:0;padding-left:10px;\"><b> {point.y:,.0f}</b></td></tr>",
					footerFormat: "</table>",
					shared: true,
					useHTML: true
				},
				plotOptions: {
					column: {
						pointPadding: 0.2,
						borderWidth: 0
					}
				},
				series: series
			}];

			calc.options.summaryGraph = chart;

			calc.build.templates.summaryChart = {
				html: [
					"<div class=\"col-lg-12\">",
						"<div class=\"row\">",
							"<div class=\"col-md-12 col-sm-12 col-xs-12\">",
								"<div class=\"ibox float-e-margins\">",
									"<div class=\"ibox-content\" style=\"padding-left: 30px;\">",
										"<div summaryChart class=\"row bar-chart-container\">",
										"</div>",
									"</div>",
								"</div>",
							"</div>",
						"</div>",
					"</div>"
				],
				children: [{
					parent:{
						"local": true,
						"selector": "[summaryChart]"
					},
					children: chart
				}]
			};
			
			var summary = [];

			summary.push('<div class="ibox-content"><div class="table-responsive" style="border: 2px solid #ddd;"><table id="summary-table" class="table table-hover" style="margin-bottom: 0;">');
			summary.push('<thead><tr><th></th>');

			for( var i=1; i<=calc.options.compSpecs.retPeriod; i++ ){
				summary.push(sprintf('<th>Year %s</th>', i));
			}

			summary.push('<th>Total</th></thead><tbody>');

			$.each(calc.options.sections, function(i, section){
				if(section.formula){
					summary.push(sprintf('<tr id="%s"><th class="section-navigation">%s</th>', section.ID + 'Summary', section.Title));
					for( var i=1; i<=calc.options.compSpecs.retPeriod; i++ ){
						summary.push(sprintf('<td class="txt-money" calc-id="%s">$0</td>', 'ST' + calc.options.letters[i - 1] + section.ID));
					}
					summary.push(sprintf('<td class="txt-money" calc-id="%s">$0</td></tr>', 'ST' + section.ID));
				}
			});

			summary.push('<tr><th>Cost</th>');
			for( var i=1; i<=calc.options.compSpecs.retPeriod; i++ ){
				summary.push(sprintf('<td class="txt-removed" calc-id="%s">$0</td>', 'AC' + i));
			}
			summary.push('<td class="txt-removed" calc-id="TC1">$0</td></tr><tr><th>Total</th>');
			for( var i=1; i<=calc.options.compSpecs.retPeriod; i++ ){
				summary.push(sprintf('<td class="txt-money" calc-id="%s">$0</td>', 'AT' + i));
			}
			summary.push('<td class="txt-money" calc-id="GT1">$0</td></tr></table></div></div>');

			calc.build.templates.savingsSummary = {
				html: summary          
			};			

			if(calc.options.testimonials.length > 0){
				var testimonials = {};
				testimonials.type = "revolver";
				testimonials.children = [];
				
				$.each(calc.options.testimonials, function(){
					var blockquote = {};

					blockquote.tag = "blockquote";
					blockquote.children = [];

					var html = '';

					if(this.testimonial){
						html += sprintf("<p>%s</p>", this.testimonial);
					}

					if(this.author){
						html += sprintf("<p>- %s</p>", this.author);
					}

					var quote = {
						html: html
					};

					blockquote.children.push(quote);
					testimonials.children.push(blockquote);
				});

				calc.build.templates.testimonials = testimonials;
			}
			
			calc.build.templates.textarea = {
				html: [
					"<div id=\"{{cell}}\" class=\"form-group\">",
						"<label class=\"control-label col-lg-7 col-md-7 col-sm-7\">{{label}}</label>",
						"<div class=\"col-lg-5 col-md-5 col-sm-5\">",
							"<textarea class=\"form-control\" calc-id=\"{{cell}}\"></textarea>",
						"</div>",
					"</div>"
				]            
			};

			calc.build.templates.dropdown = {
				attributes: {
					class: "form-group"
				},
				children: [{
					tag: "label",
					attributes: {
						class: "control-label col-lg-7 col-md-7 col-sm-7"
					},
					children: [{
						html: "Is your onboarding automated or manual"
					}]
				},{
					attributes:{
						class: "col-lg-5 col-md-5 col-sm-5"
					},
					children: [{
						tag: "select",
						type: "select",
						attributes: {
							"calc-id": "{{cell}}"
						},
						choices: [{
							"text":"Make Selection",
							"value":0
						},{
							"text":"Manual",
							"value":1
						},{
							"text":"Automated",
							"value":2
						}]
					}]
				}]
			}

			calc.build.templates.textareaTooltip = {
				html: [
					"<div id=\"{{cell}}\" class=\"form-group\">",
						"<label class=\"control-label col-lg-7 col-md-7 col-sm-7\">{{label}} <i class=\"fa fa-question-circle tooltipstered\" title=\"{{tooltip}}\"></i></label>",
						"<div class=\"col-lg-5 col-md-5 col-sm-5\">",
							"<textarea class=\"form-control\" calc-id=\"{{cell}}\"></textarea>",
						"</div>",
					"</div>"
				]            
			};

			calc.build.templates.text = {
				html: ["{{text}}"]
			}

			calc.build.templates.inputSliderTooltip = {
				html: [
					"<div id=\"{{cell}}\" class=\"form-group\">",
						"<label class=\"control-label col-lg-7 col-md-7 col-sm-7\">{{label}}</label>",
						"<div class=\"col-lg-5 col-md-5 col-sm-5\">",
							"<div class=\"row\">",
								"<div slider style=\"padding-top: 8px;\" class=\"col-lg-6 col-md-6 col-sm-6\"></div>",
								"<div class=\"col-lg-6 col-md-6 col-sm-6\">",
									"<div class=\"input-group\">" ,
										"<input class=\"form-control\" calc-id=\"{{cell}}\">",
										"<span class=\"input-group-addon right\">",
											"<i class=\"fa fa-question-circle tooltipstered\" title=\"{{tooltip}}\"></i>",
										"</span>",
									"</div>",
								"</div>",
							"</div>",
						"</div>",
					"</div>"
				],
				children: [{
					parent:{
						"local": true,
						"selector": "[slider]"
					},
					type:"slider",
					step:1,
					attributes: {
						"calc-id":"{{cell}}"
					}
				}]
			};

			calc.build.templates.inputSlider = {
				html: [
					"<div id=\"{{cell}}\" class=\"form-group\">",
						"<label class=\"control-label col-lg-7 col-md-7 col-sm-7\">{{label}}</label>",
						"<div class=\"col-lg-5 col-md-5 col-sm-5\">",
							"<div class=\"row\">",
								"<div slider style=\"padding-top: 8px;\" class=\"col-lg-6 col-md-6 col-sm-6\"></div>",
								"<div class=\"col-lg-6 col-md-6 col-sm-6\">",
									"<input class=\"form-control\" calc-id=\"{{cell}}\">",
								"</div>",
							"</div>",
						"</div>",
					"</div>"
				],
				children: [{
					parent:{
						"local": true,
						"selector": "[slider]"
					},
					type:"slider",
					step:1,
					attributes: {
						"calc-id":"{{cell}}"
					}
				}]
			};

			calc.build.templates.subsectionHeader = {
				html: [
					"<div id=\"{{cell}}\" class=\"subsection-header underlined\" style=\"margin: 15px 0 15px 0;\">",
						"<h2>{{text}}</h2>",
					"</div>"
				]
			};

			calc.build.templates.output = {
				html: [
					"<div id=\"{{cell}}\" class=\"form-group\">",
						"<label class=\"control-label col-lg-7 col-md-7 col-sm-7\">{{label}}</label>",
						"<div class=\"col-lg-5 col-md-5 col-sm-5\">",
							"<div class=\"input-group\">" ,
								"<input class=\"form-control\" disabled=\"disabled\" calc-id=\"{{cell}}\">",
								{
									require: ["append"], 
									html: "<span class=\"input-group-addon append\">{{append}}</span>"
								},
								"<span class=\"input-group-addon right helper output\">",
									{
										require: ["tooltip"], 
										html: "<i class=\"fa fa-question-circle tooltipstered\" style=\"margin-right: 5px;\" title=\"{{tooltip}}\"></i>"
									},
									"<i class=\"fa fa-calculator tooltipstered\" calculation-modal=\"{{cell}}\" title=\"Click here to view the calculation breakdown\"></i>",
								"</span>",
							"</div>",
						"</div>",
					"</div>"
				]
			};

			calc.build.templates.input = {
				html: [
					"<div id=\"{{cell}}\" class=\"form-group\">",
						"<label class=\"control-label col-lg-7 col-md-7 col-sm-7\">{{label}}</label>",
						"<div class=\"col-lg-5 col-md-5 col-sm-5\">",
							{
								require: {
									instance: "any",
									keys: ["append", "tooltip"]
								},
								html:"<div class=\"input-group\">"
							},
								"<input class=\"form-control\" calc-id=\"{{cell}}\">",
								{
									require: ["helper"],
									html: "<span class=\"form-text m-b-none\">{{helper}}</span>"
								},
								{
									require: ["append"], 
									html: "<span class=\"input-group-addon append\">{{append}}</span>"
								},
								{
									require: ["tooltip"], 
									html: "<span class=\"input-group-addon right\"><i class=\"fa fa-question-circle tooltipstered\" title=\"{{tooltip}}\"></i></span>"
								},
							{
								require: {
									instance: "any",
									keys: ["append", "tooltip"]
								},
								html: "</div>"
							},
						"</div>",
					"</div>"
				]
			}

			calc.build.templates.inputTooltip = {
				html: [
					"<div class=\"form-group\">",
						"<label class=\"control-label col-lg-7 col-md-7 col-sm-7\">{{label}}</label>",
						"<div class=\"col-lg-5 col-md-5 col-sm-5\">",
							"<div class=\"input-group\">" ,
								"<input class=\"form-control\" calc-id=\"{{cell}}\">",
								"<span class=\"input-group-addon right\">",
									"<i class=\"fa fa-question-circle tooltipstered\" title=\"{{tooltip}}\"></i>",
								"</span>",
							"</div>",
						"</div>",
					"</div>"
				]
			}

			calc.build.templates.pod = {
				attributes: {
					id: '{{id}}Pod',
					class: "col-lg-4"
				},
				children:[{
					html: [
						"<div class=\"ibox float-e-margins\">",
							"<div class=\"ibox-title\">",
								"<h5>",
									"<a class=\"smooth-scroll\" href=\"#section{{id}}\">{{title}}</a>",
								"</h5>",
							"</div>",
							"<div class=\"ibox-content section-pod equalize-pod-height\">",
								"<h1 class=\"txt-right pod-total section-total txt-money\" calc-id=\"{{cell}}\">$0</h1>",
								"<hr>",
								"<div class=\"row\">",
									"<div conSlider class=\"col-lg-12\">",
										"<div class=\"row conservative-slider\">",
											"<div class=\"value-holder\">",
												"Conservative Factor: <span class=\"pull-right\" calc-id=\"CON{{id}}\"></span>",
												"<div class=\"row slider-padding\">",
													"<div class=\"col-lg-12\"></div>",
												"</div>",
											"</div>",
										"</div>",
									"</div>",
								"</div>",
							"</div>",
						"</div>"
					],
				},{
					parent:{
						local: true,
						selector: ".value-holder .col-lg-12"
					},
					required: ["id"],
					type:"slider",
					step:5,
					attributes:{
						"calc-id":"CON{{id}}"
					}
				}]
			};

			calc.build.templates.podSavings = {
				attributes: {
					id: '{{id}}Pod',
					class: "col-lg-4"
				},
				children:[{
					html: [
						"<div class=\"ibox float-e-margins\">",
							"<div class=\"ibox-title\">",
								"<h5>",
									"<a class=\"smooth-scroll\" href=\"#section{{id}}\">{{title}}</a>",
								"</h5>",
							"</div>",
							"<div class=\"ibox-content section-pod equalize-pod-height\">",
								"<h1 class=\"txt-right pod-total section-total txt-money\" calc-id=\"GT1\">$0</h1>",
								"<hr>",
							"</div>",
						"</div>"
					],
				}]
			};

			calc.build.templates.podNoSummary = {
				attributes: {
					id: '{{id}}Pod',
					class: "col-lg-4"
				},
				children:[{
					html: [
						"<div class=\"ibox float-e-margins\">",
							"<div class=\"ibox-title\">",
								"<h5>",
									"<a class=\"smooth-scroll\" href=\"#section{{id}}\">{{title}}</a>",
								"</h5>",
							"</div>",
							"<div class=\"ibox-content section-pod equalize-pod-height\">",
							"</div>",
						"</div>"
					],
				}]
			};

			if(calc.options.templates.length){
				$.each(calc.options.templates, function(){
					calc.build.templates[this.template_id] = $.parseJSON(this.template);
				});
			}
		}

		calc.sidebarNavigation = function(){			
			var $navigation = "<nav class=\"navbar-default navbar-static-side\" role=\"navigation\">\
									<div class=\"sidebar-collapse sidebar-navigation\" class=\"overflow: hidden; width: auto; height: 100%;\">\
										<ul class=\"nav\" id=\"side-menu\">\
											<li class=\"nav-header\">\
												<div class=\"dropdown profile-element\">";
				
				$navigation +=	sprintf("<img id=\"company_logo\" alt=\"image\" src=\"https://www.theroishop.com/%scompany_specific_files/%s/logo/logo.png\">", calc.options.versionSpecs.enterprise == 1 ? 'enterprise/' : '', calc.options.versionSpecs.structure_id),
				
				$navigation += 					"</div>\
											</li>\
											<li mainNav class=\"smooth-scroll\">\
												<a href=\"#\">\
													<i class=\"fa fa-calculator\"></i>\
													<span class=\"nav-label\">ROI Sections</span>\
													<span class=\"fa arrow\"></span>\
												</a>\
												<ul class=\"nav nav-second-level collapse in\">\
													<li><a href=\"#dashboard\" class=\"section-navigator\">Dashboard</a></li>";

			$.each(calc.options.sections, function(){
				if(this.pod > 0){
					$navigation += sprintf("<li id=\"%s\"><a href=\"#%sSection\" class=\"section-navigator\">%s</a></li>", ( this.ID + 'Navigation' ), this.ID, this.Title);
				}
			});

			$navigation += 						"</ul>\
											</li>\
										</ul>\
									</div>\
								</nav>";
		
			calc.build.build.push({
				html: $navigation
			});
			
			$.each(calc.options.navigation, function(){
				if(! this.parent){
					var $nav = {
						parent: "#side-menu",
						html: this.label
					};
					calc.build.build.push($nav);
				}
			})

			if(calc.options.pdfs.length){
				var html = [];

				html.push(
					"<li mainNav class=\"smooth-scroll\">\
						<a href=\"#\">\
							<i class=\"fa fa-file-pdf-o\"></i>\
							<span class=\"nav-label\">Your PDFs</span>\
							<span class=\"fa arrow\"></span>\
						</a>\
						<ul class=\"nav nav-second-level collapse in\">"
				);

				$.each(calc.options.pdfs, function(){
					html.push(sprintf("<li><a reportId=\"%s\" class=\"renderPdf\">%s</a></li>", this.pdf_template, this.pdf_name));
				});

				html.push(
					"</ul></li>"
				);

				calc.build.build.push({
					parent: "#side-menu",
					html: html
				});				
			}

			calc.build.build.push({
				parent: "#side-menu",
				html: [
					"<li>",
						"<a href=\"../../dashboard\">",
							"<i class=\"fa fa-globe\"></i>",
							"<span class=\"nav-label\">My ROIs</span>",
						"</a>",
					"</li>"
				]
			});
		}

		calc.buildStructure = function(){

			calc.setupStructure();
			calc.sidebarNavigation();
			calc.buildCalculator();
		}

		calc.prepareCalculator = function(){
			calc.$container = calc.el;			
			calc.prepareNavigation();

			numeral.language(calc.options.specs.currency);
		}

		calc.createElements = function(){
			$.each(calc.options.elements, function(){
				if(this.parent){
					builder.build({
						parent: this.parent,
						html: this.content
					});
				} else {
					var options = $.parseJSON(this.options);
					if(options.replace){
						$(options.replace).empty();
						builder.build({
							parent: options.replace,
							html: this.content
						});						
					}
				}
			});
		}

		calc.createDomElements = function(){
			var $sliders = $('[rs-slider]');
			
			$.each($sliders, function(){
				var build = {
					parent: this,
					type: 'slider',
					step: $(this).data('step') || 1,
					min: $(this).data('min') || 0,
					max: $(this).data('max') || 100,
					attributes: {}
				};

				if($(this).data('id')){
					build.attributes['calc-id'] = $(this).data('id');
				}

				builder.build(build);
			});			
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

				var includes = [];
				$.each(calc.options.sections, function(i, section){
					var include = {
						attributes:{
							class: "row",
							style: "margin-bottom: 10px;"
						},
						children:[{
							html: "<h2 style=\"margin: 0;\" class=\"col-lg-10\">" + section.Title + "</h2>"
						},{
							attributes:{
								class: "col-lg-2"
							},
							children:[{
								type: "toggle",
								tag: "button",
								attributes: {
									"calc-id":"INC" + section.ID,
									class: "btn btn-block col-lg-2",
									type: "button"
								},
								states: [{
									value: 0,
									class: "btn-danger",
									text: "<i class=\"fa fa-times\"></i> Excluded"
								},{
									value: 1,
									class: "btn-primary",
									text: "<i class=\"fa fa-check\"></i> Included"
								}]
							}]
						}]
					};
	
					if(this.pod > 0){
						includes.push(include);
					}
				});

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
							content: includes
						}
					});
				});
			}

			calc.renderCalculator();
		}

		calc.renderCalculator = function(){
			$.each(calc.build.build, function(){
				if(! this.$parent ) this.$parent = calc.$container;
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
				url: "/enterprise/9/assets/ajax/calculator.post.php",
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
								var value = this.visibility.cell && calc.sheet.cells[(this.visibility.cell+'')] ? calc.sheet.cells[(this.visibility.cell+'')].getValue() : 0;
								var operator = this.visibility.operator ? this.visibility.operator : '==',
									evaluator = value + operator + this.visibility.value;

								var evaluation = eval(evaluator);
								if(! evaluation) return;
							}

							var equations = this.equations,
								datum = [];

							$.each(equations, function(){
								datum.push(calc.sheet.parser.parse((this+'')));
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
				if($(this).parents('#side-menu').length){
					var thisNav = $(this).closest('.smooth-scroll'),
						subItems = thisNav.find('ul > li > a'),
						allNavs = $('#side-menu').find('li.smooth-scroll');

					$.each(allNavs, function(){
						var $this = this,
							subItems = $(this).find('ul > li > a');
						$.each(subItems, function(){
							var href = $(this).attr('href');
		
							if(href){
								if($this != thisNav[0]) {
									if($(href).is(':visible')) $(href).hide();
								} else {
									if($(href).is(':hidden')) $(href).show();
								}
							}
						});	
					});
				}

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

				rsAjax({
					data: {
						action: 'RetrievePDFGraph',
						roi_id: getQueryVariable('roi')
					},
					success: function(graph){
						if(graph){
							graph = $.parseJSON(graph);
							var graph_count = graph.length,
								graphs_created = 0,
								graphs = [];

							if(graph.length){
								$.each(graph, function(){
									if(this && this.graph_build){
										var graph = $.parseJSON(this.graph_build),
											id = this.id;

										$.each(graph.series, function(){
											var equations = this.equations,
												datum = [];
					
											$.each(equations, function(){
												datum.push(calc.sheet.parser.parse((this+'')));
											});
					
											this.data = datum;
										});

										$.post("//export.highcharts.com",{
											options: JSON.stringify(graph),
											type: "png",
											width: graph.width || 700,
											async: false
										}, function(img){
											var created_graph = {
												id: id,
												graph: img
											};

											graphs.push(created_graph);
											graphs_created++;

											if(graphs_created == graph_count){
												setTimeout(function(){
													rsAjax({
														data: {
															action: "createPdf",
															roi_id: getQueryVariable('roi'),
															reportId: reportId,
															roiPath: window.location.href + '&v=' + calc.options.specs.verification_code,
															graphs: graphs
														},
														success: function(returned){
															waitingDialog.hide();
															$('<a href="/webapps/assets/customwb/10016/pdf/' + calc.options.specs.roi_title + '.pdf" download>')[0].click();
														}
													});
												}, 2500);
											}								
										});
									}
								});
							} else {
								rsAjax({
									data: {
										action: "createPdf",
										roi_id: getQueryVariable('roi'),
										reportId: reportId,
										roiPath: window.location.href + '&v=' + calc.options.specs.verification_code,
										graphs: graphs
									},
									success: function(returned){
										waitingDialog.hide();
										$('<a href="/webapps/assets/customwb/10016/pdf/' + calc.options.specs.roi_title + '.pdf" download>')[0].click();
									}
								});							
							}
						}
					}
				});

				// if(calc.options.summaryGraph.length) {
				// 	var graph = calc.options.summaryGraph[0],
				// 		id = 'summary';

				// 	$.each(graph.series, function(){
				// 		var equations = this.equations,
				// 			datum = [];

				// 		$.each(equations, function(){
				// 			if(calc.sheet.cells[(this+'')]) datum.push(calc.sheet.cells[(this+'')].getValue());
				// 		});

				// 		this.data = datum;
				// 	});
				// 	if(graph.title.text) graph.title.text = "";
				// 	graph.credits = {enabled: false};

				// 	$.post("//export.highcharts.com",{
				// 		options: JSON.stringify(graph),
				// 		type: "png",
				// 		width: graph.width || 700,
				// 		async: false
				// 	}, function(img){
				// 		setTimeout(function(){
				// 			rsAjax({
				// 				data: {
				// 					action: "createPdf",
				// 					roi_id: getQueryVariable('roi'),
				// 					reportId: reportId,
				// 					roiPath: window.location.href + '&v=' + calc.options.specs.verification_code,
				// 					image: 'http://export.highcharts.com/' + img
				// 				},
				// 				success: function(returned){console.log(returned);
				// 					waitingDialog.hide();
				// 					$('<a href="/webapps/assets/customwb/10016/pdf/' + calc.options.specs.roi_title + '.pdf" download>')[0].click();
				// 				}
				// 			});
				// 		}, 2500);						
				// 	});
				// }
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
									success: function(elements){
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
						class: "form-group",
						style: "margin-bottom: 0;"
					},
					children:[{
						attributes: {
							style: "float:right;"
						},
						children: [{
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
											var id = $(this).attr('calc-id');

											if(id){
												var val = roishop.current.sheet.cells[id].format && roishop.current.sheet.cells[id].format.includes('%') ? roishop.current.sheet.cells[id].value * 100 : roishop.current.sheet.cells[id].value;

												var field = '{"' + $(this).attr('integration-id') + '":"' + val + '"}',
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
											}												
										});
									}
								},
								children:[{
									html: "Export to Salesforce"
								}]
							}]
						},{
							attributes: {
								style: "clear: both;"
							}
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
					content: '<textarea class="ver-link-output" spellcheck="false" readonly="readonly">www.theroishop.com/calc-your-roi/enterprise?roi=' + opts.specs.roi_id + '&v=' + opts.specs.verification_code + '</textarea>'
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
	
	rsAjax({
		data: {
			action: 'retrieveCalcYourRoi',
			roi_id: getQueryVariable('roi'),
			verifiction: getQueryVariable('v')
		},
		success: function(specs){
			console.log(specs)
			specs = JSON.parse(specs);
			$('#wrapper').roishop(specs);
		}
	});

	return roishop;
})));