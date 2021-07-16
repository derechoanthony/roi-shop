<?php

	// Establish connection to the database
	
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/db_connection.php");

	$adminActions = new AdminActions($db);
	switch($_POST['action']){
		case 'updateManager':
			$adminActions->updateManager();
		break;

		case 'resetUsername':
			$status = $adminActions->resetUsername();
			echo $status;
		break;
	}
	
	class AdminActions {
		private $_db;

		public function __construct($db=NULL) {
			
			if(is_object($db)) {
				$this->_db = $db;
			} else {
				$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
				$this->_db = new PDO($dsn, DB_USER, DB_PASS);
			}			
		}

		public function updateManager() {
			$sql = "UPDATE roi_users SET manager = ?
					WHERE user_id = ?";
		
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(1, $_POST['manager'], PDO::PARAM_INT);
			$stmt->bindParam(2, $_POST['user'], PDO::PARAM_INT);
			$stmt->execute();
		}

		public function resetUsername() {
			$sql = "SELECT COUNT(username) as users FROM roi_users
					WHERE username = :user";

			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':user', $_POST['username'], PDO::PARAM_STR);
			$stmt->execute();
			$user_exists = $stmt->fetch();

			if( $user_exists['users'] == 0 ) {

				if( !$_POST['password'] ) {
					$password = substr( md5( time() ), 4, 12);
				} else { $password = $_POST['password']; }
				
				$sql = "UPDATE roi_users SET username = :username, password = :password
						WHERE user_id = :userid";

				$stmt = $this->_db->prepare($sql);
				$stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
				$stmt->bindParam(':password', md5($password), PDO::PARAM_STR);
				$stmt->bindParam(':userid', $_POST['userid'], PDO::PARAM_INT);
				$stmt->execute();

				return 'user reset';
			} else {
				return 'user exists';
			}
		}
	}

?>