<?php

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
		$this->_roiInputs = $this-> getRoiInputs();
		$this->_roiText = $this->getRoiText();
		$this->_roiVideo = $this->getRoiVideo();
		print_r($this->_roiVideo);
		$this->_roiDropdown = $this->getRoiDropdowns();
		$this->_roiDropdownChoices = $this->getRoiDropdownChoices();
		$this->_roiRowTables = $this->getRoiRowTables();
		$this->_roiTableRows = $this->getRoiTableRows();
		$this->_roiTableCells = $this->getRoiTableCells();
		$this->_roiTabs = $this->getRoiTabs();
		$this->_roiTabTabs = $this->getRoiTabTabs();
		$this->_roiGraphs = $this->getRoiGraphs();
		print_r($this->_roiGraphs);
	}
	
	public function getRoiHolders() {
		
		$sql = "SELECT * FROM tbl_holders";

		$stmt = $this->_db->prepare($sql);
		$stmt->execute();
		$RoiHolders = $stmt->fetchall();
		return $RoiHolders;		
	}
	
	public function getRoiInputs() {
		
		$sql = "SELECT * FROM tbl_input
				WHERE input_id IN (
					SELECT reference_id FROM tbl_holders
					WHERE element_type = 2 AND structure_version_id = 1
				)";

		$stmt = $this->_db->prepare($sql);
		$stmt->execute();
		$RoiInputs = $stmt->fetchall();
		return $RoiInputs;		
	}
	
	public function getRoiDropdowns() {
		
		$sql = "SELECT * FROM tbl_dropdown
				WHERE dropdown_id IN (
					SELECT reference_id FROM tbl_holders
					WHERE element_type = 5 AND structure_version_id = 1
				)";

		$stmt = $this->_db->prepare($sql);
		$stmt->execute();
		$this->_roiDropdowns = $stmt->fetchall();
		return $this->_roiDropdowns;		
	}
	
	public function getRoiDropdownChoices() {
		
		$sql = "SELECT * FROM tbl_dropdown_choices
				WHERE dropdown_holder_id IN (
					SELECT dropdown_id FROM tbl_dropdown
					WHERE dropdown_id IN (
						SELECT reference_id FROM tbl_holders
						WHERE element_type = 5 AND structure_version_id = 1
					)
				)";

		$stmt = $this->_db->prepare($sql);
		$stmt->execute();
		$this->_roiDropdowns = $stmt->fetchall();
		return $this->_roiDropdowns;		
	}
	
	public function getRoiRowTables() {
		
		$sql = "SELECT * FROM tbl_rowtable
				WHERE rowtable_id IN (
					SELECT reference_id FROM tbl_holders
					WHERE element_type = 6 AND structure_version_id = 1
				)";

		$stmt = $this->_db->prepare($sql);
		$stmt->execute();
		$this->_roiRowTables = $stmt->fetchall();
		return $this->_roiRowTables;		
	}
	
	public function getRoiTableRows() {
		
		$sql = "SELECT * FROM tbl_table_rows 
				WHERE table_id IN (
					SELECT rowtable_id FROM tbl_rowtable
					WHERE rowtable_id IN (
						SELECT reference_id FROM tbl_holders
						WHERE element_type = 6 AND structure_version_id = 1
					)
				)";

		$stmt = $this->_db->prepare($sql);
		$stmt->execute();
		$RoiRowTableRows = $stmt->fetchall();
		return $RoiRowTableRows;		
	}
	
	public function getRoiTableCells() {
		
		$sql = "SELECT * FROM tbl_table_cells
				WHERE table_id IN (
					SELECT rowtable_id FROM tbl_rowtable
					WHERE rowtable_id IN (
						SELECT reference_id FROM tbl_holders
						WHERE element_type = 6 AND structure_version_id = 1
					)
				)";

		$stmt = $this->_db->prepare($sql);
		$stmt->execute();
		$RoiRowTableCells = $stmt->fetchall();
		return $RoiRowTableCells;		
	}
	
	public function getRoiTabs() {
		
		$sql = "SELECT * FROM tbl_tab
				WHERE tab_id IN (
					SELECT reference_id FROM tbl_holders
					WHERE element_type = 8 AND structure_version_id = 1
				)";

		$stmt = $this->_db->prepare($sql);
		$stmt->execute();
		$RoiRowTabs = $stmt->fetchall();
		return $RoiRowTabs;		
	}
	
	public function getRoiTabTabs() {
		
		$sql = "SELECT * FROM tbl_tabs
				WHERE tab_master IN (
					SELECT tab_id FROM tbl_tab
					WHERE tab_id IN (
						SELECT reference_id FROM tbl_holders
						WHERE element_type = 8 AND structure_version_id = 1
					)
				)";

		$stmt = $this->_db->prepare($sql);
		$stmt->execute();
		$RoiRowTabTabs = $stmt->fetchall();
		return $RoiRowTabTabs;		
	}
	
	public function getRoiText() {
		
		$sql = "SELECT * FROM tbl_text
				WHERE text_id IN (
					SELECT reference_id FROM tbl_holders
					WHERE element_type = 3 AND structure_version_id = 1
				)";

		$stmt = $this->_db->prepare($sql);
		$stmt->execute();
		$this->_roiText = $stmt->fetchall();
		return $this->_roiText;		
	}
	
	public function getRoiVideo() {
		
		$sql = "SELECT * FROM tbl_video
				WHERE video_id IN (
					SELECT reference_id FROM tbl_holders
					WHERE element_type = 4 AND structure_version_id = 1
				)";

		$stmt = $this->_db->prepare($sql);
		$stmt->execute();
		$this->_roiTexts = $stmt->fetchall();
		return $this->_roiTexts;		
	}
	
	public function getRoiSections() {
		
		$sql = "SELECT * FROM tbl_sections
				WHERE section_id IN (
					SELECT reference_id FROM tbl_holders
					WHERE element_type = 7 AND structure_version_id = 1
				)";

		$stmt = $this->_db->prepare($sql);
		$stmt->execute();
		$RoiSections = $stmt->fetchall();
		return $RoiSections;	
	}
	
	public function getRoiGraphs() {
		
		$sql = "SELECT * FROM tbl_charts_list
				WHERE chart_id IN (
					SELECT reference_id FROM tbl_holders
					WHERE element_type = 9 AND structure_version_id = 1
				)";
	}
	
	public function buildMasterArray($parent) {
		
		$masterArray = array();
		
		$masterArray['elements'] = $this->buildHolderArray($parent);
		
		return $masterArray;
		
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
						"classes" => $classArray,
						"elements" => $this->buildHolderArray($holderArray['holder_id'])
					];

					$subArray[] = $holder;
					
				break;
				
				case 2:
					
					$input = array_keys(array_column($this->_roiInputs, 'input_id'),$holderArray['reference_id']);
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
						
						$input = [
							"type" => "input",
							"label" => array (
								"classes" => $labelClasses,
								"text" => $input['input_label']
							),
							"id" => $input['input_name'],
							"classes" => $inputClasses,
							"format" => $input['input_format'],
							"value" => $input['input_default'],
							"append" => $input['input_append'],
							"prepend" => $input['input_prepend']
						];

						if( $input['input_popup'] ) {
							$input['popup'] = array (
								"text" => $input['input_popup']
							)
						};
					}

					$subArray[] = $input;
					
				break;
				
				case 3:
					
					$text = array_keys(array_column($this->_roiText, 'text_id'),$holderArray['reference_id']);
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

					$subArray[] = $text;
					
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
					
					$dropdownChoices = array_keys(array_column($this->_roiDropdownChoices, 'dropdown_holder_id'),$holderArray['reference_id']);
					if($dropdownChoices) {

						$dropdownTotalChoices = array();
						
						foreach($dropdownChoices as $choice){
							
							$dropdownChoice = $this->_roiDropdownChoices[$choice];
							$dropdownChoice = [
								"value" => $dropdownChoice['dropdown_value'],
								"text" => $dropdownChoice['dropdown_text']
							];
							
							$dropdownTotalChoices[] = $dropdownChoice;
						}
					}
					
					$dropdown = array_keys(array_column($this->_roiDropdown, 'dropdown_id'),$holderArray['reference_id']);
					if($dropdown) {
						
						$dropdown = $this->_roiDropdown[$dropdown[0]];					

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
					}
					
					$subArray[] = $dropdown;

				break;
				
				case 6:
				
					$table = array_keys(array_column($this->_roiRowTables, 'rowtable_id'),$holderArray['reference_id']);
					if($table) {
						
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
											$cellArray['colspan'] = $cellAttributes['colspan']
										}
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
							"headers" => $table['table_headers'],
							"rows" => $rowsArray							
						];
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
								
						$graphArray = [
							"type" => 'graph',
							"id" => $masterGraph['chart_id']
						];
					
					};
					
					$subArray[] = $graphArray;
				
				break;
			}
		}
		//print_r($subArray);
		return $subArray;
	}
}

?>