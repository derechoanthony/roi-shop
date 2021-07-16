<?php
	
	require_once("../db/constants.php");
	require_once("../db/connection.php");
	
	if( $_GET['action'] == 'getverification' ) {		
		
		$sql = "SELECT ver_code FROM list_items
				WHERE ListItemID=:roi";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetch();

		echo $data['ver_code'];
	}

	if( $_GET['action'] == 'resetver' ) {		
			
		$ver = sha1(uniqid(mt_rand(), true));
			
		$sql = "UPDATE list_items SET ver_code = :ver
				WHERE ListItemID=:roi";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':ver', $ver, PDO::PARAM_STR);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		echo $ver;
			
	}
	
	if( $_GET['action'] == 'addcont' ) {
			
		$sql = "INSERT INTO createdwith ( roi, username)
				VALUES(:roi, :name)";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':name', $_GET['cont'], PDO::PARAM_STR);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		echo $db->lastInsertId();
			
	}
	
	if( $_GET['action'] == 'getcontributors' ) {		
			
		$sql = "SELECT * FROM createdwith
				WHERE roi=:roi";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetchall();

		echo json_encode($data);
	}
	
	if( $_GET['action'] == 'delcont' ) {		
		
		$sql = "DELETE FROM createdwith
				WHERE id=:id";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
		$stmt->execute();
		$data = $stmt->fetch();
			
	}
	
	if( $_GET['action'] == 'getrowdata' ) {		
		
		$sql = "SELECT * FROM table_rows
				WHERE row_id = :rowid
				LIMIT 1;";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':rowid', $_GET['rowid'], PDO::PARAM_INT);
		$stmt->execute();
		$getrowdata = $stmt->fetch();
		
		$newrowname = str_replace("{{rowname}}", $_GET['rowname'], $getrowdata['row_style']);
		
		$sql = "INSERT INTO custom_table_rows (table_id, row_name, table_header, colspan, position, master_row_id, roi_id)
				VALUES(:table, :rowname, :header, :colspan, '1', :masterrow, :roi);";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':table', $getrowdata['table_id'], PDO::PARAM_INT);
		$stmt->bindParam(':rowname', $newrowname, PDO::PARAM_STR);
		$stmt->bindParam(':header', $getrowdata['table_header'], PDO::PARAM_INT);
		$stmt->bindParam(':colspan', $getrowdata['colspan'], PDO::PARAM_INT);
		//$stmt->bindParam(':position', $getrowdata['rowid'], PDO::PARAM_INT);
		$stmt->bindParam(':masterrow', $getrowdata['row_id'], PDO::PARAM_INT);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$rewrowid = $db->lastInsertId();
		
		$sql = "SELECT * FROM table_cells
				WHERE row_id = :rowid;";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':rowid', $_GET['rowid'], PDO::PARAM_INT);
		$stmt->execute();
		$getcelldata = $stmt->fetchall();
		
		$sql = "SELECT COUNT(input_name) FROM custom_calculation_elements
				WHERE input_name = :name AND roi_id = :roi;";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':name', $getrowdata['additional_row_name'], PDO::PARAM_STR);
		$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$inputcount = $stmt->fetch();
		$totalinputs = $inputcount['COUNT(input_name)'];
		
		echo $inputcount;
		
		foreach($getcelldata as $newcell){
			
			$totalinputs += 1;
			
			$sql = "SELECT * FROM calculation_elements
					WHERE calculation_element_id = :elementid
					LIMIT 1;";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':elementid', $newcell['reference_id'], PDO::PARAM_INT);
			$stmt->execute();
			$elementdata = $stmt->fetch();
			
			$sql = "INSERT INTO custom_calculation_elements (structure_id, row_id, type, format, input_name, total_inputs, roi_id)
					VALUES(:structure, :rowid, :type, :format, :name, :inputs, :roi);";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':structure', $elementdata['structure_id'], PDO::PARAM_INT);
			$stmt->bindParam(':rowid', $rewrowid, PDO::PARAM_INT);
			$stmt->bindParam(':type', $elementdata['type'], PDO::PARAM_STR);
			$stmt->bindParam(':format', $getrowdata['row_format'], PDO::PARAM_STR);
			$stmt->bindParam(':name', $getrowdata['additional_row_name'], PDO::PARAM_INT);
			$stmt->bindParam(':inputs', $totalinputs, PDO::PARAM_INT);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$newelementid = $db->lastInsertId();

			$sql = "INSERT INTO custom_table_cells (table_id, row_id, reference_id, roi_id)
					VALUES(:tableid, :rowid, :reference, :roi);";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':tableid', $newcell['table_id'], PDO::PARAM_INT);
			$stmt->bindParam(':rowid', $rewrowid, PDO::PARAM_INT);
			$stmt->bindParam(':reference', $newelementid, PDO::PARAM_INT);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();			
		}

	}
?>