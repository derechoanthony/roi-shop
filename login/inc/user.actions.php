<?php

	class RoiShopUsers {
		
		private $_db;

		public function __construct($db=NULL) {
			
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

		public function accountLogin() {
			
			$sql = "SELECT user_id, username FROM roi_users
					WHERE username = :user AND password = MD5(:pass);";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':user', $_POST['username'], PDO::PARAM_STR);
			$stmt->bindParam(':pass', $_POST['password'], PDO::PARAM_STR);
			$stmt->execute();
			$username = $stmt->fetch();			
			
			if($username) {
				
				$sql = "INSERT INTO roi_sessions (user_id, login_dt)
						VALUES (:user, NOW());";
						
				$stmt = $this->_db->prepare($sql);
				$stmt->bindParam(':user', $username['user_id'], PDO::PARAM_INT);
				$stmt->execute();
				$session_id = $this->_db->lastInsertId();
				
				// User was successfully logged in.
				// Create Session variables.
				
				$_SESSION['Username'] = $username['username'];
				$_SESSION['UserId'] = $username['user_id'];
				$_SESSION['LoggedIn'] = date("Y-m-d H:i:s");
				$_SESSION['Id'] = $session_id;

				return true;
			};
				
			return false;
		}

		public function resetPassword() {
			
			$sql = "SELECT * FROM roi_users
					WHERE username = :user;";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':user', $_POST['noemail'], PDO::PARAM_STR);
			$stmt->execute();
			$account_info = $stmt->fetch();
			
			$user_id = $account_info['user_id'];
			
			if( !isset($user_id) ) { return false; }
			
			// Create new verification code.
			$verification_code = md5( time() );
			
			$sql = "UPDATE roi_users SET verified = 0, verification_code = :verification
					WHERE user_id = :user
					LIMIT 1;";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(":user", $user_id, PDO::PARAM_INT);
			$stmt->bindParam(":verification", $verification_code, PDO::PARAM_STR);
			$update = $stmt->execute();

			if( $update ) {
				
				return $account_info;
			};
			
			return false;
		}
		
	}