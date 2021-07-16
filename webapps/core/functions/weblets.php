<?php

function wb_user_data($userID){
	$data=array();
	$userID = (int)$userID;
	
	$func_num_args = func_num_args();
	
	$func_get_args = func_get_args();

	
	if($func_num_args > 1){
		unset($func_get_args[0]);
		
		$fields = '`' . implode('`, `',$func_get_args) . '`';
		$query = "SELECT $fields FROM `wb_users` WHERE `wbuserID`=$userID";
		$data = mysql_fetch_assoc(mysql_query($query));
		//return $query;
		return $data;
	}
	
	
}

function wb_roi_data($wb_roi_ID){
	$data=array();
	$userID = (int)$userID;
	
	$func_num_args = func_num_args();
	
	$func_get_args = func_get_args();

	
	if($func_num_args > 1){
		unset($func_get_args[0]);
		
		$fields = '`' . implode('`, `',$func_get_args) . '`';
		$query = "SELECT $fields FROM `wb_roi_list` WHERE `wb_roi_ID`=$wb_roi_ID";
		$data = mysql_fetch_assoc(mysql_query($query));
		//return $query;
		return $data;
	}
	
	
}

function user_data($userID){
	$data=array();
	$userID = (int)$userID;
	
	$func_num_args = func_num_args();
	
	$func_get_args = func_get_args();

	
	if($func_num_args > 1){
		unset($func_get_args[0]);
		
		$fields = '`' . implode('`, `',$func_get_args) . '`';
		$data = mysql_fetch_assoc(mysql_query("SELECT $fields FROM `users` WHERE `userID`=$userID"));
		return $data;
	}
	
	
}

function logged_in(){
	return(isset($_SESSION['userID'])) ? true : false;
}

function user_exists($username){
	$username = sanitize($username);
	$query = mysql_query("SELECT COUNT(`userID`) FROM `users` WHERE `username`='$username'");
	return  (mysql_result($query, 0) == 1) ? true : false;
}

function user_active($username){
	$username = sanitize($username);
	$query = mysql_query("SELECT COUNT(`userID`) FROM `users` WHERE `username`='$username' AND `active`=1");
	return  (mysql_result($query, 0) == 1) ? true : false;
}

function userID_from_username($username){
	$username = sanitize($username);
	$query = mysql_query("SELECT `userID` FROM `users` WHERE `username`='$username'");
	return  mysql_result($query, 0, 'userID');
}


function login($username, $password){
	$userID = userID_from_username($username);
	
	$username = sanitize($username);
	$password = MD5($password);
	
	$query = mysql_query("SELECT COUNT(`userID`) FROM `users` WHERE `username`='$username' AND `password`='$password'");
	return(mysql_result($query, 0) == 1) ? $userID : false;
}
?>
