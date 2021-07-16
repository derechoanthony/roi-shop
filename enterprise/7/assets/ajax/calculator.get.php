<?php

	// Establish connection to the database
	
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/db_connection.php");
	
	$roiActions = new RoiActions($db);
	switch($_GET['action']){
		case 'RetrieveRoi':
			$roiValues = array();
			$roiValues['roiSpecs'] = $roiActions->roiSpecs();
			$roiValues['roiSections'] = $roiActions->roiSections();
			$roiValues['compSpecs'] = $roiActions->roiCompSpecs();
			$roiValues['versionSpecs'] = $roiActions->roiVersionSpecs();
			$roiValues['testimonials'] = $roiActions->roiTestimonials();
			$roiValues['discoveryDocuments'] = $roiActions->roiDiscoveryDocuments();
			$roiValues['integrationElements'] = $roiActions->integrationElements();
			$roiValues['userRois'] = $roiActions->userRois();
			$roiValues['graphs'] = $roiActions->roiGraphs();
			$roiValues['sfIntegration'] = $roiActions->sfIntegration();
			$roiValues['verification'] = $roiActions->verification();
			$roiValues['pdfs'] = $roiActions->pdfTemplates();
			$roiValues['values'] = $roiActions->getStoredValues();
			$roiValues['customElements'] = $roiActions->getCustomElements();

			echo json_encode($roiValues);
		break;

		case 'roiValues':
			$roiValues = array();
			
			$roiValues['compSpecs'] = $roiActions->roiCompSpecs();
			$roiValues['versionSpecs'] = $roiActions->roiVersionSpecs();
			$roiValues['roiSections'] = $roiActions->roiSections();
			$roiValues['roiSpecs'] = $roiActions->roiSpecs();
			$roiValues['excludedSections'] = $roiActions->excludedSections();
			$roiValues['values'] = $roiActions->getStoredValues();
			$roiValues['pdf'] = $roiActions->getPDF();
			$roiValues['pdfMaxPages'] = $roiActions->getPDFPages();
			
			echo json_encode($roiValues);
		break;
		
		case 'lastexchangeupdate':
			echo $roiActions->lastCurrencyUpdate();
		break;

		case 'getCurrencies':
			echo json_encode($roiActions->availableCurrencies());
		break;

		case 'getcontributor':
			echo json_encode($roiActions->getContributors());
		break;

		case 'getSFOpportunities':
			echo json_encode($roiActions->getSFOpportunities());
		break;

		case 'createpdf':
			echo json_encode($roiActions->createPdf());
		break;

		case 'roiOwner':
			echo json_encode($roiActions->roiOwner());
		break;
	}

	class RoiActions {
		private $_db;

		public function __construct($db=NULL) {
			
			if(is_object($db)) {
				$this->_db = $db;
			} else {
				$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
				$this->_db = new PDO($dsn, DB_USER, DB_PASS);
			}			
		}

		public function pdfTemplates(){

			$sql = "SELECT pdf_template, pdf_name FROM ep_pdf_templates
					WHERE ep_version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					) ORDER BY position;";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi',$_GET['roi'],PDO::PARAM_INT);
			$stmt->execute();
			$pdfs = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $pdfs;
		}

		public function pdfTemplate($template){

			$sql = "SELECT html, css, orientation FROM ep_pdf_templates
					WHERE pdf_template = :pdf;";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':pdf',$template,PDO::PARAM_INT);
			$stmt->execute();
			$pdf = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $pdf;			
		}

		public function verification(){
			
			$verification_level = 0;
			if( isset($_GET['v']) && isset($_GET['roi']) ){
				$ver_user = $this->verifyUser();
				if($ver_user){ 
					$email_protected = $this->isEmailProtected();
					if( $email_protected['email_protected'] == 1 ){
						$verification_level = 2;
					} else {
						$verification_level = 1; 
					}
				}
			}

			if( isset( $_SESSION['Username'] ) && isset( $_GET['roi'] ) ) {
				$calculatorOwner = $this->roiOwner();
				if( rtrim(strtolower($calculatorOwner['username'])) === rtrim(strtolower($_SESSION['Username'])) || $_SESSION['Username'] == 'mfarber@theroishop.com' ){
					$verification_level = 3;
				}
				
				$calculatorManager = $this->roiManager();
				if( rtrim(strtolower($calculatorManager['username'])) === rtrim(strtolower($_SESSION['Username'])) ){
					$verification_level = 3;
				}
			}
		
			if( isset( $_SESSION['Username'] ) && isset( $_GET['roi'] ) ) {
				$calculatorAdmin = $this->userAdmin();
				if($calculatorAdmin['permission']>0) {
					$verification_level = 4;
				}
			}

			return $verification_level;
		}

		public function userAdmin() {
			
			$sql = "SELECT * FROM roi_user_companies
					WHERE user_id = ?
					AND company_id = (
						SELECT company_id FROM ep_created_rois
						WHERE roi_id = ?
					);";
					
			$stmt = $this->_db->prepare( $sql );
			$stmt->bindParam(1, $_SESSION['UserId'], PDO::PARAM_INT);
			$stmt->bindParam(2, $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$admin = $stmt->fetch();
	
			return $admin;				
		}

		public function roiManager() {
		
			$sql = "SELECT * FROM roi_users
					WHERE user_id = (
						SELECT manager FROM roi_users
						WHERE user_id = (
							SELECT user_id FROM ep_created_rois
							WHERE roi_id = ?
						)
					);";
					
			$stmt = $this->_db->prepare( $sql );
			$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$manager = $stmt->fetch();
	
			return $manager;				
		}

		public function roiOwner() {
			
			$sql = "SELECT * FROM roi_users
					WHERE user_id = (
						SELECT user_id FROM ep_created_rois
						WHERE roi_id = ?
					)";
					
			$stmt = $this->_db->prepare( $sql );
			$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$owner = $stmt->fetch();
	
			return $owner;
		}

		public function verifyUser(){

			$sql = "SELECT verification_code FROM ep_created_rois
					WHERE roi_id = ?";

			if($stmt = $this->_db->prepare($sql)){
				$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_STR);
				$stmt->execute();
				$ver_code = $stmt->fetch();
				if($ver_code['verification_code'] == $_GET['v']) {
					return TRUE;
				} else {
					return FALSE;
				}
			}				
		}

		public function isEmailProtected(){
			
			$sql = "SELECT email_protected FROM ep_created_rois WHERE roi_id = :roi;";
	
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
	
			return $stmt->fetch();
		}

		public function sfIntegration(){

			$sql = "SELECT * FROM integration
					WHERE userid = ?;";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1,$_SESSION['UserId'],PDO::PARAM_INT);
			$stmt->execute();
			$sfIntegration = $stmt->fetch(PDO::FETCH_ASSOC);
	
			return $sfIntegration;		
		}

		public function roiGraphs(){

			$sql = "SELECT * FROM graphs
					WHERE roiid = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = ?
					)";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1,$_GET['roi'],PDO::PARAM_INT);
			$stmt->execute();
			$roi_graphs = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $roi_graphs;		
		}

		public function userRois(){

			$sql = "SELECT * FROM ep_created_rois
					WHERE user_id = ?";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $_SESSION['UserId'], PDO::PARAM_INT);
			$stmt->execute();
			$user_rois = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $user_rois;
		}

		public function getCustomElements(){

			$sql = "SELECT * FROM ep_custom_elements
					WHERE version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = ?
					)
					ORDER BY position";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$customElements = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $customElements;
		}

		public function roiElements() {
			
			$sql = "SELECT * FROM entry_fields
					WHERE roiID = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = ?
					)
					ORDER BY position";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$elements = $stmt->fetchall(PDO::FETCH_ASSOC);

			$sql = "SELECT * FROM entry_choices
					WHERE entryid IN (
						SELECT ID FROM entry_fields
						WHERE roiID = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = ?
						)
					)";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$choices = $stmt->fetchall(PDO::FETCH_ASSOC);

			$count = 0;
			foreach($elements as $element){
				$element_choice = array_keys(array_column($choices,'entryid'), $element['ID']);
				if($element_choice){
					$elements[$count]['choices'] = [];
					foreach($element_choice as $choice){
						$elements[$count]['choices'][] = $choices[$choice];
					}
				}
				$count++;
			}

			return $elements;
		}

		public function roiDiscoveryDocuments(){

			$sql = "SELECT * FROM discovery_document
					WHERE company_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					)";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$discoveryDocuments = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			$sql = "SELECT * FROM discovery_questions
					WHERE discovery_id IN (
						SELECT id FROM discovery_document
						WHERE company_id = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = ?
						)
					)
					ORDER BY position;";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$discoveryQuestions = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			$count = 0;
			foreach($discoveryDocuments as $discovery){
				$discovery_element = array_keys(array_column($discoveryQuestions,'discovery_id'), $discovery['id']);
				if($discovery_element){
					$discoveryDocuments[$count]['elements'] = [];
					foreach($discovery_element as $element){
						$discoveryQuestions[$element]['choices'] = json_decode($discoveryQuestions[$element]['choices'], true);
						$discoveryDocuments[$count]['elements'][] = $discoveryQuestions[$element];
					}
				}
				$count++;
			}			
			
			return $discoveryDocuments;				
		}

		public function integrationElements() {

			$sql = "SELECT * FROM integration_elements
					WHERE roi_version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = ?
					)";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$integration_elements = $stmt->fetch(PDO::FETCH_ASSOC);

			return $integration_elements;			
		}

		public function roiVersionSpecs() {
			
			$sql = "SELECT * FROM roi_structure_versions
					WHERE version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = ?
					)";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$version_specs = $stmt->fetch(PDO::FETCH_ASSOC);

			return $version_specs;		
		}

		public function roiCompSpecs() {
			
			$sql = "SELECT * FROM comp_specs
					WHERE compID = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = ?
					)";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$comp_specs = $stmt->fetch(PDO::FETCH_ASSOC);

			return $comp_specs;		
		}

		public function roiTestimonials(){

			$sql = "SELECT * FROM testimonials
					WHERE company_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = ?
					)";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$testimonials = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $testimonials;			
		}

		public function roiSections(){

			$sql = "SELECT * FROM compsections
					WHERE compID = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = ?
					)
					ORDER BY Position";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$roiSections = $stmt->fetchall(PDO::FETCH_ASSOC);

			$sql = "SELECT * FROM hidden_entities
					WHERE roi = ? AND type ='section'";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$hidden_sections = $stmt->fetchall();

			$count = 0;
			foreach($roiSections as $section){
				$hidden = array_keys(array_column($hidden_sections,'entity_id'), $section['ID']);
				$roiSections[$count]['visible'] = $hidden ? 0 : 1;
				$count++;
			}

			$sql = "SELECT * FROM entry_fields
					WHERE roiID = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = ?
					)
					ORDER BY position";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$elements = $stmt->fetchall(PDO::FETCH_ASSOC);

			$sql = "SELECT * FROM entry_choices
					WHERE entryid IN (
						SELECT ID FROM entry_fields
						WHERE roiID = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = ?
						)
					)";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$choices = $stmt->fetchall(PDO::FETCH_ASSOC);

			$count = 0;
			foreach($elements as $element){
				$element_choice = array_keys(array_column($choices,'entryid'), $element['ID']);
				$elements[$count]['choices'] = [];
				if($element_choice){
					foreach($element_choice as $choice){
						$elements[$count]['choices'][] = $choices[$choice];
					}
				}
				$count++;
			}

			$count = 0;
			foreach($roiSections as $section){
				$section_elements = array_keys(array_column($elements,'sectionName'), $section['ID']);
				if($section_elements){
					$roiSections[$count]['elements'] = [];
					foreach($section_elements as $element){
						$roiSections[$count]['elements'][] = $elements[$element];
					}
				}
				$count++;
			}	

			return $roiSections;			
		}

		public function getStoredValues(){

			$version1Values = $this->roiValues();
			$version2Values = $this->roiSessionValues();

			$version2Values[] = $version1Values; 
			
			return $version2Values;
		}

		public function roiValues(){
			
			$sql = "SELECT `value`, `entryid` FROM roi_values
					WHERE roiid = ?
					ORDER BY `dt` ASC, sessionid ASC";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$roiValues = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $roiValues;
		}

		public function roiSessionValues(){
			$roi_id = $_GET ? $_GET['roi'] : $_POST['roi'];
			
			$sql = "SELECT * FROM roi_stored_values 
					WHERE roi_id = :roi_id 
					ORDER BY `stored_dt` DESC, `session_id` DESC;";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi_id', $roi_id, PDO::PARAM_INT);
			$stmt->execute();
			$values = $stmt->fetchall(PDO::FETCH_ASSOC);

			$sessionValues = [];
			for ($i = 0; $i < count($values); $i++){
				$sessionValues[] = $values[$i]['value_array'];
			}
			
			return $sessionValues;			
			// $sql = "SELECT `value_array` FROM roi_stored_values
			// 		WHERE roi_id = ?
			// 		ORDER BY `stored_dt` DESC, `session_id` DESC";

			// $stmt = $this->_db->prepare($sql);
			// $stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
			// $stmt->execute();
			// $roiSessionValues = $stmt->fetchall(PDO::FETCH_ASSOC);

			// $sessionValues = [];
			// for ($i = 0; $i < count($roiSessionValues); $i++){
			// 	$sessionValues[] = unserialize(base64_decode(gzuncompress($roiSessionValues[$i]['value_array'])));
			// }
			
			// return $sessionValues;			
		}

		public function roiSpecs(){
			
			$sql = "SELECT * FROM ep_created_rois
					WHERE roi_id = ?";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$roiSpecs = $stmt->fetch(PDO::FETCH_ASSOC);

			return $roiSpecs;			
		}

		public function excludedSections(){
			
			$sql = "SELECT * FROM hidden_entities
					WHERE type = 'section' AND roi = ?;";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$excludedSections = $stmt->fetchall();
			
			return $excludedSections;			
		}

		public function lastCurrencyUpdate(){
			
			$sql = "SELECT MAX(dt) FROM exchange_rates;";
						
			$stmt = $this->_db->prepare($sql);
			$stmt->execute();	
			$lastUpdate = $stmt->fetch();
			
			return $lastUpdate['MAX(dt)'];
		}

		public function availableCurrencies(){
			
			$sql = "SELECT * FROM exchange_rates;";
						
			$stmt = $this->_db->prepare($sql);
			$stmt->execute();	
			$currencies = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $currencies;
		}

		public function getContributors(){
			
			$sql = "SELECT * FROM ep_roi_allowed_emails
					WHERE roi_id=:roi";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$contributors = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $contributors;
		}

		public function getPDFPages(){
			
			$sql = "SELECT MAX(pageno) FROM pdf_specs
					WHERE roi = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = ?
					);";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $_GET['roi'],PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch(PDO::FETCH_ASSOC);
			
			return $data['MAX(pageno)'];			
		}

		public function getPDF(){

			$sql = "SELECT pdf_template FROM ep_pdf_templates
					WHERE ep_version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					);";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi',$_GET['roi'],PDO::PARAM_INT);
			$stmt->execute();
			$pdf = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $pdf;
		}

		public function createPdf(){
			
			$root = realpath($_SERVER["DOCUMENT_ROOT"]);
			require_once("$root/webapps/mpdf/mpdf.php");

			$report = $this->pdfTemplate($_GET['reportId']);
			$user = $this->roiOwner();

			$orientation =  $report[0]['orientation'];
			$report = '<html><head>' . $report[0]['css'] . '</head><body class="pdfbody">' . $report[0]['html'] . '</body></html>';

			$stylesheet = file_get_contents("$root/webapps/assets/css/pdfstyle.css");
			$comp_stylesheet = file_get_contents("$root/webapps/assets/css/style.css");

			$values = $this->getStoredValues()[0];
			$values = json_decode($values, true);
			foreach($values as $value){
				$report = str_replace('<value>'.$value['address'].'</value>', $value['value'], $report);
				$report = str_replace('<formatted>'.$value['address'].'</formatted>', $value['formattedValue'], $report);
			};
			
			$excludedSections = $this->excludedSections();
			foreach($excludedSections as $section){
				$report = str_replace('<section'. $section['entity_id'] .'>', 'style="display: none;"', $report);
			};

			$roiSpecs = $this->roiSpecs();
			$report = str_replace('<tag>ROI Name</tag>', $roiSpecs['roi_title'], $report);
			$report = str_replace('<tag>Date</tag>',  date("F j, Y"), $report);
			$report = str_replace('<tag>ROI Link</tag>', '<a href="' . $_GET['roiPath'] . '">Link to the ROI</a>', $report);
			$report = str_replace('<tag>ROI Path</tag>', $_GET['roiPath'], $report);
			$report = str_replace('<tag>Owner Name</tag>',  $user['first_name'] . ' ' . $user['last_name'], $report);
			$report = str_replace('<tag>Phone Number</tag>',  $user['phone'], $report);
			$report = str_replace('<tag>Summary Graph</tag>', '<img src="http://www.theroishop.com/enterprise/7/assets/images/' . $roiSpecs['roi_id'] . 'barchartsummary.png" style="margin-left: 80px; margin-bottom: 30px;" width="800px">', $report);

			$mpdf = new mPDF('c', $orientation);
			
			$mpdf->WriteHTML($stylesheet,1);
			$mpdf->WriteHTML($comp_stylesheet,1);
				
			$mpdf->WriteHTML($report);

			$mpdf->Output("$root/webapps/assets/customwb/10016/pdf/preview-preview2.pdf",'F');		
			
		}

		public function getSFOpportunities(){
			
			ini_set('max_execution_time', 300);
		
			$pageStart = 1;
			$returnedItemsArray = array();

			$where = "&where=Name%20like%20'%25" . $_GET['opp_name'] . "%25'";
			
			do{
				$curl = curl_init();
				
				$header = array();
				$header[] = 'Authorization: Element '. $_GET['user_id'] .', User eMYXjLZ2v/DwUYYc+NnJ/MISDJ9nr/qvsBhls4+K8Dw=';
				
			
				curl_setopt_array( $curl, array(
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_URL => 'https://api.cloud-elements.com/elements/api-v2/hubs/crm/opportunities?page=' . $pageStart . $where,
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
			} while ( $returnedCount == 200 && $pageStart <= 11 );
			
			return $returnedItemsArray;
		}
	}

?>