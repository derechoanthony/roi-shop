<?php

	// Establish connection to the database
	
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/db_connection.php");
	
	if( $_GET['action'] == 'getroiarray' ) {
		
		$roiBuilder = new RoiBuilder($db);
		$roiStructure = array();
		
		$element = $_GET['element'] ? $_GET['element'] : '0';
		
		$roiInfo = $roiBuilder->getRoiInfo();
		$structure_versions = $roiBuilder->getStructureVersions();
		$version_levels = $roiBuilder->getVersionLevels();
		$roi_owner = $roiBuilder->getRoiOwner();
		
		$version_level = array_keys(array_column($structure_versions,'version_id'), $roiInfo['version_id']);
		$roi_link = array_keys(array_column($version_levels,'version_level_id'), $structure_versions[$version_level[0]]['ep_version_level']);
		$roiInfo['roi_full_path'] = '../' . $version_levels[$roi_link[0]]['version_path'] . '?roi=' . $roiInfo['roi_id'];
			
		$roiInfo['formatted_date'] = date('M j Y g:i A', strtotime($roiInfo['dt']));
		$tags = array_unique(array_merge($tags, explode(",",$roiInfo['tags'])));
		$roi_count++;

		$roiStructure['structure'] = $roiBuilder->getRoiArray($element);
		$roiStructure['fields'] = $roiBuilder->ep_fields();
		$roiStructure['navigation'] = $roiBuilder->getRoiNavigation();
		$roiStructure['filters'] = $roiBuilder->ep_element_filters();
		$roiStructure['roiTemplate'] = $roiBuilder->getRoiTemplate();
		$roiStructure['roiInfo'] = $roiInfo;
		$roiStructure['roiOwner'] = $roi_owner;

		echo json_encode($roiStructure);
	}
	
	if( $_GET['action'] == 'getchildren' ) {
		
		$roiBuilder = new RoiBuilder($db);
		$roiStructure = array();
		
		$element = $_GET['element'] ? $_GET['element'] : '0';

		$roiStructure['structure'] = $roiBuilder->getRoiArray($element);

		echo json_encode($roiStructure);
	}
	
	if( $_GET['action'] == 'getSFDCconnection' ) {
		
		$sql = "SELECT code FROM integration
				WHERE userid = ( 
					SELECT user_id FROM ep_created_rois
					WHERE roi_id = :roi
				)
				AND element = 'sfdc'";
			
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$sfdc_code = $stmt->fetch();

		ini_set('max_execution_time', 300);
		
		$pageStart = 1;
		$returnedItemsArray = array();		
		
		do {
			$curl = curl_init();
			$header = array();
			$header[] = 'Authorization: Element '. $sfdc_code['code'] .', User eMYXjLZ2v/DwUYYc+NnJ/MISDJ9nr/qvsBhls4+K8Dw=';
			
			curl_setopt_array( $curl, array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_URL => 'https://api.cloud-elements.com/elements/api-v2/hubs/crm/opportunities?orderBy=id%20desc&page=' . $pageStart,
				CURLOPT_SSL_VERIFYPEER => true,
				CURLOPT_CAINFO => dirname(__FILE__) . '/cacert.pem',
				CURLOPT_HTTPHEADER => $header,
				CURLOPT_HEADER => 1
			));
			
			$resp = curl_exec($curl);
			$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
			$header = substr($resp, 0, $header_size);
			$body = substr($resp, $header_size);
			curl_close($curl);

			$pos = strpos($header, 'Elements-Returned-Count:');
			$posEnd = strpos($header, 'Server:');
			
			$returnedCount = substr($header, $pos + 25, $posEnd - ($pos + 25) );
			$returnedItemsArray = array_merge($returnedItemsArray, json_decode($body));
			$pageStart++;
		} while ( $returnedCount == 200 ); 
		
		echo json_encode($returnedItemsArray);
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
		
		public function getRoiInfo() {

			$sql = "SELECT * FROM ep_created_rois 
					LEFT JOIN roi_structure_versions
					ON ep_created_rois.roi_version_id = roi_structure_versions.version_id
					WHERE roi_id = :roi;";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiInfo = $stmt->fetch(PDO::FETCH_ASSOC);
			
			return $RoiInfo;			
		}
		
		public function getRoiOwner(){
			
			$sql = "SELECT username, phone, first_name, last_name, currency FROM roi_users
					WHERE user_id = (
						SELECT user_id FROM ep_created_rois
						WHERE roi_id = :roi
					);";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiOwner = $stmt->fetch(PDO::FETCH_ASSOC);
			return $RoiOwner;			
		}
		
		public function getRoiTemplate(){
			
			$sql = "SELECT * FROM roi_companies
					WHERE company_id = (
						SELECT company_id FROM roi_company_structures
						WHERE structure_id = (
							SELECT structure_id FROM roi_structure_versions
							WHERE version_id = (
								SELECT roi_version_id FROM ep_created_rois
								WHERE roi_id = :roi							
							)
						)
					);";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiTemplate = $stmt->fetch(PDO::FETCH_ASSOC);
			return $RoiTemplate;			
		}
		
		public function getStructureVersions() {

			$sql = "SELECT * FROM roi_structure_versions;";
						
			$stmt = $this->_db->prepare($sql);
			$stmt->execute();
			$structure_versions = $stmt->fetchall(PDO::FETCH_ASSOC);
				
			return $structure_versions;		
		}
		
		public function getVersionLevels() {

			$sql = "SELECT * FROM roi_version_levels;";
						
			$stmt = $this->_db->prepare($sql);
			$stmt->execute();
			$version_levels = $stmt->fetchall(PDO::FETCH_ASSOC);
				
			return $version_levels;		
		}		
		
		public function ep_fields(){
			
			$sql = "SELECT * FROM a_fields
					WHERE version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					)";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$ep_fields = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			$sql = "SELECT * FROM a_created_fields
					WHERE roi_id = :roi";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$ep_created_fields = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			$sql = "SELECT * FROM a_choices
					WHERE version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					)";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$field_choices = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			for($i=0; $i < count($ep_created_fields); $i++){
				$field = $ep_created_fields[$i]['el_field_name'];
				
				$key_exists = false;
				for($j = 0; $j < count($ep_fields); $j++){
					if ($ep_fields[$j]['el_field_name'] == $field){
						$key_exists = true;
					}
				}
				
				if (!$key_exists){
					$ep_fields[] = $ep_created_fields[$i];
				}
			}
			
			if(!empty($ep_created_fields)){ 
				
				$j = count($ep_fields);
				for($i = 0; $i < $j; $i++){
					$field = $ep_fields[$i];
					
					$field_key = array_keys(array_column($ep_created_fields,'el_field_name'), $field['el_field_name']);
					$array = $ep_created_fields[$field_key[0]];
					
					$ep_fields[$i] = array_merge((array)$ep_fields[$i], (array)$array);
				}
				
				$j = count($ep_fields);
				for($i = 0; $i < $j; $i++){
					$field = $ep_fields[$i];
					
					$choice_keys = array_keys(array_column($field_choices,'choice_id'), $field['choice_id']);
					foreach($choice_keys as $choice){
						$ep_fields[$i]['choices'][] = $field_choices[$choice];
					}				
				}
				
				return $ep_fields;
			}
			
			$j = count($ep_fields);
			for($i = 0; $i < $j; $i++){
				$field = $ep_fields[$i];
				
				$choice_keys = array_keys(array_column($field_choices,'choice_id'), $field['choice_id']);
				foreach($choice_keys as $choice){
					$ep_fields[$i]['choices'][] = $field_choices[$choice];
				}				
			}
			
			return $ep_fields;			
		}
		
		public function ep_elements(){
			
			$sql = "SELECT * FROM a_element_holders
					WHERE version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					) ORDER BY el_pos";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$ep_elements = $stmt->fetchall(PDO::FETCH_ASSOC);
			return $ep_elements;			
		}
		
		public function ep_element_filters(){
			
			$sql = "SELECT * FROM a_element_filters
					WHERE version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					);";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$ep_element_filters = $stmt->fetchall(PDO::FETCH_ASSOC);
			return $ep_element_filters;			
		}
		
		public function ep_text_fields(){
			
			$sql = "SELECT * FROM a_text_fields
					WHERE version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					)";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$ep_text_fields = $stmt->fetchall(PDO::FETCH_ASSOC);
			return $ep_text_fields;			
		}
		
		public function ep_video_fields(){
			
			$sql = "SELECT * FROM a_video_fields
					WHERE version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					)";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$ep_video_fields = $stmt->fetchall(PDO::FETCH_ASSOC);
			return $ep_video_fields;			
		}
		
		public function ep_input_fields(){
			
			$sql = "SELECT * FROM a_input_fields
					WHERE version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					)";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$ep_input_fields = $stmt->fetchall(PDO::FETCH_ASSOC);
			return $ep_input_fields;			
		}
		
		public function ep_textarea_fields(){
			
			$sql = "SELECT * FROM a_textarea_fields
					WHERE version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					)";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$ep_textarea_fields = $stmt->fetchall(PDO::FETCH_ASSOC);
			return $ep_textarea_fields;			
		}

		public function ep_select_fields(){
			
			$sql = "SELECT * FROM a_select_fields
					WHERE version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					)";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$ep_select_fields = $stmt->fetchall(PDO::FETCH_ASSOC);
			return $ep_select_fields;			
		}
		
		public function ep_choices(){
			
			$sql = "SELECT * FROM a_choices
					WHERE version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					)";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$field_choices = $stmt->fetchall(PDO::FETCH_ASSOC);
			return $field_choices;			
		}
		
		public function ep_table_fields(){

			$sql = "SELECT * FROM a_table_fields
					WHERE version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					)";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$ep_table_fields = $stmt->fetchall(PDO::FETCH_ASSOC);
			return $ep_table_fields;			
		}
		
		public function ep_table_columns(){

			$sql = "SELECT * FROM a_table_columns
					WHERE version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					) ORDER BY el_pos";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$ep_table_columns = $stmt->fetchall(PDO::FETCH_ASSOC);
			return $ep_table_columns;			
		}

		public function ep_table_cells(){

			$sql = "SELECT field_column_id, field_row_id, f_data_type, el_field_name FROM a_table_cell_fields
					WHERE version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					) UNION	SELECT field_column_id, field_row_id, f_data_type, el_field_name FROM a_table_custom_cells WHERE roi_id = :roi
					ORDER BY field_row_id;";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$ep_table_cells = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $ep_table_cells;			
		}
		
		public function ep_table_headers(){

			$sql = "SELECT * FROM a_table_header_fields
					WHERE version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					) ORDER BY el_pos";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$ep_table_headers = $stmt->fetchall(PDO::FETCH_ASSOC);
			return $ep_table_headers;			
		}
		
		public function ep_table_filters(){

			$sql = "SELECT * FROM a_table_filters
					WHERE version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					);";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$ep_table_headers = $stmt->fetchall(PDO::FETCH_ASSOC);
			return $ep_table_headers;			
		}
		
		public function ep_table_colgroups(){

			$sql = "SELECT * FROM a_table_colgroups
					WHERE version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					);";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$ep_table_colgroups = $stmt->fetchall(PDO::FETCH_ASSOC);
			return $ep_table_colgroups;			
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
		
		public function getRoiArray($element) {
			
			$elements = array();
			$elements['ep_fields'] = $this->ep_fields();
			$elements['ep_elements'] = $this->ep_elements();
			$elements['ep_text_fields'] = $this->ep_text_fields();
			$elements['ep_video_fields'] = $this->ep_video_fields();
			$elements['ep_input_fields'] = $this->ep_input_fields();
			$elements['ep_textarea_fields'] = $this->ep_textarea_fields();
			$elements['ep_select_fields'] = $this->ep_select_fields();
			$elements['ep_table_fields'] = $this->ep_table_fields();
			$elements['ep_choices'] = $this->ep_choices();
			$elements['ep_table_columns'] = $this->ep_table_columns();
			$elements['ep_table_cells'] = $this->ep_table_cells();
			$elements['ep_table_headers'] = $this->ep_table_headers();
			$elements['ep_table_filters'] = $this->ep_table_filters();
			$elements['ep_table_colgroups'] = $this->ep_table_colgroups();

			return $this->getChildren($element, $elements);
		}
		
		public function mergeElementOptions($elementArray, $all_elements){
			
			$ep_fields = $all_elements['ep_fields'];
			$ep_elements = $all_elements['ep_elements'];
			$ep_text = $all_elements['ep_text_fields'];
			$ep_video = $all_elements['ep_video_fields'];
			$ep_input = $all_elements['ep_input_fields'];
			$ep_textarea = $all_elements['ep_textarea_fields'];
			$ep_select = $all_elements['ep_select_fields'];
			$ep_table_fields = $all_elements['ep_table_fields'];
			$ep_choices = $all_elements['ep_choices'];
			$ep_table_columns = $all_elements['ep_table_columns'];
			$ep_table_headers = $all_elements['ep_table_headers'];
			$ep_table_cells = $all_elements['ep_table_cells'];
			$ep_table_filters = $all_elements['ep_table_filters'];
			$ep_table_colgroups = $all_elements['ep_table_colgroups'];			

			switch($elementArray['el_type']){
				case 'text':
					$ep_text_field = array_keys(array_column($ep_text,'el_id'), $elementArray['el_id']);
					$array = $ep_text[$ep_text_field[0]];
					if(!empty($array)){
						$elementArray = array_merge($elementArray, $array);
					}
					break;
						
				case 'table':
					$ep_table_field = array_keys(array_column($ep_table_fields,'el_id'), $elementArray['el_id']);
					$array = $ep_table_fields[$ep_table_field[0]];
					$elementArray = array_merge($elementArray, $array);
						
					$table_column_keys = array_keys(array_column($ep_table_columns,'table_id'), $elementArray['el_id']);
					foreach($table_column_keys as $key){
						$table_header_keys = array_keys(array_column($ep_table_headers,'column_id'), $ep_table_columns[$key]['el_id']);
						foreach($table_header_keys as $header_key){
							$ep_table_columns[$key]['headers'][] = $ep_table_headers[$header_key];
						}
						
						$table_cell_keys = array_keys(array_column($ep_table_cells,'field_column_id'), $ep_table_columns[$key]['column_tag']);
						foreach($table_cell_keys as $cell_key){
							$ep_table_columns[$key]['cells'][] = $ep_table_cells[$cell_key];
						}

						$choice_keys = array_keys(array_column($ep_choices,'choice_id'), $ep_table_columns[$key]['choice_id']);
						foreach($choice_keys as $choice){
							$ep_table_columns[$key]['choices'][] = $ep_choices[$choice];
						}							
						$elementArray['columns'][] = $ep_table_columns[$key];
					}
					
					$table_colgroup_keys = array_keys(array_column($ep_table_colgroups,'el_id'), $elementArray['el_id']);
					foreach($table_colgroup_keys as $key){						
						$elementArray['colgroup'][] = $ep_table_colgroups[$key];
					}
					
					$table_filter_keys = array_keys(array_column($ep_table_filters,'table_id'), $elementArray['el_id']);
					foreach($table_filter_keys as $key){						
						$elementArray['filters'][] = $ep_table_filters[$key];
					}
					break;				
						
				case 'video':
					$ep_video_field = array_keys(array_column($ep_video,'el_id'), $elementArray['el_id']);
					$array = $ep_video[$ep_video_field[0]];
					$elementArray = array_merge($elementArray, $array);
					break;
						
				case 'input':
					$ep_input_field = array_keys(array_column($ep_input,'el_id'), $elementArray['el_id']);
					$array = $ep_input[$ep_input_field[0]];
					if(!empty($array)){
						$elementArray = $array + $elementArray;
					}
						
					$input_field_keys = array_keys(array_column($ep_fields,'el_field_name'), $array['el_field_name']);
					$array = $ep_fields[$input_field_keys[0]];
					if(!empty($array)){
						$elementArray = $array + $elementArray;
					}
					break;
					
				case 'textarea':
					$ep_textarea_keys = array_keys(array_column($ep_textarea,'el_id'), $elementArray['el_id']);
					$array = $ep_textarea[$ep_textarea_keys[0]];
					if(!empty($array)){
						$elementArray = $array + $elementArray;
					}
						
					$textarea_field_keys = array_keys(array_column($ep_fields,'el_field_name'), $array['el_field_name']);
					$array = $ep_fields[$textarea_field_keys[0]];
					if(!empty($array)){
						$elementArray = $array + $elementArray;
					}
					break;
					
				case 'select':
					$ep_select_keys = array_keys(array_column($ep_select,'el_id'), $elementArray['el_id']);
					$array = $ep_select[$ep_select_keys[0]];
					if(!empty($array)){
						$elementArray = $array + $elementArray;
					}
						
					$ep_select_keys = array_keys(array_column($ep_fields,'el_field_name'), $array['el_field_name']);
					$array = $ep_fields[$ep_select_keys[0]];
					if(!empty($array)){
						$elementArray = $array + $elementArray;
					}
					break;
			}
			
			return $elementArray;
		}
		
		public function getChildren($parent, $all_elements){

			$childArray = array();
			$ep_fields = $all_elements['ep_fields'];
			$ep_elements = $all_elements['ep_elements'];
			$ep_text = $all_elements['ep_text_fields'];
			$ep_video = $all_elements['ep_video_fields'];
			$ep_input = $all_elements['ep_input_fields'];
			$ep_table_fields = $all_elements['ep_table_fields'];
			$ep_table_rows = $all_elements['ep_table_rows'];
			$ep_table_cells = $all_elements['ep_table_cells'];
			$ep_table_columns = $all_elements['ep_table_columns'];
			$ep_table_headers = $all_elements['ep_table_headers'];
			
			$elements = array_keys(array_column($ep_elements,'el_parent'), $parent);
			foreach($elements as $element) {
				
				$elementArray = $ep_elements[$element];
				$elementArray = $this->mergeElementOptions($elementArray, $all_elements);
				
				$children 	  = array_keys(array_column($ep_elements,'el_parent'), $elementArray['el_id']);
				if( count($children) > 0 ){
					
					$elementArray['children'] = $this->getChildren($elementArray['el_id'], $all_elements);
				};
				
				$childArray[] = $elementArray;
			}

			return $childArray;
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
		
		public function getRoiNavigation() {
			
			$navigation = $this->buildNavigation(0);
			return $navigation;
		}
	}

?>