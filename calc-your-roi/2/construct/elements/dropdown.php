<?php
	
	$dropdownKey = array_keys(array_column($_SESSION['inputValues'], 'entryid'),$inputSpecs['calculation_element_id']);
	
	if($dropdownKey){
		$dropdownValue = $_SESSION['inputValues'][$dropdownKey[0]];
	}
	
	$dropdownKeys = array_keys(array_column($_SESSION['dropdownChoices'], 'calculation_element_id'),$inputSpecs['calculation_element_id']);

?>

<div class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-lg-<?= $inputSpecs['label_large_columns'] ?> col-md-<?= $inputSpecs['label_medium_columns'] ?> col-sm-<?= $inputSpecs['label_small_columns'] ?> col-xs-<?= $inputSpecs['label_x_small_columns'] ?>"><?= $inputSpecs['label'] ?></label>
		<div class="col-lg-<?= 12 - $inputSpecs['label_large_columns'] ?> col-md-<?= 12 - $inputSpecs['label_medium_columns'] ?> col-sm-<?= 12 - $inputSpecs['label_small_columns'] ?> col-xs-<?= 12 - $inputSpecs['label_x_small_columns'] ?>">
			<div class="row">
				<div class="col-sm-12">
					<select 
							name="<?= $inputSpecs['calculation_element_id'] ?>"
							data-cell="A<?= $inputSpecs['calculation_element_id'] ?>" 
							data-placeholder="Please make a selection below"
							class="chosen-selector form-control">
<?php
	
	$totalOptions = 1;
	foreach($dropdownKeys as $key){

		$choice = $_SESSION['dropdownChoices'][$key];
?>
						<option <?= $choice['show_map'] ? ' data-show-map="'.$choice['show_map'].'"' : '' ?> value="<?= $choice['dropdown_value'] ?>" <?= isset($dropdownValue) && $dropdownValue['value'] == $choice['dropdown_value'] ? ' selected="selected"' : '' ?>><?= $choice['dropdown_text'] ?></option>
<?php
		$totalOptions++;
	}
?>
					</select>
				</div>
			</div>
		</div>
	</div>
</div>