<?php
	$toggleKey = array_keys(array_column($_SESSION['toggleOptions'], 'element_id'),$id);
	if($toggleKey){
		$toggle = $_SESSION['toggleOptions'][$toggleKey[0]];
	}
	
	$inputKey = array_keys(array_column($_SESSION['inputValues'], 'entryid'),$inputSpecs['calculation_element_id']);
	
	$inputValue = '';
	if($inputKey){
		$inputValue = $_SESSION['inputValues'][$inputKey[0]];
	}
	
?>

<div class="form-horizontal">
	<div class="form-group">
		<div class="col-lg-12">
			<button class="btn btn-block btn-primary btn-toggle <?= ( $inputValue && $inputValue['value'] == $toggle['off_value'] ? $toggle['off_class'] : $toggle['on_class'] ) ?>" type="button"
				data-on-value="<?= $toggle['on_value'] ?>"
				data-off-value="<?= $toggle['off_value'] ?>"
				data-on-text="<?= $toggle['on_text'] ?>"
				data-off-text="<?= $toggle['off_text'] ?>"
				data-on-class="<?= $toggle['on_class'] ?>"
				data-off-class="<?= $toggle['off_class'] ?>"
				data-cell-reference="A<?= $inputSpecs['calculation_element_id'] ?>">
				&nbsp;
			</button>
			<input 
				type="hidden" 
				name="<?= $inputSpecs['calculation_element_id'] ?>" 
				value="<?= ( $inputValue && $inputValue['value'] == $toggle['off_value'] ? $toggle['off_value'] : $toggle['on_value'] ) ?>" 
				data-cell="A<?= $id ?>" 
				data-cell-reference="A<?= $inputSpecs['calculation_element_id'] ?>"/>
		</div>
	</div>
</div>