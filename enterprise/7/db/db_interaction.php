<?php

class db_interaction {	
	
	private $_db;

	public function __construct($db=NULL) {
		
		if(is_object($db)) {
			$this->_db = $db;
		} else {
			$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
			$this->_db = new PDO($dsn, DB_USER, DB_PASS);
		}
	}

	public function discoveryDocuments(){

		$sql = "SELECT * FROM discovery_document
				WHERE company_id = (
					SELECT roi_version_id FROM ep_created_rois
					WHERE roi_id = :roi
				)";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$discoveryDocuments = $stmt->fetchall();
		
		return $discoveryDocuments;		
	}

	public function pdfDocuments(){

		$sql = "SELECT * FROM ep_pdf_templates
				WHERE ep_version_id = (
					SELECT roi_version_id FROM ep_created_rois
					WHERE roi_id = :roi
				);";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi',$_GET['roi'],PDO::PARAM_INT);
		$stmt->execute();
		$pdfs = $stmt->fetchall();
		
		return $pdfs;
	}

	public function userRois(){

		$sql = "SELECT * FROM ep_created_rois
				WHERE user_id = ?";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(1, $_SESSION['UserId'], PDO::PARAM_INT);
		$stmt->execute();
		$user_rois = $stmt->fetchall();
		
		return $user_rois;
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
		$roi_graphs = $stmt->fetchall();
		
		return $roi_graphs;		
	}

	public function hiddenSections(){
		
		$sql = "SELECT * FROM hidden_entities
				WHERE roi = ? AND type ='section'";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$hidden_sections = $stmt->fetchall();
	
		return $hidden_sections;		
	}

	public function roiSections(){
		
		$sql = "SELECT * FROM compsections
				WHERE compID = (
					SELECT roi_version_id FROM ep_created_rois
					WHERE roi_id = ?
				)";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$roi_sections = $stmt->fetchall();
	
		return $roi_sections;		
	}

	public function roiTemplateSpecs(){

		$sql = "SELECT * FROM comp_specs
				WHERE compID = (
					SELECT roi_version_id FROM ep_created_rois
					WHERE roi_id = ?
				)";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$template_specs = $stmt->fetch();
	
		return $template_specs;
	}

	public function testimonials(){

		$sql = "SELECT * FROM testimonials
				WHERE company_id = (
					SELECT roi_version_id FROM ep_created_rois
					WHERE roi_id = ?
				)";

		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$testimonials = $stmt->fetchall();

		return $testimonials;
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
		$elements = $stmt->fetchall();

		return $elements;		
	}

	public function roiElementChocies(){
		
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
		$element_choices = $stmt->fetchall();

		return $element_choices;		
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

	public function userAdmin() {
		$sql = "SELECT * FROM roi_user_companies
				WHERE user_id = ?
				AND company_id = (
					SELECT company_id FROM roi_company_structures WHERE structure_id = (
						SELECT roi_version_id FROM ep_created_rois WHERE roi_id = ? 
					)
				);";
				
		$stmt = $this->_db->prepare( $sql );
		$stmt->bindParam(1, $_SESSION['UserId'], PDO::PARAM_INT);
		$stmt->bindParam(2, $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$admin = $stmt->fetch();

		return $admin;				
	}

	public function sfIntegration(){

		$sql = "SELECT * FROM integration
				WHERE userid = ?;";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(1,$_SESSION['UserId'],PDO::PARAM_INT);
		$stmt->execute();
		$sfIntegration = $stmt->fetch();

		return $sfIntegration;		
	}

	public function isEmailProtected(){
		$sql = "SELECT email_protected FROM ep_created_rois WHERE roi_id = :roi;";

		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();

		return $stmt->fetch();
	}

	public function checkEmailVerification(){
		$sql = "SELECT * FROM ep_roi_allowed_emails WHERE roi_id = :roi AND email_address = :email";

		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi', $_POST['ref'], PDO::PARAM_INT);
		$stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
		$stmt->execute();

		return $stmt->fetchall();		
	}

	public function discoveryQuestions(){

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
		
		return $discoveryQuestions;		
	}

	public function get_roi_name() {
	
		$sql = "SELECT roi_title FROM ep_created_rois WHERE roi_id = ?";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$roi_name = $stmt->fetch();
	
		return $roi_name['roi_title'];		
	}
	
	public function get_roi_information() {
	
		$sql = "SELECT * FROM ep_created_rois WHERE roi_id = ?";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$roi_information = $stmt->fetch();
	
		return $roi_information;		
	}
	
	public function get_version_id() {
	
		$sql = "SELECT roi_version_id FROM ep_created_rois WHERE roi_id = ?";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$version_id = $stmt->fetch();
	
		return $version_id['roi_version_id'];		
	}
	
	public function check_verification() {
		
		$v = ( isset($_GET['v']) ? $_GET['v'] : ( isset($_GET['amp;v']) ? $_GET['amp;v'] : '' ) );
		
		$sql = "SELECT 1 FROM ep_created_rois WHERE roi_id = ? AND verification_code = ?";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
		$stmt->bindParam(2, $v, PDO::PARAM_STR);
		$stmt->execute();
		$verified = $stmt->fetch();
	
		return $verified;			
	}
	
	public function verify_owner() {

		$sql = "SELECT user_id FROM ep_created_rois WHERE roi_id = ? AND user_id = ?";
				
		$stmt = $this->_db->prepare( $sql );
		$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
		$stmt->bindParam(2, $_SESSION['UserId'], PDO::PARAM_INT);
		$stmt->execute();
		$owner = $stmt->fetch();

		return $owner['user_id'];	
	}
	
	public function check_privilege() {
		
		$sql = "SELECT has_dashboard_access FROM ep_admin_permissions WHERE user_id = ? AND company_id = (
					SELECT company_id FROM roi_company_structures WHERE structure_id = (
						SELECT roi_version_id FROM ep_created_rois WHERE roi_id = ? 
					)
				)";
				
		$stmt = $this->_db->prepare( $sql );
		$stmt->bindParam(1, $_SESSION['UserId'], PDO::PARAM_INT);
		$stmt->bindParam(2, $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$dashboard_access = $stmt->fetch();
		
		if($dashboard_access && $dashboard_access['has_dashboard_access'] == 1 || $_SESSION['UserId'] == 2) return true;
		return false;
	}
	
	public function verification_link_used() {
		
		self::update_view_statistics();
		self::create_guest_session();
		self::send_verification_email();
		
		$_SESSION['roi'] = $_GET['roi'];
	}
	
	public function update_view_statistics() {
		
		$sql = "UPDATE ep_created_rois SET visits = IFNULL(visits, 0) + 1 WHERE roi_id = ?";

		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		
		$is_unique_visit = self::insert_new_ip();
		if($is_unique_visit) {
			$this->add_unique_view();
		}	
	}
	
	public function insert_new_ip() {
		
		$sql = "INSERT INTO ip_info (roi, ip_address) 
				SELECT :roi, :ip FROM DUAL WHERE NOT EXISTS (
					SELECT * FROM ip_info WHERE roi = :roi AND ip_address = :ip
				) LIMIT 1;";
				
		$stmt = $this->_db->prepare( $sql );
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->bindParam(':ip', $_SERVER["HTTP_X_FORWARDED_FOR"], PDO::PARAM_STR);
		$stmt->execute();
		
		return $this->_db->lastInsertId();
	}
	
	public function add_unique_view() {

		$sql = "UPDATE ep_created_rois SET unique_ip = IFNULL(unique_ip, 0) + 1 WHERE roi_id = ?";

		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
	}
	
	public function create_guest_session() {

		$sql = "INSERT INTO roi_sessions (guest_id) VALUES (?);";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(1, $_SERVER["HTTP_X_FORWARDED_FOR"], PDO::PARAM_INT);
		$stmt->execute();
		$sesssion_id = $this->_db->lastInsertId();
		$_SESSION['Id'] = $sessionId;	
	}
	
	public function get_ip_information() {
		
		$curl_handle = curl_init();
		$address = $_SERVER["HTTP_X_FORWARDED_FOR"];
		$api_key = 'b4a21f9ee4ded936cacbe174c2798372cf70b91b';
		
		$curl_url = 'http://api.db-ip.com/addrinfo?addr='. $address .'&api_key='. $api_key;
		
		curl_setopt_array(
			$curl_handle,
			array(
				CURLOPT_URL => $curl_url,
				CURLOPT_RETURNTRANSFER => true
			)
		);

		$ip_info = json_decode(curl_exec($curl_handle));
		curl_close($curl_handle);

		return $ip_info;
	}
	
	public function get_user_email_preferences() {
		
		$sql = "SELECT user_id, email_template, last_sent, delay, when_to_send FROM roi_email_notifications WHERE roi_id = ?";
				
		$stmt = $this->_db->prepare( $sql );
		$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$email_preferences = $stmt->fetchall();

		return $email_preferences;		
	}
	
	public function get_user_info_by_id($id){

		$sql = "SELECT username, first_name, last_name, status FROM roi_users WHERE user_id = ?";
				
		$stmt = $this->_db->prepare( $sql );
		$stmt->bindParam(1, $id, PDO::PARAM_INT);
		$stmt->execute();
		$user_info = $stmt->fetch();

		return $user_info;		
	}
	
	public function get_roi_owner(){
		
		$sql = "SELECT username, first_name, last_name, status FROM roi_users WHERE user_id = (
					SELECT user_id FROM ep_created_rois WHERE roi_id = ? )";
	
		$stmt = $this->_db->prepare( $sql );
		$stmt->bindParam(1, $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$roi_owner = $stmt->fetch();

		return $roi_owner;	
	}

	public function addHit() {
		
		$sql = "SELECT COUNT(ID) FROM ip_info
	            WHERE roi=:roiID AND ip_address=:ip";

		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roiID', $_GET['roi'], PDO::PARAM_INT);
		$stmt->bindParam(':ip', $_SERVER["HTTP_X_FORWARDED_FOR"], PDO::PARAM_STR);
		$stmt->execute();
		$data = $stmt->fetch();
			
		$sql = "INSERT INTO ip_info (roi, ip_address)
				VALUES (:roiID, :ip)";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roiID', $_GET['roi'], PDO::PARAM_INT);
		$stmt->bindParam(':ip', $_SERVER["HTTP_X_FORWARDED_FOR"], PDO::PARAM_STR);
		$stmt->execute();
		$guestid = $this->_db->lastInsertId();
			
		$sql = "UPDATE ep_created_rois SET visits = IFNULL(visits, 0) + 1
				WHERE roi_id = :roiID"; 

		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roiID', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		
		if(! $_SESSINO['Id']){
			$session = hash( "sha256", time(). 'guest' );
			$token = hash( "sha256", time() ."token". 'guest' );
	
			$token_sha256 = hash( "sha256", $token);
			
			$sql = "INSERT INTO roi_login_tokens (session_id, token, ip_address)
					VALUES (:session, :token, :ipaddress)";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':session', $session, PDO::PARAM_STR);
			$stmt->bindParam(':token', $token_sha256, PDO::PARAM_STR);
			$stmt->bindParam(':ipaddress', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
			$stmt->execute();
			
			$_SESSION['Id'] = $session;
		}
		
		if( !$data['COUNT(ID)'] ) {
			
			$sql = "UPDATE ep_created_rois SET unique_ip = IFNULL(unique_ip, 0) + 1
					WHERE roi_id = :roiID"; 

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roiID', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
		}
	
		$this->mailConfirmation();
	
	}

	private function mailConfirmation(){

		$handle = curl_init();
		
		curl_setopt_array(
			$handle,
			array(
				CURLOPT_URL => 'http://api.db-ip.com/addrinfo?addr=' . $_SERVER["HTTP_X_FORWARDED_FOR"] . '&api_key=b4a21f9ee4ded936cacbe174c2798372cf70b91b',
				CURLOPT_RETURNTRANSFER => true
			)
		);

		$ipInfo = json_decode(curl_exec($handle));
		curl_close($handle);	
	
		$sql = "SELECT roi_title, visits, unique_ip FROM ep_created_rois
				WHERE roi_id=:roiID";
		
			try
			{
				$stmt = $this->_db->prepare($sql);
				$stmt->bindParam(':roiID', $_GET['roi'], PDO::PARAM_INT);
				$stmt->execute();
				$hits = $stmt->fetch();
				$ver = $hits['roi_title'];
			} catch(PDOException $e) {
	
			}
				
		$sql = "SELECT * FROM roi_users
				WHERE user_id = (
					SELECT user_id FROM ep_created_rois
					WHERE roi_id = :roi
				)";
		
			try
			{
				$stmt = $this->_db->prepare($sql);
				$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
				$stmt->execute();
				$data = $stmt->fetch();
			}
			catch(PDOException $e)
			{
				return FALSE;
			}

			$sql = "SELECT username, first_name, last_name FROM roi_users
					WHERE user_id = :manager";
			try
			{
				$stmt = $this->_db->prepare($sql);
				$stmt->bindParam(':manager', $data['manager'], PDO::PARAM_INT);
				$stmt->execute();
				$manager = $stmt->fetch();
			}
			catch(PDOException $e)
			{
				return FALSE;
			}

			//Get users that have opted out from roi email
			$sql = "SELECT Username
					FROM users
					WHERE UserID IN (
						SELECT user
						FROM optout
						WHERE roi = :roi
					)";
			try
			{
				$stmt = $this->_db->prepare($sql);
				$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
				$stmt->execute();
				$optout = $stmt->fetchall();
			}
			catch(PDOException $e)
			{
				return FALSE;
			}
			
			for($i=0;$i<count($optout);$i++)
			{
				$optedout[] = $optout[$i]['Username'];
			}
			
			//Define who the email is being sent by
			$setfrom = array('noreply@theroishop.com', 'The ROI Shop');
			
			/*******************************************/
			/*** SET WHO THE MESSAGE WILL BE SENT TO ***/
			/*******************************************/
			
			//Add Sales rep if they want to receive the email
			if( ! in_array ( $data['username'], $optedout ) ){
				$maillist[] = array( $data['username'], $data['first_name'] . ' ' . $data['last_name'], 'rep' );
			}
			
			//Check if ROI Shop employees still get emails for the ROI
			if( ! in_array ( 'mfarber@theroishop.com', $optedout ) ){
				$maillist[] = array( 'mfarber@theroishop.com', 'Mike Farber', 'roi' );
			}
			
			if( ! in_array ( 'jachorn@theroishop.com', $optedout ) ){
				$maillist[] = array( 'jachorn@theroishop.com', 'Jacob Achorn', 'roi' );
			}

			if( $manager ){ //Does sales rep have a manager defined?
				if( ! in_array ( $manager['username'], $optedout ) ){
					$maillist[] = array( $manager['username'], $manager['first_name'] . ' ' . $manager['last_name'], 'manager' );
				}
			}
			
		if( $maillist ){
				
			for($i=0;$i<count( $maillist );$i++){
				
				$to = array( $maillist[$i][0] => $maillist[$i][1] );				

				$from = array('noreply@theroishop.com' => 'The ROI Shop');

				//Create the subject line.
				$subject = $ver.' was just viewed!';
				
				$text = "HTML Emails need to be enabled to see the email's contents.";
				
				$message = file_get_contents(realpath($_SERVER['DOCUMENT_ROOT'])."/enterprise/7/assets/email/viewed.html");
				$message = str_replace('%name%', $ver, $message);
				$message = str_replace('%creator%', ($maillist[$i][2]!='rep'?$data['username']:''), $message);
				$message = str_replace('%ipaddress%', $_SERVER["HTTP_X_FORWARDED_FOR"], $message);
				$message = str_replace('%link%', ($maillist[$i][2]!='manager'?$_GET['roi']:$_GET['roi'].'&v='.$_GET['v']), $message);
				$message = str_replace('%views%', $hits['visits'], $message);
				$message = str_replace('%unique%', $hits['unique_ip'], $message);					
					
				foreach ($ipInfo as $k => $v) {
					switch($k) {
						case 'country':
							$message = str_replace('%country%', $v, $message);
							break;
						case 'stateprov':
							$message = str_replace('%state%', $v, $message);
							break;	
						case 'city':
							$message = str_replace('%city%', $v, $message);
							break;
					}
				}				
				
				// Setup Swift mailer parameters
				$transport = Swift_AWSTransport::newInstance( 'AKIAIWQ3DPP7HAL33PIA', '7LtELaOSutm4jtPVOQI/Ucw/NuqKhCLctOiIxWVF' );

				$swift = Swift_Mailer::newInstance($transport);
				
				// Create a message (subject)
				$email = new Swift_Message($subject);
				
				// attach the body of the email
				$email->setFrom($from);
				$email->setBody($message, 'text/html');
				$email->setTo($to);
				$email->addPart($text, 'text/plain');
				
				// send message 
				if ($recipients = $swift->send($email, $failures))
				{
				
				} else {
					
				}
				
			}
			
		}
			
	}
	
	public function send_verification_email() {
		
		$email_preferences = $this->get_user_email_preferences();
		$ip_info = $this->get_ip_information();
		
		$aws_transport = Swift_AWSTransport::newInstance('AKIAIWQ3DPP7HAL33PIA','7LtELaOSutm4jtPVOQI/Ucw/NuqKhCLctOiIxWVF');
		$swift_mailer = Swift_Mailer::newInstance($aws_transport);		
		
		$user_info = $this->get_roi_owner();
		$roi_information = $this->get_roi_information();
		$email_subject = $roi_information['roi_title'] .' was just viewed';
		$plain_text = 'HTML Emails need to be enabled to see the email\'s contents.';

		$to = array($user_info['username'] => $user_info['first_name']. ' ' .$user_info['last_name']);
		$from = array('noreply@theroishop.com' => 'The ROI Shop');
		
		$email_message = file_get_contents(realpath($_SERVER['DOCUMENT_ROOT'])."/php/email/templates/viewed.html");
		$message_search = array('%name%', '%creator%', '%ipaddress%', '%link%', '%views%', '%unique%');
		$message_replace = array($this->get_roi_name(), $user_info['username'], $_SERVER["HTTP_X_FORWARDED_FOR"], $_GET['roi'], $roi_information['visits'], $roi_information['unique_ip']);
		$email_message = str_replace($message_search, $message_replace, $email_message);

		foreach($ip_info as $key => $value){
					
			switch($key) {
				case 'country': $email_message = str_replace('%country%', $value, $email_message);break;
				case 'stateprov': $email_message = str_replace('%state%', $value, $email_message); break;
				case 'city': $email_message = str_replace('%city%', $value, $email_message); break;
			}
		}
				
		$message = new Swift_Message($email_subject);
		$message->SetTo($to);
		$message->SetBcc(array('mfarber@theroishop.com'));
		$message->SetFrom($from);
		$message->addPart($plain_text, 'text/plain');
		$message->SetBody($email_message, 'text/html');
				
		if($recipients = $swift_mailer->send($message, $failures)){
			//self::update_email_sent($email['user_id']);
		} else {}	
				
/* 		foreach($email_preferences as $email) {

			$next_time_to_send = strtotime($email['last_sent']) + $email['delay'];
			if( $next_time_to_send < strtotime('now') ) {
				$aws_transport = Swift_AWSTransport::newInstance('AKIAIWQ3DPP7HAL33PIA','7LtELaOSutm4jtPVOQI/Ucw/NuqKhCLctOiIxWVF');
				$swift_mailer = Swift_Mailer::newInstance($aws_transport);
			
				$user_info = $this->get_user_info_by_id($email['user_id']);
				$roi_information = $this->get_roi_information();
				$email_subject = $roi_information['roi_title'] .' was just viewed';
				$plain_text = 'HTML Emails need to be enabled to see the email\'s contents.';

				$to = array($user_info['username'] => $user_info['first_name']. ' ' .$user_info['last_name']);
				$from = array('noreply@theroishop.com' => 'The ROI Shop');
			
				$email_message = file_get_contents($email['email_template']);
				$message_search = array('%name%', '%creator%', '%ipaddress%', '%link%', '%views%', '%unique%');
				$message_replace = array($this->get_roi_name(), $user_info['username'], $_SERVER["HTTP_X_FORWARDED_FOR"], $_GET['roi'], $roi_information['visits'], $roi_information['unique']);
				$email_message = str_replace($message_search, $message_replace, $email_message);

				foreach($ip_info as $key => $value){
					
					switch($key) {
						case 'country': $email_message = str_replace('%country%', $value, $email_message);break;
						case 'stateprov': $email_message = str_replace('%state%', $value, $email_message); break;
						case 'city': $email_message = str_replace('%city%', $value, $email_message); break;
					}
				}
				
				$message = new Swift_Message($email_subject);
				$message->SetTo($to);
				$message->SetFrom($from);
				$message->addPart($plain_text, 'text/plain');
				$message->SetBody($email_message, 'text/html');
				
				if($recipients = $swift_mailer->send($message, $failures)){
					self::update_email_sent($email['user_id']);
				} else {}				
			}
		} */
	}
	
		
	public function update_email_sent($user_id) {

		$current_time = date('Y-m-d H:i:s');
		$sql = "UPDATE roi_email_notifications SET last_sent = ? WHERE user_id = ? AND roi_id = ?";

		$stmt = $this->_db->prepare( $sql );
		$stmt->bindParam(1, $current_time, PDO::PARAM_STR);
		$stmt->bindParam(2, $user_id, PDO::PARAM_INT);
		$stmt->bindParam(3, $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();		
	}
	
	public function get_all_companies() {
		
		$sql = "SELECT * FROM roi_companies";
		
		$stmt = $this->_db->prepare( $sql );
		$stmt->execute();
		$all_companies = $stmt->fetchall();

		return $all_companies;
	}
}

?>