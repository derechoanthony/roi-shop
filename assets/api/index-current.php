<?php

	// Establish connection to the database
	
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/db_connection.php");
	require_once("$root/webapps/mpdf/mpdf.php");
	require_once("$root/email/swiftmailer/lib/swift_required.php");

	require "$root/email/phpmailer/src/Exception.php";
	require "$root/email/phpmailer/src/PHPMailer.php";
	require "$root/email/phpmailer/src/SMTP.php";

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	$apiActions = new ApiActions($db);
	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		switch($_POST['action']){
			case 'addNewUser':
				$apiActions->addNewUser();
			break;

			case 'deleteSection':
				$apiActions->deleteSection();
			break;

			case 'deleteEntry':
				$apiActions->deleteEntry();
			break;

			case 'addNewEntry':
				echo $apiActions->addNewEntry();
			break;

			case 'addNewSection':
				echo $apiActions->addNewSection();
			break;

			case 'signInUser':
				$apiActions->signInUser();
			break;

			case 'logSessionActions':
				$apiActions->logSessionActions();
			break;

			// case 'sendbatch':
			// 	$apiActions->sendBatchPaycor();
			// break;

			case 'changeManager':
				$apiActions->changeManager();
			break;
			
			case 'updateSection':
				$apiActions->updateSection();
			break;

			case 'updateEntry':
				$apiActions->updateEntry();
			break;

			case 'updateTemplate':
				$apiActions->updateTemplate();
			break;

			case 'updateSectionPosition':
				$apiActions->updateSectionPosition();
			break;

			case 'updateEntryPosition':
				$apiActions->updateEntryPosition();
			break;

			case 'updateUser':
				$apiActions->updateUser();
			break;

			case 'transferRoi':
				$apiActions->transferRoi();
			break;

			case 'transferRois':
				$apiActions->transferRois();
			break;

			case 'updateCompanyLicenses':
				$apiActions->updateCompanyLicenses();
			break;

			case 'deleteUser':
				$apiActions->deleteUser();
			break;

			case 'deleteRoi':
				$apiActions->deleteRoi();
			break;

			case 'storePdfGraphs':
				$apiActions->storePdfGraphs();
			break;

			case 'createClone':
				$apiActions->createClone();
			break;

			case 'postPdf':
				$apiActions->postPdf();
			break;

			case 'storeValues':
				$apiActions->storeValues();
			break;

			case 'storeChartImage':
				$apiActions->storeChartImage();
			break;
		}	
	} else {
		switch($_GET['action']){
			case 'createPdf':
				$apiActions->createPdf();
			break;
			
			case 'RetrievePDFGraph':				
				echo json_encode($apiActions->retrievePdfGraphs());
			break;

			case 'companies':
				$design = [];
				$design['companies'] = $apiActions->getAllCompanies();

				echo json_encode($design);
			break;

			case 'design':
				$design = [];
				$design['sections'] = $apiActions->getCompanySections();
				$design['specs'] = $apiActions->getCompanySpecs();
				$design['version'] = $apiActions->getStructureVersion();

				echo json_encode($design);
			break;

			case 'buildCompany':
				$specs = [];
				$specs['users'] = $apiActions->retrieveUsers();
				$specs['rois'] = $apiActions->retrieveCompanyRois();
				$specs['company'] = $apiActions->retrieveCompanySpecs();
				$specs['permissions'] = $apiActions->retrieveUserPermissions();

				echo json_encode($specs);
			break;
			
			case 'companySpecs':
				$specs = [];
				$specs['users'] = $apiActions->retrieveUsers();
				$specs['rois'] = $apiActions->retrieveCompanyRois();
				$specs['company'] = $apiActions->company();
				$specs['permissions'] = $apiActions->retrieveUserPermissions();

				echo json_encode($specs);
			break;

			case 'versionDesign':
				$specs = [];
				$specs['version'] = $apiActions->version();
				$specs['entries'] = $apiActions->getVersionEntries();
				$specs['entryChoices'] = $apiActions->getVersionEntryChoices();
				$specs['sections'] = $apiActions->getVersionSections();
				$specs['roiCells'] = $apiActions->getVersionCells();
				$specs['specs'] = $apiActions->roiSpecs();
				$specs['versionSpecs'] = $apiActions->versionSpecs();
				$specs['compSpecs'] = $apiActions->getCompSpecs();
				$specs['build'] = $apiActions->versionBuild();
				$specs['navigation'] = $apiActions->navigation();
				$specs['testimonials'] = $apiActions->getTestimonials();
				$specs['integrations'] = $apiActions->integrations();
				$specs['integration'] = $apiActions->sfIntegration();
				$specs['templates'] = $apiActions->templates();
				$specs['elements'] = $apiActions->elements();
				$specs['pdfs'] = $apiActions->getVersionPdfs();

				echo json_encode($specs);
			break;

			case 'retrieveCalcYourRoi':
				$specs = [];
				$specs['version'] = $apiActions->versionByRoi();
				$specs['entries'] = $apiActions->getRoiEntries();
				$specs['entryChoices'] = $apiActions->getRoiEntryChoices();
				$specs['sections'] = $apiActions->getRoiSections();
				$specs['roiCells'] = $apiActions->getRoiCells();
				$specs['specs'] = $apiActions->roiSpecs();
				$specs['versionSpecs'] = $apiActions->versionSpecs();
				$specs['values'] = $apiActions->getRoiValues();
				$specs['oldValues'] = $apiActions->getOldValues();
				$specs['compSpecs'] = $apiActions->getCompSpecs();
				$specs['build'] = $apiActions->versionBuild();
				$specs['navigation'] = $apiActions->navigation();
				$specs['testimonials'] = $apiActions->getTestimonials();
				$specs['verification'] = $apiActions->verification();
				$specs['integrations'] = $apiActions->integrations();
				$specs['integration'] = $apiActions->sfIntegration();
				$specs['hiddenSections'] = $apiActions->hiddenSections();
				$specs['overriddenValues'] = $apiActions->overriddenValues();
				$specs['templates'] = $apiActions->templates();
				$specs['elements'] = $apiActions->elements();
				$specs['pdfs'] = $apiActions->getVersionPdfs();

				echo json_encode($specs);
			break;

			case 'retrieveRoi':
				$specs = [];
				$specs['roiValues'] = $apiActions->getRoiValues();
				$specs['roiSpecs'] = $apiActions->roiSpecs();
				$specs['versionSpecs'] = $apiActions->versionSpecs();
				$specs['versionBuild'] = $apiActions->versionBuild();
				// $roiValues['integrations'] = $roiActions->integrations();
				// $roiValues['integration'] = $roiActions->sfIntegration();
				// $roiValues['cells'] = $roiActions->cells();
				// $roiValues['roiSpecs'] = $roiActions->roiSpecs();
				// $roiValues['versionBuild'] = $roiActions->versionBuild();
				// $roiValues['verification'] = $roiActions->verification();
				// $roiValues['versionSpecs'] = $roiActions->versionSpecs();
	
				echo json_encode($specs);				
			break;

			case 'retrieveUsers':
				echo json_encode($apiActions->retrieveUsers());
			break;

			case 'retrieveRoisByCompany':
				echo json_encode($apiActions->retrieveCompanyRois());
			break;

			case 'retrieveCompanyStructures':
				echo json_encode($apiActions->retrieveCompanyStructures());
			break;

			case 'retrieveStructureVersions':
				echo json_encode($apiActions->retrieveStructureVersions());
			break;

			case 'retrieveUserRois':
				echo json_encode($apiActions->retrieveUserRois());
			break;

			case 'getAllCompanies':
				echo json_encode($apiActions->getAllCompanies());
			break;
		}
	}

	class ApiActions {
		
		private $_db;

		public function __construct($db=NULL) {
			
			if(is_object($db)) {
				$this->_db = $db;
			} else {
				$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
				$this->_db = new PDO($dsn, DB_USER, DB_PASS);
			}			
		}

		public function deleteSection(){
			
			$sql = "DELETE FROM compsections WHERE ID = :section;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':section', $_POST['section'], PDO::PARAM_INT);
			$stmt->execute();				
		}

		public function deleteEntry(){
			
			$sql = "DELETE FROM entry_fields WHERE ID = :entry;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':entry', $_POST['entry'], PDO::PARAM_INT);
			$stmt->execute();				
		}

		public function addNewSection(){
			
			$sql = "INSERT INTO compsections (compID, Title, Position)
					VALUES(:compID, :title, :position);";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':compID', $_POST['structure'], PDO::PARAM_INT);
			$stmt->bindParam(':title', $_POST['title'], PDO::PARAM_STR);
			$stmt->bindParam(':position', $_POST['position'], PDO::PARAM_INT);
			$stmt->execute();

			$section_id = $this->_db->lastInsertId();
			return $section_id;
		}

		public function addNewEntry(){
			
			$sql = "INSERT INTO entry_fields (roiID, sectionName, Title, Type, Format, Tip, append, formula, address, position)
					VALUES(:compID, :section, :title, :type, :format, :tip, :append, :formula, :address, :position);";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':compID', $_POST['structure'], PDO::PARAM_INT);
			$stmt->bindParam(':section', $_POST['section'], PDO::PARAM_INT);
			$stmt->bindParam(':title', $_POST['title'], PDO::PARAM_STR);
			$stmt->bindParam(':type', $_POST['type'], PDO::PARAM_STR);
			$stmt->bindParam(':format', $_POST['format'], PDO::PARAM_STR);
			$stmt->bindParam(':tip', $_POST['tip'], PDO::PARAM_STR);
			$stmt->bindParam(':append', $_POST['append'], PDO::PARAM_STR);
			$stmt->bindParam(':formula', $_POST['formula'], PDO::PARAM_STR);
			$stmt->bindParam(':address', $_POST['address'], PDO::PARAM_STR);
			$stmt->bindParam(':position', $_POST['position'], PDO::PARAM_INT);
			$stmt->execute();

			$entry_id = $this->_db->lastInsertId();
			return $entry_id;
		}

		public function getCompanySections(){
			$sql = "SELECT * FROM compsections 
					WHERE compID = :structure
					ORDER BY Position ASC;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':structure', $_GET['structure'], PDO::PARAM_INT);
			$stmt->execute();
			$sections = $stmt->fetchall(PDO::FETCH_ASSOC);

			$i=0;
			foreach($sections as $section){
				$sections[$i]['entries'] = $this->getEntriesBySection($section['ID']);
				$i++;
			}

			return $sections;				
		}

		public function storePdfGraphs(){
			$root = realpath($_SERVER["DOCUMENT_ROOT"]);

			$img = urldecode($_POST['image']);
			$img = file_get_contents($img);
			
			$roi = $_POST['roi'];
			$id = $_POST['id'];	

			file_put_contents("$root/company_specific_files/1/logo/testpng.txt", 'something here for the time being');
		}

		public function retrievePdfGraphs(){
			
			$sql = "SELECT * FROM pdf_graphs
					WHERE version = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi_id
					)";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi_id',$_GET['roi_id'],PDO::PARAM_INT);
			$stmt->execute();
			$graphs = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $graphs;
		}

		public function elements(){

			$sql = "SELECT * FROM elements
					WHERE version_id = (
						SELECT structure_id FROM roi_structure_versions
						WHERE version_id = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = :roi_id
						)
					)";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi_id',$_GET['roi_id'],PDO::PARAM_INT);
			$stmt->execute();
			$elements = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $elements;
		}

		public function templates(){

			$sql = "SELECT * FROM ep_templates
					WHERE version_id = (
						SELECT structure_id FROM roi_structure_versions
						WHERE version_id = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = :roi_id
						)
					)";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi_id',$_GET['roi_id'],PDO::PARAM_INT);
			$stmt->execute();
			$templates = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $templates;	
		}

		public function navigation(){

			$sql = "SELECT * FROM ep_navigation
					WHERE version_id = (
						SELECT structure_id FROM roi_structure_versions
						WHERE version_id = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = :roi_id
						)
					)";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi_id',$_GET['roi_id'],PDO::PARAM_INT);
			$stmt->execute();
			$navigation = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $navigation;	
		}

		public function storeValues(){
			$storedValues = $_POST['values'];

			$sql = "SELECT id FROM roi_stored_values where session_id = ? AND roi_id = ?;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $_SESSION['Id'], PDO::PARAM_STR);
			$stmt->bindParam(2, $_POST['roi'], PDO::PARAM_INT);
			$stmt->execute();
			
			$record = $stmt->fetch(PDO::FETCH_ASSOC);

			if(count($record['id']) > 0){
				
				$sql = "UPDATE roi_stored_values SET value_array = :value, stored_dt = NOW()
						WHERE id=:id";

				$stmt = $this->_db->prepare($sql);
				$stmt->bindParam(':value', $storedValues, PDO::PARAM_STR);
				$stmt->bindParam(':id', $record['id'], PDO::PARAM_INT);
				$stmt->execute();
			} else {
				
				$sql = "INSERT INTO roi_stored_values (`roi_id`, `session_id`, `value_array`, `stored_dt`)
						VALUES (:roi, :session, :value, NOW())";
						
				$stmt = $this->_db->prepare($sql);
				$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
				$stmt->bindParam(':session', $_SESSION['Id'], PDO::PARAM_STR);
				$stmt->bindParam(':value', $storedValues, PDO::PARAM_STR);
				$stmt->execute();
			}


		}

		public function hiddenSections(){
			
			$sql = "SELECT entity_id FROM hidden_entities
					WHERE roi = :roi_id";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi_id',$_GET['roi_id'],PDO::PARAM_INT);
			$stmt->execute();
			$hidden = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $hidden;				
		}

		public function overriddenValues(){

			$sql = "SELECT * FROM user_output_value
					WHERE roiid = :roi_id";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi_id',$_GET['roi_id'],PDO::PARAM_INT);
			$stmt->execute();
			$values = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $values;	
		}

		public function integrations(){

			$sql = "SELECT * FROM roi_integration
					WHERE roi_structure_id = (
						SELECT structure_id FROM roi_structure_versions
						WHERE version_id = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = :roi
						)
					);";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi',$_GET['roi_id'],PDO::PARAM_INT);
			$stmt->execute();
			$integrations = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $integrations;			
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

		public function verification(){
			
			$verification_level = 0;
			if( isset($_GET['v']) && isset($_GET['roi_id']) ){
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

			if( isset( $_SESSION['Username'] ) && isset( $_GET['roi_id'] ) ) {
				$calculatorOwner = $this->roiOwner();
				if( rtrim(strtolower($calculatorOwner['username'])) === rtrim(strtolower($_SESSION['Username'])) || $_SESSION['Username'] == 'mfarber@theroishop.com' ){
					$verification_level = 3;
				}
				
				$calculatorManager = $this->roiManager();
				if( rtrim(strtolower($calculatorManager['username'])) === rtrim(strtolower($_SESSION['Username'])) ){
					$verification_level = 3;
				}
			}
		
			if( isset( $_SESSION['Username'] ) && isset( $_GET['roi_id'] ) ) {
				$calculatorAdmin = $this->userAdmin();
				if($calculatorAdmin['permission']>0) {
					$verification_level = 4;
				}
			}

			return $verification_level;
		}

		public function verifyUser(){

			$sql = "SELECT verification_code FROM ep_created_rois
					WHERE roi_id = ?";

			if($stmt = $this->_db->prepare($sql)){
				$stmt->bindParam(1, $_GET['roi_id'], PDO::PARAM_STR);
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
			$stmt->bindParam(':roi', $_GET['roi_id'], PDO::PARAM_INT);
			$stmt->execute();
	
			return $stmt->fetch();
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
			$stmt->bindParam(1, $_GET['roi_id'], PDO::PARAM_INT);
			$stmt->execute();
			$manager = $stmt->fetch();
	
			return $manager;				
		}

		public function roiOwner() {
			$roi = $_GET ? $_GET['roi_id'] : $_POST['roi_id'];

			$sql = "SELECT * FROM roi_users
					WHERE user_id = (
						SELECT user_id FROM ep_created_rois
						WHERE roi_id = ?
					)";
					
			$stmt = $this->_db->prepare( $sql );
			$stmt->bindParam(1, $roi, PDO::PARAM_INT);
			$stmt->execute();
			$owner = $stmt->fetch(PDO::FETCH_ASSOC);
	
			return $owner;
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
			$stmt->bindParam(2, $_GET['roi_id'], PDO::PARAM_INT);
			$stmt->execute();
			$admin = $stmt->fetch();
	
			return $admin;				
		}

		public function getPdfById(){
			$reportId = $_GET ? $_GET['reportId'] : $_POST['reportId'];
			
			$sql = "SELECT css, html, orientation FROM ep_pdf_templates
					WHERE pdf_template = :id;";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':id', $reportId, PDO::PARAM_INT);
			$stmt->execute();
			$pdf = $stmt->fetch(PDO::FETCH_ASSOC);

			return $pdf;
		}

		public function getVersionPdfs(){
			$sql = "SELECT * FROM ep_pdf_templates
					WHERE ep_version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi_id
					)";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi_id', $_GET['roi_id'], PDO::PARAM_INT);
			$stmt->execute();
			$pdfs = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $pdfs;			
		}

		public function getTestimonials(){
			$sql = "SELECT * FROM testimonials
					WHERE company_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					)";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi_id'], PDO::PARAM_INT);
			$stmt->execute();
			$testimonials = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $testimonials;			
		}

		public function getVersionSections(){
			$sql = "SELECT * FROM compsections
					WHERE compID = :version
					ORDER BY Position ASC;";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':version', $_GET['version'], PDO::PARAM_INT);
			$stmt->execute();
			$sections = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $sections;
		}

		public function getRoiSections(){
			$sql = "SELECT * FROM compsections
					WHERE compID = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi_id
					)
					ORDER BY Position ASC;";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi_id', $_GET['roi_id'], PDO::PARAM_INT);
			$stmt->execute();
			$sections = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $sections;
		}

		public function getVersionEntries(){
			$sql = "SELECT * FROM entry_fields
					WHERE roiID = :version
					ORDER BY position";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':version', $_GET['version'], PDO::PARAM_INT);
			$stmt->execute();
			$entries = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $entries;
		}

		public function getRoiEntries(){
			$sql = "SELECT * FROM entry_fields
					WHERE roiID = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi_id
					)
					ORDER BY position";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi_id', $_GET['roi_id'], PDO::PARAM_INT);
			$stmt->execute();
			$entries = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $entries;
		}

		public function getVersionEntryChoices(){
			$sql = "SELECT * FROM entry_choices
					WHERE entryid IN (
						SELECT ID FROM entry_fields
						WHERE roiID = :version
					)
					ORDER BY position";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':version', $_GET['version'], PDO::PARAM_INT);
			$stmt->execute();
			$entry_choices = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $entry_choices;
		}

		public function getRoiEntryChoices(){
			$sql = "SELECT * FROM entry_choices
					WHERE entryid IN (
						SELECT ID FROM entry_fields
						WHERE roiID = (
							SELECT roi_version_id FROM ep_created_rois
							WHERE roi_id = :roi_id
						)
					)
					ORDER BY position";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi_id', $_GET['roi_id'], PDO::PARAM_INT);
			$stmt->execute();
			$entry_choices = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $entry_choices;
		}

		public function getVersionCells(){
			$sql = "SELECT * FROM cells
					WHERE version = :version";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':version', $_GET['version'], PDO::PARAM_INT);
			$stmt->execute();
			$cells = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $cells;			
		}

		public function getRoiCells(){
			$sql = "SELECT * FROM cells
					WHERE version = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi_id
					)";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi_id', $_GET['roi_id'], PDO::PARAM_INT);
			$stmt->execute();
			$cells = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $cells;			
		}

		public function getCompSpecs(){
			$sql = "SELECT * FROM comp_specs
					WHERE compID = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi_id
					)";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi_id', $_GET['roi_id'], PDO::PARAM_INT);
			$stmt->execute();
			$comp_specs = $stmt->fetch(PDO::FETCH_ASSOC);

			return $comp_specs;			
		}

		public function getCompanySpecs(){
			$sql = "SELECT * FROM comp_specs
					WHERE compID = :structure";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':structure', $_GET['structure'], PDO::PARAM_INT);
			$stmt->execute();
			$specs = $stmt->fetch(PDO::FETCH_ASSOC);

			return $specs;
		}

		public function getStructureVersion(){
			$sql = "SELECT * FROM roi_structure_versions
					WHERE version_id = :structure";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':structure', $_GET['structure'], PDO::PARAM_INT);
			$stmt->execute();
			$version = $stmt->fetch(PDO::FETCH_ASSOC);

			return $version;			
		}

		public function getEntriesBySection($section){
			$sql = "SELECT * FROM entry_fields 
					WHERE sectionName = :section AND roiID = :structure
					ORDER BY position ASC;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':section', $section, PDO::PARAM_INT);
			$stmt->bindParam(':structure', $_GET['structure'], PDO::PARAM_INT);
			$stmt->execute();
			$entries = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $entries;
		}

		public function updateSection(){
			$sql = "UPDATE compsections SET Title = :title, Caption = :writeup, Video = :video, formula = :formula
					WHERE ID = :id";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':title', $_POST['title'], PDO::PARAM_STR);
			$stmt->bindParam(':writeup', $_POST['writeup'], PDO::PARAM_STR);
			$stmt->bindParam(':video', $_POST['video'], PDO::PARAM_STR);
			$stmt->bindParam(':formula', $_POST['formula'], PDO::PARAM_STR);
			$stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
			$stmt->execute();
		}

		public function updateEntry(){
			
			$sql = "UPDATE entry_fields SET Title = :title, Type = :type, Format = :format, Tip = :tooltip, address = :address, append = :append, formula = :formula, rules = :rules, `precision` = :precision
					WHERE ID = :id";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':title', $_POST['title'], PDO::PARAM_STR);
			$stmt->bindParam(':type', $_POST['type'], PDO::PARAM_STR);
			$stmt->bindParam(':format', $_POST['format'], PDO::PARAM_STR);
			$stmt->bindParam(':tooltip', $_POST['tooltip'], PDO::PARAM_STR);
			$stmt->bindParam(':address', $_POST['address'], PDO::PARAM_STR);
			$stmt->bindParam(':append', $_POST['append'], PDO::PARAM_STR);
			$stmt->bindParam(':formula', $_POST['formula'], PDO::PARAM_STR);
			$stmt->bindParam(':rules', $_POST['rules'], PDO::PARAM_STR);
			$stmt->bindParam(':precision', $_POST['precision'], PDO::PARAM_INT);
			$stmt->bindParam(':id', $_POST['entry'], PDO::PARAM_INT);
			$stmt->execute();
		}

		public function updateTemplate(){
			$sql = "UPDATE comp_specs SET retPeriod = :returnPeriod
					WHERE compID = :structure";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':returnPeriod', $_POST['returnPeriod'], PDO::PARAM_INT);
			$stmt->bindParam(':structure', $_POST['id'], PDO::PARAM_INT);
			$stmt->execute();

			$sql = "UPDATE roi_structure_versions SET version_name = :template
					WHERE version_id = :structure";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':template', $_POST['template'], PDO::PARAM_STR);
			$stmt->bindParam(':structure', $_POST['id'], PDO::PARAM_INT);
			$stmt->execute();
		}

		public function updateSectionPosition(){
			$sql = "UPDATE compsections SET Position = :position
					WHERE ID = :section";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':position', $_POST['position'], PDO::PARAM_INT);
			$stmt->bindParam(':section', $_POST['section'], PDO::PARAM_INT);
			$stmt->execute();			
		}

		public function updateEntryPosition(){
			$sql = "UPDATE entry_fields SET position = :position
					WHERE ID = :entry";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':position', $_POST['position'], PDO::PARAM_INT);
			$stmt->bindParam(':entry', $_POST['entry'], PDO::PARAM_INT);
			$stmt->execute();			
		}

		public function versionSpecs(){
			$sql = "SELECT * FROM roi_structure_versions
					WHERE version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi_id
					)";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi_id',$_GET['roi_id'],PDO::PARAM_INT);
			$stmt->execute();
			$version_specs = $stmt->fetch(PDO::FETCH_ASSOC);

			return $version_specs;
		}

		public function versionBuild(){

			$sql = "SELECT * FROM ep_version_build
					WHERE roi_version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi_id
					);";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi_id',$_GET['roi_id'],PDO::PARAM_INT);
			$stmt->execute();
			$version_build = $stmt->fetch(PDO::FETCH_ASSOC);
			
			return $version_build;			
		}

		public function logSessionActions(){

			$sql = "INSERT INTO roi_session_actions (session_id, actions)
					VALUES(:session, :actions);";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':session', $_POST['session'], PDO::PARAM_STR);
			$stmt->bindParam(':actions', $_POST['actions'], PDO::PARAM_STR);
			$stmt->execute();
		}

		public function signInUser(){

			$login_results = [];
			
			$sql = "SELECT user_id, username, registered FROM roi_users
					WHERE username = :user AND password = MD5(:pass);";
						
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':user', $_POST['username'], PDO::PARAM_STR);
			$stmt->bindParam(':pass', $_POST['password'], PDO::PARAM_STR);
			$stmt->execute();
			$user_info = $stmt->fetch(PDO::FETCH_ASSOC);
			
			if ($user_info){
				
				if ($user_info['registered'] == 0){
					$login_results['warnings']['not_registered'] = 'username not registered';
				}
				
				$login_results['user_data'] = $user_info;
				
				if ($user_info['registered'] == 1){
					$_SESSION['Username'] = $user_info['username'];
					$_SESSION['UserId'] = $user_info['user_id'];
					$_SESSION['LoggedIn'] = date("Y-m-d H:i:s");
					
					$session = hash( "sha256", time(). $user_info['user_id'] );
					$token = hash( "sha256", time() ."token". $user_info['user_id'] );
	
					$token_sha256 = hash( "sha256", $token);
					
					$sql = "INSERT INTO roi_login_tokens (session_id, token, ip_address, user_id)
							VALUES (:session, :token, :ipaddress, :user)
							ON DUPLICATE KEY UPDATE
							session_id = :session, token = :token, user_id = :user;";
							
					$stmt = $this->_db->prepare($sql);
					$stmt->bindParam(':session', $session, PDO::PARAM_STR);
					$stmt->bindParam(':token', $token_sha256, PDO::PARAM_STR);
					$stmt->bindParam(':ipaddress', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
					$stmt->bindParam(':user', $user_info['user_id'], PDO::PARAM_INT);
					$stmt->execute();
	
					$_SESSION['Id'] = $session;
					$login_results['session_id'] = $session;
					
					if ($_POST['remember'] === 'true'){
						
						$login_results['token'] = $token;				
					}				
				}
				
			} else {
				
				$login_results['warnings']['no_user_found'] = 'no connection';
			}
			
			echo json_encode($login_results);
		}

		public function addNewUser(){
			$_GET['company'] = $_POST['company'];
			
			$users = count($this->retrieveUsers());
			$licenses = $this->company()['users'];

			if($licenses <= $users){
				echo 'no licenses';
				return;
			}
			
			$user = $this->getUserByUsername($_POST['username']);
			
			if($user){
				echo 'user exists';
				return;
			}

			$password = $_POST['password'];
			if(! $_POST['password']){
				$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789`-=~!@#$%^&*()_+,./<>?;:[]{}\|";
				
				$str = '';
				$max = strlen($chars) - 1;
				
				for ($i=0; $i < 20; $i++) {
					$str .= $chars[rand(0, $max)];
				}
				
				$password = $str;
			}

			$sql = "INSERT INTO roi_users (username, password, company_id, manager, first_name, last_name)
					VALUES (:username, :password, :company_id, :manager, :first, :last)";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
			$stmt->bindParam(':password', md5($password), PDO::PARAM_STR);
			$stmt->bindParam(':company_id', $_POST['company'], PDO::PARAM_INT);
			$stmt->bindParam(':manager', $_POST['manager'], PDO::PARAM_INT);
			$stmt->bindParam(':first', $_POST['first'], PDO::PARAM_STR);
			$stmt->bindParam(':last', $_POST['last'], PDO::PARAM_STR);
			$stmt->execute();

			$message = file_get_contents("https://www.theroishop.com/email/templates/register.html");
			$message = str_replace('%username%', $_POST['username'], $message);
			$message = str_replace('%password%', $password, $message);
			$message = str_replace('%name%', $_POST['username'], $message);

			$email = [];
			$email['message'] = $message;
			$email['subject'] = 'Welcome to the ROI Shop';

			$recipients = [];
			$recipients[] = "mfarber@theroishop.com";
			$recipients[] = "jachorn@theroishop.com";
			$recipients[] = $_POST['username'];
			
			$user_id = $this->_db->lastInsertId();

			foreach($_POST['templates'] as $template){

				$sql = "INSERT INTO roi_user_companies (user_id, structure_id)
						VALUES(:user, :structure)";

				$stmt = $this->_db->prepare($sql);
				$stmt->bindParam(':user', $user_id, PDO::PARAM_INT);
				$stmt->bindParam(':structure', $template, PDO::PARAM_INT);
				$stmt->execute();						
			}

			foreach($recipients as $recipient){
				$template = $email;
				$template['to'] = $recipient;

				$this->sendRoiShopEmail($template);
			}
		}

		public function getRoiValues(){
			$values = $_GET ? $_GET['roi_id'] : $_POST['roi_id'];
			
			$sql = "SELECT * FROM roi_stored_values 
					WHERE roi_id = :roi_id 
					ORDER BY `stored_dt` DESC, `session_id` DESC;";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi_id', $values, PDO::PARAM_INT);
			$stmt->execute();
			$values = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $values;
		}

		public function getOldValues(){

			$sql = "SELECT * FROM roi_values
					WHERE roiid = :roi_id
					ORDER BY dt ASC, sessionid ASC";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi_id', $_GET['roi_id'], PDO::PARAM_INT);
			$stmt->execute();
			$values = $stmt->fetchall(PDO::FETCH_ASSOC);

			// $sql = "SELECT id, value_array FROM roi_stored_values WHERE id > '0' AND id <= '2000'";

			// $stmt = $this->_db->prepare($sql);
			// $stmt->execute();
			// $value_arrays = $stmt->fetchall(PDO::FETCH_ASSOC);

			// foreach($value_arrays as $value_array){
			// 	$value_to_store = json_encode(unserialize(base64_decode(gzuncompress($value_array['value_array']))));
			// 	$id = $value_array['id'];

			// 	$sql = "UPDATE roi_stored_values SET deserialize = :value_array WHERE id = :value_id;";
	
			// 	$stmt = $this->_db->prepare($sql);
			// 	$stmt->bindParam(':value_array', $value_to_store, PDO::PARAM_STR);
			// 	$stmt->bindParam(':value_id', $id, PDO::PARAM_INT);
			// 	$stmt->execute();
			// }
			
			return $values;
		}

		public function retrieveUserRois(){
			
			$structure_versions = $this->retrieveAllStructures();
			$version_levels = $this->versionLevels();

			$sql = "SELECT * FROM ep_created_rois LEFT JOIN roi_structure_versions
						ON ep_created_rois.roi_version_id = roi_structure_versions.version_id
						WHERE user_id = ?
					ORDER BY roi_id DESC;";
						
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $_SESSION['UserId'], PDO::PARAM_INT);
			$stmt->execute();
			$user_rois = $stmt->fetchall(PDO::FETCH_ASSOC);
				
			$roi_count = 0;
			$tags = [];
			foreach($user_rois as $roi){
				$version_level = array_keys(array_column($structure_versions,'version_id'), $user_rois[$roi_count]['version_id']);
				$roi_link = array_keys(array_column($version_levels,'version_level_id'), $structure_versions[$version_level[0]]['ep_version_level']);
				$user_rois[$roi_count]['roi_full_path'] = '../' . $version_levels[$roi_link[0]]['version_path'] . '?roi=' . $user_rois[$roi_count]['roi_id'];
				
				$user_rois[$roi_count]['formatted_date'] = date('M j Y g:i A', strtotime($user_rois[$roi_count]['dt']));
				$tags = array_unique(array_merge($tags, explode(",",$user_rois[$roi_count]['tags'])));
				$roi_count++;
			}

			return $user_rois;
		}

		public function getUser($user_id){

			$sql = "SELECT * FROM roi_users WHERE user_id = :user_id;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
			$stmt->execute();
			$user = $stmt->fetch(PDO::FETCH_ASSOC);

			return $user;			
		}

		public function roiSpecs(){
			$roi = $_GET ? $_GET['roi_id'] : $_POST['roi_id'];
			
			$sql = "SELECT * FROM ep_created_rois WHERE roi_id = :roi_id;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi_id', $roi, PDO::PARAM_INT);
			$stmt->execute();
			$roiSpecs = $stmt->fetch(PDO::FETCH_ASSOC);

			$roiSpecs['createdBy'] = $this->getUser($roiSpecs['user_id'])['username'];

			return $roiSpecs;
		}

		public function createClone(){

			$sql = "SELECT * FROM ep_created_rois WHERE roi_id = :roi_id;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi_id', $_POST['roi_id'], PDO::PARAM_INT);
			$stmt->execute();
			$roi = $stmt->fetch(PDO::FETCH_ASSOC);

			$verificaiton_code = sha1(uniqid(mt_rand(), true));
			$sql = "INSERT INTO ep_created_rois (user_id, roi_title, roi_version_id, verification_code, currency, cloned_from_parent)
					VALUES(:user, :title, :version, :verification, :currency, :parent);";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':user', $roi['user_id'], PDO::PARAM_INT);
			$stmt->bindParam(':title', $_POST['title'], PDO::PARAM_STR);
			$stmt->bindParam(':version', $roi['roi_version_id'], PDO::PARAM_INT);
			$stmt->bindParam(':verification', $verificaiton_code, PDO::PARAM_STR);
			$stmt->bindParam(':currency', $roi['currency'], PDO::PARAM_STR);
			$stmt->bindParam(':parent', $_POST['roi_id'], PDO::PARAM_INT);
			$stmt->execute();
			
			$roi_id = $this->_db->lastInsertId();

			$values = $this->getRoiValues();
			
			$sql = "INSERT INTO roi_stored_values (roi_id, value_array)
					VALUES(:roi_id, :value_array)";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi_id', $roi_id, PDO::PARAM_INT);
			$stmt->bindParam(':value_array', $values[0]['value_array'], PDO::PARAM_STR);
			$stmt->execute();

			$sql = "SELECT version_path FROM roi_version_levels 
					WHERE version_level_id = (
						SELECT ep_version_level FROM roi_structure_versions
						WHERE version_id = :version_level
					)";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':version_level', $roi['roi_version_id'], PDO::PARAM_INT);
			$stmt->execute();
			
			$path = $stmt->fetch(PDO::FETCH_ASSOC);

			echo '../' . $path['version_path'] . '?roi=' . $roi_id;
		}

		public function versionLevels(){

			$sql = "SELECT * FROM roi_version_levels";

			$stmt = $this->_db->prepare($sql);
			$stmt->execute();
			$levels = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $levels;
		}

		public function retrieveStructureVersions(){

			$sql = "SELECT * FROM roi_structure_versions WHERE structure_id = :structure;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':structure', $_GET['structure'], PDO::PARAM_INT);
			$stmt->execute();
			$versions = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $versions;			
		}

		public function retrieveCompanyStructures(){
			
			$sql = "SELECT * FROM roi_company_structures WHERE company_id = :company;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':company', $_GET['company'], PDO::PARAM_INT);
			$stmt->execute();
			$structures = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $structures;
		}

		public function retrieveCompanyRois() {

			$rois = $this->retrieveRoisByCompany();
			$users = $this->retrieveUsers();
			$versions = $this->retrieveAllStructures();
			$version_levels = $this->versionLevels();

			$count = 0;
			foreach($rois as $roi){
				$key = array_keys(array_column($users, 'user_id'), $roi['user_id']);
				if($key){
					$rois[$count]['username'] = $users[$key[0]]['username'];
				}

				$rois[$count]['created_dt'] = date('F j, Y', strtotime($rois[$count]['dt']));

				$version_key = array_keys(array_column($versions, 'version_id'), $roi['roi_version_id']);
				$key = array_keys(array_column($version_levels, 'version_level_id'), $versions[$version_key[0]]['ep_version_level']);

				if($key){
					$hyperlink = 'https://www.theroishop.com/' . $version_levels[$key[0]]['version_path'] . '?roi=' . $rois[$count]['roi_id'] . '&v=' . $rois[$count]['verification_code'];
					$rois[$count]['link_to_roi'] = '<a href="' . $hyperlink . '" target="_blank">' . $hyperlink . '</a>';
				}
				$count++;
			}

			return $rois;
		}

		public function updateCompanyLicenses(){

			$sql = "UPDATE roi_companies SET users = :licenses
					WHERE company_id = :company_id";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':licenses', $_POST['licenses'], PDO::PARAM_INT);
			$stmt->bindParam(':company_id', $_POST['company_id'], PDO::PARAM_INT);
			$stmt->execute();			
		}

		public function retrieveUserPermissions(){

			$sql = "SELECT * FROM roi_user_permissions
					WHERE user_id = :user_id;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':user_id', $_SESSION['UserId'], PDO::PARAM_INT);
			$stmt->execute();
			$permissions = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $permissions;			
		}

		public function retrieveAllStructures(){
			$sql = "SELECT * FROM roi_structure_versions";

			$stmt = $this->_db->prepare($sql);
			$stmt->execute();
			$versions = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $versions;			
		}

		public function retrieveCompanyTemplates(){

			$sql = "SELECT * FROM roi_structure_versions
					WHERE structure_id IN (
						SELECT structure_id FROM roi_company_structures
						WHERE company_id = :company
					);";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':company', $_GET['company'], PDO::PARAM_INT);
			$stmt->execute();
			$templates = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $templates;					
		}

		public function version(){
			
			$sql = "SELECT * FROM roi_structure_versions
					WHERE version_id = :version";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':version', $_GET['version'], PDO::PARAM_INT);
			$stmt->execute();
			$version = $stmt->fetch(PDO::FETCH_ASSOC);

			return $version;				
		}

		public function versionByRoi(){
			
			$sql = "SELECT * FROM roi_structure_versions
					WHERE version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi_id
					)";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi_id', $_GET['roi_id'], PDO::PARAM_INT);
			$stmt->execute();
			$version = $stmt->fetch(PDO::FETCH_ASSOC);

			$version['structure'] = $this->structureById($version['structure_id']);

			return $version;				
		}

		public function structureById($structure_id){
			
			$sql = "SELECT * FROM roi_company_structures
					WHERE structure_id = :structure_id";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':structure_id', $structure_id, PDO::PARAM_INT);
			$stmt->execute();
			$structure = $stmt->fetch(PDO::FETCH_ASSOC);

			$structure['company'] = $this->companyById($structure['company_id']);

			return $structure;				
		}

		public function companyById($company_id){

			$sql = "SELECT * FROM roi_companies
					WHERE company_id = :company_id";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);
			$stmt->execute();
			$company = $stmt->fetch(PDO::FETCH_ASSOC);

			$company['users'] = $this->usersByCompany($company_id);

			return $company;				
		}

		public function usersByCompany($company_id){
			
			$sql = "SELECT * FROM roi_users
					WHERE company_id = :company_id";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);
			$stmt->execute();
			$users = $stmt->fetchall(PDO::FETCH_ASSOC);

			$count = 0;
			foreach($users as $user){
				$users[$count]['rois'] = $this->roisByUser($user['user_id']);
				$count++;
			}

			return $users;
		}

		public function roisByUser($user_id){
			
			$sql = "SELECT * FROM ep_created_rois
					WHERE user_id = :user";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':user', $user_id, PDO::PARAM_INT);
			$stmt->execute();
			$rois = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $rois;
		}

		public function company(){
			
			$sql = "SELECT * FROM roi_companies
					WHERE company_id = :company;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':company', $_GET['company'], PDO::PARAM_INT);
			$stmt->execute();
			$company = $stmt->fetch(PDO::FETCH_ASSOC);

			$company['templates'] = $this->structures($company['company_id']);

			return $company;			
		}

		public function retrieveCompanySpecs(){

			$sql = "SELECT * FROM roi_companies
					WHERE company_id = :company;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':company', $_GET['company'], PDO::PARAM_INT);
			$stmt->execute();
			$company = $stmt->fetch(PDO::FETCH_ASSOC);

			$company['templates'] = $this->structuresByCompany($company['company_id']);

			return $company;					
		}

		public function structures($company){
			
			$sql = "SELECT * FROM roi_company_structures
					WHERE company_id = :company;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':company', $company, PDO::PARAM_INT);
			$stmt->execute();
			$structures = $stmt->fetchall(PDO::FETCH_ASSOC);

			$count = 0;
			foreach($structures as $structure){
				$structures[$count]['versions'] = $this->versionsByStructure($structure['structure_id']);
				$count++;
			}
			
			return $structures;				
		}

		public function structuresByCompany($company){
			
			$sql = "SELECT * FROM roi_company_structures
					WHERE company_id = :company;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':company', $company, PDO::PARAM_INT);
			$stmt->execute();
			$structures = $stmt->fetchall(PDO::FETCH_ASSOC);

			$count = 0;
			foreach($structures as $structure){
				$structures[$count]['versions'] = $this->versionsByStructure($structure['structure_id']);
				$structures[$count]['users'] = $this->usersByVersion($structure['structure_id']);
				$count++;
			}
			
			return $structures;			
		}

		public function versionsByStructure($structure){
			
			$sql = "SELECT * FROM roi_structure_versions
					WHERE structure_id = :structure;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':structure', $structure, PDO::PARAM_INT);
			$stmt->execute();
			$versions = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $versions;			
		}

		public function usersByVersion($structure){
			
			$sql = "SELECT user_id FROM roi_user_companies
					WHERE structure_id = :structure;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':structure', $structure, PDO::PARAM_INT);
			$stmt->execute();
			$users = $stmt->fetchall(PDO::FETCH_ASSOC);

			$count = 0;
			foreach($users as $user){
				$users[$count] = $this->userById($user['user_id']);
				$count++;
			}

			return $users;				
		}

		public function userById($user_id){
			
			$sql = "SELECT user_id, username, verification_code, verified, registered, company_id, phone, manager, created_dt, first_name, last_name, currency, status FROM roi_users
					WHERE user_id = :user_id;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
			$stmt->execute();
			$user = $stmt->fetch(PDO::FETCH_ASSOC);

			$user['rois'] = $this->roisByUser($user['user_id']);		

			return $user;				
		}

		public function sendRoiShopEmail($email){

			$subject = $email['subject'] ? $email['subject'] : 'No subject';
			$recipient = $email['to'];
			$bodyHtml = $email['message'];
			$bodyText =  "HTML Emails need to be enabled to see the email's contents.";

			if(! $email['to']) return;

			$sender = 'noreply@theroishop.com';
			$senderName = 'The ROI Shop';

			$usernameSmtp = 'AKIA4FBYV4FACMAOYEFG';
			$passwordSmtp = 'BG2j2KEcR5QOtTpDOY7YbJ5Is+tYcsfZzPofo7gxOR99';

			$host = 'email-smtp.us-east-1.amazonaws.com';
			$port = 587;

			$mail = new PHPMailer(true);

			try {
				// Specify the SMTP settings.
				$mail->isSMTP();
				$mail->setFrom($sender, $senderName);
				$mail->Username   = $usernameSmtp;
				$mail->Password   = $passwordSmtp;
				$mail->Host       = $host;
				$mail->Port       = $port;
				$mail->SMTPAuth   = true;
				$mail->SMTPSecure = 'tls';
				//$mail->addCustomHeader('X-SES-CONFIGURATION-SET', $configurationSet);
			
				// Specify the message recipients.
				$mail->addAddress($recipient);
				// You can also add CC, BCC, and additional To recipients here.
			
				// Specify the content of the message.
				$mail->isHTML(true);
				$mail->Subject    = $subject;
				$mail->Body       = $bodyHtml;
				$mail->AltBody    = $bodyText;
				$mail->Send();
			} catch (phpmailerException $e) {

			} catch (Exception $e) {

			}
		}

		public function getUserByUsername($username){

			$sql = "SELECT * FROM roi_users WHERE username = :user";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':user', $username, PDO::PARAM_STR);
			$stmt->execute();
			$user = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $user;				
		}

		public function retrieveRoisByCompany(){

			$sql = "SELECT * FROM ep_created_rois 
					WHERE user_id IN (
						SELECT user_id FROM roi_users
						WHERE company_id = :company
					)
					ORDER BY dt DESC;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':company', $_GET['company'], PDO::PARAM_INT);
			$stmt->execute();
			$rois = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $rois;				
		}

		public function retrieveUserTemplates(){

			$sql = "SELECT * FROM roi_user_companies
					WHERE user_id IN (
						SELECT user_id FROM roi_users
						WHERE company_id = :company
					);";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':company', $_GET['company'], PDO::PARAM_INT);
			$stmt->execute();
			$templates = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $templates;
		}

		public function retrieveUsers(){

			$sql = "SELECT roi_users.username, roi_users.user_id, roi_users.manager, COUNT(ep_created_rois.user_id) AS user_rois FROM roi_users
					LEFT JOIN ep_created_rois ON ep_created_rois.user_id = roi_users.user_id
					WHERE roi_users.user_id IN (
						SELECT user_id FROM roi_users 
						WHERE company_id = :company
					) 
					GROUP BY roi_users.user_id
					ORDER BY roi_users.username";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':company', $_GET['company'], PDO::PARAM_INT);
			$stmt->execute();
			$users = $stmt->fetchall(PDO::FETCH_ASSOC);

			$templates = $this->retrieveUserTemplates();

			$count = 0;
			foreach($users as $user){
				$user_templates = array_keys(array_column($templates,'user_id'), $users[$count]['user_id']);
				foreach($user_templates as $template){
					$users[$count]['templates'][] = $templates[$template];
				}

				$users[$count]['value'] = $user['user_id'];
				$users[$count]['text'] = $user['username'];
				$count++;
			}
			
			return $users;					
		}

		public function changeManager(){

			$sql = "UPDATE roi_users SET manager = :manager
					WHERE user_id = :user;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':manager', $_POST['manager'], PDO::PARAM_INT);
			$stmt->bindParam(':user', $_POST['user'], PDO::PARAM_INT);
			$stmt->execute();
		}

		public function deleteUser(){

			$sql = "DELETE FROM roi_users WHERE user_id = :user;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':user', $_POST['user'], PDO::PARAM_INT);
			$stmt->execute();			
		}

		public function deleteRoi(){

			$sql = "DELETE FROM ep_created_rois WHERE roi_id = :roi;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->execute();			
		}

		public function transferRoi(){
			
			$sql = "UPDATE ep_created_rois SET user_id = :user_id WHERE roi_id = :roi_id";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':user_id', $_POST['user_id'], PDO::PARAM_INT);
			$stmt->bindParam(':roi_id', $_POST['roi_id'], PDO::PARAM_INT);
			$stmt->execute();			
		}

		public function transferRois(){

			$sql = "UPDATE ep_created_rois SET user_id = :target WHERE user_id = :user";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':target', $_POST['target'], PDO::PARAM_INT);
			$stmt->bindParam(':user', $_POST['user'], PDO::PARAM_INT);
			$stmt->execute();			
		}

		public function getAllCompanies() {
			
			$sql = "SELECT * FROM roi_companies ORDER BY company_name;";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->execute();
			$companies = $stmt->fetchall(PDO::FETCH_ASSOC);
				
			return $companies;					
		}

		public function updateUser(){

			$user = $this->getUserByUsername($_POST['username']);
			
			if($user){
				echo 'user exists';
				return;
			}
			
			$password = $_POST['password'];
			if(! $_POST['password']){
				$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789`-=~!@#$%^&*()_+,./<>?;:[]{}\|";
				
				$str = '';
				$max = strlen($chars) - 1;
				
				for ($i=0; $i < 20; $i++) {
					$str .= $chars[rand(0, $max)];
				}
				
				$password = $str;
			}

			foreach($_POST['availTemplates'] as $template){
				
				$sql = "DELETE FROM roi_user_companies WHERE user_id = :user and structure_id = :structure;";

				$stmt = $this->_db->prepare($sql);
				$stmt->bindParam(':user', $user_id, PDO::PARAM_INT);
				$stmt->bindParam(':structure', $template, PDO::PARAM_INT);
				$stmt->execute();			
			}

			foreach($_POST['templates'] as $template){

				$sql = "INSERT INTO roi_user_companies (user_id, structure_id)
						VALUES(:user, :structure)";

				$stmt = $this->_db->prepare($sql);
				$stmt->bindParam(':user', $user_id, PDO::PARAM_INT);
				$stmt->bindParam(':structure', $template, PDO::PARAM_INT);
				$stmt->execute();						
			}
			
			$sql = "UPDATE roi_users SET username = :username, password = :password
					WHERE user_id = :user;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
			$stmt->bindParam(':password', md5($password), PDO::PARAM_STR);
			$stmt->bindParam(':user', $_POST['user'], PDO::PARAM_INT);
			$stmt->execute();

			$message = file_get_contents("https://www.theroishop.com/email/templates/register.html");
			$message = str_replace('%username%', $_POST['username'], $message);
			$message = str_replace('%password%', $password, $message);
			$message = str_replace('%name%', $_POST['username'], $message);

			$email = [];
			$email['message'] = $message;
			$email['subject'] = 'Welcome to the ROI Shop';
			$email['to'] = $_POST['username'];

			$this->sendRoiShopEmail($email);
		}

		public function createPdf(){
			$root = realpath($_SERVER["DOCUMENT_ROOT"]);
			$pdf = $this->getPdfById();
			$stored_values = $this->getRoiValues();
			$roi = $this->roiSpecs();
			$user = $this->roiOwner();
			$graphs = $_GET['graphs'];
			
			$current_values = json_decode($stored_values[0]['value_array'], true);
						
			$pdfhtml = $pdf['html'];
			
			foreach($current_values as $value){
				$pdfhtml = str_replace("<formatted>" . $value['address'] . "</formatted>", $value['formattedValue'], $pdfhtml);
			}

			$pdfhtml = str_replace('<tag>roi_id</tag>', $_GET['roi'], $pdfhtml);
			$pdfhtml = str_replace('<tag>Companyname</tag>', $roi['roi_title'], $pdfhtml);
			$pdfhtml = str_replace('<tag>DatePrepared</tag>',  date("F j, Y"), $pdfhtml);
			$pdfhtml = str_replace('<tag>Preparedby</tag>',  $user['first_name'] . ' ' . $user['last_name'], $pdfhtml);
			$pdfhtml = str_replace('<tag>Email</tag>',  $user['username'], $pdfhtml);
			$pdfhtml = str_replace('<tag>Phone</tag>',  $user['phone'], $pdfhtml);
			$pdfhtml = str_replace('<tag>LinktoCalculator</tag>', '<a href="' . $_GET['roiPath'] . '">Link to the ROI</a>', $pdfhtml);
			$pdfhtml = str_replace('<tag>RoiPath</tag>', $_GET['roiPath'], $pdfhtml);
			
			foreach($graphs as $graph){
				$pdfhtml = str_replace('<tag>graph' . $graph['id'] . '</tag>', 'https://export.highcharts.com/' . $graph['graph'], $pdfhtml);
			}

			$report = '<html><head>' . $pdf['css'] . '</head><body class="pdfbody">' . $pdfhtml . '</body></html>';

			$stylesheet = file_get_contents("$root/webapps/assets/css/pdfstyle.css");
			$comp_stylesheet = file_get_contents("$root/webapps/assets/css/style.css");
			
			$mpdf = new mPDF('s', $pdf['orientation']);
			
			$mpdf->WriteHTML($stylesheet,1);
			$mpdf->WriteHTML($comp_stylesheet,1);
				
			$mpdf->WriteHTML($report);
			
			$mpdf->Output("$root/webapps/assets/customwb/10016/pdf/" . $roi['roi_title'] . ".pdf",'F');			
		}

		// public function sendBatchPaycor(){

		// 	$sql = "SELECT * FROM roi_users WHERE user_id > 12337;";

		// 	$stmt = $this->_db->prepare($sql);
		// 	$stmt->execute();
		// 	$users = $stmt->fetchall(PDO::FETCH_ASSOC);

		// 	foreach($users as $user){
		// 		$message = file_get_contents("https://www.theroishop.com/email/templates/register.html");
		// 		$message = str_replace('%username%', $user['username'], $message);
		// 		$message = str_replace('%password%', 'Paycor1', $message);
		// 		$message = str_replace('%name%', '', $message);
	
		// 		$email = [];
		// 		$email['message'] = $message;
		// 		$email['subject'] = 'Welcome to the ROI Shop';
		// 		$email['to'] = $user['username'];

		// 		$this->sendRoiShopEmail($email);
		// 	}
		// }		
	}

?>