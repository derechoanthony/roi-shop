<?php
	
	$sliderKey = array_keys(array_column($_SESSION['inputValues'], 'entryid'),$inputSpecs['calculation_element_id']);
	
	if($sliderKey){
		$sliderValue = $_SESSION['inputValues'][$sliderKey[0]];
	}
	
	$sliderKey = array_keys(array_column($_SESSION['sliderElements'], 'calculation_element_id'),$inputSpecs['calculation_element_id']);

	if($sliderKey){
		$sliderOptions = $_SESSION['sliderElements'][$sliderKey[0]];
	}	

	if( isset($sliderOptions) && $sliderOptions['type'] == 1){
?>

<div class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-lg-12"><?= $inputSpecs['label'] ?>
			<span class="pull-right">
				<span 
					class="slider-input"
					name="<?= $inputSpecs['calculation_element_id'] ?>"
					data-format="<?= $inputSpecs['format'] ?>"
					data-cell="A<?= $inputSpecs['calculation_element_id'] ?>"
				></span>
				<?= $inputSpecs['append'] ? $inputSpecs['append'] : '' ?>
			</span>
		</label>
		<div class="input-slider col-lg-12">
			<div id="drag-fixed" 
				class="slider" 
				data-min="<?= ( isset($sliderOptions) && $sliderOptions['min'] ) ? $sliderOptions['min'] : '0' ?>"
				data-max="<?= ( isset($sliderOptions) && $sliderOptions['max'] ) ? $sliderOptions['max'] : '100' ?>"
				<?= ( isset($sliderValue) ? 'data-start="'. $sliderValue['value'] .'"' : ( $inputSpecs['placeholder'] ? 'data-start="'. $inputSpecs['placeholder'] .'"' : '' ) ) ?>
				data-step="<?= ( isset($sliderOptions) && $sliderOptions['step'] ) ? $sliderOptions['step'] : '1' ?>"
				data-cell-reference="A<?= $inputSpecs['calculation_element_id'] ?>">
			</div>
		</div>
	</div>
</div>

<?php
	} else {
?>
<div class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-lg-<?= $inputSpecs['label_large_columns'] ?> col-md-<?= $inputSpecs['label_medium_columns'] ?> col-sm-<?= $inputSpecs['label_small_columns'] ?> col-xs-<?= $inputSpecs['label_x_small_columns'] ?>"><?= $inputSpecs['label'] ?></label>
		<div class="col-lg-<?= 12 - $inputSpecs['label_large_columns'] ?> col-md-<?= 12 - $inputSpecs['label_medium_columns'] ?> col-sm-<?= 12 - $inputSpecs['label_small_columns'] ?> col-xs-<?= 12 - $inputSpecs['label_x_small_columns'] ?>">
			<div class="row">
				<div class="col-lg-6 input-slider">
					<div id="drag-fixed" 
						class="slider" 
						data-min="<?= ( isset($sliderOptions) && $sliderOptions['min'] ) ? $sliderOptions['min'] : '0' ?>"
						data-max="<?= ( isset($sliderOptions) && $sliderOptions['max'] ) ? $sliderOptions['max'] : '100' ?>"
						<?= ( isset($sliderValue) ? 'data-start="'. $sliderValue['value'] .'"' : ( $inputSpecs['placeholder'] ? 'data-start="'. $inputSpecs['placeholder'] .'"' : '' ) ) ?>
						data-step="<?= ( isset($sliderOptions) && $sliderOptions['step'] ) ? $sliderOptions['step'] : '1' ?>">
					</div>
				</div>
				<div class="col-lg-6">
<?php
	
	if($inputSpecs['helper'] && !ctype_space( $inputSpecs['helper'] )) {
?>
					<div class="input-group">
<?php
	}
?>
					<input id="A<?= $inputSpecs['calculation_element_id'] ?>" 
							type="text" 
							class="slider-input form-control<?= $inputSpecs['helper'] ? ' input-addon' : '' ?>" 
							name="<?= $inputSpecs['calculation_element_id'] ?>"
							data-cell-reference="A<?= $inputSpecs['calculation_element_id'] ?>"
							data-yr="0"
							data-format="<?= $inputSpecs['format'] ?>"
							data-cell="A<?= $inputSpecs['calculation_element_id'] ?>"/>
<?php	
	if($inputSpecs['helper'] && !ctype_space( $inputSpecs['helper'] )) {
?>
						<span class="input-group-addon helper right">
							<i class="fa fa-question-circle tooltipstered" data-toggle="tooltip" data-placement="right" title="<?= $inputSpecs['helper'] ?>"></i>
						</span>
					</div>
<?php
	}
?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
	}
?>