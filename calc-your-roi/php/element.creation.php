<?php
	
	function buildFormElement($element, $roiPreferences, $formType) {
		
		// Find Element Type and Build that Element Type
		switch($element['Type']) {
			
			// Case 0: Element is an Input
			case '0':
			return buildInput($element, 'input', $roiPreferences, $formType);
			break;
			
			// Case 1: Element is an Output
			case '1':
			return buildInput($element, 'output', $roiPreferences, $formType);
			break;
			
			// Case 2: Element is a Textarea
			case '2':
			return buildTextarea($element, $formType);
			break;
			
			// Case 3: Element is a Dropdown
			case '3':
			return buildDropdown($element, $roiPreferences, $formType);
			break;
			
			// Case 4: Element is a Select
			case '4':
			return buildSelect($element, 'single', 'vertical', $formType);
			break;

			case '5':
			return buildSelect($element, 'multiple', 'vertical', $formType);
			break;
			
			case '6':
			return buildSelect($element, 'single', 'horizontal', $formType);
			break;

			case '7':
			return buildSelect($element, 'multiple', 'horizontal', $formType);
			break;

			case '8':
			return buildListbox($element, $formType);
			break;

			case '9':
			return buildToggle($element, $formType);
			break;

			case '10':
			return buildRating($element, $formType);
			break;

			case '11':
			return buildSlider($element, 'single', $formType);
			break;

			case '12':
			return buildSlider($element, 'range', $formType);
			break;
			
			case '13':
			return buildHeader($element, $formType);
			break;
			
			default:
			return buildInput($element, 'input', $formType);
			break;			
		}

	}
	
	function buildLabel($inputSpecs, $type)
	{
		$label = '<div class="form-group">
					<label class="control-label ' . ($type=="output" ? 'input-context-menu' : '' ) . ' col-lg-7 col-md-7 col-sm-7">'.$inputSpecs['Title'].'</label>';
					
		return $label;
	}
	
	function buildPrepend($inputSpecs, $roiPreferences)
	{
		// If format is set as currency append user's currency to the input.
		$prepend = $inputSpecs['Format'] == 1 ? '<span class="input-group-addon prepend currency"><i class="fa fa-'.( $roiPreferences['currency']?$roiPreferences['currency']:'usd' ).'"></i></span>' : '';
		
		//If a prepend is defined add it unless input is already formatted as currency.
		$prepend = $prepend ? $prepend : ( $inputSpecs['prepend'] ? '<span class="input-group-addon prepend">'.$inputSpecs['prepend'].'</span>' : '' );
	
		return $prepend;
	}
	
	function buildAppend($inputSpecs)
	{
		//If format is set as percent prepend the percent sign.
		$append = $inputSpecs['Format'] == 2 ? '<span class="input-group-addon append percent">%</span>' : '';
		
		//If an append is defined add it unless input is already formatted as percent.
		$append = $append ? $append : ( $inputSpecs['append'] ? '<span class="input-group-addon append">'.$inputSpecs['append'].'</span>' : '' );
		
		return $append;
		
	}
	
	function buildUnit($inputSpecs)
	{
		
		$unit = '<span class="input-group-addon" style="padding:0; border: 1px solid #c5c6c7; border-left: 0;">
						<select id="'.$inputSpecs['ID'].'u" name="'.$inputSpecs['ID'].'u" class="chosen-selector form-control">';
						
		$choices = json_decode($inputSpecs['units'], true);
		
		$options = '';
		$totaloptions = 1;
		foreach( $choices as $choice )
		{
			$unit .= '<option '.($choice[2] == 1 ? 'selected ' : '' ).'value="'.$choice[1].'">'.$choice[0].'</option>';
			$totaloptions++;
		}

		$unit .= 		'</select>
					</span>';

		return $unit;
		
	}
	
	function buildPlaceholder($inputSpecs)
	{
		//Add a placeholder if one is specified.
		$placeholder = $inputSpecs['Placeholder'] ? ' placeholder="'.$inputSpecs['Placeholder'].'"' : '';

		return $placeholder;
	}
	
	function buildDefault($inputSpecs)
	{
		//Add a default value if one is specified.
		$default = $inputSpecs['default'] ? $inputSpecs['default'] : '';
		
		return $default;
	}
	
	function buildEquation($inputSpecs)
	{
		//Add equation if there is an equation defined.
		$eq = $inputSpecs['eq'] ? ' data-input-equation="'.$inputSpecs['eq'].'"' : '';

		return $eq;
	}
	
	function buildPrecision($inputSpecs)
	{
		//Add equation if there is an equation defined.
		$precision = $inputSpecs['precision'] ? ' data-input-precision="'.$inputSpecs['precision'].'"' : '';

		return $precision;
	}
	
	function buildTooltip($inputSpecs, $type)
	{
		if( ctype_space( $inputSpecs['Tip'] ) )
		{
			return '';
		} else {
			
			$tooltip = '<div class="infont col-md-1 input-adivce" style="margin-top: 5px;">';
			
			$tooltip .= '<div class="col-md-6 input-tooltip" style="padding: 0;">'. ( $inputSpecs['Tip'] ? '<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="'.$inputSpecs['Tip'].'"></i>' : '' ) . '</div>';
			
			$tooltip .= ( $type=="output" ) ? '<div class="col-md-6 input-calculation" style="padding: 0;"><i class="fa fa-calculator calculator-popup" href="#calculation-form" data-equation="'.$inputSpecs['eq'].'"></i></div>' : '';
			
			$tooltip .= '</div>';

			return $tooltip;
		}
	}
	
	function buildName($inputSpecs, $formType)
	{
		if($formType == 'discovery')
		{
			$inputName = $inputSpecs['link']?$inputSpecs['link']:'disc_'.$inputSpecs['ID'];
		} else {
			$inputName = $inputSpecs['ID'];
		}
		return $inputName;
	}
	
	function buildHeader($inputSpecs)
	{
		if( $inputSpecs['Format'] == 1 )
		{
			return '</form></div></div></div></div><div class="row border-bottom gray-bg dashboard-header" style="padding-top: 0;">
		<div class="col-md-12 col-sm-12 col-xs-12">	
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>'.$inputSpecs['Title'].'</h5>
					<div class="ibox-tools">
						<a class="collapse-link">
							<i class="fa fa-chevron-up"></i>
						</a>
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">
							<i class="fa fa-wrench"></i>
						</a>
						<a class="close-link">
							<i class="fa fa-times"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<form class="form-horizontal">';
		} else {
			return '<div class="form-group"><div class="col-md-12 col-lg-11 subsection-header"><h5>'.$inputSpecs['Title'].'</h5></div></div>';
		}
	}
	
	function buildInput($inputSpecs, $type, $roiPreferences, $formType)
	{
		$input = buildLabel($inputSpecs, $type);
		
		$input .= '<div class="col-lg-4 col-md-4 col-sm-4">';
		
		$inputPrepend = buildPrepend($inputSpecs, $roiPreferences);
		
		$inputAppend = buildAppend($inputSpecs);
		
		$placeholder = buildPlaceholder($inputSpecs);
		
		$default = buildDefault($inputSpecs);
		
		$eq = buildEquation($inputSpecs);
		
		$precision = buildPrecision($inputSpecs);
		
		$inputName = buildName($inputSpecs, $formType);
		
		if( $inputSpecs['units'] ) 
		{ $units = buildUnit($inputSpecs); } else
		{ $units = ''; }
		
		if( $inputPrepend || $inputAppend || $units )
		{
			$input .= '<div class="row">
							<div class="col-sm-12">
								<div class="input-group">'.
									$inputPrepend
									.'<input id="'.($formType=='discovery'?'disc_':'').$inputSpecs['ID'].'" name="'.$inputName.'" data-cell="A'.$inputSpecs['ID'].'" class="form-control"'.
									( $placeholder ? $placeholder : '' )
									.' type="text"'.( $inputSpecs['Format'] == 4 ? ' data-input-type="alphanumeric"' : '' )
									.( $type == 'output'?' disabled="disabled"' : '' )
									.( $type == 'output'? ' data-savings-type="'.$inputSpecs['savingsType'].'" data-stakeholder="'.$inputSpecs['stakeholder'].'"' : '' )
									.( $inputSpecs['growl'] ? ' data-growler="'.$inputSpecs['growl'].'"' : '' )
									.( $default ? ' value="'.$default.'"' : '' ).$eq.$precision.'>'.
									$inputAppend.$units
								.'</div>
							</div>
						</div>';
		} else {
			$input .= '<input id="'.($formType=='discovery'?'disc_':'').$inputSpecs['ID'].'" name="'.$inputName.'" data-cell="A'.$inputSpecs['ID'].'" class="form-control"'.
							( $placeholder ? $placeholder : '' ).
							'type="text"'.( $inputSpecs['Format'] == 4 ? ' data-input-type="alphanumeric"' : '' ).
							( $type == 'output' ? ' disabled="disabled"' : '' )
							.( $type == 'output'? ' data-savings-type="'.$inputSpecs['savingsType'].'" data-stakeholder="'.$inputSpecs['stakeholder'].'"' : '' )							
							.( $inputSpecs['growl'] ? ' data-growler="'.$inputSpecs['growl'].'"' : '' ).
							( $default ? 'value="'.$default.'"' : '' ).$eq.$precision.'>';
		}
		
		$input .= '</div>';
		
		$tooltip = buildTooltip($inputSpecs, $type);
					
		$input .= $tooltip;
					
		$input .=	'</div>';
				
		return $input;
	}
	
	function buildTextarea($inputSpecs, $formType)
	{
		$textarea = buildLabel($inputSpecs);
		
		$placeholder = buildPlaceholder($inputSpecs);

		$default = buildDefault($inputSpecs);
		
		$tooltip = buildTooltip($inputSpecs, 'input');
		
		$inputName = buildName($inputSpecs, $formType);
		
		return	$textarea.
					'<div class="col-lg-4 col-md-4 col-sm-4">
						<textarea id="A'. $inputName .'" name="'.$inputName.'"class="form-control" rows="'.$inputSpecs['choices'].'"'.( $placeholder ? $placeholder : '' ).
						( $inputSpecs['growl'] ? ' data-growler="'.$inputSpecs['growl'].'"' : '' ).'>'.$default.'</textarea>
					</div>'.
					$tooltip.
				'</div>';
	}
	
	function buildDropdown($inputSpecs, $roiPreferences, $formType)
	{
		$choices = json_decode($inputSpecs['choices'], true);
		
		$options = '';
		$totaloptions = 1;
		foreach( $choices as $choice )
		{
			$options .= '<option value="'.$totaloptions.'">'.$choice.'</option>';
			$totaloptions++;
		}
		
		$inputPrepend = buildPrepend($inputSpecs);
		
		$inputAppend = buildAppend($inputSpecs);
		
		$dropdown = buildLabel($inputSpecs);
		
		$tooltip = buildTooltip($inputSpecs);
		
		$inputName = buildName($inputSpecs, $formType);
		
		if( $inputPrepend || $inputAppend )
		{
			$dropdown .= '<div class="col-lg-4 col-md-4 col-sm-4">
							<div class="row">
								<div class="col-sm-12">
									<div class="input-group">'.
										$inputPrepend
										.'<select id="'.($formType=='discovery'?'disc_':'').$inputSpecs['ID'].'" name="'.$inputName.'" class="chosen-selector form-control">'.
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
									<select id="'.($formType=='discovery'?'disc_':'').$inputSpecs['ID'].'" name="'.$inputName.'" class="chosen-selector form-control">'.
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
		
		$select = buildLabel($inputSpecs);
		
		$tooltip = buildTooltip($inputSpecs);
		
		$inputName = buildName($inputSpecs, $formType);
		
		$choices = json_decode($inputSpecs['choices'], true);
			
		$options = '';
		$totalChoices = 0;
		if( $mode == 'single' )
		{	
			foreach( $choices as $choice )
			{
				$options .= '<div class="radio'.($orientation=='horizontal'?' radio-inline':'').'"><label><input class="radiobox i-checks style-2" type="radio" name="'
					.$inputName.'" '.($choice[1]==1?'checked="checked"':'').'><span>'.$choice[0].'</span></label></div>';
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
					.$inputName.'choice'.$totalchoices.'" '.($choice[1]==1?'checked="checked"':'').'><span>'.$choice.'</span></label></div>';
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
		$listbox = buildLabel($inputSpecs);
		
		$choices = json_decode($inputSpecs['choices'], true);
		
		$inputName = buildName($inputSpecs, $formType);
		
		$tooltip = buildTooltip($inputSpecs);
		
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
		$toggle = buildLabel($inputSpecs);
		
		$tooltip = buildTooltip($inputSpecs);
		
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
		$rating = buildLabel($inputSpecs);
		
		$inputName = buildName($inputSpecs, $formType);
		
		$tooltip = buildTooltip($inputSpecs);
		
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
	
	function buildSlider($inputSpecs, $type, $formType)
	{
		$slider = buildLabel($inputSpecs);
		
		$tooltip = buildTooltip($inputSpecs);
		
		$inputName = buildName($inputSpecs, $formType);
		
		$default = buildDefault($inputSpecs);
		
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
											'<input id="' . ( $formType == 'discovery' ? 'disc_' : '' ) . $inputSpecs['ID'] .'" 
											class="slider-input form-control" type="text" name="'.$inputName.'" rel="popover-hover">'.
											( $inputSpecs['Format'] == 2 ? '<span class="input-group-addon append">%</span>' : '' ).
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
	
?>	