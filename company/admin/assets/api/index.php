<?php

	// Establish connection to the database
	
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/db_connection.php");
	require_once("$root/email/swiftmailer/lib/swift_required.php");

	$apiActions = new ApiActions($db);
	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		switch($_POST['action']){
			case 'addNewUser':
				$apiActions->addNewUser();
			break;

			case 'changeManager':
				$apiActions->changeManager();
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

			case 'createClone':
				$apiActions->createClone();
			break;
		}	
	} else {
		switch($_GET['action']){
			case 'companySpecs':
				$specs = [];
				$specs['users'] = $apiActions->retrieveUsers();
				$specs['rois'] = $apiActions->retrieveUserRois();
				$specs['company'] = $apiActions->retrieveCompanySpecs();
				$specs['permissions'] = $apiActions->retrieveUserPermissions();

				echo json_encode($specs);
			break;

			case 'retrieveUsers':
				echo json_encode($apiActions->retrieveUsers());
			break;

			case 'retrieveRoisByCompany':
				echo json_encode($apiActions->retrieveUserRois());
			break;

			case 'retrieveUserRois':
				echo json_encode($apiActions->retrieveUserRois());
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

		public function addNewUser(){
			$_GET['company'] = $_POST['company'];
			
			$users = count($this->retrieveUsers());
			$licenses = $this->retrieveCompanySpecs()['users'];

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
			$email['to'] = $_POST['username'];
			
			$user_id = $this->_db->lastInsertId();

			foreach($_POST['templates'] as $template){

				$sql = "INSERT INTO roi_user_companies (user_id, structure_id)
						VALUES(:user, :structure)";

				$stmt = $this->_db->prepare($sql);
				$stmt->bindParam(':user', $user_id, PDO::PARAM_INT);
				$stmt->bindParam(':structure', $template, PDO::PARAM_INT);
				$stmt->execute();						
			}

			$this->sendRoiShopEmail($email);
		}

		public function getRoiValues(){

			$sql = "SELECT * FROM roi_stored_values 
					WHERE roi_id = :roi_id 
					ORDER BY `stored_dt` DESC, `session_id` DESC;";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi_id', $_POST['roi_id'], PDO::PARAM_INT);
			$stmt->execute();
			$values = $stmt->fetchall(PDO::FETCH_ASSOC);

			return $values;
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

		public function retrieveUserRois() {

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

		public function retrieveCompanySpecs(){

			$sql = "SELECT * FROM roi_companies
					WHERE company_id = :company;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':company', $_GET['company'], PDO::PARAM_INT);
			$stmt->execute();
			$company = $stmt->fetch(PDO::FETCH_ASSOC);

			$company['templates'] = $this->retrieveCompanyTemplates();

			return $company;					
		}

		public function sendRoiShopEmail($email){

			$subject = $email['subject'] ? $email['subject'] : 'No subject';
			$to = $email['to'];
			$message = $email['message'];

			if(! $email['to']) return;

			$from = array('noreply@theroishop.com' => 'The ROI Shop');
			$bcc = array('jachorn@theroishop.com' => 'Jacob Achorn','mfarber@theroishop.com' => 'Mike Farber');

			$transport = Swift_AWSTransport::newInstance( 'AKIAIWQ3DPP7HAL33PIA', '7LtELaOSutm4jtPVOQI/Ucw/NuqKhCLctOiIxWVF' );
			$swift = Swift_Mailer::newInstance($transport);

			// Create a message (subject)
			$email = new Swift_Message($subject);
			
			// attach the body of the email
			$email->setFrom($from);
			$email->setBody($message, 'text/html');
			$email->setTo($to);
			$email->SetBcc($bcc);
			$email->addPart($text, 'text/plain');
			
			// send message 
			if ($recipients = $swift->send($email, $failures))
			{
			
			} else {
				
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

			$count = 0;
			foreach($users as $user){
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
	}

?>