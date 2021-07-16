<?php

	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/constants.php");
	require_once("$root/db/connection.php");
	
	if( $_POST['action'] == 'CreateNewROI' ) {
		
		$sql = "UPDATE ep_created_rois SET roi_position = roi_position + 1
				WHERE user_id = :user;";
				
		$stmt = $db->prepare($sql);
		$stmt->bindParam( ':user', $_SESSION['UserId'], PDO::PARAM_STR );
		$stmt->execute();		

		$sql = "SELECT currency FROM roi_users
				WHERE user_id = :user";
					
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':user', $_SESSION['UserId'], PDO::PARAM_STR);
		$stmt->execute();
		$currency = $stmt->fetch();
		
		$verificaiton_code = sha1(uniqid(mt_rand(), true));
		$dt = date('Y-m-d H:i:s');
		
		$sql = "INSERT INTO ep_created_rois ( user_id, roi_title, roi_position, roi_version_id, verification_code, dt, currency )
				VALUES ( :user, :title, 1, :version, :verification, :dt, :currency )";
		
		$stmt = $db->prepare($sql);
		$stmt->bindParam( ':user', $_SESSION['UserId'], PDO::PARAM_INT );
		$stmt->bindParam( ':title', $_POST['roi_name'], PDO::PARAM_STR);
		$stmt->bindParam( ':version', $_POST['version'], PDO::PARAM_STR);
		$stmt->bindParam( ':verification', $verificaiton_code, PDO::PARAM_STR);
		$stmt->bindParam( ':dt', $dt, PDO::PARAM_STR);
		$stmt->bindParam( ':currency', $currency['currency'], PDO::PARAM_STR);
		$stmt->execute();

		$created_roi = [];
		$created_roi['roi'] = $db->lastInsertId();
		
		$sql = "SELECT version_path FROM roi_version_levels
				WHERE version_level_id = ( 
					SELECT ep_version_level FROM roi_structure_versions
					WHERE version_id = :version
				);";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':version', $_POST['version'], PDO::PARAM_STR);
		$stmt->execute();
		$version_path = $stmt->fetch();
		
		$created_roi['path'] = $version_path['version_path'];
		echo json_encode($created_roi);
	}
	
	if( $_POST['action'] == 'visiblefolders' ) {
			
		$sql = "DELETE FROM roi_visible_folders WHERE user_id = :userid;";
			
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':userid', $_SESSION['UserId'], PDO::PARAM_INT);
		$stmt->execute();		
			
		$folders = json_decode($_POST['folders'], true);

		foreach($folders as $folder) {
			
			$sql = "INSERT INTO roi_visible_folders (user_id, folder_id)
					VALUES (:userid, :folderid);";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':userid', $_SESSION['UserId'], PDO::PARAM_INT);
			$stmt->bindParam(':folderid', $folder, PDO::PARAM_INT);
			$stmt->execute();
		}
	}
	
	if( $_POST['action'] == 'visiblefolders' ) {
			
		$sql = "DELETE FROM roi_visible_folders WHERE user_id = :userid;";
			
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':userid', $_SESSION['UserId'], PDO::PARAM_INT);
		$stmt->execute();		
			
		$folders = json_decode($_POST['folders'], true);

		foreach($folders as $folder) {
			
			$sql = "INSERT INTO roi_visible_folders (user_id, folder_id)
					VALUES (:userid, :folderid);";
						
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':userid', $_SESSION['UserId'], PDO::PARAM_INT);
			$stmt->bindParam(':folderid', $folder, PDO::PARAM_INT);
			$stmt->execute();
			
		}
	}
	
	if( $_POST['action'] == 'logoutUser' ) {
			
		$sql = "UPDATE roi_sessions SET `logout_dt` = NOW()
				WHERE session_id = :session;";
					
		$stmt = $db->prepare( $sql );
		$stmt->bindParam(':session', $_SESSION['id'], PDO::PARAM_INT);
		$stmt->execute();
	}
	
	if( $_POST['action'] == 'transferroi' ) {		
			
		$sql = "UPDATE ep_created_rois SET user_id = :user, roi_position = 1
				WHERE roi_id = :roi";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':user', $_POST['user'], PDO::PARAM_INT);
		$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
		$stmt->execute();
	}
	
	if( $_POST['action'] == 'deleteroi' ) {		
			
		$sql = "DELETE FROM ep_created_rois
				WHERE roi_id = :roi";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
		$stmt->execute();
	}
	
	if( $_POST['action'] == 'addnewfolder' ) {	
			
		$sql = "INSERT INTO roi_folders (title, userid, global)
				VALUES (:title, :userid, '0')";
					
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':title', $_POST['foldername'], PDO::PARAM_STR);
		$stmt->bindParam(':userid', $_SESSION['UserId'], PDO::PARAM_INT);
		$stmt->execute();
			
		echo $db->lastInsertId();
	}
	
	if( $_POST['action'] == 'renameroi' ) {		
			
		$sql = "UPDATE ep_created_rois SET roi_title = :name
				WHERE roi_id = :roi";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
		$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
		$stmt->execute();
			
		echo $_POST['name'];
	}
		
	if( $_POST['action'] == 'changefolder' ) {			
			
		$sql = "UPDATE ep_created_rois SET folder = :folder
				WHERE roi_id = :roi;";
					
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':folder', $_POST['folderid'], PDO::PARAM_INT);
		$stmt->bindParam(':roi', $_POST['roiid'], PDO::PARAM_INT);
		$stmt->execute();
	}
	
	if( $_POST['action'] == 'updatepersonal' ) {		
			
		$sql = "UPDATE roi_users SET username = :email, first_name = :firstname, last_name = :lastname, phone = :phone
				WHERE user_id = :user";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
		$stmt->bindParam(':firstname', $_POST['firstname'], PDO::PARAM_STR);
		$stmt->bindParam(':lastname', $_POST['lastname'], PDO::PARAM_STR);
		$stmt->bindParam(':phone', $_POST['phone'], PDO::PARAM_STR);
		$stmt->bindParam(':user', $_SESSION['UserId'], PDO::PARAM_INT);
		$stmt->execute();
		
		$_SESSION['Username'] = $_POST['email'];
			
		echo 'updated';
	}
	
	if( $_POST['action'] == 'updatepassword' ) {		
			
		$sql = "UPDATE roi_users SET password = :pass
				WHERE user_id = :user";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':pass', md5($_POST['newpassword']), PDO::PARAM_STR);
		$stmt->bindParam(':user', $_SESSION['UserId'], PDO::PARAM_INT);
		$stmt->execute();
		
		echo 'updated';
	}

?>