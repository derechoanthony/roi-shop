<?php
	//print_r($_SESSION['customTableRows']);
	//print_r($_SESSION['customTableCells']);
	//print_r($_SESSION['customRoiInputs']);
	$tableKey = array_keys(array_column($_SESSION['allTables'], 'table_id'),$id);
	$table = $_SESSION['allTables'][$tableKey[0]];

?>

<?php
	
	if(!$table['style_bordered']) {
?>
<div class="table-responsive" style="border:2px solid #ddd; margin-top:20px;">
<?php
	}
?>
	<table style="table-layout: fixed;" class="table<?= $table['style_striped'] ? ' table-striped' : '' ?><?= $table['style_bordered'] ? ' table-bordered' : '' ?><?= $table['style_hover'] ? ' table-hover' : '' ?>">
	
<?php

	$table = $_SESSION['allTables'][$tableKey[0]];
	
	$tableRows = array_keys(array_column($_SESSION['tableRows'], 'table_id'),$table['table_id']);
	$tableHeaders = array_keys(array_column($_SESSION['tableRows'], 'table_header'),'1');
	
	$tableHeaderKeys = array_intersect($tableRows, $tableHeaders);
	$tableRowKeys = array_diff($tableRows, $tableHeaders);

?>
		<thead>
<?php
	
	foreach($tableHeaderKeys as $key){
		
		$header = $_SESSION['tableRows'][$key];
?>
			<tr>
<?php
			if($header['colspan'] > 0) {
?>
				<th colspan="<?= $header['colspan'] ?>"><?= $header['row_name'] ?></th>
<?php
			}
			
		$rowCellKeys = array_keys(array_column($_SESSION['tableCells'], 'row_id'),$header['row_id']);
		foreach($rowCellKeys as $key) {

			$cell = $_SESSION['tableCells'][$key];
			
			if($cell['colspan'] > 0) {
?>
				<th colspan="<?= $cell['colspan'] ?>"><?= $cell['content'] ?></th>
<?php
			}
		}
?>
			</tr>
<?php
	}
?>
		</thead>
		
		<tbody>
<?php
	
	foreach($tableRowKeys as $key){
		
		$row = $_SESSION['tableRows'][$key];
		$sectionExcluded = array_keys(array_column($_SESSION['sectionsExcluded'], 'entity_id'), $row['show_with_section']);
		if(!$sectionExcluded) {	
?>
			<tr data-row-id="<?= $row['row_id'] ?>">
<?php
			if($row['colspan'] > 0) {
?>
				<td style="vertical-align: middle;" colspan="<?= $row['colspan'] ?>">
<?php
		if($row['additional_row'] == 1){
?>
					<i class="fa fa-plus-circle add-table-row tooltipstered" 
						data-toggle="tooltip" 
						data-placement="right" 
						data-custom-name="<?= $row['additional_row_name'] ?>"
						title="Add custom rows by clicking here. Once you are done adding new rows refresh the ROI for them to appear."
						></i>
<?php
		}
?>	
					<?= $row['row_name'] ?>
				</td>
<?php
			}
		
		$rowCellKeys = array_keys(array_column($_SESSION['tableCells'], 'row_id'),$row['row_id']);
		foreach($rowCellKeys as $key) {

			$cell = $_SESSION['tableCells'][$key];
			
			if($cell['colspan'] > 0) {
?>
				<td style="vertical-align: middle;" colspan="<?= $cell['colspan'] ?>">
<?php

	switch($cell['type']){
		
		case 0:
			echo $cell['content'];
			break;
			
		case 1:

			$inputKey = array_keys(array_column($_SESSION['allRoiInputs'], 'calculation_element_id'),$cell['reference_id']);
			$inputSpecs = $_SESSION['allRoiInputs'][$inputKey[0]];

			$inputValue = '';
			$inputKey = array_keys(array_column($_SESSION['inputValues'], 'entryid'),$inputSpecs['calculation_element_id']);
			
			if($inputKey){
				$inputValue = $_SESSION['inputValues'][$inputKey[0]];
			}			
?>
				<div class="col-lg-12">
<?php
	
	if($inputSpecs['helper'] && !ctype_space($inputSpecs['helper']) || $inputSpecs['append']) {
?>
					<div class="input-group">
<?php
	}
?>
						<input id="A<?= $inputSpecs['calculation_element_id'] ?>" 
							type="text" 
							style="padding: 5px; height: auto;" 
							class="form-control<?= $inputSpecs['helper'] ? ' input-addon' : '' ?>"
							<?= ( $inputValue ? ' value="'. $inputValue['value'] .'"' : ( $inputSpecs['placeholder'] ? ' value="'. $inputSpecs['placeholder'] .'"' : '' ) ) ?>
							name="<?= $inputSpecs['calculation_element_id'] ?>"
							data-cell-reference="A<?= $inputSpecs['calculation_element_id'] ?>"
							data-yr="0"
							data-format="<?= $inputSpecs['format'] ?>"
							data-cell="A<?= $inputSpecs['calculation_element_id'] ?>"/>

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
<?php
			break;
			
			case 2:
			
				$equation = null;
				$outputKey = array_keys(array_column($_SESSION['allEquations'], 'function_id'),$cell['reference_id']);
				if($outputKey){
					$equation = $_SESSION['allEquations'][$outputKey[0]];
				}
				
				$overriddenKey = array_keys(array_column($_SESSION['overriddenValues'], 'entryid'),'TABLE'.$cell['cell_id']);
				$overriddenValue = null;
				if($overriddenKey){
					$overriddenValue = $_SESSION['overriddenValues'][$overriddenKey[0]];
				}
?>
				<?= $cell['bold'] ? '<strong>' : '' ?>
				<span 
					<?= isset($overriddenValue) ? 'style="color:rgb(165,42,42)"' : '' ?>
					data-cell="TABLE<?= $cell['cell_id'] ?>" 
					data-format="<?= $cell['format'] ? $cell['format'] : '$0,0' ?>" 
					<?= $cell['conditional_formatting'] ? 'data-conditional-format="' . $cell['conditional_formatting'] . '"' : '' ?>
					data-formula="<?= isset($overriddenValue) ? $overriddenValue['value'] : ( isset($equation) ? $equation['function_formula'] : '' )?>"
					data-original-formula="<?= isset($equation) ? $equation['function_formula'] : '' ?>">
				</span>
				<?= $cell['bold'] ? '</strong>' : '' ?>
<?php	
			break;
	}
?>
				</td>
<?php
			}
		}
?>
			</tr>
			
<?php
	}	
		if($row['additional_row'] == 1){
			
			$customTableRowKeys = array_keys(array_column($_SESSION['customTableRows'], 'master_row_id'),$row['row_id']);
			$customRows = 0;
			
			foreach($customTableRowKeys as $key){
				
				$customRows += 1;
				$customRow = $_SESSION['customTableRows'][$key];
?>
				<tr data-custom-row-id="<?= $row['row_id'] ?>">
<?php
			if($customRow['colspan'] > 0) {
?>
					<td style="vertical-align: middle;" colspan="<?= $customRow['colspan'] ?>">
						<i class="fa fa-minus-circle remove-table-row tooltipstered" style="color: red;"
							data-toggle="tooltip" 
							data-placement="right" 
							data-custom-name="<?= $row['additional_row_name'] ?>"
							data-custom-row-id="<?= $customRow['custom_row_id'] ?>"
							title="Remove this custom row"
							></i>
						<?= $customRow['row_name'] ?>
					</td>
<?php
			}

		$customRowCellKeys = array_keys(array_column($_SESSION['customTableCells'], 'row_id'),$customRow['custom_row_id']);
		$customInputs = 0;
		
		foreach($customRowCellKeys as $key) {

		$customInputs += 1;
		$customCell = $_SESSION['customTableCells'][$key];
				
		if($customRow['colspan'] > 0) {
?>
					<td style="vertical-align: middle;" colspan="<?= $customCell['colspan'] ?>">
<?php
					$inputKey = array_keys(array_column($_SESSION['customRoiInputs'], 'custom_calculation_element_id'),$customCell['reference_id']);
					$inputSpecs = $_SESSION['customRoiInputs'][$inputKey[0]];
					
					$inputValue = '';
					$inputKey = array_keys(array_column($_SESSION['inputValues'], 'entryid'),$inputSpecs['input_name'].$inputSpecs['custom_calculation_element_id']);
					
					if($inputKey){
						$inputValue = $_SESSION['inputValues'][$inputKey[0]];
					}	
?>
						<div class="col-lg-12">
<?php
	
	if($inputSpecs['helper'] && !ctype_space($inputSpecs['helper']) || $inputSpecs['append']) {
?>
					<div class="input-group">
<?php
	}
?>
						<input id="<?= $inputSpecs['input_name'].$customInputs.$customRows ?>" 
							type="text" 
							style="padding: 5px; height: auto;" 
							class="form-control<?= $inputSpecs['helper'] ? ' input-addon' : '' ?>"
							<?= $inputValue ? ' value="'. $inputValue['value'] .'"' : '' ?>
							name="<?= $inputSpecs['input_name'].$inputSpecs['custom_calculation_element_id'] ?>"
							data-cell-reference="<?= $inputSpecs['input_name'].$customInputs.$customRows ?>"
							data-yr="0"
							data-format="<?= $inputSpecs['format'] ?>"
							data-cell="<?= $inputSpecs['input_name'].$customInputs.$customRows ?>"/>

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
					</td>
<?php
				}
			}
?>
				</tr>
<?php
			}
		}
	}
?>
		</tbody>
	</table>
<?php
	
	if(!$table['style_bordered']) {
?>
</div>
<?php
	}
?>