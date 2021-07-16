<?php
	
	$inputKey = array_keys(array_column($_SESSION['inputValues'], 'entryid'),$inputSpecs['calculation_element_id']);
	
	$inputValue = '';
	if($inputKey){
		$inputValue = $_SESSION['inputValues'][$inputKey[0]];
	}

?>

	<div class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-lg-<?= $inputSpecs['label_large_columns'] ?> col-md-<?= $inputSpecs['label_medium_columns'] ?> col-sm-<?= $inputSpecs['label_small_columns'] ?> col-xs-<?= $inputSpecs['label_x_small_columns'] ?>"><?= $inputSpecs['label'] ?></label>
		<div class="col-lg-<?= 12 - $inputSpecs['label_large_columns'] ?> col-md-<?= 12 - $inputSpecs['label_medium_columns'] ?> col-sm-<?= 12 - $inputSpecs['label_small_columns'] ?> col-xs-<?= 12 - $inputSpecs['label_x_small_columns'] ?>">
<?php
	
	if($inputSpecs['helper'] && !ctype_space($inputSpecs['helper']) || $inputSpecs['append']) {
?>
			<div class="input-group">
<?php
	}
?>
			<textarea rows="<?= $inputSpecs['format'] ?>" id="A<?= $inputSpecs['calculation_element_id'] ?>" 
					type="text" 
					class="form-control<?= $inputSpecs['helper'] ? ' input-addon' : '' ?>" 
					<?= $inputSpecs['format'] == 1 ? 'style="resize: none;"' : '' ?>
					name="<?= $inputSpecs['calculation_element_id'] ?>"
					data-cell-reference="A<?= $inputSpecs['calculation_element_id'] ?>"
					data-cell="A<?= $inputSpecs['calculation_element_id'] ?>"
				><?= ( $inputValue ? $inputValue['value'] : ( $inputSpecs['placeholder'] ? $inputSpecs['placeholder'] : '' ) ) ?></textarea>

<?php	
	if($inputSpecs['helper'] && !ctype_space( $inputSpecs['helper'] )) {
?>
				<span class="input-group-addon right helper">
					<i class="fa fa-question-circle tooltipstered" data-toggle="tooltip" data-placement="right" title="<?= $inputSpecs['helper'] ?>"></i>
				</span>
<?php 
	}
	
	if($inputSpecs['append']){
?>				
				<span class="input-group-addon right append"><?= $inputSpecs['append'] ?></span>
<?php
	}
	
	if($inputSpecs['helper'] && !ctype_space($inputSpecs['helper']) || $inputSpecs['append']) {
?>
			</div>
<?php
	}
?>

		</div>
	</div>
</div>