<div class="chart-holder">

<?php

	$chartSeriesKeys = array_keys(array_column($_SESSION['chartSeries'], 'chartID'), $id );
	
	foreach($chartSeriesKeys as $seriesKey) {
		
		$currentSeries = $_SESSION['chartSeries'][$seriesKey]['seriesID'];
		$seriesDataKeys = array_keys(array_column($_SESSION['chartSeriesData'], 'series_id'),$currentSeries);
		
		$sectionExcluded = array_keys(array_column($_SESSION['sectionsExcluded'], 'entity_id'), $_SESSION['chartSeries'][$seriesKey]['show_with_section']);
		if(!$sectionExcluded) {	
?>

	<div style="display: none;" class="series-holder" data-series="<?= $currentSeries ?>">
		<input class="series-name" data-formula="<?= $_SESSION['chartSeries'][$seriesKey]['seriesTitle'] ?>" />
<?php		
			foreach($seriesDataKeys as $seriesDataKey){
				$inputKey = array_keys(array_column($_SESSION['allEquations'], 'function_id'),$_SESSION['chartSeriesData'][$seriesDataKey]['formula']);
				$sliced = $_SESSION['chartSeriesData'][$seriesDataKey]['sliced'];
				$seriesName = $_SESSION['chartSeriesData'][$seriesDataKey]['series_name'];
				$seriesData = [];
				if($inputKey){
					$seriesData[] = $_SESSION['allEquations'][$inputKey[0]];
				}
		
				foreach($seriesData as $series){		
?>
			
		<input class="graph-formula" data-formula="<?= $series['function_formula'] ?>" data-sliced="<?= $sliced ?>" data-series-name="<?= $seriesName ?>"/>
			
<?php
	
				}
?>
	
	
<?php
			}
?>
	</div>
<?php	
		}
	}
?>

	<div class="ROICalcElemID" id="ROICalcElemID<?= $id ?>" data-id="<?= $id ?>" data-animate="1"></div>
	
</div>