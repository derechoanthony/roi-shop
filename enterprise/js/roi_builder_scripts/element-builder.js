
	function __getElementType(value){
		
		var element_build = '';
		var visibility = 'show';
		
		if(value.permission){
			visibility = __buildVisibility(value.permission);
		}

		if(visibility != "none") {		

			element_build += '<div data-element-type="' + value.type + '"';
			
			if(visibility == "hide") {
				
				element_build += ' style="display: none;"';
			}
			
			element_build += '>';
			console.log(value);
			switch(value.type){
				case 'section':
					element_build += __createSection(value);
					break;
					
				case 'input':
					element_build += __createInputHolder(value);
					break;
				
				case 'output':
					element_build += __createOutput(value);
					break;
					
				case 'slider':
					element_build += __createSlider(value);
					break;
					
				case 'tab':
					element_build += __createTab(value);
					break;
					
				case 'toggle':
					element_build += __createToggle(value);
					break;
					
				case 'video':
					element_build += __createVideo(value);
					break;
				
				case 'dropdown':
					element_build += __createDropdown(value);
					break;
					
				case 'textarea':
					element_build += __createTextarea(value);
					break;
					
				case 'rowtable':
					element_build += __createRowTable(value);
					break;
					
				case 'table':
					element_build += __createTableHolder(value);
					break;
					
				case 'graph':
					element_build += __createGraph(value);
					break;

				case 'holder':
					element_build += __buildHolder(value);
					break;
					
				case 'text':
					element_build += __createText(value);
					break;
			}
				
			element_build += '</div>';
			
		}
		
		return element_build;
	}
	
	function __createSection(specs) {

		/**
		* create section element and add this object to the builder array
		*
		* @return {object}             jQuery object
		*/
		
		var section_class = '';
			
		if(specs.classes && specs.classes.length !== 0){
			$.each(specs.classes, function( index, value ){
				section_class += ' ' + value;
			});			
		}
		
		var section = '<div id="section' + specs.id + '" class="' + section_class + '" data-section="' + specs.id + '">';
			
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
	
	function __createGraph(specs) {
		
		var graph = '<div class="graph-holder">';
		
		if(specs.series && specs.series.length !==0 ) {

			$.each(specs.series, function( index, value ){
				
				graph += '<div style="display: none;" class="series-holder" data-series-name="' + value.name + '">';
				
				if(value.equations && value.equations.length !== 0){
					$.each(value.equations, function( index, value ){
						
						graph += '<input class="graph-formula" data-formula="' + value.equation + '" data-series-name="' + value.name + '" data-sliced="' + value.sliced + '"/>';
					});
				}
				
				graph += '</div>';
			});
		
		}
		
		graph += '<div class="ROICalcElemID" id="ROICalcElemID' + specs.id + '" data-id="' + specs.id + '" data-animate="1"></div></div>';
		
		return graph;
	}
	
	function __createTableHolder(specs) {
		
		/**
		* create table and add the table cells
		*
		* @return {object}             jQuery object
		*/
		
		var table_header = '<thead>';
		var table_body = '<tbody>';
		var table_footer = '';

		// Build table header
		if(specs.headers){
			$.each(specs.headers, function( index, value ){
				table_header += '<tr>';
				
				if( value.header ){
					$.each(value.header, function( index, value ) {
						if(value.title){
							table_header += '<th' + ( value.title.colspan ? ' colspan="' + value.title.colspan + '"' : '' ) + ( value.title.rowspan ? ' rowspan="' + value.title.rowspan + '"' : '' ) + '>' + value.title.text + '</th>';
						}				
					});				
				}

				table_header += '</tr>';
			});
		}
		
		table_header += '</thead>';
		
		table_header = ( table_header == '<thead></thead>' ? '' : table_header );
		
		$.each(specs.rows, function( index, value ){

			var repeat = value.repeat ? value.repeat : '1';
			var id = value.id ? value.id : '';
			var row_class = '';
			
			// create label classes
			if(value.classes && value.classes.length !== 0){
				$.each(value.classes, function( index, value ){
					row_class += ' ' + value;
				});			
			}
			
			var first_repeat = 0;
			
			for( var i=0; i<repeat; i++ ){
				
				table_row = '<tr' + ( id ? ' data-row-id="' + id + '"' : '' ) + ( row_class ? ' class="' + row_class + '"' : '' ) + ( first_repeat == 0 && repeat != 1 ? ' data-repeat="' + repeat + '"' : '' ) + '>';
				
				if(value.cells) {
					
					$.each(value.cells, function( index, value ){

						try {
							table_cell = '<td' + ( value.colspan ? ' colspan="' + value.colspan + '"' : '' ) + '>';
							if(repeat > 1) {
								value.formulastring = value.formula;
								value.formula = eval( value.formula );
								value.id = ( id ? parseInt(value.id) + parseInt(id) : value.id );
								value.name = id;
							};

							switch(value.type){
								
								case 'input':
								table_cell += __createInput(value);
								break;
								
								case 'text':
								table_cell += __createText(value);
								break;
								
							};
							
							if(value.formulastring) {
								value.formula = value.formulastring;
							}
							
							table_cell += '</td>';

							table_row += table_cell;
						} catch(e) { }
						
					});
				
				}

				if(id) {
					id++;
				}
				
				table_row += '</tr>';

				table_body += table_row;
				
				first_repeat++;

			}

		});
		
		table_body += '</tbody>';

		table = '<table id="example" class="display datatable cell-border stripe" cellspacing="0" width="100%" data-paging="' + ( specs.specs && specs.specs.pagination == true ? 'true' : 'false' ) + '" data-searching="' + ( specs.specs && specs.specs.searching == true ? 'true' : 'false' ) + '" data-info="' + ( specs.specs && specs.specs.info == true ? 'true' : 'false' ) + '" data-ordering="' + ( specs.specs && specs.specs.ordering == true ? 'true' : 'false' ) + '">' + table_header + table_body + '</table>';

		return table;
		
	}
	
	function __createRowTable(specs) {
		
		/**
		* create table and add the table cells
		*
		* @return {object}             jQuery object
		*/
		
		var table_header = '<thead>';
		var table_body = '<tbody>';
		var table_footer = '';
		
		// Build table header
		if(specs.headers){
			$.each(specs.headers, function( index, value ){
				table_header += '<tr>';
				
				if( value.header ){
					$.each(value.header, function( index, value ) {
						if(value.title){
							table_header += '<th' + ( value.title.colspan ? ' colspan="' + value.title.colspan + '"' : '' ) + ( value.title.rowspan ? ' rowspan="' + value.title.rowspan + '"' : '' ) + '>' + value.title.text + '</th>';
						}				
					});				
				}

				table_header += '</tr>';
			});
		}
		
		table_header += '</thead>';
		
		table_header = ( table_header == '<thead></thead>' ? '' : table_header );
		
		$.each(specs.rows, function( index, value ){

			var repeat = value.repeat ? value.repeat : '1';
			var id = value.id ? value.id : '';
			var row_class = '';
			
			// create label classes
			if(value.classes && value.classes.length !== 0){
				$.each(value.classes, function( index, value ){
					row_class += ' ' + value;
				});			
			}
			
			var first_repeat = 0;
			
			for( var i=0; i<repeat; i++ ){
				
				table_row = '<tr' + ( id ? ' data-row-id="' + id + '"' : '' ) + ( row_class ? ' class="' + row_class + '"' : '' ) + ( first_repeat == 0 && repeat != 1 ? ' data-repeat="' + repeat + '"' : '' ) + '>';
				
				if(value.cells) {
					
					$.each(value.cells, function( index, value ){
						
						table_cell = '<td';

						if(value.type == 'input') {
							table_cell += '><input class="form-control"';
						}
								
						table_cell += ( value.id ? ' data-cell="' + ( id ? value.id + id : value.id ) + '"'  : '' );
						table_cell += ( value.id ? ' name="' + ( id ? value.id + id : value.id ) + '"'  : '' );
						table_cell += ( value.id ? ' data-cell-reference="' + ( id ? value.id + id : value.id ) + '"'  : '' );
						table_cell += ( value.format ? ' data-format="' + value.format + '"' : '' );
						table_cell += ( value.formula ? ' data-formula="' + eval( value.formula ) + '"' : '' );
						table_cell += ( value.colspan ? ' colspan="' + value.colspan + '"' : '' );
							
						if(value.type == 'input') {
							table_cell += ' value="' + value.content + '"';
						}
						
						table_cell += '>' + ( value.type == 'input' ? '' : value.content ) + '</td>';

						table_row += table_cell;
						
					});
				
				}

				if(id) {
					id++;
				}
				
				table_row += '</tr>';

				table_body += table_row;
				
				first_repeat++;

			}

		});
		
		table_body += '</tbody>';

		table = '<table id="example" class="display datatable cell-border stripe" cellspacing="0" width="100%" data-paging="' + ( specs.specs && specs.specs.pagination == true ? 'true' : 'false' ) + '" data-searching="' + ( specs.specs && specs.specs.searching == true ? 'true' : 'false' ) + '" data-info="' + ( specs.specs && specs.specs.info == true ? 'true' : 'false' ) + '" data-ordering="' + ( specs.specs && specs.specs.ordering == true ? 'true' : 'false' ) + '">' + table_header + table_body + '</table>';

		return table;
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
				table_header += '<th' + ( value.title.colspan ? ' colspan="' + value.title.colspan + '"' : '' ) + '>' + value.title.text + '</th>';
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

						if(value.rows[i].cell.type == 'input') {
							table_cell += '><input class="form-control"';
						}
						
						table_cell += ( value.rows[i].cell.id ? ' data-cell="' + value.rows[i].cell.id + '"'  : '' );
						table_cell += ( value.rows[i].cell.format ? ' data-format="' + value.rows[i].cell.format + '"' : '' );
						table_cell += ( value.rows[i].cell.formula ? ' data-formula="' + value.rows[i].cell.formula + '"' : '' );
						table_cell += ( value.rows[i].cell.colspan ? ' colspan="' + value.rows[i].cell.colspan + '"' : '' );
					
						if(value.rows[i].cell.type == 'input') {
							table_cell += ' value="' + value.rows[i].content + '"';
						}
					
					}
					
					table_cell += '>' + ( value.rows[i].cell && value.rows[i].cell.type == 'input' ? '' : value.rows[i].content ) + '</td>';
					
					table_row += table_cell;
				
				}
			});

			table_row += '</tr>';

			table_body += table_row;
		}
		
		table_body += '</tbody>';

		table = '<table id="example" class="display datatable cell-border stripe" cellspacing="0" width="100%" data-paging="' + ( specs.specs && specs.specs.pagination == true ? 'true' : 'false' ) + '" data-searching="' + ( specs.specs && specs.specs.searching == true ? 'true' : 'false' ) + '" data-info="' + ( specs.specs && specs.specs.info == true ? 'true' : 'false' ) + '" data-ordering="' + ( specs.specs && specs.specs.ordering == true ? 'true' : 'false' ) + '">' + table_header + table_body + '</table>';
		
		return table;
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
	
	function __buildVisibility(specs){
		
		/**
		* build visibility and return the string
		*
		* @return {object}             jQuery object
		*/
		
		var user_permissions = {
			permission_level: $('#verificationLevel').data('verification') ? $('#verificationLevel').data('verification') : '1'
			
		}

		var render = 'show';
		
		if(specs && specs.length !== 0){
			$.each(specs, function( index, value ){
				if(value.level >= user_permissions.permission_level) {
					if(value.visibility == "hidden" && render != "none") {
						render = 'hide';
					} else if (value.visibility == "none") {
						render = 'none';
					}
				}
			});			
		}

		return render;
		
	}
	
	function __createTab(specs) {

		/**
		* create an element holder and add the elements
		*
		* @return {object}             jQuery object
		*/
		
		var tab_class = '';
		
		if(specs.classes && specs.classes.length !== 0){
			$.each(specs.classes, function( index, value ){
				tab_class += ' ' + value;
			});			
		}
		
		var tab = '<div class="tabs-container">\
						<ul class="nav nav-tabs">';

		if(specs.tabs && specs.tabs.length !== 0){
			$.each(specs.tabs, function( index, value ){
				tab += '<li' + ( value.active ? ' class="active"' : '' ) + '><a class="tab-toggle" data-toggle="tab" href="#tab-' + value.id + '">' + value.title + '</a></li>';
			});			
		}
		
		tab += '</ul><div class="tab-content">';
		
		if(specs.tabs && specs.tabs.length !== 0){
			$.each(specs.tabs, function( index, value ){
				tab += '<div id="tab-' + value.id + '" class="tab-pane' + ( value.active ? ' active' : '' ) + '"><div class="panel-body">';
				
				tab += '<input type="hidden" class="tab-active-input" name="TAB' + value.id + '" data-cell="INPUTTAB' + value.id + '" data-active-value="' + value.value + '">';
				
				if(value.elements && value.elements.length !==0){
					$.each(value.elements, function( index, value ){
						tab += __getElementType(value);
					});				
				}
				
				tab += '</div></div>';
			});			
		}
		
		tab += '</div></div>';
		
		return tab;
		
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
				holder_class += holder_class == '' ? value : ' ' + value;
			});			
		}

		var holder = '<div id="' + specs.holder_id + '" class="' + holder_class + '" data-holder-id="' + specs.holder_id + '">';
		
		if(specs.elements && specs.elements.length !==0){
			$.each(specs.elements, function( index, value ){
				holder += __getElementType(value);
			});				
		}
		
		holder += '</div>';
		
		return holder;
	}
	
	function __createInput(specs) {

		var input = '';
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
		
		input += '<div class="input-holder' + input_holder_class + '">';
		
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
		
		if(specs.name || specs.id){
			input_attributes.name = 'name="' + specs.id + '"';
			input_attributes.cell_reference = 'data-cell-reference="' + ( specs.letter ? specs.letter : 'A' ) + specs.name + '"';
			input_attributes.cell = 'data-cell="' + ( specs.letter ? specs.letter : 'A' ) + specs.name + '"';
			input_attributes.format = 'data-format="' + specs.format + '"';			
		};
		
		input_attributes.oncalc = 'data-oncalc="' + ( specs.oncalc ? specs.oncalc : 'local' ) + '"';
		
		if(specs.formula){
			input_attributes.formula = 'data-formula="' + specs.formula + '"';
		}
		
		if(specs.disabled){
			input_attributes.disabled = 'disabled="disabled"';
		}
		
		if(specs.value){
			input_attributes.value = 'value="' + specs.value + '"';
		}

		input += '<input';
		
		$.each(input_attributes, function( index, value ){
			input += ' ' + value;
		});
		
		input += '>';
		
		var input_type = 'input';
		if(specs.disabled){
			input_type = 'output';
		}
		
		if(specs.popup){
			input += __createPopup(specs.popup, input_type);
		}
		
		if(specs.append){
			input += __createAppend(specs.append);
		}
		
		if(specs.popup || specs.append || specs.prepend){
			// add holder if input has additional information
			input += '</div>';
		}

		input += '</div>';
		
		return input;
	}
	
	function __createInputHolder(specs) {
	
        /**
		* create the input and return it to the roi build that
		* called the procedure
		*
		* @return {object}             jQuery object
		*/
		
		// initialize the input
		var input = '<div class="form-horizontal"' + ( specs.visible == 'false' ? ' style="display:none;"' : '' ) + '><div class="form-group">';
		
		input += __createInput(specs);
		
		input += '</div></div>';
		
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
		input_attributes.cell_reference = 'data-cell-reference="A' + specs.name + '"';
		input_attributes.cell = 'data-cell="A' + specs.name + '"';
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
							data-format="' + specs.format + '" data-cell="' + specs.id + '" data-cell-reference="' + specs.id + '"\
						>' + specs.value + '</span>';
						
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
								data-cell-reference="' + specs.id + '"\
								data-start="' + specs.value + '">\
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
		
		if(specs.restraints && specs.restraints.length !== 0){
			slider_attributes.min = 'data-min="' + specs.restraints.min + '"';
			slider_attributes.max = 'data-max="' + specs.restraints.max + '"';
			slider_attributes.step = 'data-step="' + specs.restraints.step + '"';
		};
		
		slider_attributes.reference = 'data-cell-reference="' + specs.id + '"';
		
		if(specs.value){
			slider_attributes.start = 'data-start="' + specs.value + '"';
		};
		
		slider += '<div class="slider"';
		
		$.each(slider_attributes, function( index, value ){
			slider += ' ' + value;
		});
		
		slider += '>';
		
		slider += '</div></div><div class="input-group col-lg-6">';
		
		// initialize input attributes
		var input_attributes = {};
		
		// build input attributes
		input_attributes.id = 'id="' + specs.id + '"';
		input_attributes.type = 'type="' + ( specs.style ? specs.style : 'text' ) + '"';
		input_attributes.class = 'class="form-control"';
		
		if(specs.name || specs.id){
			input_attributes.name = 'name="' + specs.id + '"';
			input_attributes.cell_reference = 'data-cell-reference="' + ( specs.letter ? specs.letter : 'A' ) + specs.name + '"';
			input_attributes.cell = 'data-cell="' + ( specs.letter ? specs.letter : 'A' ) + specs.name + '"';
			input_attributes.format = 'data-format="' + specs.format + '"';			
		};
		
		input_attributes.oncalc = 'data-oncalc="' + ( specs.oncalc ? specs.oncalc : 'local' ) + '"';
		
		if(specs.formula){
			input_attributes.formula = 'data-formula="' + specs.formula + '"';
		}
		
		if(specs.disabled){
			input_attributes.disabled = 'disabled="disabled"';
		}
		
		if(specs.value){
			input_attributes.value = 'value="' + specs.value + '"';
		}

		slider += '<input';
		
		$.each(input_attributes, function( index, value ){
			slider += ' ' + value;
		});
		
		slider += '>';
		
		var input_type = 'input';
		if(specs.disabled){
			input_type = 'output';
		}
		
		if(specs.popup){
			slider += __createPopup(specs.popup, input_type);
		}
		
		if(specs.append){
			slider += __createAppend(specs.append);
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
		
		if(specs.classes && specs.classes.length !== 0){
			$.each(specs.classes, function( index, value ){
				text_class += text_class == '' ? value : ' ' + value;
			});			
		}
		
		if(specs.link){
			text_link += ' href="' + specs.link +  '"';
		}
		
		if(specs.tag){
			text += '<' + specs.tag + ' class="' + text_class + '"' + text_link + '>';
		}
		
		text += specs.text;
		
		if(specs.tag){
			text += '</' + specs.tag + '>';	
		}
		
		var text_specs = '<div' + ( specs.link ? ' data-text-link="' + specs.link + '"' : '' ) + ( specs.tag ? ' data-text-tag="' + specs.tag + '"' : '' ) + ( specs.classes ? ' data-text-classes="' + specs.classes + '"' : '' ) + ( specs.text ? ' data-text-text="' + specs.text.replace(/"/g, '&quot;') + '"' : '' ) + '>';
		
		text = text_specs + text + '</div>';
		
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
			dropdown += '<option' + ( value.selected ? ' selected="selected"' : '' ) + ' value="' + value.value + '"' + ( value.showmap ? ' data-show-map="' + value.showmap + '"' : '' ) + '>' + value.text + '</option>';
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
	
	function __buildLeftSidebar(specs) {
		
		leftSidebar = '<div class="sidebar-collapse">\
							<ul class="nav metismenu" id="side-menu">';
		
		if(specs.logo) {
			
			var logo_classes= '';
			
			// create logo classes
			if(specs.logo.classes && specs.logo.classes.length !== 0){
				$.each(specs.logo.classes, function( index, value ){
					logo_classes += ' ' + value;
				});			
			}

			leftSidebar += '<li class="nav-header">\
								<div class="' + logo_classes + '">\
									<img id="company_logo" alt="' + ( specs.logo.alt ? specs.logo.alt : 'image' ) + '" src="' + specs.logo.img + '">\
								</div>\
							</li>';
		}
		
		$.each(specs.categories, function( index, value ){
			
			var visibility = 'show';
			
			if(value.permission){
				visibility = __buildVisibility(value.permission);
			}

			if(visibility != "none" && visibility != "hide") {
			
				var category_classes = '';
				
				// create category classes
				if(value.classes && value.classes.length !== 0){
					$.each(value.classes, function( index, value ){
						category_classes += ' ' + value;
					});			
				}
				
				leftSidebar += '<li class="' + category_classes + ' ' + visibility + '" data-section-category-id="' + value.id + '">\
									<a href="' + ( value.href ? value.href : '#' ) + '">\
										<i class="fa ' + value.icon + '"></i>\
										<span class="nav-label">' + value.label + '</span>';
										
				if(value.sections && value.sections.length !== 0){
					
					leftSidebar += '<span class="fa arrow"></span>';		
				}
				
				leftSidebar += '</a>';

				if(value.sections && value.sections.length !== 0){
					
					leftSidebar += '<ul class="nav nav-second-level">';

					$.each(value.sections, function( index, value ){
						leftSidebar += '<li class="section-navigator" data-section-id="' + value.id + '">\
											<a href="' + value.href + '">' + value.label + '</a>\
										</li>';
					});

					leftSidebar += '</ul>';
				}
				
				leftSidebar += '</li>';
			}
			
		});
		
		leftSidebar += '</ul></div>';
		
		return leftSidebar;
		
	}