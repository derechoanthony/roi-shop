<?php

/**
 * Handles log in actions for The ROI Shop
 * 
 * PHP version 5
 **/
 
class LogInActions
{
	
	//Create database object
	private $_db;
		
	/**
	 * Checks for a database object and creates one if none is found
	 **/
	public function __construct($db=NULL)
	{
		if(is_object($db))
		{
			$this->_db = $db;
		}
		else
		{
			$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
			$this->_db = new PDO($dsn, DB_USER, DB_PASS);
		}
	}

	public function retrieveUserPreferences()
	{
		$sql = "SELECT * FROM users
				WHERE Username = :user";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':user', $_SESSION['Username'], PDO::PARAM_STR);
		$stmt->execute();
		$data = $stmt->fetch();
		return $data;
	}
	
	public function accountLogin()
	{
		//Retrieve user information entered in the log in screen.
		$sql = "SELECT * FROM users
	    		WHERE Username=:user AND Password=MD5(:pass)";
		
		$stmt = $this->_db->prepare($sql);
	    $stmt->bindParam(':user', $_POST['username'], PDO::PARAM_STR);
	    $stmt->bindParam(':pass', $_POST['password'], PDO::PARAM_STR);
	    $stmt->execute();
		$userInformation = $stmt->fetch();
	    
		// Check to see if there is a username and password match.
		if( $stmt->rowCount()==1 )
	    {
	    	// Log user in and set up session variables.
			$_SESSION['Username'] = htmlentities($_POST['username'], ENT_QUOTES);
			// Get users first name from array of names entered.
			$userFullName = explode(' ',trim($userInformation['full_name']));
			$_SESSION['FirstName'] = $userFullName[0];
			$_SESSION['FullName'] = $userInformation['full_name'];
			$_SESSION['LogInTime'] = date('Y-m-d H:i:s');
	    	return TRUE;
	    } else { return FALSE; }
	}

	public function resetPassword()
	{
		// Get user information from username entered.
		$resetAccountInfo = retrieveAccountInfo( $_POST['noemail'] );
		
		// Get the User ID for the username requested.
		$userId = $resetAccountInfo['id'];
		// If username entered does not exist return false.
		if( !isset( $userId ) ) { return FALSE; }
		
		// Generate unique verification to be sent to user email.
		$verificationCode = sha1( time() );
		
		$sql = "UPDATE users SET verified=0, verCode=:ver
				WHERE id=:user";
			
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(":user", $userId, PDO::PARAM_STR);
		$stmt->bindParam(":ver", $verificationCode, PDO::PARAM_STR);
		$stmt->execute();

		// Send the reset email
		if( !$this->sendResetEmail($_POST['noemail'], $verificationCode, $data['fullName']) )
		{ return FALSE; } else { return TRUE; }
	}
	
	public function retrieveAccountInfo($user)
	{
		$sql = "SELECT * FROM users
	            WHERE Username=:user";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':user', $user, PDO::PARAM_STR);
		$stmt->execute();
		$accountInfo = $stmt->fetch();
		return $accountInfo;
	}
	

	
	public function verifyUser()
	{
		$sql = "SELECT ver_code, ListText
				FROM list_items
				WHERE ListItemID=:roi";

		if($stmt = $this->_db->prepare($sql))
		{
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_STR);
			$stmt->execute();
			$row = $stmt->fetch();
			if($row['ver_code'] == $_GET['v'])
			{
				return $row['ListText'];
			}
			else
			{
				return FALSE;
			}
		}
	}

	public function password()
	{
		$sql = "SELECT Username, Password FROM list_items
				WHERE ListItemID=:roi";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_STR);
		$stmt->execute();
		$data = $stmt->fetch();
		if($data['password']) { return $data['password']; }
		else { return FALSE; }
	}
	
	public function roiLogin()
	{
		$sql = "SELECT username, password
				FROM list_items
				WHERE ListItemID=:roi";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_STR);
		$stmt->execute();
		$row = $stmt->fetch();
		if($row['username'] == $_POST['username'] && $row['password'] == $_POST['password'])
		{
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	public function roiOwner()
	{
		$sql = "SELECT *
				FROM users
				WHERE UserID = (
					SELECT ListID
					FROM list_items
					WHERE ListItemID = :roi
				)";
				
		$stmt = $this->_db->prepare( $sql );
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$owner = $stmt->fetch();

		return $owner;
	}
	
	public function roiManager() {
		
		$sql = "SELECT * FROM users
				WHERE UserID = (
					SELECT manager FROM users
					WHERE UserID = (
						SELECT ListID FROM list_items
						WHERE ListItemID = :roi
					)
				);";
				
		$stmt = $this->_db->prepare( $sql );
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$manager = $stmt->fetch();

		return $manager;				
	}
	
	public function addHit() {
		
		$sql = "SELECT COUNT(ID) FROM ip_info
	            WHERE roi=:roiID AND ip_address=:ip";

		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roiID', $_GET['roi'], PDO::PARAM_INT);
		$stmt->bindParam(':ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
		$stmt->execute();
		$data = $stmt->fetch();
			
		$sql = "INSERT INTO ip_info (roi, ip_address)
				VALUES (:roiID, :ip)";
				
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roiID', $_GET['roi'], PDO::PARAM_INT);
		$stmt->bindParam(':ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
		$stmt->execute();
		$guestid = $this->_db->lastInsertId();
			
		$sql = "UPDATE list_items SET visits = IFNULL(visits, 0) + 1
				WHERE ListItemID=:roiID"; 

		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':roiID', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		
		$sql = "INSERT INTO sessions (`guestid`,`logindt`)
				VALUES (:guest, NOW());";
		
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':guest', $guestid, PDO::PARAM_INT);
		$stmt->execute();
		$sessionId = $this->_db->lastInsertId();
		$_SESSION['id'] = $sessionId;
		
		if( !$data['COUNT(ID)'] ) {
			
			$sql = "UPDATE list_items SET unique_ip = IFNULL(unique_ip, 0) + 1
					WHERE ListItemID=:roiID"; 

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roiID', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
		}
	
		$this->mailConfirmation();
	
	}
	
	private function mailConfirmation()
	{

		$handle = curl_init();
		
		curl_setopt_array(
			$handle,
			array(
				CURLOPT_URL => 'http://api.db-ip.com/addrinfo?addr=' . $_SERVER["REMOTE_ADDR"] . '&api_key=b4a21f9ee4ded936cacbe174c2798372cf70b91b',
				CURLOPT_RETURNTRANSFER => true
			)
		);

		$ipInfo = json_decode(curl_exec($handle));
		curl_close($handle);	
	
		$sql = "SELECT ListText, visits, unique_ip
				FROM list_items
				WHERE ListItemID=:roiID";
		
			try
			{
				$stmt = $this->_db->prepare($sql);
				$stmt->bindParam(':roiID', $_GET['roi'], PDO::PARAM_INT);
				$stmt->execute();
				$hits = $stmt->fetch();
				$ver = $hits['ListText'];
			} catch(PDOException $e) {
	
			}
				
		$sql = "SELECT * FROM users
				WHERE UserID = (
					SELECT ListID FROM list_items
					WHERE ListItemID = :roi
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

			$sql = "SELECT Username, full_name
					FROM users
					WHERE UserID = :manager";
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

			$optout = [];
			
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
			
		/*	//Add Sales rep if they want to receive the email
			$maillist[] = array( $data['Username'], $data['full_name'], 'rep' );
			
			//Check if ROI Shop employees still get emails for the ROI
			$maillist[] = array( 'mfarber@theroishop.com', 'Mike Farber', 'roi' );
			$maillist[] = array( 'jachorn@theroishop.com', 'Jacob Achorn', 'roi' );

			if( $manager ){ //Does sales rep have a manager defined?
				$maillist[] = array( $manager['Username'], $manager['full_name'], 'manager' );
			}*/
			
		if( isset($maillist) ){
				
			for($i=0;$i<count( $maillist );$i++){
				
				$to = array( $maillist[$i][0] => $maillist[$i][1] );				

				$from = array('noreply@theroishop.com' => 'The ROI Shop');

				//Create the subject line.
				$subject = $ver.' was just viewed!';
				
				$text = "HTML Emails need to be enabled to see the email's contents.";
				
				$message = file_get_contents('email/viewed.html');
				$message = str_replace('%name%', $ver, $message);
				$message = str_replace('%creator%', ($maillist[$i][2]!='rep'?$data['Username']:''), $message);
				$message = str_replace('%ipaddress%', $_SERVER["REMOTE_ADDR"], $message);
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
	
	public function userAdmin() {
		
		$sql = "SELECT * FROM user_comps
				WHERE UserID = (
					SELECT UserID FROM users
					WHERE Username = :user
				) AND CompID = (
					SELECT compStructure FROM list_items
					WHERE ListItemID = :roi
				);";
				
		$stmt = $this->_db->prepare( $sql );
		$stmt->bindParam(':user', $_SESSION['Username'], PDO::PARAM_STR);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$admin = $stmt->fetch();

		return $admin;				
	}
	
	public function logoutUser() {
		
		$sql = "UPDATE sessions SET `logoutdt` = NOW()
				WHERE id = :session;";
				
		$stmt = $this->_db->prepare( $sql );
		$stmt->bindParam(':session', $_SESSION['id'], PDO::PARAM_INT);
		$stmt->execute();
	}
	
}	