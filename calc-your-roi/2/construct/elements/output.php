<?php
	
	$outputKey = array_keys(array_column($_SESSION['allEquations'], 'function_id'),$inputSpecs['formula']);
	if($outputKey){
		$equation = $_SESSION['allEquations'][$outputKey[0]];
	}
	
	$overriddenKey = array_keys(array_column($_SESSION['overriddenValues'], 'entryid'),'A'.$inputSpecs['calculation_element_id']);
	if($overriddenKey){
		$overriddenValue = $_SESSION['overriddenValues'][$overriddenKey[0]];
	}
?>

<div class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-lg-<?= $inputSpecs['label_large_columns'] ?> col-md-<?= $inputSpecs['label_medium_columns'] ?> col-sm-<?= $inputSpecs['label_small_columns'] ?> col-xs-<?= $inputSpecs['label_x_small_columns'] ?>"><?= $inputSpecs['label'] ?></label>
		<div class="col-lg-<?= 12 - $inputSpecs['label_large_columns'] ?> col-md-<?= 12 - $inputSpecs['label_medium_columns'] ?> col-sm-<?= 12 - $inputSpecs['label_small_columns'] ?> col-xs-<?= 12 - $inputSpecs['label_x_small_columns'] ?>">
<?php
	
	if($inputSpecs['helper'] && !ctype_space($inputSpecs['helper']) || $inputSpecs['append'] || $inputSpecs['formula_helper']) {
?>
			<div class="input-group">
<?php
	}
?>			
			<input id="A<?= $inputSpecs['calculation_element_id'] ?>" 
					<?= isset($overriddenValue) ? 'style="color:rgb(165,42,42)"' : '' ?>
					type="text" 
					disabled="disabled"
					class="form-control<?= $inputSpecs['helper'] || $inputSpecs['formula_helper'] ? ' input-addon' : '' ?>" 
					name="<?= $inputSpecs['calculation_element_id'] ?>"
					data-cell-reference="A<?= $inputSpecs['calculation_element_id'] ?>"
					data-yr="0"
					data-format="<?= $inputSpecs['format'] ?>"
					data-formula="<?= isset($overriddenValue) ? $overriddenValue['value'] : ( isset($equation) ? $equation['function_formula'] : '' )?>"
					data-original-equation="<?= isset($equation) ? $equation['function_formula'] : '' ?>"
					data-cell="A<?= $inputSpecs['calculation_element_id'] ?>"/>
<?php	
	if($inputSpecs['helper'] && !ctype_space($inputSpecs['helper']) || $inputSpecs['formula_helper']) {
?>
				<span class="input-group-addon right output helper">
<?php
	}
	
	if($inputSpecs['helper'] && !ctype_space($inputSpecs['helper'])){
?>
					<i class="fa fa-question-circle tooltipstered" <?= $inputSpecs['formula_helper'] ? 'style="margin-right: 7px;"' : '' ?>data-toggle="tooltip" data-placement="right" title="<?= $inputSpecs['helper'] ?>"></i>
<?php
	}
	
	if($inputSpecs['formula_helper']){
?>
					<i class="fa fa-calculator tooltipstered calculator-popup" data-cell-identifier="A<?= $inputSpecs['calculation_element_id'] ?>" data-toggle="tooltip" data-placement="right" title="Click here to view the calculation breakdown"></i>
<?php
	}
	
	if($inputSpecs['helper'] && !ctype_space($inputSpecs['helper']) || $inputSpecs['formula_helper']) {
?>
				</span>
<?php 
	}
	
	if($inputSpecs['append']){
?>				
				<span class="input-group-addon right append"><?= $inputSpecs['append'] ?></span>
<?php
	}
	
	if($inputSpecs['helper'] && !ctype_space($inputSpecs['helper']) || $inputSpecs['append'] || $inputSpecs['formula_helper']) {
?>
			</div>
<?php
	}
?>
		</div>
	</div>
</div>