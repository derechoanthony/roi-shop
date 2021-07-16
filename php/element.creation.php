<?php
	
	require_once( "calculator.actions.php" );
	
	function buildFormElement($element, $roiPreferences, $formType, $permission, $yr) {
		
		// Find Element Type and Build that Element Type
		switch($element['Type']) {
			
			// Case 0: Element is an Input
			case '0':
			return buildInput($element, 'input', $roiPreferences, $formType, $permission, $yr);
			break;
			
			// Case 1: Element is an Output
			case '1':
			return buildInput($element, 'output', $roiPreferences, $formType, $permission, $yr);
			break;
			
			// Case 2: Element is a Textarea
			case '2':
			return buildTextarea($element, $formType, $yr);
			break;
			
			// Case 3: Element is a Dropdown
			case '3':
			return buildDropdown($element, $roiPreferences, $formType, $yr);
			break;
			
			// Case 4: Element is a Select
			case '4':
			return buildSelect($element, 'single', 'vertical', $formType, $yr);
			break;

			case '5':
			return buildSelect($element, 'multiple', 'vertical', $formType, $yr);
			break;
			
			case '6':
			return buildSelect($element, 'single', 'horizontal', $formType, $yr);
			break;

			case '7':
			return buildSelect($element, 'multiple', 'horizontal', $formType, $yr);
			break;

			case '8':
			return buildListbox($element, $formType, $yr);
			break;

			case '9':
			return buildToggle($element, $formType, $yr);
			break;

			case '10':
			return buildRating($element, $formType, $yr);
			break;

			case '11':
			return buildSlider($element, 'single', $formType, $yr);
			break;

			case '12':
			return buildSlider($element, 'range', $formType, $yr);
			break;
			
			case '13':
			return buildHeader($element, $formType, $yr);
			break;
			
			case '14':
			return buildSavingsTable();
			break;
			
			case '15':
			return buildCustomTable($element);
			break;
			
			case '16':
			return buildTableRow($element, 'input');
			break;
			
			case '17':
			return buildTableRow($element, 'output');
			break;
			
			case '18':
			return buildTextRow($element, $yr);
			break;
			
			default:
			return buildInput($element, 'input', $roiPreferences, $formType, $permission, $yr);
			break;			
		}

	}
	

/***

	BUILD INPUT FUNCTION
	Created 11/9/2015
	
***/

	function buildInput($inputSpecs, $type, $roiPreferences, $formType, $permission) {
	
		// Set up calculator object in order to retrieve the ROI Specs
		$calculator = new CalculatorActions($db);	
		$roiSpecs = $calculator->retrieveRoiSpecs();
		
		$input = '';
		$annualInputs = ( $roiSpecs['retPeriod'] - 1 ) * $inputSpecs['annual'];
		
		for($yr=0; $yr<=$annualInputs; $yr++) {		
		
			// Set up the initial input and create the input label.
			$input .= buildLabel($inputSpecs, $type, $permission, $formType, $yr);
			
			// Input should be wrapped by the column spacer
			$input .= '<div class="col-lg-4 col-md-4 col-sm-4">';
			
			// Determine if the input has an appended value or a prepended value. If so it
			// needs to be set up as an input group. Otherwise it is just an input.
			$inputPrepend = buildPrepend($inputSpecs);
			$inputAppend = buildAppend($inputSpecs);
			
			// If units are defined set up a unit dropdown for the user.
			if( $inputSpecs['units'] ) {
				
				// Units exist so create dropdown
				$units = buildUnit( $inputSpecs, $yr );
			}
			
			if( $inputPrepend || $inputAppend || $units ) {
			
				$input .= 	'<div class="row">
								<div class="col-sm-12">
									<div class="input-group">';			
			}

			// Begin building the input
			$input .= '<input'.

							// Create the input ID and define the input as text (this only affects how the browser
							// displays content, text is shown where password would be hidden).
							' id="'.cellReference( $inputSpecs, $yr + 1 ).'" type="text"'.
							
							// Add the input class.
							' class="form-control'. ( $formType == 'integration' ? ' integration-value' : '' ) .'"'.
			
							// Create the name of the input. The name is used to store the value within the database.
							// The name will include the year prefix, i.e. "B" for Year 2, "C" for Year 3, etc. Year 1
							// does not include the prefix "A" because older values were stored without this prefix.
							// Because of this "A" cannot be included or the values will not load correctly.
							' name="'.buildName($inputSpecs, $formType, $yr).'"'.
							
							// The cell reference should always refer to the first cell created, therefore the
							// first year. This links every related input.
							' data-cell-reference="'.cellReference( $inputSpecs, '1' ).'"'.
							
							// Include the year of this input
							' data-yr="'.$yr.'"'.
							
							// Create format for the cell
							' data-format="'.buildFormat($inputSpecs).'"'.
							
							// Create the cell name
							' data-cell="'.cellReference( $inputSpecs, $yr + 1 ).'"'.
							
							// If the input type has been defined as alphanumeric then data type needs to be added
							// so text won't be overridden as 0.
							( $inputSpecs['Format'] == 4 ? ' data-input-type="alphanumeric"' : '' ).

							( $inputSpecs['annual_reduction'] ? ( ' annual-reduction-' . $inputSpecs['annual_reduction'] ) : '' ).
							
							// If there is a growler set up, then added it to the input. A growler will pop-up once
							// a user enters the input.
							( $inputSpecs['growl'] ? ' data-growler="'.$inputSpecs['growl'].'"' : '' ).
							
							// If the input has a default value, then add it in here.
							' value="'.buildDefault($inputSpecs).'"'.
							
							// If the input is specified as a cost then a data field must be added to indicate what year
							// the cost will be paid.
							( $inputSpecs['cost'] == 1 ? ' data-cost-yr="' . ( $yr + 1 ) . '"' : '' );
							
			if( $type == 'output' ) {
				
				// Add original equation and current equation. Original equation field is added so user can manually override
				// the current equation and reset it back if needed.
				$input .= 	' data-original-equation="'.$inputSpecs['formula'].'" disabled="disabled"'.
							' data-savings-type="'.$inputSpecs['savingsType'].'" data-stakeholder="'.$inputSpecs['stakeholder'].'"'.
							buildEquation($inputSpecs, $yr);
			}
			
			// End the input
			$input .= '>';
			
			if( $inputPrepend || $inputAppend || $units ) {
			
				// If the input included prepended, appended or unit values then they must be added in here.
				$input .= 		$inputAppend.
								$units.
									'</div>
								</div>
							</div>';	
			}
							
			$input .= '</div>';
				
			// Add the input's tooltip. This will result in a question mark appearing beside the input indicating to
			// the user there is a helpful hint related to the input.
			$input .= 	buildTooltip($inputSpecs, $type, $formType, $yr);
							
			$input .=	'</div>';
		
		}
		
		return $input;
	}
	
/***

	BUILD LABEL FUNCTION
	Created 11/9/2015
	
***/
	
	function buildLabel($inputSpecs, $type, $permission, $formType, $yr) {
		
		// Begin building the label
		$label = '<div class="form-group'.
			
			// If the input is on the integration form then add the integration element class to the input
			( $formType == 'integration' ? ' integration-element' : '' ). '" '.
			
			// If the input is on the integration form then add the sfdc data fields to the input
			( $formType == 'integration' ? 'data-sfdc-account="'.$inputSpecs['sfdc_account'].'" data-sfdc-opportunity="'.$inputSpecs['sfdc_opportunity'].'" data-sfdc-lead="'.$inputSpecs['sfdc_lead'].'"' : '' ) . '>';
		
			// If the input is on the integration form then a check box must be added to the beginning of the input
			if($formType == 'integration') {
				
				$label .= 	'<div class="col-sm-1">
								<div class="i-checks">
									<label>
										<input type="checkbox" class="integration-key sfdc" checked="" value="'. $inputSpecs['sfdc_element'] . '"> <i></i>
									</label>
								</div>
							</div>';
			}
		
			$label .=	'<label class="control-label '.
				
				// If the input is a calculation then add the context menu class to the label. This will allow
				// the user to select the output in the future and change the stakeholder and savings type in
				// the future.
				( $type=="output" ? 'input-context-menu ' : '' ).
				
				// Add the title to the lable and close out the label.
				' col-lg-7 col-md-7 col-sm-7">'. $inputSpecs['Title'] . ($inputSpecs['annual'] == 1 ? ' - Year '.($yr + 1) : '') . '</label>';

		return $label;
	}
	
/***

	BUILD PREPEND FUNCTION
	Created 11/9/2015
	
***/
	
	function buildPrepend($inputSpecs) {
		
		//If a prepend is defined add it.
		$prepend = $prepend ? $prepend : ( $inputSpecs['prepend'] ? '<span class="input-group-addon prepend">'.$inputSpecs['prepend'].'</span>' : '' );
	
		return $prepend;
	}
	
/***

	BUILD APPEND FUNCTION
	Created 11/9/2015
	
***/
	
	function buildAppend($inputSpecs) {
		
		//If an append is defined add it.
		$append = ( $inputSpecs['append'] ? '<span class="input-group-addon append">'.$inputSpecs['append'].'</span>' : '' );
		
		return $append;
	}
	
/***

	BUILD UNIT FUNCTION
	Created 11/9/2015
	
***/
	
	function buildUnit($inputSpecs, $yr) {
		
		switch($yr) {
			
			// Select the case that matches the year of the input being built. If the year is 1 then define
			// no prefix so that older values will still load correctly.
			case '1': $cellPrefix = ''; break;
			case '2': $cellPrefix = 'B'; break;
			case '3': $cellPrefix = 'C'; break;
			case '4': $cellPrefix = 'D'; break;
			case '5': $cellPrefix = 'E'; break;
			default: $cellPrefix = ''; break;
		};
		
		$unit = '<span class="input-group-addon" style="padding:0; border: 1px solid #c5c6c7; border-left: 0;">
						<select id="'. $cellPrefix . $inputSpecs['ID'] . 'u" 
							name="'. $inputSpecs['ID'] . 'u"
							data-cell="U'. $cellPrefix . $inputSpecs['ID'] .'" 
							class="chosen-selector form-control">';
						
		// Parse the available choices into an array.
		$choices = json_decode($inputSpecs['units'], true);
		
		$options = '';
		$totaloptions = 1;
		
		// Loop through each choice and place it into the unit dropdown box. If the second part of the array is 1
		// then it should be initially selected.
		
		foreach( $choices as $choice ) {
			$unit .= '<option '.($choice[2] == 1 ? 'selected ' : '' ).'value="'.$choice[1].'">'.$choice[0].'</option>';
			$totaloptions++;
		}

		$unit .= 		'</select>
					</span>';

		return $unit;
		
	}

/***

	BUILD CELL REFERENCE FUNCTION
	Created 11/9/2015
	
***/	
	
	function cellReference($inputSpecs, $yr) {
		
		// Build the reference for the cell
		switch($yr) {
			
			case '1': $prefix = 'A'; break;
			case '2': $prefix = 'B'; break;
			case '3': $prefix = 'C'; break;
			case '4': $prefix = 'D'; break;
			case '5': $prefix = 'E'; break;
			default: $prefix = 'A'; break;
		}
		
		return $prefix . $inputSpecs['ID'];
	}
	
/***

	BUILD NAME FUNCTION
	Created 11/9/2015
	
***/
	
	function buildName($inputSpecs, $formType, $yr) {
		
		if($formType == 'discovery' || $formType == 'integration')
		{
			$inputName = $inputSpecs['link']?$inputSpecs['link']:'disc_'.$inputSpecs['ID'];
		} else {
			$inputName = $inputSpecs['ID'].($inputSpecs['annual'] == 1 && $yr != 0 ? 'yr'.($yr + 1) : '');
		}
		return $inputName;
	}

/***

	BUILD FORMAT FUNCTION
	Created 11/9/2015
	
***/
	
	function buildFormat($inputSpecs) {
		
		// Create the format for the current cell.
		
		switch( $inputSpecs['Format'] ) {
				
			case '1': $inputFormat = '$0,0'; break;
			case '2': $inputFormat = '0,0'; break;
			case '4': $inputFormat = ''; break;
			default: $inputFormat = '0,0'; break;
		}

		if ( $inputSpecs['precision'] ) {
			
			// If precision exists then create a decimal place
			$inputFormat .= '.';
			
			for( $i=0; $i<$inputSpecs['precision']; $i++ ) {
				$inputFormat .= '0';
			}
		}
		
		if( $inputSpecs['Format'] == 2 ) {
			$inputFormat .= '%';
		}
		
		return $inputFormat;
	}	
	
/***

	BUILD DEFAULT FUNCTION
	Created 11/9/2015
	
***/	
	
	function buildDefault($inputSpecs) {
		
		//Add a default value if one is specified.
		$default = $inputSpecs['default'] ? $inputSpecs['default'] : '';
		
		return $default;
	}	

/***

	BUILD EQUATION FUNCTION
	Created 11/9/2015
	
***/	
	
	function buildEquation($inputSpecs, $yr) {
		
		//Add equation if there is an equation defined.
		$eq = $inputSpecs['formula'] ? ' data-formula="'.$inputSpecs['formula'].'"' : '';

		return $eq;
	}

/***

	BUILD TOOLTIP FUNCTION
	Created 11/9/2015
	
***/
	
	function buildTooltip($inputSpecs, $type, $formType, $yr) {
		
		
		// If input is being built for the integration form then the tooltip isn't needed.
		if( $formType == 'integration' ) {
			
			return '';
		} else {
			
			$tooltip = '<div class="infont col-md-1 input-advice" style="margin-top: 5px;">
							<div class="col-md-6 input-tooltip" style="padding: 0;">'.
								( $inputSpecs['Tip'] && !ctype_space( $inputSpecs['Tip'] ) ? '<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="'.$inputSpecs['Tip'].'"></i>' : '' ).
							'</div>'.
							( $type=="output" ? '<div class="col-md-6 input-calculation" style="padding: 0;"><i class="fa fa-calculator calculator-popup" href="#calculation-form" data-cell-identifier="'.buildCellName($inputSpecs, $yr).'"></i></div>' : '' ) .
						'</div>';

			return $tooltip;
		}
	}
	
	
	
	
	
	

	

	
	function buildPlaceholder($inputSpecs)
	{
		//Add a placeholder if one is specified.
		$placeholder = $inputSpecs['Placeholder'] ? ' data-placeholder-value="'.$inputSpecs['Placeholder'].'" value="'.$inputSpecs['Placeholder'].'"' : '';

		return $placeholder;
	}
	

	

	

	
	function buildPrecision($inputSpecs)
	{
		//Add precision if there is precision defined.
		$precision = ' data-input-precision="'. ( $inputSpecs['precision'] ? $inputSpecs['precision'] : '0' ) .'"';

		return $precision;
	}
	

	

	
	function buildCellName($inputSpecs, $yr) {
		
		switch($yr) {
			
			case '1': $cellPrefix = 'A'; break;
			case '2': $cellPrefix = 'B'; break;
			case '3'; $cellPrefix = 'C'; break;
			case '4': $cellPrefix = 'D'; break;
			case '5': $cellPrefix = 'E'; break;
			default: $cellPrefix = 'A'; break;
		}
		
		return $cellPrefix.$inputSpecs['ID'];
	}
	
	function buildHeader($inputSpecs, $formtype, $yr)
	{
		if( $inputSpecs['Format'] == 1 || $inputSpecs['Format'] == 2 )
		{
			return 			'</form>
						</div>
					</div>
					<div class="ibox float-e-margins">
						<div class="ibox-content" style="padding-top: 20px;">
							<form class="form-horizontal">
								<div class="form-group">
									<div id="'.cellReference( $inputSpecs, $yr + 1 ).'" class="col-md-12 col-lg-11 subsection-header"' . ( $inputSpecs['Format'] == 2 ? ' style="border:none;"' :'' ) . '>
										<h5>'.$inputSpecs['Title'].'</h5>
									</div>
								</div>';
		} else {
			return '<div class="form-group">
						<div id="'.cellReference( $inputSpecs, $yr + 1 ).'" class="col-md-12 col-lg-11 subsection-header"' . ( $inputSpecs['Format'] == 3 ? ' style="border:none;"' :'' ) . '>
							<h5>'.$inputSpecs['Title'].'</h5>
						</div>
					</div>';
		}
	}
	

	
	function buildTextarea($inputSpecs, $formType) {
		
		$textarea = buildLabel($inputSpecs, 'input', '0', $formType, '1');
		
		$placeholder = buildPlaceholder($inputSpecs);

		$default = buildDefault($inputSpecs);
		
		$tooltip = buildTooltip($inputSpecs, 'input', $formType);
		
		$inputName = buildName($inputSpecs, $formType);
		
		return	$textarea.
					'<div class="col-lg-4 col-md-4 col-sm-4">
						<textarea id="A'. $inputName .'" name="'.$inputName.'"class="form-control' . ( $formType == 'integration' ? ' integration-value' : '' ) . '" rows="'.$inputSpecs['choices'].'"'.( $placeholder ? $placeholder : '' ).
						( $inputSpecs['Format'] == 4 ? ' data-input-type="alphanumeric"' : '' ).( $inputSpecs['growl'] ? ' data-growler="'.$inputSpecs['growl'].'"' : '' ).'>'.$default.'</textarea>
					</div>'.
					$tooltip.
				'</div>';
	}
	
	function buildDropdown($inputSpecs, $roiPreferences, $formType, $yr)
	{
		$calculator = new CalculatorActions($db);
		$choices = $calculator->retrieveEntryChoices($inputSpecs['ID']);
		
		$options = '';
		$totaloptions = 1;
		foreach( $choices as $choice ) {
			
			$options .= '<option value="'.$totaloptions.'"'. ( $choice['show_map'] ? ' data-show-map="'.$choice['show_map'].'"' : '' ) .'>'.$choice['value'].'</option>';
			$totaloptions++;
		}
		
		if( $options == '' ) { 
			$options = '<option value="0">No Options</option>';
		}
		
		$inputPrepend = buildPrepend($inputSpecs, $roiPreferences);
		
		$inputAppend = buildAppend($inputSpecs);
		
		$dropdown = buildLabel($inputSpecs, 'input', '0', $formType, '1');
		
		$tooltip = buildTooltip($inputSpecs, $formType);
		
		$inputName = buildName($inputSpecs, $formType);
		
		$inputCell = ( $formType == 'discovery' ? '' : ' data-cell="A'. $inputSpecs['ID'] .'"' );
		
		if( $inputPrepend || $inputAppend )
		{
			$dropdown .= '<div class="col-lg-4 col-md-4 col-sm-4">
							<div class="row">
								<div class="col-sm-12">
									<div class="input-group">'.
										$inputPrepend
										.'<select id="'.cellReference( $inputSpecs, $yr ).'" name="'. $inputName . '"' . $inputCell . ' data-placeholder="Please make a selection below" class="chosen-selector form-control">'.
											$options
										.'</select>'.
										$inputAppend
									.'</div>
								</div>
							</div>
						</div>'.
						$tooltip.
					'</div>';
		} else {
			$dropdown .= '<div class="col-lg-4 col-md-4 col-sm-4">
							<div class="row">
								<div class="col-sm-12">
									<select id="'.cellReference( $inputSpecs, $yr ).'" name="'. $inputName . '"' . $inputCell . ' data-placeholder="Please make a selection below" class="chosen-selector form-control">'.
										$options
									.'</select>
								</div>
							</div>
						</div>'.
						$tooltip.
					'</div>';
		}
		
		return 	$dropdown;
	}
	
	function buildSelect($inputSpecs, $mode, $orientation, $formType)
	{	
		
		$select = buildLabel($inputSpecs, 'input', '0', $formType, '1');
		
		$tooltip = buildTooltip($inputSpecs, $formType);
		
		$inputName = buildName($inputSpecs, $formType);
		
		$calculator = new CalculatorActions($db);
		$choices = $calculator->retrieveEntryChoices();
			
		$options = '';
		$totalChoices = 0;
		if( $mode == 'single' )
		{	
			foreach( $choices as $choice )
			{
				$options .= '<div class="radio'.($orientation=='horizontal'?' radio-inline':'').'"><label><input class="radiobox i-checks style-2" type="radio" name="'
					.$inputName.'" '.($choice[1]==1?'checked="checked"':'').'><span>'.$choice['value'].'</span></label></div>';
			}
			
			return $select.
						'<div class="col-lg-4 col-md-4 col-sm-4">'.
							$options
						.'</div>'.
						$tooltip
					.'</div>';
		} else if ( $mode == 'multiple' ) {
			$totalchoices = 0;
			foreach( $choices as $choice )
			{
				$totalchoices += 1;
				$options .= '<div class="checkbox i-checks'.($orientation=='horizontal'?'-inline':'').'">
								<label>
									<input class="checkbox style-2" type="checkbox" name="'
					.$inputName.'choice'.$totalchoices.'" '.($choice[1]==1?'checked="checked"':'').'><span>'.$choice['value'].'</span></label></div>';
			}
			
			return $select.
						'<div class="col-lg-4 col-md-4 col-sm-4">'.
							$options
						.'</div>'.
						$tooltip
					.'</div>';
		}
		
	}
	
	function buildListbox($inputSpecs, $formType)
	{
		$listbox = buildLabel($inputSpecs, 'input', '0', $formType);
		
		$choices = json_decode($inputSpecs['choices'], true);
		
		$inputName = buildName($inputSpecs, $formType);
		
		$tooltip = buildTooltip($inputSpecs, $formType);
		
		$options = '';
		foreach( $choices as $choice )
		{
			$options .= '<option'.($choice[1]==1?' selected="selected"':'').'>'.$choice[0].'</option>';
		}
		
		return 	$listbox.
					'<div class="col-lg-4 col-md-4 col-sm-4">
						<select id="'.($formType=='discovery'?'disc_':'').$inputSpecs['ID'].'" name="'.$inputName.'" id="multiselect1" class="form-control custom-scroll" multiple="multiple">'.
							$options
						.'</select>
					</div>'.
					$tooltip
				.'</div>';		
	}
	
	function buildToggle($inputSpecs, $formType)
	{
		$toggle = buildLabel($inputSpecs, 'input', '0', $formType);
		
		$tooltip = buildTooltip($inputSpecs, $formType);
		
		$inputName = buildName($inputSpecs, $formType);
		
		$choices = json_decode($inputSpecs['choices'], true);
		
		return $toggle.
						'<div style="float:left;" class="col-lg-4 col-md-4 col-sm-4">
							<input class="switcher" type="checkbox" name="'.$inputName.'" checked data-size="small" data-on-text="'.($choices[0]?$choices[0]:'YES').'" data-off-text="'.($choices[1]?$choices[1]:'NO').'">
						</div>'.
						$tooltip
					.'</div>';
	}
	
	function buildRating($inputSpecs, $formType)
	{
		$rating = buildLabel($inputSpecs, 'input', '0', $formType);
		
		$inputName = buildName($inputSpecs, $formType);
		
		$tooltip = buildTooltip($inputSpecs, $formType);
		
		$choices = json_decode($inputSpecs['choices'], true);
		
		$options = '';
		for( $i=0; $i<$choices[0]; $i++ )
		{
			$options .= '<input id="stars-rating-'.($choices[0]-$i).'" type="radio" name="'.$inputName.'"><label for="stars-rating-'.($choices[0]-$i).'"><i class="fa fa-'.$choices[1].'"></i></label>';
		}

		return $rating.
					'<div class="col-lg-4 col-md-4 col-sm-4 smart-form">
						<div class="rating">'.
							$options
						.'</div>
					</div>'.
					$tooltip
				.'</div>';
	}
	
	function buildSlider($inputSpecs, $type, $formType, $yr)
	{
		$slider = buildLabel($inputSpecs, 'input', '0', $formType);
		
		$tooltip = buildTooltip($inputSpecs, $formType);
		
		$inputName = buildName($inputSpecs, $formType, $yr);
		
		$default = buildDefault($inputSpecs);
		
		$inputCell = buildCellName($inputSpecs, $yr);
		
		switch($yr) {
			
			case '1':
				$cellPrefix = 'A';
				break;
					
			case '2':
				$cellPrefix = 'B';
				break;
					
			case '3':
				$cellPrefix = 'C';
				break;

			case '4':
				$cellPrefix = 'D';
				break;

			case '5':
				$cellPrefix = 'E';
				break;
					
			default:
				$cellPrefix = 'A';
				break;
		};
		
		if( $type == 'single' )
		{
			return $slider.
							'<div class="col-lg-4 col-md-4 col-sm-4 element-slider">
								<div class="row">
									<div class="col-lg-6 input-slider">
										<div id="drag-fixed" class="slider slider_red"></div>
									</div>
									<div class="col-lg-6">
										<div class="input-group">'.
											( $inputSpecs['Format'] == 1 ? '<span class="input-group-addon prepend">$</span>' : '' ).
											'<input id="' . ( $formType == 'discovery' ? 'disc_' : '' ) . $cellPrefix.$inputSpecs['ID'] .'" 
											class="slider-input form-control' . ( $formType == 'integration' ? ' integration-value' : '' ) . '" '. ( $inputSpecs['Format'] == 2 ? 'data-format="0,0[.]00%" ' : '' ) . ' data-cell="' . $inputCell .'" type="text" name="'.$inputName.'" rel="popover-hover">'.
										'</div>
									</div>
								</div>
							</div>'.
						$tooltip.
					'</div>';
		} else if( $type == 'range' ) {
			return $slider.
							'<div class="row m-b-lg">
                                <div class="col-lg-12">
                                    <div id="drag-fixed" class="slider_red"></div>
                                </div>
							</div>'.						
						/*'<div class="col-lg-4 col-md-4 col-sm-4 element-slider">	
							<input type="text" class="slider percent slider-primary" id="'.($formType=='discovery'?'disc_':'').$inputSpecs['ID'].'" name="'.$inputName.'" value="" 
								data-slider-min="'.($inputSpecs['min']?$inputSpecs['min']:0).'"
								data-slider-max="'.($inputSpecs['max']?$inputSpecs['max']:100).'" 
								data-slider-value="['.($inputSpecs['min']?$inputSpecs['min']:0).','.($inputSpecs['max']?$inputSpecs['max']:100).']"
								data-slider-handle="round">
						</div>'.*/
						$tooltip.
					'</div>';
		}			
	}
	
	function buildSavingsTable() {
		
		$calculator = new CalculatorActions($db);
		
		$savingsShell = '<div class="ibox-content">
							<div class="table-responsive" style="border:2px solid #ddd;">
								<table id="summary-table" class="table table-hover" style="margin-bottom:0;">
									<thead>
										<tr>
											<th></th>';
													
		$roiSpecs = $calculator->retrieveRoiSpecs();
			
		for( $i=0; $i<$roiSpecs['retPeriod']; $i++ ){
				
			$savingsShell .=				'<th>Year '. ($i+1) .'</th>';
		}

		$savingsShell .=					'<th>Total</th>
										</tr>
									</thead>
									<tbody>';

		$roiSections = $calculator->retrieveRoiSections();
		$sectionsExcluded = $calculator->retrieveExcludedSections();
			
		foreach( $roiSections as $section ){
				
			$include = 'true';
			
			foreach($sectionsExcluded as $exclude) {
				
				if($exclude['entity_id'] === $section['ID']) {
					$include = 'false';
				}
			}			
			
			if($include == 'true') {
			
				// Add a table row if the section has a formula defined
				if( $section['formula'] ){
							
					$savingsShell .= 		'<tr class="value-holder" data-section-name="'. $section['Title'] .'">
												<th class="section-navigation" data-section-id="'. $section['ID'] .'">
													<a class="section-navigator smooth-scroll table-scroll" data-section-type="section" href="#section'. $section['ID'] .'">'. $section['Title'] .'</a>
												</th>';

					for( $i=0; $i<$roiSpecs['retPeriod']; $i++ ){	
						
						$savingsShell .=		'<td class="section-total" data-section-id="'. $section['ID'] .'" data-format="($0,0)" data-formula="SECTIONTOTAL('. $section['formula'] .', '. ($i+1) .', '. $section['ID'] .')"></td>';
					}

						$savingsShell .=		'<td class="section-total" data-section-id="'. $section['ID'] .'" data-format="($0,0)" data-formula="SECTIONTOTAL('. $section['formula'] .', \'total\', '. $section['ID'] .')"> 0</td>
											</tr>';
						
				}
			}
		}
				
			$savingsShell .=			'<tr class="value-holder" data-section-name="cost">
											<th class="cost-row">Cost</th>';
										
			for( $i=0; $i<$roiSpecs['retPeriod']; $i++ ){
					
				$savingsShell .=			'<td class="cost" data-format="($0,0)" data-formula="ANNUALCOST('. ($i+1) .')"></td>';
			}

			$savingsShell .=				'<td class="cost" data-format="($0,0)" data-formula="ANNUALCOST(\'total\')"></td>
										</tr>
										<tr class="value-holder" data-section-name="total">
											<th class="annual-total-row">Total</td>';

			for( $i=0; $i<$roiSpecs['retPeriod']; $i++ ){	

				$savingsShell .=			'<td class="section-total" data-format="($0,0)" data-formula="';
											
				$sectiontotals = 1;
				foreach( $roiSections as $section ){
						
					if( $section['formula'] ){
							
						$savingsShell .=	( $sectiontotals == 1 ? '' : '+' ). 'SECTIONTOTAL('. $section['formula'] .', '. ($i+1) .', '. $section['ID'] .', \'true\')';
					}
						
					// Add one to the section total
					$sectiontotals++;
				}
				
				$savingsShell .=	'+ ANNUALCOST('. ($i+1) .')"></td>';
			}
				
			$savingsShell .=	'<td class="roi-summary-total section-total" data-format="($0,0)" data-formula="GRANDTOTAL(\'true\')"></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>';

			return $savingsShell;
	}
	
	function buildCustomTable($inputSpecs){
		
		$table = $inputSpecs['Title'];

		return $table;		
	}
	
	function buildTableRow($inputSpecs, $type) {
		
		switch( $inputSpecs['Format'] ) {
				
			case '1': $inputFormat = ' data-format="$0,0[.]zz00"';
			break;
				
			case '2': $inputFormat = ' data-format="0,0[.]00%"';
			break;
				
			case '4': $inputFormat = '';
			break;
				
			default: $inputFormat = ' data-format="0,0[.]00"';
			break;
		}
		
		$placeholder = buildPlaceholder($inputSpecs);
		
		$default = buildDefault($inputSpecs);
		
		$inputCost = ( $inputSpecs['cost'] == 1 ? ' data-cost-yr="' . ( $yr + 1 ) . '"' : '' );

		if($type='input') {
			
			$tableRow = 	'<input id="'. $inputSpecs['ID'] .'" name="'. $inputSpecs['ID'] .'"'. $inputFormat . ' class="form-control"'.( $placeholder ? $placeholder : '' )
							.' type="text"'.( $inputSpecs['Format'] == 4 ? ' data-input-type="alphanumeric"' : '' )
							.( $type == 'output' ? ' data-original-equation="'.$inputSpecs['formula'].'" disabled="disabled"' : '' )
							.( $type == 'output'? ' data-savings-type="'.$inputSpecs['savingsType'].'" data-stakeholder="'.$inputSpecs['stakeholder'].'"' : '' )
							.( $inputSpecs['growl'] ? ' data-growler="'.$inputSpecs['growl'].'"' : '' ).$inputCost
							.( $default ? ' value="'.$default.'"' : '' ).' data-table-with="'. $inputSpecs['part_of'] .'" data-row-name="'. $inputSpecs['Title'] .'">';	
			
		} else {
			
			$formulas = json_decode($inputSpecs['formula']);
			
			foreach( $formulas as $formula ) {
				
				$tableRow = 	'<th><input id="'. $inputSpecs['ID'] .'" name="'. $inputSpecs['ID'] .'"'. $inputFormat . ' class="form-control"'.( $placeholder ? $placeholder : '' )
								.' type="text"'.( $inputSpecs['Format'] == 4 ? ' data-input-type="alphanumeric"' : '' )
								.' data-original-equation="'.$formula.'" disabled="disabled"'
								.' data-savings-type="'.$inputSpecs['savingsType'].'" data-stakeholder="'.$inputSpecs['stakeholder'].'"'
								.( $inputSpecs['growl'] ? ' data-growler="'.$inputSpecs['growl'].'"' : '' ).$inputCost
								.( $default ? ' value="'.$default.'"' : '' ).' data-formula="'.$formula.'" data-table-with="'. $inputSpecs['part_of'] .'" data-row-name="'. $inputSpecs['Title'] .'></th>';
			}

		}
		
		return $tableRow;
	}
	
	function buildTextRow($inputSpecs, $yr) {
		
		$textRow = '<div class="form-group"><label id="'.cellReference( $inputSpecs, $yr ).'" class="control-label col-lg-12">' . $inputSpecs['Title'] . '</label></div>';
		
		return $textRow;
	}
	
?>	