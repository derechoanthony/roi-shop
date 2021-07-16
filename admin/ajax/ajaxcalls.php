<?php

	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	
	include_once("../../common/base.php");
	require_once("../php/classes.admin.php");
	require_once("$root/email/swiftmailer/lib/swift_required.php");
	
	if( $_POST['action'] == 'changeCurrency' )
	{
		$sql = "UPDATE roi_users
				SET currency = :currency
				WHERE username = :user";
		
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':currency', $_POST['currency'], PDO::PARAM_STR);
		$stmt->bindParam(':user', $_POST['user'], PDO::PARAM_STR);
		$stmt->execute();
		
		echo $_POST['currency'];
	}
	
	if( $_POST['action'] == 'changeManager' )
	{
		$sql = "UPDATE roi_users
				SET manager = :manager
				WHERE username = :user";
		
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':manager', $_POST['manager'], PDO::PARAM_STR);
		$stmt->bindParam(':user', $_POST['user'], PDO::PARAM_STR);
		$stmt->execute();
	}
	
	if( $_POST['action'] == 'deleteUser' )
	{
		$sql = "DELETE FROM roi_users
				WHERE username=:user";
		
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':user', $_POST['user'], PDO::PARAM_STR);
		$stmt->execute();
		
		echo $_POST['user'];
		
	}

	if( $_POST['action'] == 'changeUser' )
	{
		
		$sql = "SELECT * FROM roi_users
				WHERE username=:newuser";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':newuser', $_POST['newuser'], PDO::PARAM_STR);
		$stmt->execute();
		$existUser = $stmt->fetchall();

		if(!$existUser[0]){
		
			if( !$_POST['password'] )
			{
				$password = substr( md5( time() ), 4, 12);
			} else { $password = $_POST['password']; }		
			
			$sql = "UPDATE roi_users
					SET username=:newuser, password=:pass, first_name=:fullname, phone=:phone
					WHERE username=:user";
			
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':newuser', $_POST['newuser'], PDO::PARAM_STR);
			$stmt->bindParam(':pass', md5($password), PDO::PARAM_STR);
			$stmt->bindParam(':fullname', $_POST['fullname'], PDO::PARAM_STR);
			$stmt->bindParam(':phone', $_POST['phone'], PDO::PARAM_STR);
			$stmt->bindParam(':user', $_POST['user'], PDO::PARAM_STR);
			$stmt->execute();
			
			$admin = new TheROIShopAdmin($db);
				
			$admin->sendUsernameCreation( $_POST['newuser'], $password );
			echo $_POST['newuser'];
		
		} else { echo 'Exists'; }
		
	}
	
	if( $_POST['action'] == 'addUser' )
	{
		
		$sql = "SELECT * FROM roi_users
				WHERE username=:user";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':user', $_POST['username'], PDO::PARAM_STR);
		$stmt->execute();
		$existUser = $stmt->fetchall();
		
		if(!$existUser[0]){
		
			$sql = "SELECT parent FROM comp_specs
					WHERE compID=:comp;";
					
			$stmt = $db->prepare( $sql );
			$stmt->bindParam(':comp', $_SESSION['Admin'], PDO::PARAM_INT);
			$stmt->execute();

			$parent = $stmt->fetch();
			$users = ( $parent['parent'] == 0 ? $_SESSION['Admin'] : $parent['parent'] );			
			
			$manager = $_POST['manager'] ? $_POST['manager'] : '0';
			
			if( !$_POST['password'] )
			{
				$password = substr( md5( time() ), 4, 12);
			} else { $password = $_POST['password']; }
			
			$sql = "INSERT INTO roi_users ( `username`, `password`, `verified`, `company_id`, `first_name`, `last_name`, `phone`, `manager`, `currency` )
					VALUES ( :user, :pass, '1', :comp, :fname, :lname, :phone, :manager, :cur );";
			
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':user', $_POST['username'], PDO::PARAM_STR);
			$stmt->bindParam(':pass', md5($password), PDO::PARAM_STR);
			$stmt->bindParam(':comp', $users, PDO::PARAM_INT);
			$stmt->bindParam(':fname', $_POST['fname'], PDO::PARAM_STR);
			$stmt->bindParam(':lname', $_POST['lname'], PDO::PARAM_STR);
			$stmt->bindParam(':phone', $_POST['wphone'], PDO::PARAM_STR);
			$stmt->bindParam(':manager', $manager, PDO::PARAM_INT);
			$stmt->bindParam(':cur', $_POST['defineCurrency'], PDO::PARAM_STR);
			$stmt->execute();
			
			$lastId = $db->lastInsertId();		
			
			foreach($_POST['addUserRoi'] as $addRoi)
			{
				if( $addRoi != 0 ) {
					$sql = "INSERT INTO roi_user_companies ( `user_id`, `structure_id` )
							VALUES ( :userid, :comp );";
							
					$stmt = $db->prepare($sql);
					$stmt->bindParam(':userid', $lastId, PDO::PARAM_INT);
					$stmt->bindParam(':comp', $addRoi, PDO::PARAM_INT);
					$stmt->execute();
				}
			}			
			
			$admin = new TheROIShopAdmin($db);
			
			$admin->sendUsernameCreation( $_POST['username'], $password, $_POST['fname'] );
			echo $_POST['username'];
		
		} else { echo 'Exists'; }
		
	}
	
	if( $_POST['action'] == 'changeSection' )
	{
		$sql = "UPDATE compsections 
				SET Title=:title, Caption=:caption, Video=:video, nickname=:nickname, growl=:growl
				WHERE ID=:sectionid";
		
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':title', $_POST['title'], PDO::PARAM_STR);
		$stmt->bindParam(':caption', $_POST['caption'], PDO::PARAM_STR);
		$stmt->bindParam(':video', $_POST['video'], PDO::PARAM_STR);
		$stmt->bindParam(':nickname', $_POST['nickname'], PDO::PARAM_STR);
		$stmt->bindParam(':growl', $_POST['growl'], PDO::PARAM_STR);
		$stmt->bindParam(':sectionid', $_POST['sectionid'], PDO::PARAM_INT);
		$stmt->execute();
	}

	if( $_POST['action'] == 'reorderSection' )
	{
		$sql = "UPDATE compsections 
				SET Position=:pos
				WHERE ID=:sectionid";
		
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':pos', $_POST['pos'], PDO::PARAM_INT);
		$stmt->bindParam(':sectionid', $_POST['id'], PDO::PARAM_INT);
		$stmt->execute();
	}
	
	if( $_POST['action'] == 'changeEntry' )
	{
		$sql = "UPDATE entry_fields 
				SET Title=:title, Type=:type, Format=:format, Tip=:tip
				WHERE ID=:entryid";
		
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':title', $_POST['title'], PDO::PARAM_STR);
		$stmt->bindParam(':type', $_POST['type'], PDO::PARAM_INT);
		$stmt->bindParam(':format', $_POST['format'], PDO::PARAM_INT);
		$stmt->bindParam(':tip', $_POST['tip'], PDO::PARAM_STR);
		$stmt->bindParam(':entryid', $_POST['entryid'], PDO::PARAM_INT);
		$stmt->execute();
	}
	
	if( $_POST['action'] == 'reorderEntry' )
	{
		$sql = "UPDATE entry_fields 
				SET position=:pos
				WHERE ID=:entryid";
		
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':pos', $_POST['pos'], PDO::PARAM_INT);
		$stmt->bindParam(':entryid', $_POST['id'], PDO::PARAM_INT);
		$stmt->execute();
	}
	
	if( $_POST['action'] == 'transferRoi' )
	{
		
		$sql = "UPDATE ep_created_rois SET user_id = :newlist
				WHERE user_id = (
					SELECT user_id FROM roi_users
					WHERE username = :user
				);";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':newlist', $_POST['newuser'], PDO::PARAM_STR);
		$stmt->bindParam(':user', $_POST['user'], PDO::PARAM_STR);
		$stmt->execute();

		echo $_POST['newuser'];
		
	}
	
?>