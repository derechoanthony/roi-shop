$(document).ready(function() {
				
	var testInputs = {
		elements: [{
			type: 'section',
			id: 44,
			classes: ['row','border-bottom','white-bg','dashboard-header'],
			header: {
				text: '<h1 style="margin-bottom: 20px;">ROI Dashboard | 60 Year Projection</h1>'
			}
		},{
			type: 'holder',
			classes: ['row','bottom-border','gray-bg','dashboard-header'],
			elements: [{
				type: 'holder',
				classes: ['row'],
				elements: [{
					type: 'holder',
					classes: ['col-xs-3','col-sm-3','col-md-3','col-lg-3'], 
					elements: [{
						type: 'holder',
						classes: ['ibox-content','section-pod'],
						elements: [{
							type	:	'text',
							classes: ['smooth-scroll'],
							tag: 'a',
							link: '#section45',
							text: '<h2 class="section-header" style="font-size: 26px; height: 28px;">WIS - Inputs</h2>'
						},{
							type:	'text',
							text: '<h1 class="txt-right pod-total section-total txt-money" data-format="($0,0)" data-formula="( ST1 + ST2 )">$0</h1>'
						}]
					}]					
				}]
			}]
		},{
			type: 'section',
			id: 45,
			classes: ['row','border-bottom','white-bg','dashboard-header'],
			header: {
				tag: 'h1',
				text: 'WIS - Inputs and TCO - Summary',
				style: 'margin-bottom: 20px;'
			}
		},{
			type: 'holder',
			classes: ['row','bottom-border','gray-bg','dashboard-header'],
			elements: [{
				type: 'holder',
				classes: ['bottom-border','white-bg','dashboard-header','col-xs-12','col-sm-12','col-md-12','col-lg-12','margin-bottom-25'],
				elements: [{
					type: 'holder',
					classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-8'],
					elements: [{
						type: 'text',
						classes: ['caption-text'],
						tag: 'p',
						text: '<span style="font-size: 22px;">The Nimble Storage Predictive Flash platform efficiently delivers the benefits of primary storage, backup, and disaster recovery in an all-inclusive package to provide the ideal foundation for comprehensive data protection. And with InfoSight predictive analytics, you can be confident that your data protection strategy is working as expected through intuitive dashboards and proactive notifications.</span>'
					}]
				},{
					type: 'holder',
					classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-4'],
					elements: [{
						type: 'video',
						src: '//www.youtube.com/embed/jjsHZ3dVXes'
					}]
				}]
			},{
				type: 'holder',
				classes: ['row'],
				elements: [{
					type: 'holder',
					classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-6'], 
					elements: [{
						type: 'holder',
						classes: ['ibox-content'],
						elements: [{
							type: 'holder',
							classes: ['form-horizontal'],
							elements: [{
								type: 'holder',
								classes: ['form-group'],
								elements: [{
									type: 'text',
									classes: ['subsection-header'],
									tag: 'div',
									text: '<h2>BASIC INPUTS</h2>'
								}]
							}]
						},{
							type:	'input',
							id:	'1',
							classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-4'],
							label	:	{
								classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-8'],
								text: 'Primary Data'
							},
							format: '0,0[.]00',
							append: 'TB',
							value: '16'
						},{
							type: 'input',
							id:	'2',
							classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-4'],
							label: {
								classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-8'],
								text: 'Backup Retention'
							},
							format:	'0,0[.]00',
							append: 'Days',
							value: '60'
						},{
							type: 'text',
							tag: 'div',
							text: '<h3>Backup Management</h3>'
						},{
							type: 'input',
							id:	'3',
							classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-4'],
							label: {
								classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-8','txt-align-right'],
								text: 'Hours/Week'
							},
							format:	'0,0[.]00',
							value: '10'
						},{
							type: 'input',
							id:	'4',
							classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-4'],
							label: {
								classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-8','txt-align-right'],
								text: 'Costs/Hour'
							},
							format:	'$0,0[.]00',
							value: '30'
						},{
							type: 'input',
							id:	'5',
							classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-4'],
							label: {
								classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-8','txt-align-right'],
								text: 'Converged Backup Open Reduction'
							},
							format:	'0,0[.]00%',
							value: '80'
						},{
							type: 'input',
							id:	'6',
							classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-4'],
							label: {
								classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-8','txt-align-right'],
								text: 'Data loss cost/hour'
							},
							popup: {
								text: 'Cost of losing/re-constructing data due to non-zero RPO'
							},
							format:	'$0,0[.]00',
							value: '250'
						},{
							type: 'input',
							id:	'7',
							classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-4'],
							label: {
								classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-8','txt-align-right'],
								text: 'Annual number of restores from backup'
							},
							popup: {
								text: 'Number of times/year application or user data is restored from a backup system'
							},
							format:	'0,0[.]00',
							value: '4'
						},{
							type: 'dropdown',
							id:	'8',
							classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-4'],
							label: {
								classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-8'],
								text: '<h3>Replication</h3>'
							},
							options: [
								{
									value: '0',
									text: 'None'
								},{
									value: '0.05',
									text: 'D2D Tier'
								},{
									value: '0.01',
									text: 'Primary Tier'
								}
							]
						},{
							type: 'input',
							id:	'9',
							classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-4'],
							label: {
								classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-8','txt-align-right'],
								text: 'Hourly cost of site downtime'
							},
							popup: {
								text: 'Hourly cost of application unavailability due to non-zero RTO'
							},
							format:	'$0,0[.]00',
							value: '20000'
						},{
							type: 'input',
							id:	'10',
							classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-4'],
							label: {
								classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-8','txt-align-right'],
								text: 'Annual probability of site downtime'
							},
							popup: {
								text: 'Probability of data center failure / downtime'
							},
							format:	'0,0[.]00%',
							value: '2'
						},{
							type: 'text',
							tag: 'div',
							text: '<h3>Power/Cooling</h3>'
						},{
							type: 'input',
							id:	'11',
							classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-4'],
							label: {
								classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-8','txt-align-right'],
								text: 'cost/KWH'
							},
							format:	'$0,0[.]00',
							value: '0.12'
						},{
							type: 'input',
							id:	'12',
							classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-4'],
							label: {
								classes: ['control-label','col-xs-12','col-sm-12','col-md-12','col-lg-8','txt-align-right'],
								text: 'cooling cost/KWH'
							},
							format:	'$0,0[.]00',
							value: '0.14'
						}]											
					}]
				},{
					type: 'holder',
					classes: ['col-xs-12','col-sm-12','col-md-12','col-lg-6'], 
					elements: [{
						type: 'holder',
						elements: [{						
							type: 'holder',
							classes: ['ibox-content'],
							elements: [{
								type:	'text',
								classes: ['smooth-scroll'],
								tag: 'a',
								link: '#section45',
								text: '<h2 class="section-header" style="font-size: 26px; height: 28px;">Summary Output</h2>'
							},{
								type: 'table',
								columns: [{
									title: '&nbsp;',
									rows: [{
										content: 'Hardware',
									},{
										content: 'Support',
									},{
										content: 'Power/Cooling',
									},{
										content: 'Backup Management Opex',
									},{
										content: 'Downtime/Restore Cost',
									},{
										content: 'Total',
									}]
								},{
									title: 'Nimble',
									rows: [{
										content: '$0',
										cell: {
											id: 'ST1',
											format: '($0,0)',
											formula: '134300 * 1'
										}
									},{
										content: '$0',
										cell: {
											id: 'ST2',
											format: '($0,0)',
											formula: '35400 * 1'
										}
									},{
										content: '$0',
										cell: {
											id: 'ST3',
											format: '($0,0)',
											formula: '6927 * 1'
										}
									},{
										content: '$0',
										cell: {
											id: 'ST4',
											format: '($0,0)',
											formula: '9360 * 1'
										}
									},{
										content: '$0',
										cell: {
											id: 'ST5',
											format: '($0,0)',
											formula: '4395 * 1'
										}
									},{
										content: '$0',
										cell: {
											format: '($0,0)',
											formula: 'ST1 + ST2 + ST3 + ST4 + ST5'
										}
									}]
								},{
									title: 'Alternative',
									rows: [{
										content: '$0',
										cell: {
											id: 'ST6',
											format: '($0,0)',
											formula: '350000 * 1'
										}
									},{
										content: '$0',
										cell: {
											id: 'ST7',
											format: '($0,0)',
											formula: '61200 * 1'
										}
									},{
										content: '$0',
										cell: {
											id: 'ST8',
											format: '($0,0)',
											formula: '25498 * 1'
										}
									},{
										content: '$0',
										cell: {
											id: 'ST9',
											format: '($0,0)',
											formula: '46800 * 1'
										}
									},{
										content: '$0',
										cell: {
											id: 'ST10',
											format: '($0,0)',
											formula: '40140 * 1'
										}
									},{
										content: '$0',
										cell: {
											format: '($0,0)',
											formula: 'ST6 + ST7 + ST8 + ST9 + ST10'
										}
									}]
								}]	
							}]
						}]
					}]
				}]
			}]
		}]
	};
	
	var sectionBuild = '';
	$.each(testInputs.elements, function( index, value ){
		sectionBuild += __getElementType(value);
	});
	
	$('#roiContent').append(sectionBuild);
});
	
	function __getElementType(value){
		switch(value.type){
			case 'section':
				return __createSection(value);
				break;
				
			case 'input':
				return __createInput(value);
				break;
			
			case 'output':
				return __createOutput(value);
				break;
				
			case 'slider':
				return __createSlider(value);
				break;
				
			case 'toggle':
				return __createToggle(value);
				break;
				
			case 'video':
				return __createVideo(value);
				break;
			
			case 'dropdown':
				return __createDropdown(value);
				break;
				
			case 'textarea':
				return __createTextarea(value);
				break;
				
			case 'table':
				return __createTable(value);
				break;
				
			case 'holder':
				return __buildHolder(value);
				break;
				
			case 'text':
				return __createText(value);
				break;
		}
	}
	
	function __createTable(specs) {
		
		/**
		* create table and add the table cells
		*
		* @return {object}             jQuery object
		*/
		
		var table_header = '<thead>';
		var table_body = '<tbody>';
		var table_footer = '';
		
		// Build table header
		$.each(specs.columns, function( index, value ){
			if(value.title){
				table_header += '<th>' + value.title + '</th>';
			}
		});
		
		table_header += '</thead>';
		
		table_header = ( table_header == '<thead></thead>' ? '' : table_header );
		
		var max_rows = 0;
		
		// Determine max number of rows that need to be built
		$.each(specs.columns, function( index, value ){
			if(value.rows.length > max_rows){
				max_rows = value.rows.length;
			}
		});
		
		for(i=0; i<max_rows; i++){
			
			table_row_cells = [];
			
			table_row = '<tr>';

			$.each(specs.columns, function( index, value ){

				if(value.rows[i] && value.rows[i].length !== 0){
				
					table_cell = '<td';

					if(value.rows[i].cell){
						table_cell += ( value.rows[i].cell.id ? ' data-cell="' + value.rows[i].cell.id + '"'  : '' );
						table_cell += ( value.rows[i].cell.format ? ' data-format="' + value.rows[i].cell.format + '"' : '' );
						table_cell += ( value.rows[i].cell.formula ? ' data-formula="' + value.rows[i].cell.formula + '"' : '' );
					}
					
					table_cell += '>' + value.rows[i].content + '</td>';
					
					table_row += table_cell;
				
				}
			});

			table_row += '</tr>';

			table_body += table_row;
		}
		
		table_body += '</tbody>';

		return '<table id="example" class="display datatable cell-border stripe" cellspacing="0" width="100%" data-paging="false" data-searching="false" data-info="false" data-ordering="false">' + table_header + table_body + '</table>';
	}
	
	function __createTextarea(specs){

		/**
		* create an textarea holder and add the elements
		*
		* @return {object}             jQuery object
		*/
		
		// initialize the input
		var textarea = '<div class="form-horizontal"' + ( specs.visible == 'false' ? ' style="display:none;"' : '' ) + '><div class="form-group">';
		var textarea_holder_class = '';
		var textarea_class = 'form-control';
		
		if(specs.label){
			// create label if one defined in array
			textarea += __createLabel(specs.label);
		}
		
		// create label classes
		if(specs.classes && specs.classes.length !== 0){
			$.each(specs.classes, function( index, value ){
				textarea_holder_class += ' ' + value;
			});			
		}
		
		if(specs.popup){
			textarea_class += ' input-addon';
		}
		
		textarea += '<div class="' + textarea_holder_class + '">';
		
		if(specs.popup || specs.append || specs.prepend){
			// add holder if input has additional information
			textarea += '<div class="input-group">';
		}
		
		// initialize input attributes
		var textarea_attributes = {};
		
		// build input attributes
		textarea_attributes.id = 'id="' + specs.id + '"';
		textarea_attributes.type = 'type="' + ( specs.style ? specs.style : 'text' ) + '"';
		textarea_attributes.class = 'class="' + textarea_class + '"';
		textarea_attributes.name = 'name="' + specs.id + '"';
		textarea_attributes.cell_reference = 'data-cell-reference="A' + specs.id + '"';
		textarea_attributes.cell = 'data-cell="A' + specs.id + '"';
		textarea_attributes.rows = 'rows="' + specs.rows + '"';
		
		textarea += '<textarea';
		
		$.each(textarea_attributes, function( index, value ){
			textarea += ' ' + value;
		});
		
		textarea += '>';
		
		if(specs.value){
			textarea += specs.value;
		}		
		
		textarea += '</textarea>';
		
		if(specs.popup){
			textarea += __createPopup(specs.popup, 'input');
		}
		
		if(specs.append){
			textarea += __createAppend(specs.append);
		}
		
		if(specs.popup || specs.append || specs.prepend){
			// add holder if input has additional information
			textarea += '</div>';
		}
		
		textarea += '</div></div></div>';
		
		return textarea;
		
		
		
	}
	
	function __createSection(specs) {

		/**
		* create an element holder and add the elements
		*
		* @return {object}             jQuery object
		*/
		
		var section_class = '';
		
		if(specs.classes && specs.classes.length !== 0){
			$.each(specs.classes, function( index, value ){
				section_class += ' ' + value;
			});			
		}
		
		var section = '<div id="section' + specs.id + '" class="' + section_class + '">';
		
		if(specs.header.tag){
			section += '<' + specs.header.tag;
		}
		
		if(specs.header.style){
			section += ' style="' + specs.header.style + '"';
		}

		if(specs.header.tag){
			section += '>';
		}
		
		if(specs.header){
			section += specs.header.text;
		}
		
		
		if(specs.header.equation){
			section += '<span class="pull-right pod-total section-total txt-money" data-format="($0,0)" data-formula="' + specs.header.equation + '"></span>';
		}
		
		if(specs.header.tag){
			section += '</' + specs.header.tag + '>';
		}
		
		if(specs.elements && specs.elements.length !==0){
			$.each(specs.elements, function( index, value ){
				section += __getElementType(value);
			});				
		}
		
		section += '</div>';
		
		return section;
		
	}
	
	function __buildHolder(specs) {
        
		/**
		* create an element holder and add the elements
		*
		* @return {object}             jQuery object
		*/
		
		var holder_class = '';
		
		if(specs.classes && specs.classes.length !== 0){
			$.each(specs.classes, function( index, value ){
				holder_class += ' ' + value;
			});			
		}
		
		var holder = '<div class="' + holder_class + '">';
		
		if(specs.elements && specs.elements.length !==0){
			$.each(specs.elements, function( index, value ){
				holder += __getElementType(value);
			});				
		}
		
		holder += '</div>';
		
		return holder;
	}
	
	function __createInput(specs) {
	
        /**
		* create the input and return it to the roi build that
		* called the procedure
		*
		* @return {object}             jQuery object
		*/
		
		// initialize the input
		var input = '<div class="form-horizontal"' + ( specs.visible == 'false' ? ' style="display:none;"' : '' ) + '><div class="form-group">';
		var input_holder_class = '';
		var input_class = 'form-control';
		
		if(specs.label){
			// create label if one defined in array
			input += __createLabel(specs.label);
		}
		
		// create label classes
		if(specs.classes && specs.classes.length !== 0){
			$.each(specs.classes, function( index, value ){
				input_holder_class += ' ' + value;
			});			
		}
		
		if(specs.popup){
			input_class += ' input-addon';
		}
		
		input += '<div class="' + input_holder_class + '">';
		
		if(specs.popup || specs.append || specs.prepend){
			// add holder if input has additional information
			input += '<div class="input-group">';
		}
		
		// initialize input attributes
		var input_attributes = {};
		
		// build input attributes
		input_attributes.id = 'id="' + specs.id + '"';
		input_attributes.type = 'type="' + ( specs.style ? specs.style : 'text' ) + '"';
		input_attributes.class = 'class="' + input_class + '"';
		input_attributes.name = 'name="' + specs.id + '"';
		input_attributes.cell_reference = 'data-cell-reference="A' + specs.id + '"';
		input_attributes.cell = 'data-cell="A' + specs.id + '"';
		input_attributes.format = 'data-format="' + specs.format + '"';
		
		if(specs.value){
			input_attributes.value = 'value="' + specs.value + '"';
		}

		input += '<input';
		
		$.each(input_attributes, function( index, value ){
			input += ' ' + value;
		});
		
		input += '>';
		
		if(specs.popup){
			input += __createPopup(specs.popup, 'input');
		}
		
		if(specs.append){
			input += __createAppend(specs.append);
		}
		
		if(specs.popup || specs.append || specs.prepend){
			// add holder if input has additional information
			input += '</div>';
		}
		
		input += '</div></div></div>';
		
		return input;
		
	}
	
	function __createOutput(specs) {
	
        /**
		* create the output and return it to the roi build that
		* called the procedure
		*
		* @return {object}             jQuery object
		*/
		
		// initialize the input
		var input = '<div class="form-horizontal"><div class="form-group">';
		var input_holder_class = '';
		var input_class = 'form-control';
		
		if(specs.label){
			// create label if one defined in array
			input += __createLabel(specs.label);
		}
		
		// create output classes
		if(specs.classes && specs.classes.length !== 0){
			$.each(specs.classes, function( index, value ){
				input_holder_class += ' ' + value;
			});			
		}
		
		if(specs.popup){
			input_class += ' input-addon';
		}
		
		input += '<div class="' + input_holder_class + '">';
		
		if(specs.popup || specs.append || specs.prepend){
			// add holder if input has additional information
			input += '<div class="input-group">';
		}
		
		// initialize input attributes
		var input_attributes = {};
		
		// build output attributes
		input_attributes.id = 'id="' + specs.id + '"';
		input_attributes.type = 'type="' + ( specs.style ? specs.style : 'text' ) + '"';
		input_attributes.class = 'class="' + input_class + '"';
		input_attributes.name = 'name="' + specs.id + '"';
		input_attributes.cell_reference = 'data-cell-reference="A' + specs.id + '"';
		input_attributes.cell = 'data-cell="A' + specs.id + '"';
		input_attributes.format = 'data-format="' + specs.format + '"';
		input_attributes.formula = 'data-formula="' + specs.formula + '"';
		input_attributes.disabled = 'disabled="disabled"';
		
		if(specs.value){
			input_attributes.value = 'value="' + specs.value + '"';
		}

		input += '<input';
		
		$.each(input_attributes, function( index, value ){
			input += ' ' + value;
		});
		
		input += '>';
		
		if(specs.popup){
			input += __createPopup(specs.popup, 'output');
		}
		
		if(specs.append){
			input += __createAppend(specs.append);
		}
		
		if(specs.popup || specs.append || specs.prepend){
			// add holder if input has additional information
			input += '</div>';
		}
		
		input += '</div></div></div>';
		
		return input;
		
	}
	
	function __createSlider(specs) {
	
        /**
		* create the input and return it to the roi build that
		* called the procedure
		*
		* @return {object}             jQuery object
		*/

		// initialize the slider
		var slider = '<div class="form-horizontal">\
						<div class="form-group">';
		var slider_class = '';
		
		if(specs.style == 'stacked'){
			
			slider += '<label class="control-label col-lg-12">' + specs.label.text;
			
			slider += '<span class="pull-right">\
						<span class="slider-input"\
							data-format="' + specs.format + '" data-cell="A' + specs.id + '" data-cell-reference="A' + specs.id + '"\
						></span>';
						
			if(specs.append){
				slider += specs.append;
			}
			
			slider += '</span>\
						</label>\
						<div class="input-slider col-lg-12">\
							<div id="drag-fixed" class="slider"\
								data-min="' + ( specs.restraints.min ? specs.restraints.min : '0' ) + '"\
								data-max="' + ( specs.restraints.max ? specs.restraints.max : '100' ) + '"\
								data-step="' + ( specs.restraints.step ? specs.restraints.step : '1' ) + '"\
								data-cell-reference="A' + specs.id + '">\
							</div>\
						</div>\
					</div></div>';
					
			return slider;
		}
		
		if(specs.label){
			// create label if one defined in array
			slider += __createLabel(specs.label);
		}
		
		// create label classes
		if(specs.classes && specs.classes.length !== 0){
			$.each(specs.classes, function( index, value ){
				slider_class += ' ' + value;
			});			
		}
		
		slider += '<div class="' + slider_class + '">';
		
		slider += '<div class="col-lg-6 input-slider">';
		
		slider_attributes = {};
		
		slider_attributes.min = 'data-min="' + specs.restraints.min + '"';
		slider_attributes.max = 'data-max="' + specs.restraints.max + '"';
		slider_attributes.step = 'data-step="' + specs.restraints.step + '"';
		slider_attributes.reference = 'data-cell-reference="' + specs.id + '"';
		
		slider += '<div class="slider"';
		
		$.each(slider_attributes, function( index, value ){
			slider += ' ' + value;
		});
		
		slider += '>';
		
		slider += '</div></div><div class="input-group">';
		
		if(specs.elements && specs.elements.length !==0){
			$.each(specs.elements, function( index, value ){
				slider += __getElementType(value);
			});
		}
		
		slider += '</div></div></div></div>';
		
		return slider;
		
	}
	
	function __createToggle(specs) {
	
        /**
		* create the input and return it to the roi build that
		* called the procedure
		*
		* @return {object}             jQuery object
		*/

		// initialize the slider
		var toggle = '<div class="form-horizontal">\
						<div class="form-group">\
							<div class="col-lg-12">';
		var toggle_class = '';
		
		// create label classes
		if(specs.classes && specs.classes.length !== 0){
			$.each(specs.classes, function( index, value ){
				toggle_class += ' ' + value;
			});			
		}
		
		toggle += '<button class="' + toggle_class + '" type="button"\
					data-on-value="' + specs.restraints.onvalue + '"\
					data-off-value="' + specs.restraints.offvalue + '"\
					data-on-text="' + specs.restraints.ontext + '"\
					data-off-text="' + specs.restraints.offtext + '"\
					data-on-class="' + specs.restraints.onclass + '"\
					data-off-class="' + specs.restraints.offclass + '"\
					data-cell-reference="A' + specs.id + '">' + specs.restraints.ontext + '</button>';
		
		toggle += '<input type="hidden" name="' + specs.id + '" data-cell="A' + specs.id + '" data-cell-reference="A' + specs.id + '" value="1">';
		
		toggle += '</div></div></div>';
		
		return toggle;
		
	}
	
	function __createVideo(specs){
		
		/**
		* create video element and return it to the element that
		* called the procedure
		*
		* @return {object}             jQuery object
		*/
		
		var video = '';
		
		video += 	'<div class="player">\
						<a class="popup-iframe" href="' + specs.src + '"></a>\
						<iframe width="425" height="239" style="margin-left: 5px;" src="' + specs.src + '" frameborder="0" allowfullscreen></iframe>\
					</div>';
				
		return video;
	}
	
	function __createText(specs){

        /**
		* create text element and return it to the element that
		* called the procedure
		*
		* @return {object}             jQuery object
		*/

		var text = '';
		var text_link = '';
		var text_class = '';
		
		if(specs.classes){
			text_class = __createClasses(specs.classes);
		}
		
		if(specs.link){
			text_link += ' href="' + specs.link +  '"';
		}
		
		if(specs.tag){
			text += '<' + specs.tag + ' ' + text_class + text_link + '>';
		}
		
		text += specs.text;
		
		if(specs.tag){
			text += '</' + specs.tag + '>';	
		}
		
		return text;
	}
	
	function __createDropdown(specs){
		
        /**
		* create the dropdown and return it to the roi build that
		* called the procedure
		*
		* @return {object}             jQuery object
		*/
		
		// initialize the input
		var dropdown = '<div class="form-horizontal"><div class="form-group">';
		var dropdown_holder_class = '';
		var dropdown_class = 'form-control chosen-selector';

		if(specs.label){
			// create label if one defined in array
			dropdown += __createLabel(specs.label);
		}
		
		// create label classes
		if(specs.classes && specs.classes.length !== 0){
			$.each(specs.classes, function( index, value ){
				dropdown_holder_class += ' ' + value;
			});			
		}
		
		dropdown += '<div class="' + dropdown_holder_class + '">';
		
		// initialize input attributes
		var dropdown_attributes = {};
		
		// build input attributes
		dropdown_attributes.id = 'id="' + specs.id + '"';
		dropdown_attributes.name = 'name="' + specs.id + '"';
		dropdown_attributes.placeholder = 'data-placeholder="Please make a selection below"';
		dropdown_attributes.cell = 'data-cell="A' + specs.id + '"';
		
		if(specs.value){
			dropdown_attributes.value = 'value="' + specs.value + '"';
		}

		dropdown += '<select class="' + dropdown_class + '"';
		
		$.each(dropdown_attributes, function( index, value ){
			dropdown += ' ' + value;
		});
		
		dropdown += '>';
		
		$.each(specs.options, function( index, value ){
			dropdown += '<option value="' + value.value + '">' + value.text + '</option>';
		});
		
		dropdown += '</select>';
		
		dropdown += '</div></div></div>';
		
		return dropdown;		
	}
	
	function __createClasses(classes){
		
		var element_classes = '';
		
		// create label classes
		if(classes && classes.length !== 0){
			$.each(classes, function( index, value ){
				element_classes += ( element_classes == '' ? '' : ' ' ) + value;
			});			
		}
		
		element_classes = 'class="' + element_classes + '"';

		return element_classes;
	}
	
	function __createLabel(specs) {
		
        /**
		* create the element label and return it to the element that
		* called the procedure
		*
		* @return {object}             jQuery object
		*/
		
		// initialize label and label class
		var label = '<label ';
		
		// create label classes
		label_class = __createClasses(specs.classes);
		
		label += label_class + '>' + specs.text + '</label>';

		// return the created label
		return label;
	}
	
	function __createPopup(specs, type){
		
        /**
		* create the element popup and return it to the element that
		* called the procedure
		*
		* @return {object}             jQuery object
		*/
						
		var popup = '<span class="input-group-addon ' + type + ' right helper">';
		popup += '<i class="fa fa-question-circle tooltipstered" data-toggle="tooltip" data-placement="right" title="' + specs.text + '"></i>';
		popup += '</span>';
		
		return popup;
	}
	
	function __createAppend(append){
        
		/**
		* create the element append and return it to the element that
		* called the procedure
		*
		* @return {object}             jQuery object
		*/
		
		append = '<span class="input-group-addon right append">' + append + '</span>';
		
		return append;
	}