<?php

	// Establish connection to the database
	
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/db_connection_noutf.php");
	
	if( $_GET['action'] == 'getroiarray' ) {
		
		$roiBuilder = new RoiBuilder($db);
		$roiStructure = array();
		
		$roiStoredArray = $roiBuilder->getStoredRoiArray();
		if( $roiStoredArray ){
			$storedArray = gzuncompress($roiStoredArray);
			$storedArray = unserialize( base64_decode($storedArray) );
			$roiStructure['storedArray'] = json_decode($storedArray);
		}

		$roiStructure['structure'] = $roiBuilder->getRoiArray();		
		$roiStructure['navigation'] = $roiBuilder->getNavigation();
		$roiStructure['currency'] = $roiBuilder->getCurrency();
		
		$roiStoredValues = $roiBuilder->getStoredValues();
		if( $roiStoredValues ){
			$storedValues = gzuncompress($roiStoredValues);
			$storedValues = unserialize( base64_decode($storedValues) );
			$roiStructure['storedValues'] = json_decode($storedValues);
		}
		
		echo json_encode($roiStructure);
	}
	
	if( $_GET['action'] == 'getverification' ) {
		
		$roiBuilder = new RoiBuilder($db);
		
		echo $roiBuilder->getVerification();
	}
	
	class RoiBuilder {
		
		private $_db;
		
		public function __construct($db=NULL) {
			
			if(is_object($db)) {
				$this->_db = $db;
			} else {
				$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
				$this->_db = new PDO($dsn, DB_USER, DB_PASS);
			}
		}
		
		public function getVerification() {

			$sql = "SELECT verification_code FROM ep_created_rois
					WHERE roi_id = :roi";
						
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$verification = $stmt->fetch();
			
			return $verification['verification_code'];
		}
		
		public function getCurrency() {

			$sql = "SELECT currency FROM ep_created_rois
					WHERE roi_id = :roi";
						
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$verification = $stmt->fetch();
			
			return $verification['currency'];
		}
		
		public function getStoredRoiArray() {
			
			$sql = "SELECT roi_array FROM ep_created_roi_array
					WHERE roi_id = :roi
					ORDER BY array_id DESC LIMIT 1";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$StoredArray = $stmt->fetch();
			
			return $StoredArray['roi_array'];					
		}
		
		public function getStoredValues() {
			
			$sql = "SELECT roi_values FROM ep_created_roi_array
					WHERE roi_id = :roi
					ORDER BY array_id DESC LIMIT 1";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$StoredValues = $stmt->fetch();
			
			return $StoredValues['roi_values'];					
		}
		
		public function getRoiElements() {

			$sql = "SELECT * FROM ep_elements
					WHERE el_version = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi					
					)
					ORDER BY el_pos, el_id";
	
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiElements = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $RoiElements;		
		}
		
		public function getElementChoices(){
			
			$sql = "SELECT * FROM ep_element_choices
					WHERE el_id IN (
						SELECT el_id FROM ep_elements
						WHERE el_version = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = :roi
						)
					)
					ORDER BY ch_pos, ch_id";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$ElementChoices = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $ElementChoices;
		}
		
		public function getTableHeaders(){
			
			$sql = "SELECT * FROM ep_table_headers
					WHERE el_id IN (
						SELECT el_id FROM ep_elements
						WHERE el_version = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = :roi
						)
					)
					ORDER BY header_pos, header_id";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$TableHeaders = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $TableHeaders;
		}
		
		public function getChartOptions(){

			$sql = "SELECT * FROM ep_chart_options
					WHERE chart_id IN (
						SELECT el_id FROM ep_elements
						WHERE el_version = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = :roi
						) AND el_type = 'graph'
					)";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$ChartOptions = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $ChartOptions;			
		}
		
		public function getChartSeries(){

			$sql = "SELECT * FROM ep_chart_series_options
					WHERE chart_id IN (
						SELECT el_id FROM ep_elements
						WHERE el_version = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = :roi
						) AND el_type = 'graph'
					) ORDER BY position";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$ChartSeries = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $ChartSeries;			
		}
		
		public function getChartxAxis(){

			$sql = "SELECT * FROM ep_chart_x_categories
					WHERE chart_id IN (
						SELECT el_id FROM ep_elements
						WHERE el_version = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = :roi
						) AND el_type = 'graph'
					) ORDER BY position";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$ChartxAxis = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $ChartxAxis;			
		}
		
		public function getChartyAxis(){

			$sql = "SELECT * FROM ep_chart_y_options
					WHERE chart_id IN (
						SELECT el_id FROM ep_elements
						WHERE el_version = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = :roi
						) AND el_type = 'graph'
					) ORDER BY position";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$ChartyAxis = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $ChartyAxis;			
		}
		
		public function getSeriesEquations(){

			$sql = "SELECT * FROM ep_chart_series_equations
					WHERE chart_id IN (
						SELECT el_id FROM ep_elements
						WHERE el_version = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = :roi
						) AND el_type = 'graph'
					) ORDER BY position";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$seriesEquations = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $seriesEquations;			
		}
		
		public function roiNavigation(){

			$sql = "SELECT * FROM ep_navigation
					WHERE version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					) ORDER BY position";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$navigation = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $navigation;		
		}
		
		public function getRoiArray() {
			
			$roiElements = $this->getRoiElements();
			$elementChoices  = $this->getElementChoices();
			$chartOptions    = $this->getChartOptions();
			$chartSeries     = $this->getChartSeries();
			$chartxAxis	     = $this->getChartxAxis();
			$chartyAxis	     = $this->getChartyAxis();
			$seriesEquations = $this->getSeriesEquations();
			
			$roiBuild = $this->getChildren(0, $roiElements, $elementChoices, $chartOptions, $chartSeries, $chartxAxis, $chartyAxis, $seriesEquations);
			return $roiBuild;
		}
		
		public function getNavigation() {
			
			$navigation = $this->buildNavigation(0);
			return $navigation;
		}
		
		public function buildNavigation($parent){
			
			$navArray = array();
			
			$navigation = $this->roiNavigation();
			$navElements = array_keys(array_column($navigation,'parent'), $parent);
			
			foreach($navElements as $nav){
				$currentNav = $navigation[$nav];
				$children 	  = array_keys(array_column($navigation,'parent'), $currentNav['navigation_id']);
				if( count($children) > 0 ){
					$currentNav['children'] = $this->buildNavigation($currentNav['navigation_id']);
				}
				
				$navArray[] = $currentNav;
			}
			
			return $navArray;
		}
		
		public function getChildren($parent, $roiElements, $elementChoices, $chartOptions, $chartSeries, $chartxAxis, $chartyAxis, $seriesEquations){

			$childArray     = array();
			
			$elements = array_keys(array_column($roiElements,'el_parent'), $parent);
			foreach($elements as $element) {
				
				$elementArray = $roiElements[$element];
				$choices      = array_keys(array_column($elementChoices,'el_id'), $elementArray['el_id']);
				foreach($choices as $choice){
					$elementArray['choices'][] = $elementChoices[$choice];
				};
				
				$children 	  = array_keys(array_column($roiElements,'el_parent'), $elementArray['el_id']);
				if( count($children) > 0 ){
					
					switch($elementArray['el_type']){
						
						case 'table':
							$sub = 'rows';
							break;
							
						case 'tblrow':
						case 'tblheaders':
							$sub = 'cells';
							break;
							
						case 'tabgroup':
							$sub = 'tabs';
							break;
							
						default:
							$sub = 'children';
							break;
					}
					
					$elementArray[$sub] = $this->getChildren($elementArray['el_id'], $roiElements, $elementChoices, $chartOptions, $chartSeries, $chartxAxis, $chartyAxis, $seriesEquations);
				};
				
				$options 	  = array_keys(array_column($chartOptions,'chart_id'), $elementArray['el_id']);
				foreach($options as $option){
					
					$elementArray['highchart']['title']['text'] = $chartOptions[$option]['chart_title'];
					$elementArray['highchart']['tooltip']['pointFormat'] = $chartOptions[$option]['point_format'];
					
					if($chartOptions[$option]['column_stacking']){
						
						if(isset($chartOptions[$option]['column_stacking']) && $chartOptions[$option]['column_stacking'] !== 'NULL'){
							$elementArray['highchart']['plotOptions']['series']['stacking'] = $chartOptions[$option]['column_stacking'];
						};

						if(isset($chartOptions[$option]['point_width']) && $chartOptions[$option]['point_width'] != 0) {
							$elementArray['highchart']['plotOptions']['series']['pointWidth'] = $chartOptions[$option]['point_width'];
						};						
					}
					
					if($chartOptions[$option]['legend_reversed']){
						
						if(isset($chartOptions[$option]['legend_reversed']) && $chartOptions[$option]['legend_reversed'] !== 'NULL'){
							$elementArray['highchart']['legend']['reversed'] = $chartOptions[$option]['legend_reversed'];
						};					
					}
				};
				
				$seriesOptions  = array_keys(array_column($chartSeries,'chart_id'), $elementArray['el_id']);
				if($seriesOptions){
					
					$seriesCount = 0;
					foreach($seriesOptions as $series){
						
						$elementArray['highchart']['series'][$seriesCount]['type'] = $chartSeries[$series]['series_type'];
						$elementArray['highchart']['series'][$seriesCount]['name'] = $chartSeries[$series]['series_title'];
						
						if($chartSeries[$series]['series_color']){
							$elementArray['highchart']['series'][$seriesCount]['color'] = $chartSeries[$series]['series_color'];
						};
						
						$equations  = array_keys(array_column($seriesEquations,'series_id'), $chartSeries[$series]['series_id']);
						foreach($equations as $equation){
							$elementArray['highchart']['series'][$seriesCount]['formula'][] = $seriesEquations[$equation]['equation'];
						};
						$seriesCount++;
					};				
				};
				
				$xAxisCategory	  = array_keys(array_column($chartxAxis,'chart_id'), $elementArray['el_id']);
				if($xAxisCategory){
					
					foreach($xAxisCategory as $xAxis){
						
						$elementArray['highchart']['xAxis']['categories'][] = $chartxAxis[$xAxis]['category_name'];
					};				
				};
				
				$yAxisCategory	  = array_keys(array_column($chartyAxis,'chart_id'), $elementArray['el_id']);
				if($yAxisCategory){
					
					$yAxisCount = 0;
					foreach($yAxisCategory as $yAxis){
						
						$elementArray['highchart']['yAxis'][$yAxisCount]['title']['text'] = $chartyAxis[$yAxis]['label_title'];
						$yAxisCount++;
					};				
				};
				
				$childArray[] = $elementArray;
			}
			
			return $childArray;
		}
		
		
	}

?>