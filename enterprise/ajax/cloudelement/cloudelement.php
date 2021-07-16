<?php

	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/constants.php");
	require_once("$root/db/connection.php");
	
	if($_GET['action'] == 'getSFelements') {
		
		ini_set('max_execution_time', 300);
		
		$pageStart = 1;
		$returnedItemsArray = array();

		$sql = "SELECT code FROM integration
				WHERE userid = :userid
				AND element = 'sfdc'";
			
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':userid', $_SESSION['UserId'], PDO::PARAM_INT);
		$stmt->execute();
		$user = $stmt->fetch();

		$where = '';
		if( isset($_GET['where']) ) {
			$where = '&' . $_GET['where'];
		};
		
		if( $_GET['opportunity'] == 'true' ) {
			
			do {	
				
				$curl = curl_init();
				
				$header = array();
				$header[] = 'Authorization: Element '. $user['code'] .', User eMYXjLZ2v/DwUYYc+NnJ/MISDJ9nr/qvsBhls4+K8Dw=';
				
			
				curl_setopt_array( $curl, array(
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_URL => 'https://api.cloud-elements.com/elements/api-v2/hubs/crm/opportunities?page=' . $pageStart . $where,
					CURLOPT_SSL_VERIFYPEER => true,
					CURLOPT_CAINFO => dirname(__FILE__) . '/cacert.pem',
					CURLOPT_HTTPHEADER => $header,
					CURLOPT_HEADER => 1
				));

				$resp = curl_exec($curl);
				
				$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
				$header = substr($resp, 0, $header_size);
				$body = substr($resp, $header_size);
				
				curl_close($curl);
				
				$pos = strpos($header, 'Elements-Returned-Count:');
				$posEnd = strpos($header, 'Server:');
				
				$returnedCount = substr($header, $pos + 25, $posEnd - ($pos + 25) );
				
				$returnedItemsArray = array_merge($returnedItemsArray, json_decode($body));

				$pageStart++;
				
			} while ( $returnedCount == 200 && $pageStart <= 11 );
			
			$objects['Opportunity'] = json_encode($returnedItemsArray);
			
		} else { $objects['Opportunity'] = ''; }

		echo json_encode($objects);
	}
	
	if( $_GET['action'] == 'updaterecord' ) {
		
		$sql = "SELECT code FROM integration
				WHERE userid = :user
				AND element = 'sfdc'";
		
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':user', $_SESSION['UserId'], PDO::PARAM_STR);
		$stmt->execute();
		$user = $stmt->fetch();
		
		$sql = "SELECT * FROM ep_created_rois
				WHERE roi_id = :roi;";
				
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$roi = $stmt->fetch();
		
		$curl = curl_init();
		
		$header = array();
		$header[] = 'Content-Type: application/json';
		$header[] = 'Authorization: Element '. $user['code'] .', User eMYXjLZ2v/DwUYYc+NnJ/MISDJ9nr/qvsBhls4+K8Dw=';
		
		$updated_fields = $_GET['updated_fields'];
		
		curl_setopt_array( $curl, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CUSTOMREQUEST => 'PATCH',
			CURLOPT_POSTFIELDS => $updated_fields,
			CURLOPT_URL => 'https://console.cloud-elements.com:443/elements/api-v2/hubs/crm/' . $roi['instance'] . '/' . $roi['sfdc_link'],
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_CAINFO => dirname(__FILE__) . '/cacert.pem',
			CURLOPT_HTTPHEADER => $header
		));

		$resp = curl_exec($curl);
		$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		
		if($resp === false) { echo curl_error($curl); } else { echo $httpcode; echo $resp; }
		curl_close($curl);
		
	}
	
	if( $_GET['action'] == 'getrecord' )
	{
		
		$sql = "SELECT code FROM integration
				WHERE userid = ( 
					SELECT UserID FROM users
					WHERE Username = :user
				)
				AND element = 'sfdc'";
		
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':user', $_SESSION['Username'], PDO::PARAM_STR);
		$stmt->execute();
		$user = $stmt->fetch();
		
		$sql = "SELECT * FROM list_items
				WHERE ListItemID = :roi;";
				
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch();
		
		$sfdc_link = $result['sfdc_link'];
		$instance = $result['instance'];
		
		$curl = curl_init();
		
		$header = array();
		$header[] = 'Authorization: Element '. $user['code'] .', User eMYXjLZ2v/DwUYYc+NnJ/MISDJ9nr/qvsBhls4+K8Dw=';
		
		curl_setopt_array( $curl, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_URL => 'https://api.cloud-elements.com/elements/api-v2/hubs/crm/' . $instance . '/' . $sfdc_link,
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_CAINFO => dirname(__FILE__) . '/cacert.pem',
			CURLOPT_HTTPHEADER => $header
		));

		$resp = curl_exec($curl);
		
		if($resp === false) { echo curl_error($curl); }
		curl_close($curl);
				
		echo $resp;

	}
	
	if( $_GET['action'] == 'getsfdcconnections') {
		
		ini_set('max_execution_time', 300);
		
		$pageStart = 1;
		$returnedItemsArray = array();
		
		$sql = "SELECT code FROM integration
				WHERE userid = ( 
					SELECT UserID FROM users
					WHERE Username = :user
				)
				AND element = 'sfdc'";
			
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':user', $_SESSION['Username'], PDO::PARAM_STR);
		$stmt->execute();
		$user = $stmt->fetch();
			
		if( $_GET['opportunity'] == 'true' ) {
			
			do {	
				
				$curl = curl_init();
				
				$header = array();
				$header[] = 'Authorization: Element '. $user['code'] .', User eMYXjLZ2v/DwUYYc+NnJ/MISDJ9nr/qvsBhls4+K8Dw=';
				
			
				curl_setopt_array( $curl, array(
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_URL => 'https://api.cloud-elements.com/elements/api-v2/hubs/crm/opportunities?page=' . $pageStart,
					CURLOPT_SSL_VERIFYPEER => true,
					CURLOPT_CAINFO => dirname(__FILE__) . '/cacert.pem',
					CURLOPT_HTTPHEADER => $header,
					CURLOPT_HEADER => 1
				));

				$resp = curl_exec($curl);
				
				$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
				$header = substr($resp, 0, $header_size);
				$body = substr($resp, $header_size);
				
				curl_close($curl);
				
				$pos = strpos($header, 'Elements-Returned-Count:');
				$posEnd = strpos($header, 'Server:');
				
				$returnedCount = substr($header, $pos + 25, $posEnd - ($pos + 25) );
				
				$returnedItemsArray = array_merge($returnedItemsArray, json_decode($body));

				$pageStart++;
				
			} while ( $returnedCount == 200 );
			
			$objects['Opportunity'] = json_encode($returnedItemsArray);
			
		} else { $objects['Opportunity'] = ''; }
		
		$pageStart = 1;
		$returnedItemsArray = array();		
		
		if( $_GET['account'] == 'true' ) {
			
			do {
				
				$curl = curl_init();
				
				$header = array();
				$header[] = 'Authorization: Element '. $user['code'] .', User eMYXjLZ2v/DwUYYc+NnJ/MISDJ9nr/qvsBhls4+K8Dw=';
				
				curl_setopt_array( $curl, array(
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_URL => 'https://api.cloud-elements.com/elements/api-v2/hubs/crm/accounts?pageSize=1000&page=' . $pageStart,
					CURLOPT_SSL_VERIFYPEER => true,
					CURLOPT_CAINFO => dirname(__FILE__) . '/cacert.pem',
					CURLOPT_HTTPHEADER => $header,
					CURLOPT_HEADER => 1
				));

				$resp = curl_exec($curl);
				
				$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
				$header = substr($resp, 0, $header_size);
				$body = substr($resp, $header_size);
				
				curl_close($curl);

				$pos = strpos($header, 'Elements-Returned-Count:');
				$posEnd = strpos($header, 'Server:');
				
				$returnedCount = substr($header, $pos + 25, $posEnd - ($pos + 25) );
				
				if($body) {
					$returnedItemsArray = array_merge($returnedItemsArray, json_decode($body));
				}

				$pageStart++;
				
			} while ( $returnedCount == 1000 );
			
			$objects['Account'] = json_encode($returnedItemsArray);
		
		} else { $objects['Account'] = ''; }
		
		$pageStart = 1;
		$returnedItemsArray = array();
		
		if( $_GET['lead'] == 'true' ) {
		
			do {
				
				$curl = curl_init();
				
				$header = array();
				$header[] = 'Authorization: Element '. $user['code'] .', User eMYXjLZ2v/DwUYYc+NnJ/MISDJ9nr/qvsBhls4+K8Dw=';
				
				curl_setopt_array( $curl, array(
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_URL => 'https://api.cloud-elements.com/elements/api-v2/hubs/crm/leads?page=' . $pageStart,
					CURLOPT_SSL_VERIFYPEER => true,
					CURLOPT_CAINFO => dirname(__FILE__) . '/cacert.pem',
					CURLOPT_HTTPHEADER => $header,
					CURLOPT_HEADER => 1
				));

				$resp = curl_exec($curl);
				
				$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
				$header = substr($resp, 0, $header_size);
				$body = substr($resp, $header_size);
				
				curl_close($curl);

				$pos = strpos($header, 'Elements-Returned-Count:');
				$posEnd = strpos($header, 'Server:');
				
				$returnedCount = substr($header, $pos + 25, $posEnd - ($pos + 25) );
				
				if($body) {
					$returnedItemsArray = array_merge($returnedItemsArray, json_decode($body));
				}

				$pageStart++;
				
			} while ( $returnedCount == 200 );
			
			$objects['Lead'] = json_encode($returnedItemsArray);
			
		} else { $objects['Lead'] = ''; }
		
		echo json_encode($objects);
		
	}
	
	if( $_GET['action'] == 'getsalesforceurl' )
	{
			
		$apiKey = '3MVG9A2kN3Bn17hvzQu7V2s1w3syS8ZQOyTO5rf1jVHGjKahZOuzi279__0x8O.77OFcNx8kLm82uHL_RiWJ2';
		$apiSecret = '2245576767359628946';
		$callbackUrl = 'https://www.theroishop.com/salesforceintegration';
		
		$curl = curl_init();
		
		$header = array();
		$header[] = 'Authorization: User eMYXjLZ2v/DwUYYc+NnJ/MISDJ9nr/qvsBhls4+K8Dw=, Organization 3ee0a5025541c25f7dda6a5ec004d548';
		
		curl_setopt_array( $curl, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_URL => 'https://api.cloud-elements.com/elements/api-v2/elements/sfdc/oauth/url?apiKey='.$apiKey.'&apiSecret='.$apiSecret.'&callbackUrl='.$callbackUrl,
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_CAINFO => dirname(__FILE__) . '/cacert.pem',
			CURLOPT_HTTPHEADER => $header
		));

		$resp = curl_exec($curl);
		
		if($resp === false) { echo curl_error($curl); }
		curl_close($curl);
				
		echo $resp;

	}
	
	if( $_GET['action'] == 'getaccountobjects' )
	{
		
		$sql = "SELECT code FROM integration
				WHERE userid = ( 
					SELECT UserID FROM users
					WHERE Username = :user
				)
				AND element = 'sfdc'";
		
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':user', $_SESSION['Username'], PDO::PARAM_STR);
		$stmt->execute();
		$user = $stmt->fetch();
		
		$curl = curl_init();
		
		$header = array();
		$header[] = 'Authorization: Element '. $user['code'] .', User eMYXjLZ2v/DwUYYc+NnJ/MISDJ9nr/qvsBhls4+K8Dw=';
		
		curl_setopt_array( $curl, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_URL => 'https://api.cloud-elements.com/elements/api-v2/hubs/crm/objects/Account/metadata',
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_CAINFO => dirname(__FILE__) . '/cacert.pem',
			CURLOPT_HTTPHEADER => $header
		));

		$resp = curl_exec($curl);
		
		if($resp === false) { echo curl_error($curl); }
		$objects['Account'] = $resp;
		curl_close($curl);
		
		$curl = curl_init();
		
		curl_setopt_array( $curl, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_URL => 'https://api.cloud-elements.com/elements/api-v2/hubs/crm/objects/Opportunity/metadata',
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_CAINFO => dirname(__FILE__) . '/cacert.pem',
			CURLOPT_HTTPHEADER => $header
		));
		
		$resp = curl_exec($curl);
		
		if($resp === false) { echo curl_error($curl); }
		$objects['Opportunity'] = $resp;
		curl_close($curl);
		
		$curl = curl_init();
		
		curl_setopt_array( $curl, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_URL => 'https://api.cloud-elements.com/elements/api-v2/hubs/crm/objects/Lead/metadata',
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_CAINFO => dirname(__FILE__) . '/cacert.pem',
			CURLOPT_HTTPHEADER => $header
		));
		
		$resp = curl_exec($curl);
		
		if($resp === false) { echo curl_error($curl); }
		$objects['Lead'] = $resp;
		curl_close($curl);
				
		echo json_encode($objects);

	}
	
	if( $_POST['action'] == 'storesfdclink' )
	{
		
		$sql = "UPDATE list_items SET sfdc_link = :link, instance = :instance, linked_title = :linktitle
				WHERE ListItemID = :roi";
				
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
		$stmt->bindParam(':link', $_POST['link'], PDO::PARAM_STR);
		$stmt->bindParam(':instance', $_POST['instance'], PDO::PARAM_STR);
		$stmt->bindParam(':linktitle', $_POST['title'], PDO::PARAM_STR);
		$stmt->execute();
		
	}
	
	if( $_GET['action'] == 'getsfdclink' )
	{
		
		$sql = "SELECT * FROM list_items
				WHERE ListItemID = :roi;";
				
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch();
		
		echo $result['sfdc_link'];
		
	}
	
	if( $_GET['action'] == 'getentries' )
	{
		
		$sql = "SELECT * FROM discovery_questions
				WHERE discovery_id = :discovery;";
		
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':discovery', $_GET['discovery'], PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetchall();
		
		echo json_encode( $result );
		
	}
	
?>