<?php
	
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/constants.php");
	require_once("$root/db/connection.php");
	
	require_once("$root/company/dashboard/php/dashboard.actions.php");
	require_once("$root/email/swiftmailer/lib/swift_required.php");
	
	if( $_POST['action'] == 'createnewstructure' ) {

		$sql = "INSERT INTO roi_company_structures (structure_title, company_id, active, created_dt)
				VALUES (:structure,:company,'1',NOW());";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':structure', $_POST['template'], PDO::PARAM_STR);
		$stmt->bindParam(':company', $_POST['company'], PDO::PARAM_STR);
		$stmt->execute();
	}
	
	if( $_POST['action'] == 'resetusername' ) {

		$sql = "SELECT username FROM roi_users
				WHERE username = :user";		

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':user', $_POST['username'], PDO::PARAM_STR);
		$stmt->execute();
		$user_exists = $stmt->fetchall();

		if( count($user_exists) == 0 ) {

			if( !$_POST['password'] ) {
				
				$password = substr( md5( time() ), 4, 12);
			} else { $password = $_POST['password']; }
			
			$sql = "UPDATE roi_users SET username = :username, password = :password
					WHERE user_id = :userid";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
			$stmt->bindParam(':password', md5($password), PDO::PARAM_STR);
			$stmt->bindParam(':userid', $_POST['userid'], PDO::PARAM_INT);
			$stmt->execute();

			echo 'user reset';
		} else {
			
			echo 'user exists';
		}

	}
	
	if( $_POST['action'] == 'adduser' ) {

		if( !is_null($_POST['username']) ) {
			
			$sql = "SELECT username, status FROM roi_users
					WHERE username = :user";		

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':user', $_POST['username'], PDO::PARAM_STR);
			$stmt->execute();
			$user_exists = $stmt->fetchall();
			
			if( count($user_exists) == 0 ) {

				if( !$_POST['password'] ) {
					
					$password = substr( md5( microtime() ), 4, 12 );
				} else { $password = $_POST['password']; }
				
				$sql = "INSERT INTO roi_users (username, password, company_id, created_dt, first_name, last_name)
						VALUES (:username,:password,:company,NOW(),:first,:last);";

				$stmt = $db->prepare($sql);
				$stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
				$stmt->bindParam(':password', md5($password), PDO::PARAM_STR);
				$stmt->bindParam(':company', $_POST['company'], PDO::PARAM_INT);
				$stmt->bindParam(':first', $_POST['first'], PDO::PARAM_STR);
				$stmt->bindParam(':last', $_POST['last'], PDO::PARAM_STR);
				$stmt->execute();
				
				$created_user_id = $db->lastInsertId();
				
				$sql = "INSERT INTO roi_user_companies (user_id, company_id)
						VALUES (:user, :company);";

				$stmt = $db->prepare($sql);
				$stmt->bindParam(':user', $created_user_id, PDO::PARAM_STR);
				$stmt->bindParam(':company', $_POST['company'], PDO::PARAM_INT);
				$stmt->execute();
				
				$dashboard = new DashboardActions($db);
				$dashboard->sendWelcomeEmail( $_POST['username'], $password, $_POST['first']. ' ' . $_POST['last']);

				echo $created_user_id;
			} elseif ( $user_exists[0]['status'] == 99 ) {

				if( !$_POST['password'] ) {
						
					$password = substr( md5( microtime() ), 4, 12 );
				} else { $password = $_POST['password']; }
					
				$sql = "UPDATE roi_users SET status = 1, password = :password, company_id = :company, created_dt = NOW(), first_name = :first, last_name = :last
						WHERE username = :username;";

				$stmt = $db->prepare($sql);
				$stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
				$stmt->bindParam(':password', md5($password), PDO::PARAM_STR);
				$stmt->bindParam(':company', $_POST['company'], PDO::PARAM_INT);
				$stmt->bindParam(':first', $_POST['first'], PDO::PARAM_STR);
				$stmt->bindParam(':last', $_POST['last'], PDO::PARAM_STR);
				$stmt->execute();
				
				$dashboard = new DashboardActions($db);
				$dashboard->sendWelcomeEmail( $_POST['username'], $password, $_POST['first']. ' ' . $_POST['last']);
			}
		}
	}
	
	if( $_POST['action'] == 'transferrois' ) {

		$sql = "UPDATE ep_created_rois SET user_id = :transfer WHERE user_id = :userid";		

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':userid', $_POST['userid'], PDO::PARAM_INT);
		$stmt->bindParam(':transfer', $_POST['transferto'], PDO::PARAM_INT);
		$stmt->execute();

		echo 'rois transferred';
	}
	
	if( $_POST['action'] == 'statuschange' ) {

		$sql = "UPDATE roi_users SET status = :status WHERE user_id = :userid";		

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':userid', $_POST['userid'], PDO::PARAM_INT);
		$stmt->bindParam(':status', $_POST['status'], PDO::PARAM_INT);
		$stmt->execute();

		echo $_POST['status'];
	}
	
	if( $_POST['action'] == 'checkavailability' ) {

		$sql = "SELECT username FROM roi_users WHERE username = :user AND status <> 99";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':user', $_POST['username'], PDO::PARAM_STR);
		$stmt->execute();
		$user_exists = $stmt->fetchall();
		
		echo count($user_exists);
	}

?>