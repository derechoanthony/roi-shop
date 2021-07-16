<?php
	
	require_once("../db/constants.php");
	require_once("../db/connection.php");
	
	if( $_POST['action'] == 'storevalues' ) {
		
		$storevalues = json_decode($_POST['storevalues'], true);
		
		foreach($storevalues as $value) {
					
			$sql = "INSERT INTO roi_values (`roiid`,`value`,`sessionid`,`entryid`, `dt`)
					VALUES (:roi,:value,:session,:entry,NOW());";
									
				$stmt = $db->prepare($sql);
				$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
				$stmt->bindParam(':session', $_SESSION['id'], PDO::PARAM_INT);
				$stmt->bindParam(':entry', $value[0], PDO::PARAM_STR);
				$stmt->bindParam(':value', $value[1], PDO::PARAM_STR);
				$stmt->execute();
		}
		
		echo json_encode('completed');

	}
	
	if($_POST['action'] == 'deletepdf') {
		
		$sql = "DELETE FROM pdf_builder
				WHERE roi=:roi";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
		$stmt->execute();		
	}
	
	if( $_POST['action'] == 'changepdf' ) {
			
		$sql = "SELECT * FROM pdf_builder
				WHERE element_id=:id AND roi=:roi;";
			
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':id', $_POST['element'], PDO::PARAM_INT);
		$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$element_data = $stmt->fetchall();
			
		if($element_data) {
				
			$sql = "UPDATE pdf_builder SET html=:html, pos_x=:posx, pos_y=:posy, page=:page
					WHERE element_id=:id AND roi=:roi;";
						
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':html', $_POST['html'], PDO::PARAM_STR);
			$stmt->bindParam(':page', $_POST['page'], PDO::PARAM_INT);
			$stmt->bindParam(':posx', $_POST['posx'], PDO::PARAM_INT);
			$stmt->bindParam(':posy', $_POST['posy'], PDO::PARAM_INT);
			$stmt->bindParam(':id', $_POST['element'], PDO::PARAM_INT);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->execute();
			
		} else {
					
			$sql = "INSERT INTO pdf_builder (html, page, pos_x, pos_y, element_id, roi)
					VALUES (:html, :page, :posx, :posy, :element, :roi);";
			
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':html', $_POST['html'], PDO::PARAM_STR);
			$stmt->bindParam(':page', $_POST['page'], PDO::PARAM_INT);
			$stmt->bindParam(':posx', $_POST['posx'], PDO::PARAM_INT);
			$stmt->bindParam(':posy', $_POST['posy'], PDO::PARAM_INT);
			$stmt->bindParam(':element', $_POST['element'], PDO::PARAM_STR);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->execute();
		}
			
	}
	
	if( $_POST['action'] == 'overrideoutput' ) {
			
		$sql = "SELECT * FROM user_output_value
				WHERE roiid=:roi AND sessionid=:session AND entryid=:entry;";
				
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
		$stmt->bindParam(':session', $_SESSION['id'], PDO::PARAM_INT);
		$stmt->bindParam(':entry', $_POST['entry'], PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetchall();
					
		if( $stmt->rowCount() > 0 ) {
			
			$sql = "UPDATE user_output_value SET value=:value
					WHERE roiid=:roi AND sessionid=:session AND entryid=:entry;";
						
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->bindParam(':session', $_SESSION['id'], PDO::PARAM_INT);
			$stmt->bindParam(':entry', $_POST['entry'], PDO::PARAM_INT);
			$stmt->bindParam(':value', $_POST['value'], PDO::PARAM_STR);
			$stmt->execute();				
		} else {
			
			$sql = "INSERT INTO user_output_value (`roiid`,`value`,`sessionid`,`entryid`)
					VALUES (:roi,:value,:session,:entry);";
						
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->bindParam(':session', $_SESSION['id'], PDO::PARAM_INT);
			$stmt->bindParam(':entry', $_POST['entry'], PDO::PARAM_INT);
			$stmt->bindParam(':value', $_POST['value'], PDO::PARAM_STR);
			$stmt->execute();				
		}
	}
	
	if( $_POST['action'] == 'deleteoutputvalue' ) {
			
		$sql = "DELETE FROM user_output_value
				WHERE roiid = :roi AND entryid = :entry";
					
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
		$stmt->bindParam(':entry', $_POST['entry'], PDO::PARAM_INT);
		$stmt->execute();				
	}
	
	if( $_POST['action'] == 'removehiddensections' ) {
			
		$sql = "DELETE FROM hidden_entities
				WHERE roi = :roi;";
			
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
		$stmt->execute();
	}
	
	if( $_POST['action'] == 'hidesection' ) {
			
		$sql = "INSERT INTO hidden_entities (`type`, `entity_id`, `roi`)
				VALUES ('section', :entityid, :roi);";
			
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
		$stmt->bindParam(':entityid', $_POST['section'], PDO::PARAM_INT);
		$stmt->execute();
	}
	
	if( $_POST['action'] == 'storecurrency' ) {

		$sql = "SELECT * FROM roi_currency
				WHERE roiid = :roi;";
						
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
		$stmt->execute();	
		$stmt->fetchall();
			
		if( $stmt->rowCount() > 0 ) {
				
			$sql = "UPDATE roi_currency SET currency = :currency
					WHERE roiid = :roi;";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->bindParam(':currency', $_POST['currency'], PDO::PARAM_STR);
			$stmt->execute();
		} else {
				
			$sql = "INSERT INTO roi_currency (`roiid`,`currency`)
					VALUES (:roi, :currency);";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->bindParam(':currency', $_POST['currency'], PDO::PARAM_STR);
			$stmt->execute();			
		}
			
		$sql = "UPDATE list_items SET currency = :language
				WHERE ListItemID = :roi;";
					
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
		$stmt->bindParam(':language', $_POST['language'], PDO::PARAM_STR);
		$stmt->execute();
			
	}
	
	if( $_POST['action'] == 'removerow' ) {
	
		$sql = "DELETE FROM custom_calculation_elements
				WHERE row_id = :rowid;";
			
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':rowid', $_POST['rowid'], PDO::PARAM_INT);
		$stmt->execute();
		
		$sql = "DELETE FROM custom_table_rows
				WHERE roi_id = :roi AND custom_row_id = :rowid;";
			
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':rowid', $_POST['rowid'], PDO::PARAM_INT);
		$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
		$stmt->execute();
		
		$sql = "DELETE FROM custom_table_cells
				WHERE row_id = :rowid;";
			
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':rowid', $_POST['rowid'], PDO::PARAM_INT);
		$stmt->execute();

	}
?>