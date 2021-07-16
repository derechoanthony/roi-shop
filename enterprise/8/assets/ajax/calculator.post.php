<?php

	// Establish connection to the database
	
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/db_connection.php");

	$roiActions = new RoiActions($db);
	switch($_POST['action']){
		case 'resetVerification':
			$verification = $roiActions->resetVerification();
			echo $verification;
		break;

		case 'removeHiddenSections':
			$roiActions->removeHiddenSections();
		break;

		case 'hideSection':
			$roiActions->hideSection();
		break;

		case 'exchangeRate':
			$roiActions->exchangeRate();
		break;

		case 'updateCurrency':
			$roiActions->updateCurrency();
		break;

		case 'storeValues':
			$roiActions->storeValues();
		break;

		case 'addcont':
			$roiActions->addContributor();
		break;

		case 'delcont':
			$roiActions->delContributor();
		break;

		case 'linkToOpportunity':
			$roiActions->linkToOpportunity();
		break;

		case 'updateSFRecord':
			$roiActions->updateSFRecord();
		break;

		case 'storePdf':
			$roiActions->storePdf();
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

		public function resetVerification() {
			$verification = sha1(uniqid(mt_rand(), true));
			
			$sql = "UPDATE ep_created_rois SET verification_code = ?
					WHERE roi_id = ?";
		
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $verification, PDO::PARAM_STR);
			$stmt->bindParam(2, $_POST['roi'], PDO::PARAM_INT);
			$stmt->execute();
			
			return $verification;
		}

		public function removeHiddenSections(){
			
			$sql = "DELETE FROM hidden_entities
					WHERE roi = ? AND type='section'";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $_POST['roi'], PDO::PARAM_INT);
			$stmt->execute();
		}

		public function hideSection(){
			
			$sql = "INSERT INTO hidden_entities (`type`, `entity_id`, `roi`)
					VALUES ('section', ?, ?);";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $_POST['section'], PDO::PARAM_INT);
			$stmt->bindParam(2, $_POST['roi'], PDO::PARAM_INT);
			$stmt->execute();			
		}

		public function exchangeRate(){

			$getGMT = gmdate("Y-m-d H:i:s");
			
			$sql = "UPDATE exchange_rates SET `rate` = ?, `dt` = ?
					WHERE currency = ?";
						
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $_POST['rate'], PDO::PARAM_STR);
			$stmt->bindParam(2, $getGMT, PDO::PARAM_STR);
			$stmt->bindParam(3, $_POST['currency'], PDO::PARAM_STR);
			$stmt->execute();
		}

		public function updateCurrency(){
			
			$sql = "UPDATE ep_created_rois SET currency = ?
					WHERE roi_id = ?";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $_POST['language'], PDO::PARAM_STR);
			$stmt->bindParam(2, $_POST['roi'], PDO::PARAM_INT);
			$stmt->execute();
		}

		public function storeValues(){

			$storedValues = base64_encode(serialize($_POST['values']));
			$storedValues = gzcompress($storedValues);

			$sql = "SELECT session_id FROM roi_stored_values where session_id = ? AND roi_id = ?;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $_SESSION['Id'], PDO::PARAM_STR);
			$stmt->bindParam(2, $_POST['roi'], PDO::PARAM_INT);
			$stmt->execute();
			
			$record = $stmt->fetch(PDO::FETCH_ASSOC);

			if(count($record['session_id']) > 0){
				
				$sql = "UPDATE roi_stored_values SET roi_id = :roi, session_id = :session, value_array = :value, stored_dt = NOW()
						WHERE roi_id = :roi AND session_id = :session;";
			} else {
				
				$sql = "INSERT INTO roi_stored_values (`roi_id`, `session_id`, `value_array`, `stored_dt`)
						VALUES (:roi, :session, :value, NOW())";			
			}

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->bindParam(':session', $_SESSION['Id'], PDO::PARAM_STR);
			$stmt->bindParam(':value', $storedValues, PDO::PARAM_STR);
			$stmt->execute();
		}

		public function addContributor(){
			
			$sql = "INSERT INTO ep_roi_allowed_emails (`roi_id`,`email_address`)
					VALUES(:roi, :name)";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':name', $_POST['cont'], PDO::PARAM_STR);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->execute();
			echo $_POST['roi'];

			$sql = "UPDATE ep_created_rois SET email_protected = 1
					WHERE roi_id = :roi;";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->execute();		
		}

		public function delContributor(){

			$sql = "DELETE FROM ep_roi_allowed_emails
					WHERE auto_id=:id";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch();

			$sql = "SELECT COUNT(auto_id) as emails FROM ep_roi_allowed_emails
					WHERE roi_id = :roi;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch();

			if($data['emails'] == 0){
				
				$sql = "UPDATE ep_created_rois SET email_protected = 0
						WHERE roi_id = :roi;";
				
				$stmt = $this->_db->prepare($sql);
				$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
				$stmt->execute();				
			}			
		}

		public function linkToOpportunity(){
			
			$sql = "UPDATE ep_created_rois SET sfdc_link = ?, linked_title = ?
					WHERE roi_id = ?";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $_POST['linked_id'], PDO::PARAM_STR);
			$stmt->bindParam(2, $_POST['linked_title'], PDO::PARAM_STR);
			$stmt->bindParam(3, $_POST['roi_id'], PDO::PARAM_INT);
			$stmt->execute();			
		}

		public function updateSFRecord(){
			
			$curl = curl_init();
		
			$header = array();
			$header[] = 'Content-Type: application/json';
			$header[] = 'Authorization: Element '. $_POST['user_id'] .', User eMYXjLZ2v/DwUYYc+NnJ/MISDJ9nr/qvsBhls4+K8Dw=';
			
			$updated_fields = $_POST['fields'];
			
			curl_setopt_array( $curl, array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_CUSTOMREQUEST => 'PATCH',
				CURLOPT_POSTFIELDS => $updated_fields,
				CURLOPT_URL => 'https://console.cloud-elements.com:443/elements/api-v2/hubs/crm/opportunities/' . $_POST['linked_id'],
				CURLOPT_SSL_VERIFYPEER => true,
				CURLOPT_CAINFO => dirname(__FILE__) . '/cacert.pem',
				CURLOPT_HTTPHEADER => $header
			));
	
			$resp = curl_exec($curl);
			$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			
			if($resp === false) { echo curl_error($curl); } else { echo $httpcode; echo $resp; }
			curl_close($curl);			
		}

		public function storePdf(){
			$root = realpath($_SERVER["DOCUMENT_ROOT"]);
			
			$img = urldecode($_POST['image']);
			$img = file_get_contents($img);
			
			$roi = $_POST['roi'];
			$id = $_POST['id'];	

			file_put_contents("$root/enterprise/9/assets/images/" . $roi . "chart" . $id . ".png", $img);			
		}
	}

?>