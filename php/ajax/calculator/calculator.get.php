<?php

	// Establish connection to the database
	
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/db_connection_noutf.php");
	
	if( $_GET['action'] == 'getroiarray' ) {
		
		$roiBuilder = new RoiBuilder($db);
		$roiStructure = array();

		$roiStructure['structure'] = $roiBuilder->getRoiArray();
		$roiStructure['navigation'] = $roiBuilder->getRoiNavigation();
		$roiStructure['contributors'] = $roiBuilder->getRoiContributors();
		
		$roiInfo = $roiBuilder->getRoiInfo();
		$structure_versions = $roiBuilder->getStructureVersions();
		$version_levels = $roiBuilder->getVersionLevels();
		$pdf_structure = $roiBuilder->getRoiPdfSetup();
		$roi_owner = $roiBuilder->getRoiOwner();
		
		$version_level = array_keys(array_column($structure_versions,'version_id'), $roiInfo['version_id']);
		$roi_link = array_keys(array_column($version_levels,'version_level_id'), $structure_versions[$version_level[0]]['ep_version_level']);
		$roiInfo['roi_full_path'] = '../' . $version_levels[$roi_link[0]]['version_path'] . '?roi=' . $roiInfo['roi_id'];
			
		$roiInfo['formatted_date'] = date('M j Y g:i A', strtotime($roiInfo['dt']));
		$tags = array_unique(array_merge($tags, explode(",",$roiInfo['tags'])));
		$roi_count++;

		$roiStructure['roiInfo'] = $roiInfo;
		$roiStructure['pdfSetup'] = $pdf_structure;
		$roiStructure['roiOwner'] = $roi_owner;
		$roiStructure['roiTemplate'] = $roiBuilder->getRoiTemplate();
		$roiStructure['roiSections'] = $roiBuilder->compileRoiSections();
		$roiStructure['roiElements'] = $roiBuilder->getRoiCustomElements();
		
		$roiStoredArray = $roiBuilder->getStoredRoiArray();
		if( $roiStoredArray ){
			$storedArray = gzuncompress($roiStoredArray);
			$storedArray = unserialize( base64_decode($storedArray) );
			$roiStructure['storedArray'] = json_decode($storedArray);
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
		
		public function getRoiCustomElements(){
			
			$sql = "SELECT * FROM ep_elements
					WHERE el_section_id IN (
						SELECT ID FROM compsections
						WHERE compID = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = :roi
						)
					) ORDER BY el_pos;";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiElements = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $RoiElements;
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
		
		public function hiddenEntities(){

			$sql = "SELECT * FROM hidden_entities
					WHERE type = 'section' AND roi = :roi;";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$HiddenEntities = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $HiddenEntities;	
		}
		
		public function getRoiContributors() {
			
			$sql = "SELECT id, username FROM createdwith
					WHERE roi=:roi";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$roiContributors = $stmt->fetchall(PDO::FETCH_ASSOC);
			return $roiContributors;	
		}
		
		public function getStoredRoiArray() {
			
			$sql = "SELECT roi_options FROM ep_created_roi_array
					WHERE roi_id = :roi
					ORDER BY array_id DESC LIMIT 1";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$StoredArray = $stmt->fetch();
			
			return $StoredArray['roi_options'];					
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
		
		public function getRoiSections() {

			$sql = "SELECT * FROM compsections
					WHERE compID = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					)
					ORDER BY Position";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiSections = $stmt->fetchall(PDO::FETCH_ASSOC);
			return $RoiSections;	
		}
		
		public function getRoiPdfs(){
			
			$sql = "SELECT * FROM pdf_specs
					WHERE roi = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					);";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi',$_GET['roi'],PDO::PARAM_INT);
			$stmt->execute();
			$RoiPdfs = $stmt->fetchall(PDO::FETCH_ASSOC);
			return $RoiPdfs;			
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
		
		public function getRoiTableHeaders() {

			$sql = "SELECT * FROM ep_elements
					WHERE el_version = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi					
					)
					AND el_type = 'tblheaders'
					ORDER BY el_pos, el_id";
	
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiTableHeaders = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $RoiTableHeaders;		
		}
		
		public function getRoiTableColgroup() {
			
			$sql = "SELECT * FROM ep_elements
					WHERE el_version = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi					
					)
					AND el_type = 'colgroup'
					ORDER BY el_pos, el_id";
	
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiTableHeaders = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $RoiTableHeaders;
		}
		
		public function getRoiTableData() {

			$sql = "SELECT * FROM ep_elements
					WHERE el_version = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi					
					)
					AND el_type = 'tblrow'
					ORDER BY el_pos, el_id";
	
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiTableData = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $RoiTableData;		
		}
		
		public function getEntryFields(){
			
			$sql = "SELECT * FROM entry_fields
					WHERE roiID = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					)
					ORDER BY position";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiElements = $stmt->fetchall();
			return $RoiElements;
		}
		
		public function getRoiValues(){
			
			$sql = "SELECT value, entryid FROM roi_values
					WHERE roiid = :roi
					ORDER BY dt DESC, sessionid DESC";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiValues = $stmt->fetchall();
			return $RoiValues;			
		}
		
		public function getRoiGraphs(){

			$sql = "SELECT html, sectionid FROM graphs
					WHERE sectionid IN(
						SELECT ID FROM compsections
						WHERE compID = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = :roi
						)
					)
					ORDER BY position";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$RoiGraphs = $stmt->fetchall();
			return $RoiGraphs;			
		}
		
		public function getRoiSpecifics(){
			
			$sql = "SELECT * FROM comp_specs
					WHERE compID = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					)";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$roiSpecs = $stmt->fetch();
			return $roiSpecs;			
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
		
		public function compileRoiSections() {
			$roiSections = $this->getRoiSections();
			$hidden_entities = $this->hiddenEntities();
			
			foreach($roiSections as $section){
				$hidden = array_keys(array_column($hidden_entities,'entity_id'), $section['ID']);
				$section['included'] = count($hidden) ? 0 : 1;
				$sections[] = $section;
			}
			
			return $sections;
		}
		
		public function getRoiArray() {

			$roiInfo = $this->getRoiInfo();
			switch($roiInfo['ep_version_level']){
				case '7':
					$roiElements = $this->getRoiElements();
					$elementChoices = $this->getElementChoices();
					$tableHeaders = $this->getRoiTableHeaders();
					$colGroups = $this->getRoiTableColgroup();
					$tableData = $this->getRoiTableData();
					$chartOptions = $this->getChartOptions();
					$chartSeries = $this->getChartSeries();
					$chartxAxis = $this->getChartxAxis();
					$chartyAxis = $this->getChartyAxis();
					$seriesEquations = $this->getSeriesEquations();
					$roiBuild = $this->getChildren(0, $roiElements, $elementChoices, $tableHeaders, $colGroups, $tableData, $chartOptions, $chartSeries, $chartxAxis, $chartyAxis, $seriesEquations);
					return $roiBuild;
				break;
					
				case '9':
					$roiSpecs = $this->getRoiSpecifics();
					$roiSections = $this->getRoiSections();
					$roiElements = $this->getEntryFields();
					$roiValues = $this->getRoiValues();
					$roiGraphs = $this->getRoiGraphs();
					$hidden_entities = $this->hiddenEntities();
					$custom_elements = $this->getRoiCustomElements();
					$element_choices = $this->getElementChoices();
					$tableHeaders = $this->getRoiTableHeaders();
					$tableData = $this->getRoiTableData();
					$chartOptions = $this->getChartOptions();
					$chartSeries = $this->getChartSeries();
					$chartxAxis = $this->getChartxAxis();
					$chartyAxis = $this->getChartyAxis();
					$seriesEquations = $this->getSeriesEquations();
					
					$containerArray = array(
						'el_type' 		=> 	'holder',
						'el_class'	=>	'roi-holder'
					);

					$dashboardPods = array();
					
					$dashboardPods[] = array(
									'el_type' => 'text',
									'el_text' => '<div class="col-lg-12">
													<div class="ibox-content">
														<h3 style="font-size: 18px; font-weight: 700;">Select a section below to review your ROI</h3>
														<p style="font-size: 16px;">
															To calculate your return on investment, begin with the first section below. The information 
															entered therein will automatically populate corresponding fields in the other sections. You 
															will be able to move from section to section to add and/or adjust values to best reflect your 
															organization and process. To return to this screen, click the ROI Dashboard button to the left.
														</p>
													</div>
												</div>'
								);
					
					foreach($roiSections as $section){
						
						$section_total = array();
						if($section['formula']){
							$section_total = array(
								array(
									'el_type' => 'text',
									'el_text' => '<h1 class="txt-right pod-total section-total txt-money" data-format="($0,0)" data-cell="SECTIONTOT'. $section['ID'] .'" data-formula="('. $section['formula'] . ' * ' . $roiSpecs['retPeriod'] .' * ( 1 - CON' . $section['ID'] . ') * ( ( ' . $roiSpecs['retPeriod'] . ' * 12 - IMP1 ) / ( ' . $roiSpecs['retPeriod'] . ' * 12 ) ) * SECINC' . $section['ID'] . ' )"></h1>'
								),
								array(
									'el_type' => 'holder',
									'el_class' => 'row row-padding',
									'children' => array(
										array(
											'el_type' => 'holder',
											'el_class' => 'row value-holder',
											'children' => array(
												array(
													'el_type' => 'slider',
													'el_stacked' => 1,
													'el_text' => 'Conservative Factor:',
													'el_format' => '0,0%',
													'el_field_name' => 'CON' . $section['ID']
												)
											)
										)
									)
								)
							);
						} elseif ($section['grandtotal']){
							$section_total = array(
								array(
									'el_type' => 'text',
									'el_text' => '<h1 class="txt-right pod-total section-total txt-money" data-format="($0,0)" data-formula="TOTALSAVINGS1"></h1>'						
								)
							);
						}
						
						$header = array(
									array(
										'el_type' => 'text',
										'el_text' => '<div class="row">
													<h2 class="col-lg-10 font-bold no-margins pod-header rs-equalize-pod-header">
														<a class="smooth-scroll section-navigator" href="#section'.$section['ID'].'">'.$section['Title'].'</a>	
													</h2>
												</div>'
									)
								);
								
						$sectionChildren = array_merge($header, $section_total);
						
						$hidden = array_keys(array_column($hidden_entities,'entity_id'), $section['ID']);
						
						$pod = array(
							'el_type' => 'holder',
							'el_class' => 'col-lg-3 rs-include-'.$section['ID'],
							'children' => array(
								array(
									'el_type' => 'holder',
									'el_class' => 'rs-equalize-pods widget white-bg',
									'children' => array(
										array(
											'el_type' => 'holder',
											'el_class' => 'pm-row',
											'children' => $sectionChildren
										),
										array(
											'el_visibility' => 0,
											'el_type' => 'holder',
											'children' => array(
												array(
													'el_type' => 'input',
													'el_field_name' => 'SECINC' . $section['ID'],
													'el_value' => ( count($hidden) ? 0 : 1 )
												)
											)
										)
									)
								)
							)
						);
						
						if (count($hidden)) {
							$pod['el_visibility'] = 0;
						}
						
						$dashboardPods[] = $pod;
					}			
					
					$dashboard = array(
						array(
							'el_type' => 'text',
							'el_text' => '<div id="dash" class="row border-bottom white-bg dashboard-header"><div class="col-lg-12"><h1 style="margin-bottom: 20px;">ROI Dashboard | '.$roiSpecs['retPeriod'].' Year Projection <span class="pull-right pod-total section-total grand-total txt-money" data-format="($0,0)" data-formula="TOTALSAVINGS1">$0</span></h1></div></div>'
						),
						array(
							'el_type' => 'holder',
							'el_class' => 'row border-bottom gray-bg dashboard-header',
							'children' => $dashboardPods
						)
					);
					
					$containerArray['children'] = $dashboard;
					
					foreach($roiSections as $section){
						
						if ($section['custom_build'] == 0) {

							$container = array(
								'el_type' => 'holder',
								'el_class' => 'rs-include-'.$section['ID'],
								'children' => array(
									array(
										'el_type' => 'text',
										'el_text' => '<div id="section'.$section['ID'].'" class="row border-bottom white-bg dashboard-header"><div class="col-lg-12"><h1 style="margin-bottom: 20px;">'.$section['Title']. ( $section['formula'] ? '<span class="pull-right pod-total section-total grand-total txt-money" data-format="($0,0)" data-formula="('. $section['formula'] . ' * ' . $roiSpecs['retPeriod'] .' * ( 1 - CON' . $section['ID'] . ') * ( ( ' . $roiSpecs['retPeriod'] . ' * 12 - IMP1 ) / ( ' . $roiSpecs['retPeriod'] . ' * 12 ) ) )">$0</span>' : ( $section['grandtotal'] ? '<span class="pull-right pod-total section-total grand-total txt-money" data-format="($0,0)" data-formula="TOTALSAVINGS1">$0</span>' : '' ) ) . '</h1></div></div>'						
									)
								)
							);
							$hidden = array_keys(array_column($hidden_entities,'entity_id'), $section['ID']);
							if (count($hidden)) {
								$container['el_visibility'] = 0;
							}				
							$containerArray['children'][] = $container;
							
							$section_inputs = [];
							
							$video_array = '';
							if($section['Video']){
								$video_array = array(
									'el_type' => 'holder',
									'el_class' => 'col-xs-12 col-sm-12 col-md-12 col-lg-4',
									'children' => array(
										array(
											'el_type' => 'video',
											'el_src' => $section['Video']
										)
									)
								);
							}
							
							$container = array(
								'el_type' => 'holder',
								'el_class' => 'row border-bottom gray-bg dashboard-header rs-include-'.$section['ID'],
								'children' => array(
									array(
										'el_type' => 'holder',
										'el_class' => 'bottom-border white-bg dashboard-header col-xs-12 col-sm-12 col-md-12 col-lg-12',
										'children' => array(
											array(
												'el_type' => 'holder',
												'el_class' => $section['Video'] ? 'col-xs-12 col-sm-12 col-md-12 col-lg-8' : 'col-xs-12 col-sm-12 col-md-12 col-lg-12',
												'children' => array(
													array(
														'el_type' => 'text',
														'el_text' => $section['Caption']
													)
												)
											),
											$video_array
										)
									)
								)
							);

							$hidden = array_keys(array_column($hidden_entities,'entity_id'), $section['ID']);
							if (count($hidden)) {
								$container['el_visibility'] = 0;
							}				
							$containerArray['children'][] = $container;
							
							$section_inputs = [];
							$section_elements = array_keys(array_column($roiElements,'sectionName'), $section['ID']);
							foreach($section_elements as $section_element){
								
								$inputs = $roiElements[$section_element]['annual'] > 0 ? $roiSpecs['retPeriod'] : 1;
								for($i=1; $i<=$inputs; $i++){
									$el_enabled = 1;
									$el_text = $roiElements[$section_element]['Title'] . ( $inputs > 1 ? ' - Year ' . $i : '' );
									$entry_values = array_keys(array_column($roiValues,'entryid'), $roiElements[$section_element]['ID']);
									
									$el_value = null;
									if(count($entry_values)){
										$el_value = $roiValues[$entry_values[0]]['value'];
									}
									
									switch($roiElements[$section_element]['Type']){
										case '0': 
											$el_type = 'input';
											$el_class = 'input-holder col-xs-12 col-sm-12 col-md-12 col-lg-4';
											$el_label_class = 'control-label col-xs-12 col-sm-12 col-md-12 col-lg-8';
											break;
										case '1':
											$el_type = 'input';
											$el_class = 'input-holder col-xs-12 col-sm-12 col-md-12 col-lg-4';
											$el_label_class = 'control-label col-xs-12 col-sm-12 col-md-12 col-lg-8';
											$el_enabled = 0;
											break;						
										case '2':
											$el_type = 'textarea'; 
											$el_class = 'col-lg-5 col-md-5 col-sm-5';
											$el_label_class = 'control-label  col-lg-7 col-md-7 col-sm-7';
											break;
										case '3': $el_type = 'dropdown'; break;
										case '4': $el_type = 'select'; break;
										case '5': $el_type = 'select'; break;
										case '6': $el_type = 'select'; break;
										case '7': $el_type = 'select'; break;
										case '11': $el_type = 'slider'; break;
										case '12': $el_type = 'slider'; break;
										case '13':
											$el_type = 'text';
											$el_text = '<div class="form-group"><div class="col-md-12 col-lg-11 subsection-header"><h5>'.$roiElements[$section_element]['Title'].'</h5></div></div>';
											break;
										case '14':
											$el_type = 'text';
											$el_text = '<div class="table-responsive" style="border:2px solid #ddd;">
															<table id="summary-table" class="table table-hover" style="margin-bottom:0;">
																<thead>
																	<tr>
																		<th></th>';
																		
											for($i=1; $i<$roiSpecs['retPeriod'] + 1; $i++){
													$el_text .= '<th>Year ' . $i . '</th>';
											}

											$el_text .= 				'<th>Total</th>
																	</tr>
																</thead>
																<tbody>';
														
											foreach($roiSections as $section){
												$hidden = array_keys(array_column($hidden_entities,'entity_id'), $section['ID']);									
												if($section['formula']){
													$el_text .= '<tr data-section-id="'.$section['ID'].'" class="rs-include-'.$section['ID'].'"'. ( count($hidden) ? ' style="display: none;"' : '' ).'>
																	<th><p style="margin: 0;" class="smooth-scroll table-scroll" href="#section' . $section['ID'] . '">' . $section['Title'] . '</p></th>';
																
													for($i=1; $i<$roiSpecs['retPeriod'] + 1; $i++){
														$el_text .= '<td class="section-total txt-money" data-format="($0,0)" data-formula="('. $section['formula'] . ' * ( 1 - CON' . $section['ID'] . ') * ( IF( IMP1 >= ' . $i . ' * 12, 0, IF( IMP1 < ( ' . $i . ' - 1 ) * 12, 1, ( ' . $i * 12 . ' - IMP1 ) / 12 ) ) ) )"></td>';
													}
													
													$el_text .= '<td class="section-total txt-money" data-format="($0,0)" data-formula="('. $section['formula'] . ' * ' . $roiSpecs['retPeriod'] .' * ( 1 - CON' . $section['ID'] . ') * ( ( ' . $roiSpecs['retPeriod'] . ' * 12 - IMP1 ) / ( ' . $roiSpecs['retPeriod'] . ' * 12 ) ))"></td>';
													
													$el_text .= '</tr>';
												}
											}
											
											$el_text .= 		'<tr>
																	<th>Cost</th>';
											
											for($i=1; $i<$roiSpecs['retPeriod'] + 1; $i++){
													$el_text .= 	'<td class="section-total cost txt-removed" data-format="($0,0)" data-formula="( ANNUALCOST(' . $i . ') )"></td>';
											}
											
											$el_text .=				'<td class="section-total cost txt-removed" data-format="($0,0)" data-formula="( ANNUALCOST(\'total\') )"></td>
																</tr>';
																
											$el_text .= 		'<tr>
																	<th>Total</th>';
											
											for($i=1; $i<$roiSpecs['retPeriod'] + 1; $i++){
												
												$el_text .= 	'<td class="section-total cost txt-money" data-format="($0,0)" data-formula="( ';
											
												foreach($roiSections as $section){
													if($section['formula']){
														$el_text .=	'SECINC' . $section['ID'] . ' * ( ' . $section['formula'] . ' * ( 1 - CON' . $section['ID'] . ') * ( IF( IMP1 >= ' . $i . ' * 12, 0, IF( IMP1 < ( ' . $i . ' - 1 ) * 12, 1, ( ' . $i * 12 . ' - IMP1 ) / 12 ) ) ) ) + ';
													}
												}
												
												$el_text .= 'ANNUALCOST(' . $i . ') )" data-cell="ANNUALSAVINGS'. $i .'"></td>';
											}
											
											$el_text .= 		'<td class="section-total cost txt-money" data-format="($0,0)" data-cell="TOTALSAVINGS1" data-formula="( ';
											
											foreach($roiSections as $section){
												if($section['formula']){
													$el_text .=	'SECINC' . $section['ID'] . ' * ( '. $section['formula'] . ' * ' . $roiSpecs['retPeriod'] .' * ( 1 - CON' . $section['ID'] . ') * ( ( ' . $roiSpecs['retPeriod'] . ' * 12 - IMP1 ) / ( ' . $roiSpecs['retPeriod'] . ' * 12 ) ) ) + ';
												}									
											}
											
											$el_text .= 'ANNUALCOST(\'total\') )"></td>';
											
											$el_text .= 			'</tr>
																</tbody>
															</table>
														</div>';
											
											break;
										default: $el_type = 'text';
									};
									
									$format_append = '';
									if($roiElements[$section_element]['precision'] > 0){
										$format_append = '[.]';
										for($i=0; $i<$roiElements[$section_element]['precision']; $i++){
											$format_append . '0';
										}
									};
									
									$el_format = '';
									
									switch($roiElements[$section_element]['Format']){
										case '0':
											$el_format = '0,0' . $format_append;
											break;
										case '1':
											$el_format = '$0,0' . $format_append;
											break;
										case '2':
											$el_format = '0,0' . $format_append . '%';
											break;
									}
									
									$input_array = array(
										'el_type' => $el_type,
										'el_text' => $el_text,
										'el_class' => $el_class,
										'el_label_class' => $el_label_class,
										'el_field_name' => 'A' . $roiElements[$section_element]['ID'],
										'el_tooltip' => $roiElements[$section_element]['Tip'],
										'el_append' => $roiElements[$section_element]['append'],
										'el_formula' => $roiElements[$section_element]['formula'],
										'el_enabled' => $el_enabled,
										'el_format' => $el_format,
										'el_value' => $el_value,
										'el_year' => $i,
										'el_cost' => $roiElements[$section_element]['cost']
									);
									
									$section_inputs[] = $input_array;
								}
							}
							
							$annual_total_list = '<ul>';			
							for($i=1; $i<$roiSpecs['retPeriod'] + 1; $i++){
								$annual_total_list .= '<li>Year '. $i .':<span class="pull-right section-total txt-money" data-format="($0,0)" data-formula="('. $section['formula'] . ' * ( 1 - CON' . $section['ID'] . ') * ( IF( IMP1 >= ' . $i . ' * 12, 0, IF( IMP1 < ( ' . $i . ' - 1 ) * 12, 1, ( ' . $i * 12 . ' - IMP1 ) / 12 ) ) ) )">$0</span></li>';
							}
							$annual_total_list .= '<li><hr class="calculation-divider"></li><li>Section Total:<span class="pull-right section-total txt-money" data-format="($0,0)" data-formula="('. $section['formula'] . ' * ' . $roiSpecs['retPeriod'] .' * ( 1 - CON' . $section['ID'] . ') * ( ( ' . $roiSpecs['retPeriod'] . ' * 12 - IMP1 ) / ( ' . $roiSpecs['retPeriod'] . ' * 12 ) ))">$0</span></li>';
							$annual_total_list .= '</ul>';
							
							$section_sidebar = [];
							if($section['formula']){
								$formula_holder = array(
										array(
											'el_type' => 'holder',
											'el_class' => 'ibox float-e-margins',
											'children' => array(
												array(
													'el_type' => 'holder',
													'el_class' => 'ibox-title',
													'children' => array(
														array(
															'el_type' => 'text',
															'el_text' => '<h5 class="col-lg-12">Baseline Totals</h5>'
														)
													)
												),
												array(
													'el_type' => 'holder',
													'el_class' => 'faq-item',
													'children' => array(
														array(
															'el_type' => 'holder',
															'el_class' => 'row',
															'children' => array(
																array(
																	'el_type' => 'holder',
																	'el_class' => 'col-lg-8 col-md-12',
																	'children' => array(
																		array(
																			'el_type' => 'text',
																			'el_text' => '<a class="faq-question collapsed" href="'.$section['ID'].'faq'.$section['ID'].'" data-toggle="collapse" aria-expanded="false">'.$section['Title'].'</a>'
																		)
																	)
																),
																array(
																	'el_type' => 'holder',
																	'el_class' => 'col-lg-4 col-md-12',
																	'children' => array(
																		array(
																			'el_type' => 'text',
																			'el_text' => '<span class="section-total txt-money" data-format="($0,0)" data-formula="('. $section['formula'] . ' * ' . $roiSpecs['retPeriod'] .' * ( 1 - CON' . $section['ID'] . ') * ( ( ' . $roiSpecs['retPeriod'] . ' * 12 - IMP1 ) / ( ' . $roiSpecs['retPeriod'] . ' * 12 ) ) )" style="white-space: no wrap;">$0</span>'
																		)
																	)
																),
																array(
																	'el_type' => 'holder',
																	'el_class' => 'row',
																	'children' => array(
																		array(
																			'el_type' => 'holder',
																			'el_class' => 'col-lg-12 annual-totals',
																			'children' => array(
																				array(
																					'el_type' => 'holder',
																					'el_class' => 'panel-collapse faq-answer collapse in',
																					'children' => array(
																						array(
																							'el_type' => 'text',
																							'el_text' => $annual_total_list
																						),
																						array(
																							'el_type' => 'holder',
																							'el_class' => 'row padding-bottom-10',
																							'children' => array(
																								array(
																									'el_type' => 'slider',
																									'el_stacked' => 1,
																									'el_text' => 'Conservative Factor:',
																									'el_format' => '0,0%',
																									'el_field_name' => 'CON' . $section['ID']
																								)
																							)
																						)
																					)																		
																				)
																			)
																		)
																	)
																)
															)
														)
													)
												)
											)
										)
								);
								
								$section_sidebar = $formula_holder;
							}
							
							if($section['statistics'] == 1){
								$NPV = '';
								for($i=1; $i<$roiSpecs['retPeriod'] + 1; $i++){
									if ($NPV == '') { $NPV .= 'ANNUALSAVINGS' . $i; }
									else { $NPV .= ', ANNUALSAVINGS' . $i; }
								};
								
								$statistics = array(
										array(
											'el_type' => 'holder',
											'el_class' => 'ibox float-e-margins',
											'children' => array(
												array(
													'el_type' => 'holder',
													'el_class' => 'ibox-title',
													'children' => array(
														array(
															'el_type' => 'text',
															'el_text' => '<h5 class="col-lg-12">Baseline Totals</h5>'
														)
													)
												),
												array(
													'el_type' => 'holder',
													'el_class' => 'faq-item',
													'children' => array(
														array(
															'el_type' => 'holder',
															'el_class' => 'row',
															'children' => array(
																array(
																	'el_type' => 'holder',
																	'el_class' => 'col-lg-8',
																	'children' => array(
																		array(
																			'el_type' => 'text',
																			'el_text' => '<a class="faq-question collapsed nohover">Return on Investment</a>'									
																		)
																	)
																),
																array(
																	'el_type' => 'holder',
																	'el_class' => 'col-lg-4',
																	'children' => array(
																		array(
																			'el_type' => 'text',
																			'el_text' => '<div class="pull-right" data-format="(0,0%)" data-formula="( TOTALSAVINGS1 - ANNUALCOST(\'total\') ) / ABS(ANNUALCOST(\'total\'))" data-cell="ROI1">100%</div>'
																		)
																	)
																)
															)
														)
													)
												),
												array(
													'el_type' => 'holder',
													'el_class' => 'faq-item',
													'children' => array(
														array(
															'el_type' => 'holder',
															'el_class' => 'row',
															'children' => array(
																array(
																	'el_type' => 'holder',
																	'el_class' => 'col-lg-8',
																	'children' => array(
																		array(
																			'el_type' => 'text',
																			'el_text' => '<a class="faq-question collapsed nohover">Net Present Value</a>'									
																		)
																	)
																),
																array(
																	'el_type' => 'holder',
																	'el_class' => 'col-lg-4',
																	'children' => array(
																		array(
																			'el_type' => 'text',
																			'el_text' => '<div class="pull-right" data-format="($0,0)" data-formula="NPV( 0.02, '. $NPV .')" data-cell="NPV1">100%</div>'
																		)
																	)
																)
															)
														)
													)
												),
												array(
													'el_type' => 'holder',
													'el_class' => 'faq-item',
													'children' => array(
														array(
															'el_type' => 'holder',
															'el_class' => 'row',
															'children' => array(
																array(
																	'el_type' => 'holder',
																	'el_class' => 'col-lg-7',
																	'children' => array(
																		array(
																			'el_type' => 'text',
																			'el_text' => '<a class="faq-question collapsed nohover">Payback Period</a>'									
																		)
																	)
																),
																array(
																	'el_type' => 'holder',
																	'el_class' => 'col-lg-5',
																	'children' => array(
																		array(
																			'el_type' => 'text',
																			'el_text' => '<div class="pull-right"><span data-format="0,0[.]00" data-formula="( 0 - ANNUALCOST(\'total\') ) / ( ANNUALSAVINGS1 - ANNUALCOST(1) ) * 12 + IMP1" data-cell="PAY1">0</span> months</div>'
																		)
																	)
																)
															)
														)
													)
												)
											)
										)
								);
								
								$section_sidebar = $statistics;
							}
							
							$implementation_period = array(
									'el_type' => 'holder',
									'el_class' => 'ibox float-e-margins',
									'children' => array(
										array(
											'el_type' => 'holder',
											'el_class' => 'faq-item',
											'children' => array(
												array(
													'el_type' => 'holder',
													'el_class' => 'row',
													'children' => array(
														array(
															'el_type' => 'slider',
															'el_stacked' => 1,
															'el_text' => 'Implementation Period:',
															'el_append' => ' months',
															'el_format' => '0,0',
															'el_min' => 0,
															'el_max' => $roiSpecs['retPeriod'] * 12,
															'el_field_name' => 'IMP1'
														)										
													)
												)
											)
										)
									)
							);
							
							if($section_sidebar){
								$section_sidebar[] = $implementation_period;
							} else {
								$section_sidebar = array(
									$implementation_period
								);
							}
							
							$container = array(
								'el_type' => 'holder',
								'el_class' => 'row border-bottom gray-bg dashboard-header rs-include-'.$section['ID'],
								'children' => array(				
									array(
										'el_type' => 'holder',
										'el_class' => 'row',
										'children' => array(
											array(
												'el_type' => 'holder',
												'el_class' => 'col-lg-9 col-md-9 col-sm-12 col-xs-12',
												'children' => array(
													array(
														'el_type' => 'holder',
														'el_class' => 'ibox float-e-margins',
														'children' => array(
															array(
																'el_type' => 'holder',
																'el_class' => 'ibox-content',
																'children' => array(
																	array(
																		'el_type' => 'holder',
																		'el_class' => 'form-horizontal',
																		'children' => $section_inputs
																	)
																)
															)
														)
													)
												)
											),
											array(
												'el_type' => 'holder',
												'el_class' => 'col-lg-3 col-md-3 col-sm-12 col-xs-12',
												'children' => $section_sidebar
											)
										)
									)
								)
							);
							$hidden = array_keys(array_column($hidden_entities,'entity_id'), $section['ID']);
							if (count($hidden)) {
								$container['el_visibility'] = 0;
							}
							
							$containerArray['children'][] = $container;
							
							$graphs = array_keys(array_column($roiGraphs,'sectionid'), $section['ID']);
							
							foreach($graphs as $graph){
								
								foreach($roiSections as $section){
									$hidden = array_keys(array_column($hidden_entities,'entity_id'), $section['ID']);						
									if($section['formula']){
										
										$formulas = array();
										for($i=1; $i<$roiSpecs['retPeriod'] + 1; $i++){
											$formulas[] = '( ' . $section['formula'] . ' * ( 1 - CON' . $section['ID'] . ') * ( IF( IMP1 >= ' . $i . ' * 12, 0, IF( IMP1 < ( ' . $i . ' - 1 ) * 12, 1, ( ' . $i * 12 . ' - IMP1 ) / 12 ) ) ) )';
										}
										
										$series[] = array('name' => $section['Title'], 'section' => $section['ID'], 'included' => (count($hidden) ? '0' : '1'), 'data' => array(100,200), 'formulas' => $formulas);
									}
								}
								
								for($i=1; $i<$roiSpecs['retPeriod'] + 1; $i++){
									$costformulas[] = 'ABS(ANNUALCOST(' . $i . '))';
								}
								$series[] = array('name' => 'Cost', 'included' => 1, 'data' => array(5,15), 'formulas' => $costformulas);
								
								$xAxis = array();
								for($i=1; $i<$roiSpecs['retPeriod'] + 1; $i++){
									$xAxis[] = 'Year ' . $i;
								}
							
								$graph_container = array(
									'el_type' => 'holder',
									'el_class' => 'row border-bottom gray-bg dashboard-header',
									'children' => array(
										array(
											'el_type' => 'holder',
											'el_class' => 'col-lg-12',
											'children' => array(
												array(
													'el_type' => 'holder',
													'el_class' => 'row',
													'children' => array(
														array(
															'el_type' => 'holder',
															'el_class' => 'col-md-12 col-sm-12 col-xs-12',
															'children' => array(
																array(
																	'el_type' => 'holder',
																	'el_class' => 'ibox float-e-margins',
																	'children' => array(
																		array(
																			'el_type' => 'holder',
																			'el_class' => 'ibox-content padding-left-30',
																			'children' => array(
																				array(
																					'el_type' => 'holder',
																					'el_class' => 'row bar-chart-container',
																					'children' => array(
																						array(
																							'el_type' => 'graph',
																							'highchart' => array(
																								'chart' => array(
																									'type' => 'column',
																									'margin' => 75,
																									'options3d' => array(
																										'enabled' => true,
																										'alpha' => 0,
																										'beta' => 0,
																										'depth' => 60,
																										'viewDistance' => 10
																									)
																								),
																								'title' => array(
																									'text' => 'Your Potential Return on Investment'
																								),
																								'xAxis' => array(
																									'categories' => array('Year 1','Year 2')
																								),
																								'yAxis' => array(
																									'min' => 0,
																									'style' => array(
																										'color' => '#333',
																										'fontWeight' => 'bold',
																										'fontSize' => '12px',
																										'fontFamily' => 'Trebuchet MS, Verdana, sans-serif'
																									),
																									'title' => array(
																										'text' => 'Money'
																									)
																								),
																								'tooltip' => array(
																									'headerFormat' => '<span style="font-size:10px">{point.key}</span><table>',
																									'pointFormat' => '<tr><td style="color:black;padding:0;font-size:12px;">{series.name}: </td><td style="color:black;padding:0;font-size:12px;padding-left:10px;"><b> {point.y:,.0f}</b></td></tr>',
																									'footerFormat' => '</table>',
																									'shared' => true,
																									'useHTML' => true
																								),
																								'plotOptions' => array(
																									'column' => array(
																										'pointPadding' => 0.2,
																										'borderWidth' => 0
																									)
																								),
																								'series' => $series
																							)
																						)
																					)
																				)
																			)																			
																		)
																	)																
																)
															)													
														)
													)											
												)
											)
										)
									)
								);
								
								$containerArray['children'][] = $graph_container;
							}
						} else {

							$section_elements = $this->getSectionChildren(0, $section['ID'], $custom_elements, $element_choices, $tableHeaders, $colGroups, $tableData, $chartOptions, $chartSeries, $chartxAxis, $chartyAxis, $seriesEquations);
							foreach($section_elements as $element){
								$containerArray['children'][] = $element;
							}
						}
						
					}
					
					$masterArray[] = $containerArray;	
					return $masterArray;
				break;
			}
		}
		
		public function getChildren($parent, $roiElements, $elementChoices, $tableHeaders, $colGroups, $tableData, $chartOptions, $chartSeries, $chartxAxis, $chartyAxis, $seriesEquations){

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
							$elementArray['headers'] = $this->getHeaders($elementArray['el_id'], $roiElements, $elementChoices, $tableHeaders, $colGroups, $tableData, $chartOptions, $chartSeries, $chartxAxis, $chartyAxis, $seriesEquations);
							$elementArray['colgroups'] = $this->getColGroups($elementArray['el_id'], $roiElements, $elementChoices, $tableHeaders, $colGroups, $tableData, $chartOptions, $chartSeries, $chartxAxis, $chartyAxis, $seriesEquations);
							$elementArray['data'] = $this->getData($elementArray['el_id'], $roiElements, $elementChoices, $tableHeaders, $colGroups, $tableData, $chartOptions, $chartSeries, $chartxAxis, $chartyAxis, $seriesEquations);
							break;
							
						case 'tabgroup':
							$sub = 'tabs';
							break;
							
						default:
							$elementArray['children'] = $this->getChildren($elementArray['el_id'], $roiElements, $elementChoices, $tableHeaders, $colGroups, $tableData, $chartOptions, $chartSeries, $chartxAxis, $chartyAxis, $seriesEquations);
							break;
					}
				};
				
				$options = array_keys(array_column($chartOptions,'chart_id'), $elementArray['el_id']);
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
							$formulaArray = array('formula' => $seriesEquations[$equation]['equation'], 'name' => $seriesEquations[$equation]['series_name']);
							$elementArray['highchart']['series'][$seriesCount]['formula'][] = $formulaArray;
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
						$elementArray['highchart']['yAxis'][$yAxisCount]['labels']['format'] = $chartyAxis[$yAxis]['label_format'];
						$yAxisCount++;
					};				
				};
				
				$childArray[] = $elementArray;
			}
			
			return $childArray;
		}
		
		public function getHeaders($parent, $roiElements, $elementChoices, $tableHeaders, $colGroups, $tableData, $chartOptions, $chartSeries, $chartxAxis, $chartyAxis, $seriesEquations){

			$childArray     = array();
			
			$elements = array_keys(array_column($tableHeaders,'el_parent'), $parent);
			foreach($elements as $element) {
				
				$elementArray = $tableHeaders[$element];
				$choices      = array_keys(array_column($elementChoices,'el_id'), $elementArray['el_id']);
				foreach($choices as $choice){
					$elementArray['choices'][] = $elementChoices[$choice];
				};
				
				$children 	  = array_keys(array_column($roiElements,'el_parent'), $elementArray['el_id']);
				if( count($children) > 0 ){
					$elementArray['children'] = $this->getChildren($elementArray['el_id'], $roiElements, $elementChoices, $tableHeaders, $colGroups, $tableData, $chartOptions, $chartSeries, $chartxAxis, $chartyAxis, $seriesEquations);
				};
				
				$childArray[] = $elementArray;
			}
			
			return $childArray;
		}
		
		public function getColGroups($parent, $roiElements, $elementChoices, $tableHeaders, $colGroups, $tableData, $chartOptions, $chartSeries, $chartxAxis, $chartyAxis, $seriesEquations){

			$childArray     = array();
			
			$elements = array_keys(array_column($colGroups,'el_parent'), $parent);
			foreach($elements as $element) {
				
				$elementArray = $colGroups[$element];
				$choices      = array_keys(array_column($elementChoices,'el_id'), $elementArray['el_id']);
				foreach($choices as $choice){
					$elementArray['choices'][] = $elementChoices[$choice];
				};
				
				$children 	  = array_keys(array_column($roiElements,'el_parent'), $elementArray['el_id']);
				if( count($children) > 0 ){
					$elementArray['children'] = $this->getChildren($elementArray['el_id'], $roiElements, $elementChoices, $tableHeaders, $colGroups, $tableData, $chartOptions, $chartSeries, $chartxAxis, $chartyAxis, $seriesEquations);
				};
				
				$childArray[] = $elementArray;
			}
			
			return $childArray;
		}
		
		public function getData($parent, $roiElements, $elementChoices, $tableHeaders, $colGroups, $tableData, $chartOptions, $chartSeries, $chartxAxis, $chartyAxis, $seriesEquations){

			$childArray     = array();
			
			$elements = array_keys(array_column($tableData,'el_parent'), $parent);
			foreach($elements as $element) {
				
				$elementArray = $tableData[$element];
				$choices      = array_keys(array_column($elementChoices,'el_id'), $elementArray['el_id']);
				foreach($choices as $choice){
					$elementArray['choices'][] = $elementChoices[$choice];
				};
				
				$children 	  = array_keys(array_column($roiElements,'el_parent'), $elementArray['el_id']);
				if( count($children) > 0 ){
					$elementArray['children'] = $this->getChildren($elementArray['el_id'], $roiElements, $elementChoices, $tableHeaders, $colGroups, $tableData, $chartOptions, $chartSeries, $chartxAxis, $chartyAxis, $seriesEquations);
				};
				
				$childArray[] = $elementArray;
			}
			
			return $childArray;
		}
		
		public function getSectionChildren($parent, $section, $custom_elements, $element_choices, $tableHeaders, $colGroups,  $tableData, $chartOptions, $chartSeries, $chartxAxis, $chartyAxis, $seriesEquations){
			
			$childArray = array();
			$roi_elements = array_keys(array_column($custom_elements,'el_section_id'), $section);
			foreach($roi_elements as $element){
				$section_element[] = $custom_elements[$element];
			}
			
			$current_elements = array_keys(array_column($section_element,'el_parent'), $parent);
			foreach($current_elements as $element) {
				$elementArray = $section_element[$element];
				$choices = array_keys(array_column($element_choices,'el_id'), $elementArray['el_id']);
				foreach($choices as $choice){
					$elementArray['choices'][] = $element_choices[$choice];
				};
				
				$children = array_keys(array_column($section_element,'el_parent'), $elementArray['el_id']);
				if( count($children) > 0 ){
					
					switch($elementArray['el_type']){
						
						case 'table':
							$elementArray['headers'] = $this->getHeaders($elementArray['el_id'], $custom_elements, $element_choices, $tableHeaders, $colGroups, $tableData, $chartOptions, $chartSeries, $chartxAxis, $chartyAxis, $seriesEquations);
							$elementArray['data'] = $this->getData($elementArray['el_id'], $custom_elements, $element_choices, $tableHeaders, $colGroups, $tableData, $chartOptions, $chartSeries, $chartxAxis, $chartyAxis, $seriesEquations);
							break;

						case 'tabgroup':
							$sub = 'tabs';
							break;
							
						default:
							$elementArray['children'] = $this->getSectionChildren($elementArray['el_id'], $section, $custom_elements, $element_choices, $tableHeaders, $tableData, $chartOptions, $chartSeries, $chartxAxis, $chartyAxis, $seriesEquations);
					}
					
					
				};
				
				$options = array_keys(array_column($chartOptions,'chart_id'), $elementArray['el_id']);
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
						$elementArray['highchart']['yAxis'][$yAxisCount]['labels']['format'] = $chartyAxis[$yAxis]['label_format'];
						$yAxisCount++;
					};				
				};
				
				$childArray[] = $elementArray;
			}
			
			return $childArray;
		}
		
		public function getRoiPdfSetup() {
			return $this->getRoiPdfs();	
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
			
			$roiInfo = $this->getRoiInfo();
			switch($roiInfo['ep_version_level']){
				case '7':
					$navigation = $this->buildNavigation(0);
					return $navigation;
				break;
					
				case '9':
					$roiSections = $this->getRoiSections();
					$roiPdfs = $this->getRoiPdfs();
					$hidden_entities = $this->hiddenEntities();
					
					$sections = array(
						array(
							'href' => '#dash',
							'label' => 'Dashboard'
						)
					);
					
					foreach($roiSections as $section){			
						$hidden = array_keys(array_column($hidden_entities,'entity_id'), $section['ID']);
						$sections[] = array(
							'id' => $section['ID'],
							'href' => '#section' . $section['ID'],
							'label' => $section['Title'],
							'el_visibility' => count($hidden) ? 0 : 1
						);	
					}
					
					$navigation = array(
						array(
							'href' => '#',
							'icon' => 'fa fa-calculator',
							'label' => 'ROI Sections',
							'children' => $sections
						)
					);
					
					if(count($roiPdfs)){
						$pdf = array(
							'href' => '#',
							'icon' => 'fa fa-file-pdf-o',
							'label' => 'Your PDFs',
							'children' => array(
								array(
									'href' => '#pdf',
									'label' => 'View PDF'
								)
							)
						);
						
						$navigation[] = $pdf;
					}
					
					return $navigation;
				break;
			}
		}
	}

?>