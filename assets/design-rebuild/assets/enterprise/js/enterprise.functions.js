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

	var roishop = (function(el, options) {
		var calc = {};

		var defaults = {
			isScrolling: false,
			sectionEntries: [],
			cells: [],
			colors: ['#265F96', '#114F1C', '#318E1E', '#1aadce', '#492970', '#f28f43', '#77a1e5', '#c42525', '#a6c96a', '#910000'],
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
			sideNav: true,
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

		var sections = calc.options.sections;
		var entries = calc.options.entries;
		var version = calc.options.version;
		var pdfs = calc.options.pdfs;
		var navigation = calc.options.navigation;
		var title = 'Temp Title';

		// Extend Version Options
		if(version.options) {
			var opts = $.parseJSON(version.options);
				version = $.extend(true, version, opts);
		}

		$.each(sections, function(){
			var section = this;

			if(section.options){
				var opts = $.parseJSON(section.options);
					section = $.extend(true, section, opts);
			}
		})

		var term = ( version.term || calc.options.compSpecs.retPeriod ) || 1;

		calc.el = $(el);

		calc.navigation = [];
		calc.templates = [];
		calc.wrappers = [];
		
		calc.render = {
			build: []
		};

		var $page = $("<div class=\"white-bg\" id=\"page-wrapper\"></div>");
		calc.el.append($page);

		calc.init = function(){
			roishop.current = calc;

			calc.setup();
			calc.calculator();
			calc.events();
		}

		/*******
		 * BEGIN SETUP OF THE CALCULATOR
		 */

		calc.setup = function() {
			calc.builder = {};

			numeral.language('usd');
			$(document).prop('title', 'The ROI Shop | ' + title);

			calc.templates();
		}

		/*******
		 * HANDLE CREATION OF ALL CALCULATION CELLS
		 */

		calc.cells = function(){
			if(entries) calc.entryCells();
			calc.sharedCells();
			calc.mergeCells();
		}

		calc.entryCells = function(){
			var rsEntries = 1,
				years = 1;

			calc.builder.cells = [];

			var cellFormat = function(cell){
				if(cell.format) return cell.format;

				switch(cell.Format){
					case '0': format = '0,0'; break;
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

			$.each(entries, function(){
				var entry = this,
					title = entry.Title,
					cells = calc.builder.cells;

				years = entry.annual > 0 ? term : 1;

				if(title.includes('<span ')){
					var $spans = title.match(/<span (.*?)<\/span>/g);
					
					$.each($spans, function(){
						var $title = this;
						if($title.includes('calc-id')) return;
						
						var dataFormula = $title.match(/data-formula="(.*?)"/),
							formula = dataFormula ? dataFormula[1] : null,
							dataFormat = $title.match(/data-format="(.*?)"/),
							format = dataFormat ? dataFormat[1] : '0,0',
							address = 'RSX' + rsEntries;

						$title = $title.replace('data-formula', 'calc-id');
						if(dataFormat) $title = $title.replace(dataFormat[0], '');
						$title = $title.replace(formula, address);

						cells.push({
							address: address,
							format: format,
							formula: formula,
							label: 'Auto generated calculation: ' + rsEntries
						})

						entry.Title = entry.Title.replace(this, $title);
						rsEntries++;
					})
				}

				for( var i=0; i<years; i++){
					var cell = {
						address: this.address ? this.address : calc.options.letters[i] + this.ID,
						format: cellFormat(this),
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

					cells.push(cell);
				}
			});

			calc.finalCellRegistry();
		}

		calc.finalCellRegistry = function(){
			var $unregistered = $('[data-formula]'),
				cells = calc.builder.cells,
				rsEntries = 1;

			$.each($unregistered, function(){
				if($(this).attr('calc-id')) return;
				
				var formula = $(this).data('formula');
					format = $(this).data('format') || '0,0',
					address = $(this).data('cell') || 'RSX' + rsEntries;

				$(this).removeAttr('data-formula');
				$(this).removeAttr('data-format');
				$(this).removeAttr('data-cell');

				$(this).attr('calc-id', address);

				cells.push({
					address: address,
					format: format,
					formula: formula,
					label: 'Auto generated calculation: ' + rsEntries
				});

				rsEntries++;
			});
		}

		calc.sharedCells = function(){
			var cells = calc.builder.cells,
				grand_total = '',
				npv = '';

			for( var i=0; i<term; i++){
				var annual_total = '';
				
				$.each(sections, function(){
					if(this.formula){
						var a = 'ST' + calc.options.letters[i] + this.ID
						annual_total += ( annual_total == '' ? ( '( ' + a ) : ' + ' + a );
					}
				});

				annual_total += ' + AC' + ( i + 1 ) + ' )';

				npv += ( npv == '' ? ( 'NPV( 0.02, AT' + ( i + 1 ) ) : ', AT' + ( i + 1 ) );

				cells.push({
					address: 'AT' + ( i + 1 ),
					format: '($0,0)',
					formula: annual_total,
					label: 'Annual Total for Year' + i
				});
			}

			npv += ' )';

			$.each(sections, function(i, section){
				var exclude = false;

				cells.push({
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
				});
				
				if(section.formula){
					cells.push({
						address: 'CON' + section.ID,
						format: '0,0%',
						formula: null,
						label: section.Title ? 'Conservative Factor: ' + section.Title : null,
						value: null
					});
	
					for( var i=0; i<term; i++){
						cells.push({
							address: ( this.address ? this.address : 'ST' ) + calc.options.letters[i] + this.ID,
							format: '($0,0)',
							formula: this.formula ? ( '( IF( INC' + this.ID + '=1,' + this.formula + ', 0 ) ) * ( 1 - CON' + this.ID + ' ) * I' + ( i + 1 ) ): null,
							label: this.Title ? ( this.Title + 'Annual Total for Year ' + i ) : null,
							value: null
						});
					}
	
					var section_address = ( this.address ? this.address : 'ST' ) + this.ID
	
					cells.push({
						address: section_address,
						format: '($0,0)',
						formula: 'SUM( STA' + this.ID + ':ST' + calc.options.letters[term - 1] + this.ID + ' )',
						label: this.Title ? 'Conservative Factor: ' + this.Title : null,
						value: null
					});
	
					grand_total += ( grand_total == '' ? ( '( ' + section_address ) : ' + ' + section_address );
				}
			});

			var totalcost = '';

			for( var i=0; i<term; i++ ){
				var cost = '';

				$.each(entries, function(){
					if(this.cost > 0){
						if(i > 0 && this.annual > 0){
							cost += ( cost == '' ? ( '( 0 - ' + calc.options.letters[i] + this.ID ) : ( ' + ' + calc.options.letters[i] + this.ID ) );
						}

						if( i==0 ) cost += ( cost == '' ? ( '( 0 - A' + this.ID ) : ( ' - A' + this.ID ) );
					}
				});

				cost += ' )';

				totalcost += ( totalcost == '' ? ( '( AC' + ( i + 1 ) ) : ( ' + AC' + ( i + 1 ) ) );
				
				cells.push({
					address: 'AC' + ( i + 1 ),
					format: "($0,0)",
					formula: cost
				});
			}

			totalcost += ' )';

			cells.push({
				address: 'TC1',
				format: "($0,0)",
				formula: totalcost
			});

			if(grand_total !== ''){
				grand_total += ' + TC1 )';

				cells.push({
					address: 'GT1',
					format: '($0,0)',
					formula: grand_total,
					label: 'Grand Total'
				});	
			}

			for( var i=1; i<=term; i++){
				cells.push({
					address: 'I' + i,
					format: '0,0',
					formula: 'IF( IMP1 > ( ' + i + ' * 12 ), 0, IF( IMP1 < ( ' + ( i - 1 ) + ' * 12 ), 1, ( ( ' + i + ' * 12 ) - IMP1 ) / 12 ) )',
					label: 'Implementation Period - Year ' + i,
					value: null
				});				
			}
		}

		calc.mergeCells = function(){
			calc.builder.cells = calc.builder.cells.concat(calc.options.roiCells);	
		}
		
		/*******
		 * CREATE TEMPLATES USED TO RENDER CALCULATOR ELEMENTS
		 */

		calc.templates = function(){
			calc.summaryChartTemplate();
			calc.summaryTableTemplate();
			calc.testimonialTemplate();
			calc.textareaTemplate();
			calc.dropdownTemplate();
			calc.textTemplate();
			calc.sliderTemplate();
			calc.headerTemplate();
			calc.outputTemplate();
			calc.inputTemplate();
			calc.podTemplate();

			// Add any templates created for individual builds or override the pre-existing ones.
			if(calc.options.templates.length){
				$.each(calc.options.templates, function(){
					calc.templates[this.template_id] = $.parseJSON(this.template);
				});
			}
		}

		calc.summaryChartTemplate = function(){
			var categories = [],
				series = [],
				colors = calc.options.colors;

			for( var i=1; i<=term; i++ ){
				categories.push(sprintf("Year %s", i));
			}

			$.each(sections, function(n, section){
				if(this.formula && this.customformula != 0){
					var entry = {},
						equations = [];
						
					for( var i=1; i<=term; i++ ){
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
			for( var i=1; i<=term; i++ ){
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

			calc.templates.summaryChart = {
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
		}

		calc.summaryTableTemplate = function(){
			var summary = [];
	
			summary.push('<div class="ibox-content"><div class="table-responsive" style="border: 2px solid #ddd;"><table id="summary-table" class="table table-hover" style="margin-bottom: 0;">');
			summary.push('<thead><tr><th></th>');

			for( var i=1; i<=term; i++ ){
				summary.push(sprintf('<th>Year %s</th>', i));
			}

			summary.push('<th>Total</th></thead><tbody>');

			$.each(calc.options.sections, function(i, section){
				if(section.formula){
					summary.push(sprintf('<tr id="%s"><th class="section-navigation">%s</th>', section.ID + 'Summary', section.Title));
					for( var i=1; i<=term; i++ ){
						summary.push(sprintf('<td class="txt-money" calc-id="%s">$0</td>', 'ST' + calc.options.letters[i - 1] + section.ID));
					}
					summary.push(sprintf('<td class="txt-money" calc-id="%s">$0</td></tr>', 'ST' + section.ID));
				}
			});

			summary.push('<tr><th>Cost</th>');
			for( var i=1; i<=term; i++ ){
				summary.push(sprintf('<td class="txt-removed" calc-id="%s">$0</td>', 'AC' + i));
			}
			summary.push('<td class="txt-removed" calc-id="TC1">$0</td></tr><tr><th>Total</th>');
			for( var i=1; i<=term; i++ ){
				summary.push(sprintf('<td class="txt-money" calc-id="%s">$0</td>', 'AT' + i));
			}
			summary.push('<td class="txt-money" calc-id="GT1">$0</td></tr></table></div></div>');

			calc.templates.savingsSummary = {
				html: summary          
			};
		}

		calc.testimonialTemplate = function(){
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

				calc.templates.testimonials = testimonials;
			}			
		}

		calc.textareaTemplate = function(){
			calc.templates.textarea = {
				html: [
					"<div id=\"{{id}}\" class=\"form-group\">",
						"<label class=\"control-label col-lg-7 col-md-7 col-sm-7\">{{label}}",
						{
							require: ["tooltip"], 
							html: " <i class=\"fa fa-question-circle tooltipstered\" title=\"{{tooltip}}\"></i>"
						},
						"</label>",
						"<div class=\"col-lg-5 col-md-5 col-sm-5\">",
							"<textarea style=\"resize:vertical;\" class=\"form-control\" calc-id=\"{{cell}}\"></textarea>",
						"</div>",
					"</div>"
				]            
			};
		}

		calc.textTemplate = function(){
			calc.templates.text = {
				html: ["{{text}}"]
			}
		}

		calc.sliderTemplate = function(){
			calc.templates.inputSlider = {
				html: [
					"<div id=\"{{id}}\" class=\"form-group\">",
						"<label class=\"control-label col-lg-7 col-md-7 col-sm-7\">{{label}}</label>",
						"<div class=\"col-lg-5 col-md-5 col-sm-5\">",
							"<div class=\"row\">",
								"<div slider style=\"padding-top: 8px;\" class=\"col-lg-6 col-md-6 col-sm-6\"></div>",
								"<div class=\"col-lg-6 col-md-6 col-sm-6\">",
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
		}

		calc.headerTemplate = function(){
			calc.templates.subsectionHeader = {
				html: [
					"<div id=\"{{id}}\" class=\"subsection-header underlined\" style=\"margin: 15px 0 15px 0;\">",
						"<h2>{{text}}</h2>",
					"</div>"
				]
			};

			calc.templates.include = {
				children:[{
					html: [
						"<div id=\"{{id}}\" class=\"subsection-header underlined\" style=\"margin: 15px 0 15px 0;\">",
							"<div class=\"row\">",
								"<h2 class=\"col-lg-10\">{{text}}</h2>",
							"</div>",
						"</div>"
					]
				},{
					parent:{
						local: true,
						selector: ".row"
					},
					attributes:{
						class:"col-lg-2"
					},
					children:[{
						type:"toggle",
						tag:"button",
						attributes:{
							"calc-id": "{{cell}}",
							class: "btn btn-block col-lg-2",
							type: "button"
						},
						states:[{
							value:0,
							class:"btn-danger",
							text:"<i class=\"fa fa-times\"></i> Excluded"
						},{
							value:1,
							class:"btn-primary",
							text:"<i class=\"fa fa-check\"></i> Included"
						}]
					}]
				}]
			};
		};

		calc.outputTemplate = function(){
			calc.templates.output = {
				html: [
					"<div id=\"{{id}}\" class=\"form-group\">",
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
		};

		calc.inputTemplate = function(){
			calc.templates.input = {
				html: [
					"<div id=\"{{id}}\" class=\"form-group\">",
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
			};

			calc.templates.inputTooltip = {
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
			};
		};

		calc.dropdownTemplate = function(){
			calc.templates.dropdown = {
				html: [
					"<div id=\"{{id}}\" class=\"form-group\">",
						"<label class=\"control-label col-lg-7 col-md-7 col-sm-7\">{{label}}</label>",
						"<div class=\"col-lg-5 col-md-5 col-sm-5\" choices>",
						"</div>",
					"</div>"
				]
			}			
		}

		calc.podTemplate = function(){
			calc.templates.pod = {
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

			calc.templates.podSavings = {
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

			calc.templates.podNoSummary = {
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
		};

		/*******
		 * BUILD CALCULATOR USER INTERFACE
		 */

		calc.calculator = function(){
			calc.navigation();
			calc.sections();
			calc.elements();

			// Render everything that was built
			$.each(calc.render.build, function(){
				if(! this.$parent ) this.$parent = calc.el;
				builder.build(this);
			});

			calc.cells();

			// Create new calculation sheet to handle dynamic calculations
			calc.sheet = new sheet(calc.builder.cells);
		}

		/* Sections */

		calc.sections = function(){

			calc.dashboard();

			// Loop through the sections to create their build
			$.each(sections, function(i, section){
				if(section.options){
					var opts = $.parseJSON(section.options);
						section = $.extend(true, section, opts);
				}

				var sectionId = section.section_id || section.ID,
					sectionTitle = section.Title,
					visibieOnLoad = section.pod > 0 || section.pod && section.pod.build ? '' : 'style="display: none;" ',
					sectionHash = section.section_id ? section.section_id : (sectionId + 'Section'),
					contentSections = section.sections ? section.sections : ( section.formula || section.statistics > 0 ? [{
						class: 'ibox-content col-xs-12 col-sm-12 col-md-9 col-lg-9',
						entries: true
					},{
						class: "col-xs-12 col-sm-12 col-md-3 col-lg-3",
						sidebar: true
					}] : [{
						class: "ibox-content col-xs-12 col-sm-12 col-md-12 col-lg-12",
						entries: true
					}] );

				// Create section holder
				var $section = $(sprintf("<div %sid=\"%s\"></div>", visibieOnLoad, sectionHash));
					$page.append($section);

				section.$holder = $section;

				// Create section header
				var $header = $([
					sprintf("<div class=\"row border-bottom white-bg dashboard-header\" id=\"section%s\">", section.ID),
						"<div class=\"col-lg-12\">",
							sprintf("<h1 style=\"margin-bottom: 20px\">%s %s</h1>", sectionTitle, section.customformula == 0 ? '' : section.formula || section.grandtotal == 1 ? sprintf('<span class=\"pull-right pod-total section-total grand-total txt-money\" calc-id=\"%s\">$0</span>', this.grandtotal == 1 ? 'GT1' : ( 'ST' + sectionId ) ) : ''),
						"</div>",
					"</div>"].join(''));
					$section.append($header);

				// Fill header
				if(section.Caption){
					var $description = $([
						"<div caption class=\"col-lg-12\"><hr>",
							sprintf("<div class=\"%s section-writeup\">%s</div>", section.Video ? "col-lg-7" : "col-lg-12", section.Caption),
						"</div>"
					].join(''));

					$header.append($description);

					if(section.Video){
						var $video = $("<div class=\"col-lg-5 video\"></div>");
							$description.append($video);

						// Add video to elements that need to be rendered after section built.
						calc.render.build.push({
							parent: $video,
							type: "video",
							src: section.Video
						});
					}

					if(section.testimonials > 0 && calc.options.testimonials.length > 0){
						$description.find('.section-writeup').append('<hr>');
						builder.build({
							parent: $description.find('.section-writeup'),
							template: "testimonials"
						});
					}
				};

				// Section Main Content
				var $content = $([
					"<div class=\"row border-bottom gray-bg dashboard-header\">",
						"<div class=\"col-lg-12\">",
							"<div class=\"row\">",
							"</div>",
						"</div>",
					"</div>"
				].join(''));
				$section.append($content);

				$.each(contentSections, function(){
					var contentSection = this,
						keys = Object.keys(this),
						$holder = $('<div></div>');
					
					$.each(keys, function(){
						if((this+'') !== "build") $holder.attr(this, contentSection[this]);
					})

					$content.append($holder);

					if(this.build){
						$.each(this.build, function(){
							this.parent = $holder;
							builder.build(this);
						})
					}
				});

				// Add Section Entries
				if(entries){
					var entryBySection = $.map(entries, function(entry, i){
						if(entry.sectionName === section.ID) return entry;
					})
				}
				
				if(entryBySection){
					var $sectionForm = $('<div class="form-horizontal"></div>');
						$content.find('[entries]').append($sectionForm);
					
					$.each(entryBySection, function(i, entry){
						entry.$parent = $sectionForm;
						calc.buildElement(entry);
					});
				}

				if($content.find('[sidebar]')){
					calc.sectionSidebar(section, $content.find('[sidebar]'));
				}
			})
		}

		calc.sectionSidebar = function(section, $parent){
			// Create the Baseline Stat box			
			var $baseline = $(
				sprintf('<div class="ibox float-e-margins">\
					<div class="ibox-content">\
						<div class="row">\
							<div class=\"col-lg-12 col-md-12\">\
								<h1 class=\section-header\">%s</h1>\
								<div class=\"table-responsive m-t\">\
									<table class=\"table invoice-table\">\
										<tbody>\
										</tbody>\
									</table>\
								</div>\
								<div class="value-holder">\
									Conservative Factor: <span class=\"pull-right\" calc-id=\"CON%s\"></span>\
									<div class=\"row slider-padding\">\
										<div class=\"col-lg-12\">\
										</div>\
									</div>\
								</div>\
							</div>\
						</div>\
					</div>\
				</div>', section.Title, section.ID)
			)

			for(var i=1; i<=term; i++){
				$baseline.find('tbody').append($(sprintf("<tr><td>Year %s: </td><td calc-id=\"ST%s%s\">$0</td></tr>", i, calc.options.letters[i - 1], section.ID)));
			}

			$baseline.find('tbody').append($(sprintf("<tr><td>%s Total: </td><td calc-id=\"ST%s\">$0</td></tr>", section.Title, section.ID)));

			// Create the statistic box
			var $statistics = $(
				'<div class="ibox float-e-margins">\
					<div class="ibox-content">\
						<div class="row">\
							<div class=\"col-lg-12 col-md-12\">\
								<h1 class=\section-header\">ROI Statistics</h1>\
								<div class=\"table-responsive m-t\">\
									<table class=\"table invoice-table\">\
										<tbody>\
											<tr>\
												<td>Return on Investment: </td>\
												<td calc-id=\"ROI1\">0%</td>\
											</tr>\
											<tr>\
												<td>Net Present Value: </td>\
												<td calc-id=\"NPV1\">$0</td>\
											</tr>\
											<tr>\
												<td>Payback Period: </td>\
												<td><span calc-id=\"PAY1\">0</span> months</td>\
											</tr>\
										</tbody>\
									</table>\
								</div>\
							</div>\
						</div>\
					</div>\
				</div>'
			);

			if(section.sidebar && section.sidebar.build){
				var $sidebar;
				$.each(section.sidebar.build, function(){
					var sidebar = this;
					sidebar.parent = $parent;

					builder.build(sidebar);
				});
			} else {
				if(section.formula){
					if(section.statistics > 0){
						$parent.append($statistics);
					} else {
						$parent.append($baseline);
	
						builder.build({
							parent: $baseline.find('.value-holder .col-lg-12'),
							type:"slider",
							step:5,
							attributes:{
								"calc-id": sprintf("CON%s", section.ID)
							}
						});
					}
				} else if(section.statistics > 0){
					$parent.append($statistics);
				}
				
				// Add implementation Sliders
				var $implementation = $(
					"<div class=\"faq-item\">\
						<div class=\"row conservative-slider\">\
							<div class=\"value-holder\">\
								Implementation Period: <span class=\"pull-right\"><span calc-id=\"IMP1\"></span> months</span>\
								<div class=\"row slider-padding\">\
									<div class=\"col-lg-12\"></div>\
								</div>\
							</div>\
						</div>\
					</div>"				
				);

				if(section.formula || section.statistics > 0){
					$parent.append($implementation);

					builder.build({
						parent: $implementation.find('.value-holder .col-lg-12'),
						type:"slider",
						step:1,
						min: 0,
						max: term * 12,
						attributes:{
							"calc-id": "IMP1"
						}
					});				
				}

				if(section.statistics > 0){
					$graph = $(
						"<div graph class=\"row border-bottom gray-bg dashboard-header\">\
						</div>"
					)

					section.$holder.append($graph);

					builder.build({
						parent: $graph,
						template: "summaryChart"
					});
				}
			}
		}

		calc.buildElement = function(element){
			var years = element.annual > 0 ? term : 1;

			for( var yr=0; yr<years; yr++){
				if(element.options){
					var custom = $.parseJSON(element.options);
						element = $.extend(true, element, custom);
				}

				var elementObj = {},
					opts = {
						label: years == 1 ? element.Title : element.Title + ' - Year ' + ( yr + 1 ),
						cell: element.address ? element.address : calc.options.letters[yr] + element.ID,
						id: element.ID
					},
					choices = [],
					totalChoices = 1;

				if(element.Tip) opts.tooltip = element.Tip;
				if(element.append) opts.append = element.append;

				$.each(calc.options.entryChoices, function(){
					if(element.ID == this.entryid){
						var choice = {
							text: this.value,
							value: this.dropdown_value ? this.dropdown_value : totalChoices
						}
						totalChoices++;
						choices.push(choice);
					}
				});

				switch(element.Type){
					case '0':
					case 'input':
						elementObj.template = "input";
					break;

					case '1':
					case 'output':
						elementObj.template = "output";
					break;

					case '2':
					case 'textarea':
						elementObj.template = "textarea";
					break;

					case '3':
					case 'dropdown':
						elementObj.template = "dropdown";
						elementObj.children = [{
							parent:{
								local: true,
								selector: "[choices]"
							},
							tag: "select",
							type: "select",
							attributes: {
								"calc-id": element.address ? element.address : calc.options.letters[yr] + element.ID
							},
							choices: choices
						}]
					break;

					case '11':
					case 'slider':
						elementObj.template = "inputSlider";
					break;

					case '13':
					case 'header':
						elementObj.template = "subsectionHeader";
						opts.text = element.Title;
					break;

					case '14':
						elementObj.template = "savingsSummary";
					break;

					case 'include':
						elementObj.template = "include";
						opts.text = element.Title;
					break;

					case 'text':
						elementObj.template = "text";
						opts.text = element.Title;
					break;

					case 'wrapper':
						calc.wrappers.push(element);
					break;
				}

				elementObj.builds = [{
					options: opts
				}];
				elementObj.parent = element.$parent;

				builder.build(elementObj);
			}
		}

		calc.elements = function(){
			var $rsInline = $('[rs-type]');

			$rsInline.each(function(i, element){
				var $element = $(element),
					type = $element.attr('rs-type');

				switch(type){
					case 'toggle':
						var opts = {};
							opts.$container = $element;
							try{
								opts.states = $element.data('states');
								$element.removeAttr('data-states');
							} catch(e){
								$element.remove();
							}

						builder.toggle(opts);
					break;
				}
			});
			
			$.each(calc.wrappers, function(){
				$(this.elements.join()).wrapAll(this.Title);
			});
		}

		/* Dashboard */

		calc.dashboard = function(){
			var $dashboard,
				builds = [],
				opts = calc.options.version.dashboard;
			
			$dashboard = $(
				"<div id=\"dashboard\">\
					<div class=\"row border-bottom white-bg dashboard-header\" id=\"dashboard\">\
						<div writeup class=\"col-lg-12\">\
							<h1 style=\"margin-bottom: 20px\">ROI Dashboard | <span calc-id=\"RP1\"></span> Year Projection <span class=\"pull-right pod-total section-total grand-total txt-money\" calc-id=\"GT1\">$0</span></h1>\
						</div>\
					</div>\
				</div>"				
			);
			$page.append($dashboard);

			$dashboardCaption = $(
				opts && opts.writeup != "undefined" ?
					opts.writeup :	
					"<div class=\"col-lg-12\">\
						<hr>\
						<h3 style=\"font-size: 18px; font-weight: 700;\">Select a section below to review your ROI</h3>\
						<p style=\"font-size: 16px;\">To calculate your return on investment, begin with the first section below. The information entered therein will automatically populate corresponding fields in the other sections. You will be able to move from section to section to add and/or adjust values to best reflect your organization and process. To return to this screen, click the ROI Dashboard button to the left.</p>\
					</div>" 
			);

			$dashboard.find('[writeup]').append($dashboardCaption);
			
			$.each(sections, function(){
				var $pod;

				if(this.pod > 0){
					$pod = [{
						options: {
							id: this.section_id ? this.section_id : this.ID,
							title: this.Title,
							cell: 'ST' + (this.section_id ? this.section_id : this.ID)
						}
					}];

					builds.push({
						template: this.formula || this.statistics > 0 ? this.statistics > 0 ? 'podSavings' : 'pod' : 'podNoSummary',
						builds: $pod
					})
				} else if(this.pod && this.pod.build){
					builds.push(this.pod.build);
				}
			});

			$('#page-wrapper > #dashboard').append($("<div id=\"PodHolder\" class=\"row border-bottom gray-bg dashboard-header\"></div>"));

			builder.build({
				parent: "#page-wrapper > #dashboard > #PodHolder",
				children: builds
			});

			if(opts && opts.appended){
				$('#page-wrapper > #dashboard').append($("<div class=\"row border-bottom gray-bg dashboard-header\">" + opts.appended + "</div>"));			
			}
		}

		/* Navigation */

		calc.navigation = function(){
			calc.sidebar();
			calc.header();
		}

		calc.sidebar = function(){		
			var $sidebar;

			$sidebar = $(sprintf(
				"<nav class=\"navbar-default navbar-static-side\" role=\"navigation\">\
					<div class=\"sidebar-collapse sidebar-navigation\" class=\"overflow: hidden; width: auto; height: 100%;\">\
						<ul class=\"nav\" id=\"side-menu\">\
							<li class=\"nav-header\">\
								<div class=\"dropdown profile-element\">\
									<img id=\"company_logo\" alt=\"image\" src=\"https://www.theroishop.com/%scompany_specific_files/%s/logo/logo.png\">\
								</div>\
							</li>\
							<li mainNav class=\"smooth-scroll\">\
								<a href=\"#\">\
									<i class=\"fa fa-calculator\"></i>\
									<span class=\"nav-label\">ROI Sections</span>\
									<span class=\"fa arrow\"></span>\
								</a>\
								<ul class=\"nav nav-second-level collapse in\" sections>\
									<li><a href=\"#dashboard\" class=\"section-navigator\">Dashboard</a></li>\
								</ul>\
							</li>\
						</ul>\
					</div>\
				</nav>", version.enterprise == 1 ? 'enterprise/' : '', version.version_id));

			calc.el.append($sidebar);

			$.each(sections, function(){
				if(this.pod > 0 || this.pod && this.pod.build){
					$sidebar.find('[sections]').append($(sprintf("<li id=\"%s\"><a href=\"#%sSection\" class=\"section-navigator\">%s</a></li>", ( this.ID + 'Navigation' ), this.ID, this.Title)));
				}
			});
			
			$.each(navigation, function(){
				if(! this.parent){
					var $nav = {
						parent: "#side-menu",
						html: this.label
					};
					calc.render.build.push($nav);
				}
			})

			if(pdfs && pdfs.length){
				var html = [];

				html.push(
					"<li>\
						<a href=\"#\">\
							<i class=\"fa fa-file-pdf-o\"></i>\
							<span class=\"nav-label\">Your PDFs</span>\
							<span class=\"fa arrow\"></span>\
						</a>\
						<ul class=\"nav nav-second-level collapse in\">"
				);

				$.each(pdfs, function(){
					html.push(sprintf("<li><a reportId=\"%s\" class=\"renderPdf\">%s</a></li>", this.pdf_template, this.pdf_name));
				});

				html.push(
					"</ul></li>"
				);

				calc.render.build.push({
					parent: "#side-menu",
					html: html
				});				
			}

			calc.render.build.push({
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

		calc.header = function(){
			var $topNavigation = calc.navigation.$topNavigation = $('<div class="row bottom-border"></div>');
			calc.el.append($topNavigation);

			var actions = [];
			for (var item in calc.options.navigationItems){
				var headerNav = calc.options.navigationItems[item];

				if(calc.options.verification > headerNav.verification){
					var $action = $(sprintf('<li><a>%s</a></li>', headerNav.text));
					actions.push($action);

					if(headerNav.actions){
						var keys = Object.keys(headerNav.actions);
						
						for (var i = 0; i < keys.length; i++) {
							$action.off(keys[i]).on(keys[i], headerNav.actions[keys[i]]);
						}
					}
				}
			}

			var $navigation = $([
				'<nav class="navbar navbar-fixed-top" role="navigation">',
					'<div class="navbar-header">',
						sprintf('<h3>%s</h3>', title),
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

			$topNavigation.append($navigation);

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
	
					if(this.pod > 0 || this.pod && this.pod.build){
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
		}

		calc.events = function(){
			calc.handleInputs();
			calc.setScroll();

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
				roishop.calculationBreakdown($(this).attr('calculation-modal'));
			});

			$('.revolving-html').quovolver({
				autoPlaySpeed : 8000,
				transitionSpeed : 500,
				equalHeight: true
			});

			$('.quovolve-box').each(function(){
				var $blockquotes = $(this).find('blockquote'),
					maxHeight = 0;

				$.each($blockquotes, function(){
					maxHeight = maxHeight > $(this).outerHeight() ? maxHeight : $(this).outerHeight();
				})

				$(this).css('height', maxHeight + 'px');
			});

			$('.share-calculator').on('click', function(){
				roishop.showVerification();
			});

			calc.equalize();
			roishop.updateGraphs();
		};

		calc.handleInputs = function(){
			this.el.on('focus', 'input[calc-id], textarea[calc-id]', function(){
				$(this).select();
			});

			this.el.on('change', '[calc-id]', function(){
				if($(this).prop('tagName').toLowerCase() !== 'td') $(this).trigger('calc.setValue');
			});
		}

		calc.equalize = function(){
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

		calc.setValue = function(cell, value, el){
			if(! calc.sheet) return false;

			calc.sheet.cells[cell].setValue(value);
			calc.sheet.cells[cell].clearProcessedFlag();
			calc.sheet.cells[cell].calculate();

			calc.sheet.renderComputedValue(el);
		}

		calc.setScroll = function(){
			var scrollTo = function(){
				calc.options.isScrolling = true;
				var target = $(this.hash);
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
				
				if (target.length){
					target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
					$('html, body').animate({
						scrollTop: target.offset().top - 63
					}, 1000, function(){
						calc.options.isScrolling = false;
					});
				};
	
				var parent = $(this).parent();
				$('#side-menu').find('li').each(function(){
					if ($(this) !== parent) $(this).removeClass('active');
				});
				
				return false;
			}
			
			$('.smooth-scroll a').off('click').on('click', scrollTo);
			$('a.smooth-scroll').off('click').on('click', scrollTo);
			
			var scroll = function() {
				var item,
					target,
					height = $(window).height(),
					top = $(window).scrollTop();
				

				var $focus = $(document.activeElement);
				if($focus.prop('tagName') == "A" && $focus.parent().prop('tagName') == 'LI'){
					$($focus).blur();
				}

				$('li.smooth-scroll a').each(function(){
					target = $(this.hash);
					if(target.length && target.is(':visible')){
						if (target.offset().top - height / 2 <= top) item = this;
					};
				});
				
				if (item){
					$('li.smooth-scroll a').not(item).each(function(){
						$(this).parent().removeClass('active');
					});
					
					$(item).parent().addClass('active');				
				}
			}
			
			$(window).off('scroll').on('scroll', scroll).scroll();
		}

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
					content: '<textarea class="ver-link-output" spellcheck="false" readonly="readonly">www.theroishop.com/enterprise?roi=' + opts.specs.roi_id + '&v=' + opts.specs.verification_code + '</textarea>'
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

	roishop.updateGraphs = function(){
		var calc = roishop.current;

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

					$.each(chart.userOptions.xAxis.categories, function(i, category){
						if(typeof category === "object"){
							if(category.equation){
								xCategories.push(calc.sheet.cells[(category.equation+'')].getValue());
							}
						} else if(typeof category === "string"){
							xCategories.push(category);
						}
					});

					chart.xAxis[0].update({categories: xCategories}, false);
				}

				chart.redraw();
			}
		});
	}

	roishop.calculationBreakdown = function(cell){
		var calc = roishop.current,
			cell = calc.sheet.cells[cell],
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

			roishop.storeValues();
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
			action: 'versionDesign',
			version: getQueryVariable('version')
		},
		success: function(specs){
			specs = JSON.parse(specs);
			console.log(specs);
			$('#wrapper').roishop(specs);
		}
	});

	return roishop;
})));