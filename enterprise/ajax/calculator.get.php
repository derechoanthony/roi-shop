<?php

	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/constants.php");
	require_once("$root/db/connection.php");

	// Get ROI Array Action
	
	if( $_GET['action'] == 'getroicurrency' ) {
		
		$sql = "SELECT * FROM ep_roi_currency
				WHERE roi_id = :roi;";
					
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$currency = $stmt->fetch();
		echo json_encode($currency);	
	}
	
	if( $_GET['action'] == 'getroiversion' ) {
		
		$sql = "SELECT roi_version_id FROM ep_created_rois
				WHERE roi_id = :roi;";
					
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$version = $stmt->fetch();
		echo json_encode($version);	
	}
	
	if( $_GET['action'] == 'getroiarray' ) {
		
		$roiBuilder = new RoiBuilder($db);
		$roiArray = $roiBuilder->buildMasterArray(0);
		$sidebar = $roiBuilder->buildSidebar();
		
		$RoiBuilderArray = [];
		
		$RoiBuilderArray['main_content'] = $roiArray;
		$RoiBuilderArray['sidebar'] = $sidebar;
	
		echo json_encode($RoiBuilderArray);
	}
	
	if( $_GET['action'] == 'getchartarray' ) {
		
		$chartBuilder = new ChartBuilder($db);
		$chartArray = $chartBuilder->buildChartArray($_GET['chartid']);
	
		echo json_encode($chartArray);
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
			
			$this->_roiHolders = $this->getRoiHolders();
			$this->_roiSections = $this->getRoiSections();
			$this->_roiInputs = $this->getRoiInputs();
			$this->_roiText = $this->getRoiText();
			$this->_roiVideo = $this->getRoiVideo();
			$this->_roiDropdown = $this->getRoiDropdowns();
			$this->_roiDropdownChoices = $this->getRoiDropdownChoices();
			$this->_roiRowTables = $this->getRoiRowTables();
			$this->_roiRowHeaders = $this->getRoiTableHeaders();
			$this->_roiRowHeaderHeaders = $this->getRoiTableHeaderHeaders();
			$this->_roiTableRows = $this->getRoiTableRows();
			$this->_roiTableCells = $this->getRoiTableCells();
			$this->_roiTabs = $this->getRoiTabs();
			$this->_roiTabTabs = $this->getRoiTabTabs();
			$this->_roiGraphs = $this->getRoiGraphs();
			$this->_roiGraphSeries = $this->getRoiGraphSeries();
			$this->_roiGraphEquations = $this->getRoiGraphEquations();
			$this->_roiCategories = $this->getRoiCategories();
			$this->_roiCategorySections = $this->getRoiCategorySections();
			$this->_roiLogo = $this->getRoiLogo();
			$this->_roiTables = $this->getRoiTables();
			$this->_roiValues = $this->getRoiSavedValues();
		}
		
		public function getRoiSavedValues() {
			
			$sql = "SELECT * FROM roi_values
					WHERE roiid = :roi
					ORDER BY dt DESC;";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiSavedValues = $stmt->fetchall();
			return $RoiSavedValues;	
		}
		
		public function getRoiLogo() {
			
			$sql = "SELECT * FROM ep_roi_logo
					WHERE version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					);";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiLogos = $stmt->fetchall();
			return $RoiLogos;			
		}
		
		public function getRoiCategories() {
			
			$sql = "SELECT * FROM ep_roi_categories
					WHERE version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					)
					ORDER BY position;";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiCategories = $stmt->fetchall();
			return $RoiCategories;			
		}
		
		public function getRoiCategorySections() {
			
			$sql = "SELECT * FROM ep_roi_category_sections
					WHERE category_id IN (
						SELECT category_id FROM ep_roi_categories
						WHERE version_id = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = :roi
						)
					) ORDER BY position;";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiCategorySections = $stmt->fetchall();
			return $RoiCategorySections;			
		}		
		
		public function getRoiHolders() {
			
			$sql = "SELECT * FROM ep_holders
					WHERE structure_version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi					
					)
					ORDER BY position, holder_id";
	
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiHolders = $stmt->fetchall();
			return $RoiHolders;		
		}
		
		public function getRoiInputs() {
			
			$sql = "SELECT * FROM ep_input
					WHERE field_id IN (
						SELECT field_id FROM ep_roi_fields
						WHERE field_type = 2 AND structure_version_id = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = :roi
						)
					)";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiInputs = $stmt->fetchall();
			return $RoiInputs;		
		}
		
		public function getRoiDropdowns() {
			
			$sql = "SELECT * FROM ep_dropdown
					WHERE dropdown_id IN (
						SELECT reference_id FROM ep_holders
						WHERE element_type = 5 AND structure_version_id = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = :roi
						)
					)";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$this->_roiDropdowns = $stmt->fetchall();
			return $this->_roiDropdowns;		
		}
		
		public function getRoiDropdownChoices() {
			
			$sql = "SELECT * FROM ep_dropdown_choices
					WHERE dropdown_holder_id IN (
						SELECT dropdown_id FROM ep_dropdown
						WHERE dropdown_id IN (
							SELECT reference_id FROM ep_holders
							WHERE element_type = 5 AND structure_version_id = (
								SELECT roi_version_id FROM ep_created_rois
								WHERE roi_id = :roi
							)
						)
					)";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$this->_roiDropdowns = $stmt->fetchall();
			return $this->_roiDropdowns;		
		}
		
		public function getRoiTables() {
			
			$sql = "SELECT * FROM ep_table
					WHERE table_id IN (
						SELECT reference_id FROM ep_holders
						WHERE element_type = 10 AND structure_version_id = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = :roi
						)
					)";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$this->_roiRowTables = $stmt->fetchall();
			return $this->_roiRowTables;		
		}
		
		public function getRoiRowTables() {
			
			$sql = "SELECT * FROM ep_rowtable
					WHERE rowtable_id IN (
						SELECT reference_id FROM ep_holders
						WHERE element_type = 6 AND structure_version_id = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = :roi
						)
					)";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$this->_roiRowTables = $stmt->fetchall();
			return $this->_roiRowTables;		
		}
		
		public function getRoiTableRows() {
			
			$sql = "SELECT * FROM ep_table_rows 
					WHERE table_id IN (
						SELECT table_id FROM ep_table
						WHERE table_id IN (
							SELECT reference_id FROM ep_holders
							WHERE element_type = 10 AND structure_version_id = (
								SELECT roi_version_id FROM ep_created_rois
								WHERE roi_id = :roi
							)
						)
					)";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiRowTableRows = $stmt->fetchall();
			return $RoiRowTableRows;		
		}
		
		public function getRoiTableCells() {
			
			$sql = "SELECT * FROM ep_table_cells
					WHERE table_id IN (
						SELECT table_id FROM ep_table
						WHERE table_id IN (
							SELECT reference_id FROM ep_holders
							WHERE element_type = 10 AND structure_version_id = (
								SELECT roi_version_id FROM ep_created_rois
								WHERE roi_id = :roi
							)
						)
					)";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiRowTableCells = $stmt->fetchall();
			return $RoiRowTableCells;
		}
		
		public function getRoiTableHeaders() {
			
			$sql = "SELECT * FROM ep_table_headers 
					WHERE table_id IN (
						SELECT table_id FROM ep_table
						WHERE table_id IN (
							SELECT reference_id FROM ep_holders
							WHERE element_type = 10 AND structure_version_id = (
								SELECT roi_version_id FROM ep_created_rois
								WHERE roi_id = :roi
							)
						)
					)";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiRowTableRows = $stmt->fetchall();
			return $RoiRowTableRows;		
		}
		
		public function getRoiTableHeaderHeaders() {
			
			$sql = "SELECT * FROM ep_headers_header
					WHERE headers_id IN (
						SELECT tbl_header_id FROM ep_table_headers 
						WHERE table_id IN (
							SELECT table_id FROM ep_table
							WHERE table_id IN (
								SELECT reference_id FROM ep_holders
								WHERE element_type = 10 AND structure_version_id = (
									SELECT roi_version_id FROM ep_created_rois
									WHERE roi_id = :roi
								)
							)
						)
					)";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiRowTableRows = $stmt->fetchall();
			return $RoiRowTableRows;		
		}
		
		public function getRoiTabs() {
			
			$sql = "SELECT * FROM ep_tab
					WHERE tab_id IN (
						SELECT reference_id FROM ep_holders
						WHERE element_type = 8 AND structure_version_id = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = :roi
						)
					)";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiRowTabs = $stmt->fetchall();
			return $RoiRowTabs;		
		}
		
		public function getRoiTabTabs() {
			
			$sql = "SELECT * FROM ep_tabs
					WHERE tab_master IN (
						SELECT tab_id FROM ep_tab
						WHERE tab_id IN (
							SELECT reference_id FROM ep_holders
							WHERE element_type = 8 AND structure_version_id = (
								SELECT roi_version_id FROM ep_created_rois
								WHERE roi_id = :roi
							)
						)
					)";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiRowTabTabs = $stmt->fetchall();
			return $RoiRowTabTabs;		
		}
		
		public function getRoiText() {
			
			$sql = "SELECT * FROM ep_text;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$this->_roiText = $stmt->fetchall();
			return $this->_roiText;		
		}
		
		public function getRoiVideo() {
			
			$sql = "SELECT * FROM ep_video
					WHERE video_id IN (
						SELECT reference_id FROM ep_holders
						WHERE element_type = 4 AND structure_version_id = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = :roi
						)
					)";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$this->_roiTexts = $stmt->fetchall();
			return $this->_roiTexts;		
		}
		
		public function getRoiSections() {
			
			$sql = "SELECT * FROM ep_sections
					WHERE section_id IN (
						SELECT reference_id FROM ep_holders
						WHERE element_type = 7 AND structure_version_id = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = :roi
						)
					)";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiSections = $stmt->fetchall();
			return $RoiSections;	
		}
		
		public function getRoiGraphs() {
			
			$sql = "SELECT * FROM ep_charts_list
					WHERE chart_id IN (
						SELECT reference_id FROM ep_holders
						WHERE element_type = 9 AND structure_version_id = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = :roi
						)
					)";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiGraphs = $stmt->fetchall();
			return $RoiGraphs;	
		}
		
		public function getRoiGraphSeries() {
			
			$sql = "SELECT * FROM ep_chart_series_options
					WHERE chart_id IN (
						SELECT reference_id FROM ep_holders
						WHERE element_type = 9 AND structure_version_id = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = :roi
						)
					)";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiGraphs = $stmt->fetchall();
			return $RoiGraphs;			
		}
		
		public function getRoiGraphEquations() {
			
			$sql = "SELECT * FROM ep_chart_series_equations
					WHERE chart_id IN (
						SELECT reference_id FROM ep_holders
						WHERE element_type = 9 AND structure_version_id = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = :roi
						)
					)";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiGraphs = $stmt->fetchall();
			return $RoiGraphs;			
		}
		
		public function buildMasterArray($parent) {
			
			$masterArray = array();
			$masterArray['elements'] = $this->buildHolderArray($parent);
			
			// Return the built Master Array
			return $masterArray;
		}
		
		public function buildSidebar() {
			
			$roiCategories = $this->_roiCategories;
			$categories = [];
			
			foreach($roiCategories as $category) {
				
				$categoryArray = [
					"id" => $category['category_id'],
					"icon" => $category['icon'],
					"label" => $category['label']
				];
				
				$classes = preg_replace('/\[|\"|\]/', '', $category['category_classes']);
				$classes = explode(',',$classes);
				$classArray = array();
						
				foreach($classes as $class) {
					$classArray[] = $class;
				};
				
				if(count($classArray)) {
					$categoryArray['classes'] = $classArray;
				};
				
				if( isset($category['href']) ) {
					$categoryArray['href'] = $category['href'];
				};
				
				$roiCategorySections = $this->_roiCategorySections;
				$categorySections = [];
				
				$holders = array_keys(array_column($roiCategorySections,'category_id'), $category['category_id']);
				foreach($holders as $holder) {
					
					$holderAttributes = $roiCategorySections[$holder];
					$section = [
						"id" => $holderAttributes['category_section_id'],
						"label" => $holderAttributes['label'],
						"href" => $holderAttributes['href']
					];
					
					$categorySections[] = $section;
				};
				
				if(count($categorySections)) {
					$categoryArray['sections'] = $categorySections;
				};

				$categories[] = $categoryArray;
			}
			
			$sidebar = [];
			$sidebar['categories'] = $categories;
			
			$roiLogo = $this->_roiLogo;
			if($roiLogo){
				$classes = preg_replace('/\[|\"|\]/', '', $roiLogo[0]['logo_classes']);
				$classes = explode(',',$classes);
				$classArray = array();
							
				foreach($classes as $class) {
					$classArray[] = $class;
				};			
				
				$logo = [
					"classes" => $classArray,
					"img" => $roiLogo[0]['logo_img'],
					"alt" => $roiLogo[0]['logo_alt_text']
				];
				
				$sidebar['logo'] = $logo;
			}
			
			return $sidebar;
		}
		
		public function buildInputArray($reference_id) {

			$input = array_keys(array_column($this->_roiInputs, 'input_id'),$reference_id);
			if($input) {

				$input = $this->_roiInputs[$input[0]];					

				$classes = preg_replace('/\[|\"|\]/', '', $input['input_classes']);
				$classes = explode(',',$classes);
				$inputClasses = array();

				foreach($classes as $class) {
					$inputClasses[] = $class;
				}

				$classes = preg_replace('/\[|\"|\]/', '', $input['input_label_classes']);
				$classes = explode(',',$classes);
				$labelClasses = array();

				foreach($classes as $class) {
					$labelClasses[] = $class;
				}
				
				$inputArray = [
					"type" => $input['input_type'],
					"id" => $input['field_name'],
					"name" => $input['input_name'],
					"letter" => $input['input_letter'],
					"classes" => $inputClasses,
					"format" => $input['input_format'],
					"value" => $input['input_default'],
					"append" => $input['input_append'],
					"prepend" => $input['input_prepend']
				];
				
				$value = array_keys(array_column($this->_roiValues, 'entryid'),$input['field_name']);
				if($value) {
					$inputArray['value'] = $this->_roiValues[$value[0]]['value'];
				};

				if( isset($input['input_oncalc']) ) {
					$inputArray['oncalc'] = $input['input_oncalc'];
				};
				
				$restraints = array();
				if( isset($input['input_min']) ) {
					$restraints['min'] = $input['input_min'];
				};
				
				if( isset($input['input_max']) ) {
					$restraints['max'] = $input['input_max'];
				};
				
				if( isset($input['input_step']) ) {
					$restraints['step'] = $input['input_step'];
				};
				
				if( isset($input['input_stacked']) && $input['input_stacked'] == 1 ) {
					$inputArray['style'] = 'stacked';
				};
				
				if($restraints){
					$inputArray['restraints'] = $restraints;
				}
				
				if( isset($input['input_label']) ) {
					$inputArray['label'] = array (
						"classes" => $labelClasses,
						"text" => $input['input_label']
					);
				};
				
				if($input['input_disabled'] == 1) {
					$inputArray['disabled'] = true;
				};
				
				if($input['input_formula']) {
					$inputArray['formula'] = $input['input_formula'];
				};

				if( isset($input['input_popup']) ) {
					$inputArray['popup'] = array (
						"text" => $input['input_popup']
					);
				};
			
			}

			return $inputArray;
		}
		
		public function buildTextArray($reference_id){
			
			$text = array_keys(array_column($this->_roiText, 'text_id'),$reference_id);
			if($text) {
				
				$text = $this->_roiText[$text[0]];
				
				$classes = preg_replace('/\[|\"|\]/', '', $text['text_classes']);
				$classes = explode(',',$classes);
				$textClasses = array();
				
				foreach($classes as $class) {
					$textClasses[] = $class;
				}						
				
				$text = [
					"type" => "text",
					"classes" => $textClasses,
					"text" => $text['text_text']
				];						
			}

			return $text;
		}
		
		public function buildHolderArray($parent) {
			
			$roiHolders = $this->_roiHolders;
			$subArray = array();
			
			$holders = array_keys(array_column($roiHolders,'holder_parent'), $parent);
			foreach($holders as $holder) {
				
				$holderArray = $roiHolders[$holder];
				switch($holderArray['element_type']) {
					
					case 1:
					
						$classes = preg_replace('/\[|\"|\]/', '', $holderArray['holder_classes']);
						$classes = explode(',',$classes);
						$classArray = array();
						
						foreach($classes as $class) {
							$classArray[] = $class;
						}
						
						$holder = [
							"type" => "holder",
							"holder_id" => $holderArray['holder_id'],
							"classes" => $classArray,
							"elements" => $this->buildHolderArray($holderArray['holder_id'])
						];

						$subArray[] = $holder;
						
					break;
					
					case 2:
						
						$inputArray = $this->buildInputArray($holderArray['reference_id']);
						if(isset($inputArray)) {
							$subArray[] = $inputArray;
						}	
						
					break;
					
					case 3:

						$textArray = $this->buildTextArray($holderArray['reference_id']);
						if(isset($textArray)) {
							$subArray[] = $textArray;
						}
						
					break;
					
					case 4:
						
						$video = array_keys(array_column($this->_roiVideo, 'video_id'),$holderArray['reference_id']);
						if($video) {
							
							$video = $this->_roiVideo[$video[0]];					
								
							$video = [
								"type" => "video",
								"src" => $video['video_src']
							];					
						}
						
						$subArray[] = $video;

					break;
					
					case 5:
						
						$dropdown = array_keys(array_column($this->_roiDropdown, 'dropdown_id'),$holderArray['reference_id']);
						if($dropdown) {
							
							$dropdown = $this->_roiDropdown[$dropdown[0]];					

							$value = array_keys(array_column($this->_roiValues, 'entryid'),$dropdown['dropdown_name']);
							if($value) {
								$value = $this->_roiValues[$value[0]]['value'];
							};

							$dropdownChoices = array_keys(array_column($this->_roiDropdownChoices, 'dropdown_holder_id'),$holderArray['reference_id']);
							if($dropdownChoices) {

								$dropdownTotalChoices = array();
								
								foreach($dropdownChoices as $choice){
									
									$dropdownChoice = $this->_roiDropdownChoices[$choice];
									$dropdownArray = [
										"value" => $dropdownChoice['dropdown_value'],
										"text" => $dropdownChoice['dropdown_text'],
										"showmap" => $dropdownChoice['dropdown_showmap']
									];

									if($value === $dropdownChoice['dropdown_value']) {
										$dropdownArray['selected'] = 'true';
									};
									
									$dropdownTotalChoices[] = $dropdownArray;
								}
							}
						
							$classes = preg_replace('/\[|\"|\]/', '', $dropdown['dropdown_classes']);
							$classes = explode(',',$classes);
							$dropdownClasses = array();
							
							foreach($classes as $class) {
								$dropdownClasses[] = $class;
							}
							
							$classes = preg_replace('/\[|\"|\]/', '', $dropdown['dropdown_label_classes']);
							$classes = explode(',',$classes);
							$dropdownLabelClasses = array();
							
							foreach($classes as $class) {
								$dropdownLabelClasses[] = $class;
							}
							
							$dropdown = [
								"type" => "dropdown",
								"id" => $dropdown['dropdown_name'],
								"classes" => $dropdownClasses,
								"label" => array(
									"classes" => $dropdownLabelClasses,
									"text" => $dropdown['dropdown_label']
								),
								"options" => $dropdownTotalChoices
							];

							$value = array_keys(array_column($this->_roiValues, 'entryid'),$dropdown['id']);
							if($value) {
								$dropdown['value'] = $this->_roiValues[$value[0]]['value'];
							};							
						}
						
						$subArray[] = $dropdown;

					break;
					
					case 6:
					
						$table = array_keys(array_column($this->_roiRowTables, 'rowtable_id'),$holderArray['reference_id']);
						if($table) {
							
							$headers = array_keys(array_column($this->_roiRowHeaders, 'table_id'),$holderArray['reference_id']);
							$headersArray = array();
							if($headers){
								
								foreach($headers as $header){
									
									$headersAttributes = $this->_roiRowHeaders[$header];
									
									$headerHeaders = array();
									$headerHeader = array_keys(array_column($this->_roiRowHeaderHeaders, 'headers_id'),$headersAttributes['tbl_header_id']);
									if($headerHeader){
										
										foreach($headerHeader as $head){
											
											$headerAttributes = $this->_roiRowHeaderHeaders[$head];
											$headerHeaderArray = [
												"title" => array(
													"text" => ( $headerAttributes['header_text'] ? $headerAttributes['header_text'] : '&nbsp;' ),
													"colspan" => ( $headerAttributes['colspan'] ? $headerAttributes['colspan'] : 1 ),
													"rowspan" => ( $headerAttributes['rowspan'] ? $headerAttributes['rowspan'] : 1 )
												)
											];
											$headerHeaders[] = $headerHeaderArray;
										}
									}
									
									$headerArray = [
										"header" => $headerHeaders
									];
									$headersArray[] = $headerArray;
								}
							}
							
							$table = $this->_roiRowTables[$table[0]];
							$rows = array_keys(array_column($this->_roiTableRows, 'table_id'),$holderArray['reference_id']);
							if($rows){
								
								$rowsArray = array();
								foreach($rows as $row){
									
									$rowAttributes = $this->_roiTableRows[$row];
									
									$cellsArray = array();
									$cells = array_keys(array_column($this->_roiTableCells, 'row_id'),$rowAttributes['row_id']);
									if($cells){
										
										foreach($cells as $cell){
											
											$cellAttributes = $this->_roiTableCells[$cell];
											$cellArray = [
												"id" => $cellAttributes['cell_identifier'],
												"type" => $cellAttributes['cell_type'],
												"format" => $cellAttributes['cell_format'],
												"content" => $cellAttributes['cell_content'],
												"formula" => $cellAttributes['cell_formula']
											];
											
											if( isset($cellAttributes['colspan']) ) {
												$cellArray['colspan'] = $cellAttributes['colspan'];
											};
											$cellsArray[] = $cellArray;
										}
									}
									
									$rowArray = [
										"id" => $rowAttributes['row_number'],
										"repeat" => $rowAttributes['repeat_num'],
										"cells" => $cellsArray
									];
									$rowsArray[] = $rowArray;
								}
							}
							
							$pagination = ( $table['pagination'] == 0 ? false : true );
							$searching = ( $table['searching'] == 0 ? false : true );
							$info = ( $table['info'] == 0 ? false : true );
							$ordering = ( $table['ordering'] == 0 ? false : true );
							
							$table = [
								"type" => "rowtable",
								"specs" => array(
									"pagination" => $pagination,
									"searching" => $searching,
									"info" => $info,
									"ordering" => $ordering
								),
								"rows" => $rowsArray							
							];
							
							if(isset($headersArray) && $headersArray) {
								$table['headers'] = $headersArray;
							}
						}

						$subArray[] = $table;					
					
					break;					
					
					case 7:
						
						$section = array_keys(array_column($this->_roiSections, 'section_id'),$holderArray['reference_id']);

						if($section) {
							
							$section = $this->_roiSections[$section[0]];
							
							$classes = preg_replace('/\[|\"|\]/', '', $section['section_classes']);
							$classes = explode(',',$classes);
							$classArray = array();
							
							foreach($classes as $class) {
								$classArray[] = $class;
							}
						
							$section = [
								"type" => "section",
								"id" => $section['section_identifier'],
								"classes" => $classArray,
								"header" => array(
									"text" => $section['section_header_text']
								)
							];						
						}

						$subArray[] = $section;

					break;
					
					case 8:
					
						$masterTab = array_keys(array_column($this->_roiTabs, 'tab_id'),$holderArray['reference_id']);
						if($masterTab){
							
							$tabsArray = array();
							$tabs = array_keys(array_column($this->_roiTabTabs, 'tab_master'),$holderArray['reference_id']);
							if($tabs){
										
								foreach($tabs as $tab){

									$tabAttributes = $this->_roiTabTabs[$tab];
									$active = ( $tabAttributes['tab_active'] == 0 ? false : true );
									
									$tabArray = [
										"id" => $tabAttributes['tab_id'],
										"title" => $tabAttributes['tab_title'],
										"active" => $active,
										"value" => $tabAttributes['tab_value'],
										"elements" => $this->buildHolderArray($tabAttributes['tab_holder_id'])
									];
									
									$tabsArray[] = $tabArray;
								}
							};
							
							$tab = [
								"type" => "tab",
								"tabs" => $tabsArray
							];
						}
						
						if(isset($tab)) { $subArray[] = $tab; }
					
					break;
					
					case 9:
					
						$masterGraph = array_keys(array_column($this->_roiGraphs, 'chart_id'),$holderArray['reference_id']);
						$graphArray = array();
						
						if($masterGraph){
									
							$graphSeries = array_keys(array_column($this->_roiGraphSeries, 'chart_id'),$holderArray['reference_id']);
							if(isset($graphSeries)) {
								
								$seriesArray = [];
								foreach($graphSeries as $series) {
									
									$seriesAttributes = $this->_roiGraphSeries[$series];
									$seriesEquations = array_keys(array_column($this->_roiGraphEquations, 'series_id'),$seriesAttributes['series_id']);
									
									$equationArray = [];
									foreach($seriesEquations as $equation) {
										
										$equationFormula = $this->_roiGraphEquations[$equation];
										$equationArray[] = [
											"equation" => $equationFormula['equation'],
											"name" => $equationFormula['series_name'],
											"sliced" => $equationFormula['sliced']
										];
									};
									
									$series = array(
										"name" => $seriesAttributes['series_title'],
										"equations" => $equationArray
									);
									
									$seriesArray[] = $series;
								}
							}
							
							$graph = $this->_roiGraphs[$masterGraph[0]];
							
							$graphArray = [
								"type" => 'graph',
								"id" => $graph['chart_id']
							];
							
							if(isset($seriesArray)) {
								$graphArray['series'] = $seriesArray;
							}
						
						};

						$subArray[] = $graphArray;
					
					break;
					
					case 10:
					
						$table = array_keys(array_column($this->_roiTables, 'table_id'),$holderArray['reference_id']);
						if($table) {
							
							$headers = array_keys(array_column($this->_roiRowHeaders, 'table_id'),$holderArray['reference_id']);
							$headersArray = array();
							if($headers){
								
								foreach($headers as $header){
									
									$headersAttributes = $this->_roiRowHeaders[$header];
									
									$headerHeaders = array();
									$headerHeader = array_keys(array_column($this->_roiRowHeaderHeaders, 'headers_id'),$headersAttributes['tbl_header_id']);
									if($headerHeader){
										
										foreach($headerHeader as $head){
											
											$headerAttributes = $this->_roiRowHeaderHeaders[$head];
											$headerHeaderArray = [
												"title" => array(
													"text" => ( $headerAttributes['header_text'] ? $headerAttributes['header_text'] : '&nbsp;' ),
													"colspan" => ( $headerAttributes['colspan'] ? $headerAttributes['colspan'] : 1 ),
													"rowspan" => ( $headerAttributes['rowspan'] ? $headerAttributes['rowspan'] : 1 )
												)
											];
											$headerHeaders[] = $headerHeaderArray;
										}
									}
									
									$headerArray = [
										"header" => $headerHeaders
									];
									$headersArray[] = $headerArray;
								}
							}
							
							$table = $this->_roiRowTables[$table[0]];
							$rows = array_keys(array_column($this->_roiTableRows, 'table_id'),$holderArray['reference_id']);
							if($rows){
								
								$rowsArray = array();
								foreach($rows as $row){
									
									$rowAttributes = $this->_roiTableRows[$row];
									
									$cellsArray = array();
									$cells = array_keys(array_column($this->_roiTableCells, 'row_id'),$rowAttributes['row_id']);
									if($cells){
										
										foreach($cells as $cell){
											
											$cellAttributes = $this->_roiTableCells[$cell];
											switch($cellAttributes['cell_type']) {
												
												case 2:
													
													$inputArray = $this->buildInputArray($cellAttributes['reference_id']);
													if(isset($inputArray)) {
														$cellArray = $inputArray;
													}
													
												break;

												case 3:
												
													$textArray = $this->buildTextArray($cellAttributes['reference_id']);
													if(isset($textArray)) {
														$cellArray = $textArray;
													}
													
												break;
											}

											if( isset($cellAttributes['colspan']) ) {
												$cellArray['colspan'] = $cellAttributes['colspan'];
											};
											$cellsArray[] = $cellArray;
										}

									}
									
									$rowArray = [
										"id" => $rowAttributes['row_number'],
										"repeat" => $rowAttributes['repeat_num'],
										"cells" => $cellsArray
									];
									$rowsArray[] = $rowArray;
								}
							}
							
							$pagination = ( $table['pagination'] == 0 ? false : true );
							$searching = ( $table['searching'] == 0 ? false : true );
							$info = ( $table['info'] == 0 ? false : true );
							$ordering = ( $table['ordering'] == 0 ? false : true );
							
							$table = [
								"type" => "table",
								"specs" => array(
									"pagination" => $pagination,
									"searching" => $searching,
									"info" => $info,
									"ordering" => $ordering
								),
								"rows" => $rowsArray							
							];

							if(isset($headersArray) && $headersArray) {
								$table['headers'] = $headersArray;
							}
						}

						$subArray[] = $table;
					
					break;
					
				}
			}

			return $subArray;
		}
	}
	
	class ChartBuilder {
		
		private $_db;
		
		public function __construct($db=NULL) {
			
			if(is_object($db)) {
				$this->_db = $db;
			} else {
				$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
				$this->_db = new PDO($dsn, DB_USER, DB_PASS);
			}
			
			$this->_roiChartList = $this->getRoiCharts();
			$this->_roiChartOptions = $this->getRoiChartOptions();
			$this->_roiChartSeriesOptions = $this->getRoiChartSeriesOptions();
			$this->_roixAxisCategories = $this->getxAxisCategories();
			$this->_roiyAxisOptions = $this->getyAxisOptions();
			
		}
		
		public function getRoiCharts() {
			
			$sql = "SELECT * FROM ep_charts_list
					WHERE chart_id IN (
						SELECT reference_id FROM ep_holders
						WHERE element_type = 9 AND structure_version_id = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = :roi
						)
					);";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiCharts = $stmt->fetchall();
			return $RoiCharts;
		}
		
		public function getRoiChartOptions() {
			
			$sql = "SELECT * FROM ep_chart_options
					WHERE chart_id IN (
						SELECT reference_id FROM ep_holders
						WHERE element_type = 9 AND structure_version_id = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = :roi						
						)			
					)";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiCharts = $stmt->fetchall();
			return $RoiCharts;
		}
		
		public function getRoiChartSeriesOptions() {
			
			$sql = "SELECT * FROM ep_chart_series_options
					WHERE chart_id IN (
						SELECT reference_id FROM ep_holders
						WHERE element_type = 9 AND structure_version_id = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = :roi							
						)				
					)";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiCharts = $stmt->fetchall();
			return $RoiCharts;
		}
		
		public function getxAxisCategories() {
			
			$sql = "SELECT * FROM ep_chart_x_categories
					WHERE chart_id IN (
						SELECT reference_id FROM ep_holders
						WHERE element_type = 9 AND structure_version_id = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = :roi							
						)			
					)";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiCharts = $stmt->fetchall();
			return $RoiCharts;
		}
		
		public function getyAxisOptions() {
			
			$sql = "SELECT * FROM ep_chart_y_options
					WHERE chart_id IN (
						SELECT reference_id FROM ep_holders
						WHERE element_type = 9 AND structure_version_id = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = :roi							
						)				
					)";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiCharts = $stmt->fetchall();
			return $RoiCharts;
		}
		
		public function buildChartArray($elemId) {
			
			$chartOptions = array_keys(array_column($this->_roiChartOptions, 'chart_id'),$elemId);

			if($chartOptions) {
				
				$options = $this->_roiChartOptions[$chartOptions[0]];
				
				$chartArray['tooltip']['pointFormat'] = $options['point_format'];
				
				$title = array(
						'text' => $options['chart_title']
					);	
					
				if($options['column_stacking']) {
					
					$series = array();
					
					if(isset($options['column_stacking']) && $options['column_stacking'] !== 'NULL'){
						$series['stacking'] = $options['column_stacking'];
					};
					
					if(isset($options['point_width']) && $options['point_width'] != 0) {
						$series['pointWidth'] = $options['point_width'];
					};
					
					$plotOptions = array(
						'series' => $series
					);
					
					$chartArray['plotOptions'] = $plotOptions;
				}
			};
			
			if(isset($title)) {
				$chartArray['title'] = $title;
			}
			
			$chartSeriesOptions = array_keys(array_column($this->_roiChartSeriesOptions, 'chart_id'),$elemId);
			
			if($chartSeriesOptions) {
				
				$seriesArray = [];
				
				foreach($chartSeriesOptions as $seriesOption) {
					$series = array (
						'type' => $this->_roiChartSeriesOptions[$seriesOption]['series_type'],
						'name' => $this->_roiChartSeriesOptions[$seriesOption]['series_title']
					);

					if(isset($this->_roiChartSeriesOptions[$seriesOption]['series_color'])) {
						$series['color'] = $this->_roiChartSeriesOptions[$seriesOption]['series_color'];
					}
					
					$seriesArray[] = $series;
				}
				
			}
			
			if(isset($seriesArray)) {
				$chartArray['series'] = $seriesArray;
			}
			
			$chartxAxisCategories = array_keys(array_column($this->_roixAxisCategories, 'chart_id'),$elemId);

			if($chartxAxisCategories) {
				
				$xAxis = [];
				foreach($chartxAxisCategories as $category) {
					$xAxis[] = $this->_roixAxisCategories[$category]['category_name'];
				};
			}
			
			if(isset($xAxis)) {
				$chartArray['xAxis']['categories'] = $xAxis;
			}
			
			$chartxAxisCategories = array_keys(array_column($this->_roixAxisCategories, 'chart_id'),$elemId);

			if($chartxAxisCategories) {
				
				$xAxis = [];
				foreach($chartxAxisCategories as $category) {
					$xAxis[] = $this->_roixAxisCategories[$category]['category_name'];
				};
			}
			
			if(isset($xAxis)) {
				$chartArray['xAxis']['categories'] = $xAxis;
			}
			
			$chartyAxisOptions = array_keys(array_column($this->_roiyAxisOptions, 'chart_id'),$elemId);

			if($chartyAxisOptions) {
				
				$yAxis = [];
				$chartArray['yAxis']['title']['text'] = $this->_roiyAxisOptions[$chartyAxisOptions[0]]['label_title'];
			}
			
			$chartArray['chart'] = array(
				"renderTo" => 'ROICalcElemID' . $elemId,
				"type" => $options['chart_type']
			);
			
			return json_encode($chartArray);
			
		}	
	}

?>