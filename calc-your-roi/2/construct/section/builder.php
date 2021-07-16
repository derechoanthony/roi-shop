<?php

	$elementKeys = array_keys(array_column($_SESSION['roiSectionElements'], 'roi_section_id'),$section['roi_section_id']);
	$parentKeys = array_keys(array_column($_SESSION['roiSectionElements'], 'parent'),'0');
	
	$formula = null;
	$formulaKey = array_keys(array_column($_SESSION['allEquations'], 'function_id'),$section['formula']);
	if($formulaKey){
		$formula = $_SESSION['allEquations'][$formulaKey[0]];
	}
	
	$parentSectionKeys = $tableHeaderKeys = array_intersect($elementKeys, $parentKeys);
	$sectionExcluded = array_keys(array_column($_SESSION['sectionsExcluded'], 'entity_id'), $section['roi_section_id']);
	
	if(!$sectionExcluded) {	
?>

<div data-section-id="<?= $section['roi_section_id'] ?>" data-show-with="<?= $section['roi_section_category'] ?>">
	
	<div id="section<?= $section['roi_section_id'] ?>" class="row border-bottom white-bg dashboard-header">		
		<div class="col-lg-12">
			<h1 style="margin-bottom: 20px;"><?= replaceTextElements($section['roi_section_title']) ?>
<?php

	if(isset($formula) && $formula['function_formula']){
?>
				<span class="pull-right pod-total section-total grand-total txt-money"
						data-format="($0,0)"
						data-formula="<?= $formula['function_formula'] ?>">$ 0
				</span>
<?php
	
	}
?>
			</h1>
		</div>
	</div>
	
	<div class="row bottom-border gray-bg dashboard-header">
	
<?php
		echo createSectionElement($parentSectionKeys);
?>

	</div>
	
</div>

<?php
	}
?>