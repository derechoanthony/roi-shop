<?php

	// Establish connection to the database
	
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/db_connection.php");
	
	$roiActions = new RoiActions($db);
	switch($_GET['action']){
		case 'RetrieveRoi':
			$roiValues = array();
			$roiValues['storedValues'] = $roiActions->roiStoredValues();
			$roiValues['versionBuild'] = $roiActions->versionBuild();
			$roiValues['sfIntegration'] = $roiActions->sfIntegration();
			$roiValues['verification'] = $roiActions->verification();
			$roiValues['roiSpecs'] = $roiActions->roiSpecs();

			echo json_encode($roiValues);
		break;

		case 'getcontributor':
			echo json_encode($roiActions->getContributors());
		break;

		case 'RetrievePDFGraph':
			echo json_encode($roiActions->retrievePdfGraph());
		break;

		case 'getPdf':
			echo json_encode($roiActions->getPdf());
		break;

		case 'createpdf':
			echo $roiActions->createPdf();
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

		public function retrievePdfGraph(){

			$sql = "SELECT * FROM pdf_graphs
					WHERE version = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					)";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi',$_GET['roi'],PDO::PARAM_INT);
			$stmt->execute();
			$cells = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $cells;					
		}

		public function versionBuild(){

			$sql = "SELECT * FROM ep_version_build
					WHERE roi_version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					);";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi',$_GET['roi'],PDO::PARAM_INT);
			$stmt->execute();
			$version_build = $stmt->fetch(PDO::FETCH_ASSOC);
			
			return $version_build;			
		}

		public function roiStoredValues(){

			$sql = "SELECT `value_array` FROM roi_stored_values
					WHERE roi_id = ?
					ORDER BY `stored_dt` DESC, `session_id` DESC";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$roiStoredValues = $stmt->fetchall(PDO::FETCH_ASSOC);

			$storedValues = [];
			for ($i = 0; $i < count($roiStoredValues); $i++){
				$storedValues[] = unserialize(base64_decode(gzuncompress($roiStoredValues[$i]['value_array'])));
			}
			
			return $storedValues;			
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

		public function isEmailProtected(){
			
			$sql = "SELECT email_protected FROM ep_created_rois WHERE roi_id = :roi;";
	
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
	
			return $stmt->fetch();
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
		
		public function roiSpecs(){
			
			$sql = "SELECT * FROM ep_created_rois
					WHERE roi_id = ?";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$roiSpecs = $stmt->fetch(PDO::FETCH_ASSOC);

			return $roiSpecs;			
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

		public function getPdf(){

			$sql = "SELECT * FROM ep_pdf_templates
					WHERE pdf_id = :id";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
			$stmt->execute();
			$pdf = $stmt->fetch(PDO::FETCH_ASSOC);

			return $pdf;
		}

		public function createPdf(){
			$root = realpath($_SERVER["DOCUMENT_ROOT"]);
			require_once("$root/vendor/autoload.php");
			
			$report = $this->pdfTemplate($_GET['reportId']);


			$orientation =  $report[0]['orientation'];
			$report = '<html><head>' . $report[0]['css'] . '</head><body class="pdfbody">' . $report[0]['html'] . '</body></html>';

			$stylesheet = file_get_contents("$root/webapps/assets/css/pdfstyle.css");
			$comp_stylesheet = file_get_contents("$root/webapps/assets/css/style.css");

			$roiSpecs = $this->roiSpecs();
			$report = str_replace('<tag>ROI Name</tag>', $roiSpecs['roi_title'], $report);
			$report = str_replace('<tag>Date</tag>',  date("F j, Y"), $report);
			$report = str_replace('<tag>ROI Link</tag>', '<a href="' . $_GET['roiPath'] . '">Link to the ROI</a>', $report);
			$report = str_replace('<tag>ROI Path</tag>', $_GET['roiPath'], $report);
			$report = str_replace('<tag>Owner Name</tag>',  $user['first_name'] . ' ' . $user['last_name'], $report);
			$report = str_replace('<tag>Phone Number</tag>',  $user['phone'], $report);
			$report = str_replace('<tag>Summary Graph</tag>', '<img src="http://www.theroishop.com/enterprise/7/assets/images/' . $roiSpecs['roi_id'] . 'barchartsummary.png" style="margin-left: 80px; margin-bottom: 30px;" width="800px">', $report);

			try{
				$mpdf = new \Mpdf\Mpdf([
					'format' => 'A4-L',
					'mode' => 's'
				]);
		
				$mpdf->WriteHTML(utf8_encode($report));
				$mpdf->Output("$root/webapps/assets/customwb/10016/pdf/preview-preview2.pdf",'F');
			} catch(\Mpdf\MpdfException $e){
				echo $e->getMessage();
			}
			
			// $mpdf->WriteHTML($stylesheet,1);
			// $mpdf->WriteHTML($comp_stylesheet,1);
				
			// $mpdf->WriteHTML($report);

			// $mpdf->Output("$root/webapps/assets/customwb/10016/pdf/preview-preview2.pdf",'F');	

			// echo 'something';
			
		}

		public function pdfTemplate($template){

			$sql = "SELECT html, css FROM ep_pdf_templates
					WHERE pdf_id = :pdf;";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':pdf',$template,PDO::PARAM_INT);
			$stmt->execute();
			$pdf = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $pdf;			
		}
	}

?>