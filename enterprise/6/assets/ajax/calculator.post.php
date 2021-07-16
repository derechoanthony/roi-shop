<?php
	
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/constants.php");
	require_once("$root/db/connection.php");
	
	if( $_POST['action'] == 'storeRoiArray' ) {

		$array_to_store = base64_encode( serialize($_POST['array']) );
		$array_to_store = gzcompress($array_to_store);
		$values_to_store = base64_encode( serialize($_POST['values']) );
		$values_to_store = gzcompress($values_to_store);
		
		$sql = "SELECT * FROM ep_created_roi_array
				WHERE roi_id = :roi;";
				
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
		$stmt->execute();
		$record = $stmt->fetchall();
		
		if(!$record){
			
			$sql = "INSERT INTO ep_created_roi_array (`roi_id`,`roi_array`,`roi_values`,`stored_dt`)
					VALUES (:roi,:array,:values,NOW());";
										
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->bindParam(':array', $array_to_store, PDO::PARAM_STR);
			$stmt->bindParam(':values', $values_to_store, PDO::PARAM_STR);
			$stmt->execute();
			
		} else {
			
			$sql = "UPDATE ep_created_roi_array
					SET roi_array = :array, roi_values = :values, stored_dt = NOW()
					WHERE roi_id = :roi;";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->bindParam(':array', $array_to_store, PDO::PARAM_STR);
			$stmt->bindParam(':values', $values_to_store, PDO::PARAM_STR);
			$stmt->execute();				
		}
	}
	
	if( $_POST['action'] == 'writeToFile' ) {

		$filename = 'members.json';

		//open or create the file
		$handle = fopen($filename,'w+');

		//write the data into the file
		fwrite($handle,$_POST['options']);

		//close the file
		fclose($handle);		
	}
	
	if( $_POST['action'] == 'storeRoiOption' ) {

		foreach($_POST['fields'] as $value){
			
			$sql = "INSERT INTO a_created_fields (roi_id, el_field_name, f_data_type, f_text, choice_id, el_formula, el_value, el_formatted_value, f_format, version_id)
					VALUES (:roi, :field_name, :data, :text, :choice, :formula, :value, :formatted, :format, :version)
					ON DUPLICATE KEY UPDATE
					el_value = :value, el_formatted_value = :formatted;";
										
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->bindParam(':field_name', $value['el_field_name'], PDO::PARAM_STR);
			$stmt->bindParam(':data', $value['f_data_type'], PDO::PARAM_STR);
			$stmt->bindParam(':text', $value['f_text'], PDO::PARAM_STR);
			$stmt->bindParam(':choice', $value['choice_id'], PDO::PARAM_STR);
			$stmt->bindParam(':formula', $value['el_formula'], PDO::PARAM_STR);
			$stmt->bindParam(':value', $value['el_value'], PDO::PARAM_STR);
			$stmt->bindParam(':formatted', $value['el_formatted_value'], PDO::PARAM_STR);
			$stmt->bindParam(':format', $value['f_format'], PDO::PARAM_STR);
			$stmt->bindParam(':version', $value['version_id'], PDO::PARAM_INT);
			$stmt->execute();
		}
	}	
	
	if( $_POST['action'] == 'storeTableCell' ) {
		
		$cell = $_POST['cell'];
			
		$sql = "INSERT INTO a_table_custom_cells (field_column_id, field_row_id, f_data_type, el_field_name, roi_id, version_id)
				VALUES (:column, :row, :data, :field, :roi, :version);";
										
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':column', $cell['field_column_id'], PDO::PARAM_STR);
		$stmt->bindParam(':row', $cell['field_row_id'], PDO::PARAM_INT);
		$stmt->bindParam(':data', $cell['f_data_type'], PDO::PARAM_STR);
		$stmt->bindParam(':field', $cell['el_field_name'], PDO::PARAM_STR);
		$stmt->bindParam(':roi', $cell['roi_id'], PDO::PARAM_INT);
		$stmt->bindParam(':version', $cell['version_id'], PDO::PARAM_INT);
		$stmt->execute();
	}	
	
	if( $_POST['action'] == 'addContributor' ) {
		
		$sql = "INSERT INTO createdwith (username, roi)
				VALUES (:username, :roi);";
										
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
		$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
		$stmt->execute();
		
		$sql = "SELECT id, username FROM createdwith
				WHERE id = LAST_INSERT_ID();";
										
		$stmt = $db->prepare($sql);
		$stmt->execute();
		
		echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
	}
	
	if( $_POST['action'] == 'resetVerification' ) {
		
		$verification = sha1(uniqid(mt_rand(), true));
		
		$sql = "UPDATE ep_created_rois SET verification_code = :verification
				WHERE roi_id = :roi;";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':verification', $verification, PDO::PARAM_STR);
		$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
		$stmt->execute();
		echo $verification;
	}
	
	if( $_POST['action'] == 'storePdf' ){
		
		$img = urldecode($_POST['image']);
		$img = file_get_contents($img);
		$comp = $_POST['company'];
		$roi = $_POST['roi'];
		$section = $_POST['section'];
		$type = $_POST['type'];
		
		if(!is_dir("$root/company_specific_files/$comp/pdfs/")){
			mkdir("$root/company_specific_files/$comp/pdfs/");
		}
		
		file_put_contents("$root/company_specific_files/$comp/pdfs/$roi$type$section.png", $img);
	}
	
	if( $_POST['action'] == 'createpdf' ) {

		require_once("$root/webapps/mpdf/mpdf.php");
		require_once("$root/webapps/core/functions/general.php");

		$GeneralFunctions = new GeneralFunctions();
		
		$reportId = $_POST['reportId'];
		$wbRoiId = $GeneralFunctions->Dlookup('wb_roi_ID','wb_roi_reports','wb_roi_report_ID=' . $reportId);
		$orient = $GeneralFunctions->Dlookup('PDForientation','wb_roi_reports','wb_roi_report_ID=' . $reportId);
		$reportCSS 	= $GeneralFunctions->Dlookup('CSS','wb_roi_reports','wb_roi_report_ID=' . $reportId);
		$reportHTML = $GeneralFunctions->Dlookup('html','wb_roi_reports','wb_roi_report_ID=' . $reportId);
		
		$roiTitle = $GeneralFunctions->Dlookup('roi_title','ep_created_rois','roi_id=' . $_POST['roi']);
		$reportHTML = str_replace('<tag>Companyname</tag>', $roiTitle, $reportHTML);
		
		$userId = $GeneralFunctions->Dlookup('user_id','ep_created_rois','roi_id=' . $_POST['roi']);
		$roiOwner = $GeneralFunctions->Dlookup('full_name','users','UserId=' . $userId);
		$reportHTML = str_replace('<tag>Preparedby</tag>', $roiOwner, $reportHTML);
		
		$reportHTML = str_replace('<tag>DatePrepared</tag>',  date("F j, Y"), $reportHTML);
		$reportHTML = str_replace('<tag>LinktoCalculator</tag>', '<a href="' . $_POST['roiPath'] . '">Link to the ROI</a>', $reportHTML);

			$sql = "SELECT * FROM ep_created_rois
					WHERE roi_id = :roi;";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$roi_specifics = $stmt->fetchall(PDO::FETCH_ASSOC);			
			
			$sql = "SELECT * FROM a_fields
					WHERE version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					)";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$ep_fields = $stmt->fetchall(PDO::FETCH_ASSOC);
 			
			$sql = "SELECT * FROM a_created_fields
					WHERE roi_id = :roi";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$ep_created_fields = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			$sql = "SELECT * FROM a_choices
					WHERE version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					)";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$field_choices = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			for($i=0; $i < count($ep_created_fields); $i++){
				$field = $ep_created_fields[$i]['el_field_name'];
				
				$key_exists = false;
				for($j = 0; $j < count($ep_fields); $j++){
					if ($ep_fields[$j]['el_field_name'] == $field){
						$key_exists = true;
					}
				}
				
				if (!$key_exists){
					$ep_fields[] = $ep_created_fields[$i];
				}
			}
			
			if(!empty($ep_created_fields)){ 
				
				$j = count($ep_fields);
				for($i = 0; $i < $j; $i++){
					$field = $ep_fields[$i];
					
					$field_key = array_keys(array_column($ep_created_fields,'el_field_name'), $field['el_field_name']);
					$array = $ep_created_fields[$field_key[0]];
					
					$ep_fields[$i] = array_merge((array)$ep_fields[$i], (array)$array);
				}
				
				$j = count($ep_fields);
				for($i = 0; $i < $j; $i++){
					$field = $ep_fields[$i];
					
					$choice_keys = array_keys(array_column($field_choices,'choice_id'), $field['choice_id']);
					foreach($choice_keys as $choice){
						$ep_fields[$i]['choices'][] = $field_choices[$choice];
					}				
				}
			}
			
			$j = count($ep_fields);
			for($i = 0; $i < $j; $i++){
				$field = $ep_fields[$i];
				
				$choice_keys = array_keys(array_column($field_choices,'choice_id'), $field['choice_id']);
				foreach($choice_keys as $choice){
					$ep_fields[$i]['choices'][] = $field_choices[$choice];
				}				
			}

		$checked = '<img src="data:image/svg+xml;base64,PHN2ZyBoZWlnaHQ9JzMwMHB4JyB3aWR0aD0nMzAwcHgnICBmaWxsPSIjMDAwMDAwIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjEiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMTAwIDEwMCI+PGRlZnM+PGcgaWQ9ImEiPjxwYXRoIGZpbGw9IiMwMDAwMDAiIHN0cm9rZT0ibm9uZSIgZD0iIE0gODEuMjUgODAgTCA4MS4yNSAyMCBRIDgxLjI0NTUwNzgxMjUgMTkuNDg2NzE4NzUgODAuODUgMTkuMSA4MC41MTMyODEyNSAxOC43NTQ0OTIxODc1IDgwIDE4Ljc1IEwgMjAgMTguNzUgUSAxOS40ODY3MTg3NSAxOC43NTQ0OTIxODc1IDE5LjEgMTkuMSAxOC43NTQ0OTIxODc1IDE5LjQ4NjcxODc1IDE4Ljc1IDIwIEwgMTguNzUgODAgUSAxOC43NTQ0OTIxODc1IDgwLjUxMzI4MTI1IDE5LjEgODAuODUgMTkuNDg2NzE4NzUgODEuMjQ1NTA3ODEyNSAyMCA4MS4yNSBMIDgwIDgxLjI1IFEgODAuNTEzMjgxMjUgODEuMjQ1NTA3ODEyNSA4MC44NSA4MC44NSA4MS4yNDU1MDc4MTI1IDgwLjUxMzI4MTI1IDgxLjI1IDgwIE0gMjEuMjUgMjEuMjUgTCA3OC43NSAyMS4yNSA3OC43NSA3OC43NSAyMS4yNSA3OC43NSAyMS4yNSAyMS4yNSBNIDczLjQgMjQuMSBMIDQ5LjggNDggMjUuNiAyNC4xIFEgMjUuMjU5NzY1NjI1IDIzLjc0MTc5Njg3NSAyNC43NSAyMy43NSAyNC4yMjQ0MTQwNjI1IDIzLjc1NDY4NzUgMjMuODUgMjQuMSAyMy40OTEyMTA5Mzc1IDI0LjQ4OTI1NzgxMjUgMjMuNSAyNSAyMy41MDU0Njg3NSAyNS41MjUgMjMuODUgMjUuOSBMIDQ4IDQ5Ljc1IDI0LjEgNzMuODUgUSAyMy43NTUyNzM0Mzc1IDc0LjIzODY3MTg3NSAyMy43NSA3NC43NSAyMy43NTQyOTY4NzUgNzUuMjYyNSAyNC4xIDc1LjYgMjQuNDg4NDc2NTYyNSA3NS45OTYwOTM3NSAyNSA3NiAyNS41MjM0Mzc1IDc1Ljk5NTUwNzgxMjUgMjUuOSA3NS42IEwgNDkuOCA1MS41IDczLjY1IDc1LjE1IFEgNzQuMDM4NjcxODc1IDc1LjUwNzAzMTI1IDc0LjU1IDc1LjUgNzUuMDYyNSA3NS40OTU3MDMxMjUgNzUuNCA3NS4xIDc1Ljc5NjA5Mzc1IDc0Ljc2MTUyMzQzNzUgNzUuOCA3NC4yNSA3NS43OTU1MDc4MTI1IDczLjczODg2NzE4NzUgNzUuNCA3My4zNSBMIDUxLjU1IDQ5Ljc1IDc1LjIgMjUuODUgUSA3NS41NTcwMzEyNSAyNS41MTEzMjgxMjUgNzUuNTUgMjUgNzUuNTQ1NzAzMTI1IDI0LjQ4NzUgNzUuMTUgMjQuMSA3NC44MTE1MjM0Mzc1IDIzLjc1MzkwNjI1IDc0LjMgMjMuNzUgNzMuNzg4ODY3MTg3NSAyMy43NTQ0OTIxODc1IDczLjQgMjQuMSBaIj48L3BhdGg+PC9nPjwvZGVmcz48ZyB0cmFuc2Zvcm09Im1hdHJpeCggMSwgMCwgMCwgMSwgMCwwKSAiPjx1c2UgeGxpbms6aHJlZj0iI2EiPjwvdXNlPjwvZz48L3N2Zz4=" width="12px"></img>';
		
		$unchecked = '<img src="data:image/svg+xml;base64,PHN2ZyBoZWlnaHQ9JzMwMHB4JyB3aWR0aD0nMzAwcHgnICBmaWxsPSIjMDAwMDAwIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjEiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMTAwIDEwMCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMTAwIDEwMCIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PGc+PGc+PHBhdGggZD0iTTg0LDg2SDE0VjE2aDcwVjg2eiBNMjQsNzZoNTBWMjZIMjRWNzZ6Ij48L3BhdGg+PC9nPjwvZz48L3N2Zz4=" width="12px"></img>';

		foreach($ep_fields as $value){
			$reportHTML = str_replace('<value>'.$value['el_field_name'].'</value>', $value['el_value'], $reportHTML);
			$reportHTML = str_replace('<formatted>'.$value['el_field_name'].'</formatted>', $value['el_formatted_value'], $reportHTML);
			
			if (substr($value['el_field_name'], 0, 4) == "TBLE"){
				$list = "<li class=\"checklist_unsel\">". ( $value['el_value'] == 1 ? $checked : $unchecked ) ."Essential</li><li class=\"checklist_unsel\">". ( $value['el_value'] == 2 ? $checked : $unchecked ) ."Important</li><li class=\"checklist_unsel\">". ( $value['el_value'] == 3 ? $checked : $unchecked ) ."Useful</li><li class=\"checklist_unsel\">". ( $value['el_value'] == 4 ? $checked : $unchecked ) ."Future Opportunity</li><li class=\"checklist_unsel\">". ( $value['el_value'] == 5 ? $checked : $unchecked ) ."Not Applicable</li>";
				
				$reportHTML = str_replace('<importance>'.$value['el_field_name'].'</importance>', $list, $reportHTML);
			}
			
			if (substr($value['el_field_name'], 0, 4) == "TBLK"){
				$list = "<li class=\"checklist_unsel\">". ( $value['el_value'] == 1 ? $checked : $unchecked ) ."Currently Owned</li><li class=\"checklist_unsel\">". ( $value['el_value'] == 2 ? $checked : $unchecked ) ."Under Evaluation</li><li class=\"checklist_unsel\">". ( $value['el_value'] == 3 ? $checked : $unchecked ) ."Not Owned / Recommended</li><li class=\"checklist_unsel\">". ( $value['el_value'] == 4 ? $checked : $unchecked ) ."Not Owned</li>";
				
				$reportHTML = str_replace('<license>'.$value['el_field_name'].'</license>', $list, $reportHTML);
			}
			
			if (substr($value['el_field_name'], 0, 4) == "TBLJ"){
				$list = "<li class=\"checklist_unsel\">". ( $value['el_value'] == 1 ? $checked : $unchecked ) ."Not Applicable</li><li class=\"checklist_unsel\">". ( $value['el_value'] == 2 ? $checked : $unchecked ) ."Need Identified</li><li class=\"checklist_unsel\">". ( $value['el_value'] == 3 ? $checked : $unchecked ) ."In Deployment</li><li class=\"checklist_unsel\">". ( $value['el_value'] == 4 ? $checked : $unchecked ) ."Deployed / Active</li><li class=\"checklist_unsel\">". ( $value['el_value'] == 5 ? $checked : $unchecked ) ."Not Active / Product Issue</li><li class=\"checklist_unsel\">". ( $value['el_value'] == 6 ? $checked : $unchecked ) ."Not Active / Customer Issue</li>";
				
				$reportHTML = str_replace('<status>'.$value['el_field_name'].'</status>', $list, $reportHTML);
			}
			
			if (substr($value['el_field_name'], 0, 4) == "TBLA"){
				if($value['el_value'] == 1){
					$reportHTML = preg_replace('/%'.$value['el_field_name'].'%(.*?)%\/'.$value['el_field_name'].'%/', 'block', $reportHTML);
				} else {
					$reportHTML = preg_replace('/%'.$value['el_field_name'].'%(.*?)%\/'.$value['el_field_name'].'%/', 'none', $reportHTML);
				}
			}
			
			if (substr($value['el_field_name'], 0, 4) == "TBLA"){
				if($value['el_value'] == 1){

				} else {
					$reportHTML = preg_replace('/<'.$value['el_field_name'].'>(.*?)<\/'.$value['el_field_name'].'>/', '', $reportHTML);
				}
			}
			
			if (substr($value['el_field_name'], 0, 4) == "TBLE"){
				if($value['el_value'] == 1){
					$reportHTML = preg_replace('/<2'.$value['el_field_name'].'>(.*?)<\/2'.$value['el_field_name'].'>/', '', $reportHTML);
					$reportHTML = preg_replace('/<3'.$value['el_field_name'].'>(.*?)<\/3'.$value['el_field_name'].'>/', '', $reportHTML);
					$reportHTML = preg_replace('/<4'.$value['el_field_name'].'>(.*?)<\/4'.$value['el_field_name'].'>/', '', $reportHTML);
					$reportHTML = preg_replace('/<5'.$value['el_field_name'].'>(.*?)<\/5'.$value['el_field_name'].'>/', '', $reportHTML);
				} else if($value['el_value'] == 2){
					$reportHTML = preg_replace('/<1'.$value['el_field_name'].'>(.*?)<\/1'.$value['el_field_name'].'>/', '', $reportHTML);
					$reportHTML = preg_replace('/<3'.$value['el_field_name'].'>(.*?)<\/3'.$value['el_field_name'].'>/', '', $reportHTML);
					$reportHTML = preg_replace('/<4'.$value['el_field_name'].'>(.*?)<\/4'.$value['el_field_name'].'>/', '', $reportHTML);
					$reportHTML = preg_replace('/<5'.$value['el_field_name'].'>(.*?)<\/5'.$value['el_field_name'].'>/', '', $reportHTML);
				} else if($value['el_value'] == 3){
					$reportHTML = preg_replace('/<1'.$value['el_field_name'].'>(.*?)<\/1'.$value['el_field_name'].'>/', '', $reportHTML);
					$reportHTML = preg_replace('/<2'.$value['el_field_name'].'>(.*?)<\/2'.$value['el_field_name'].'>/', '', $reportHTML);
					$reportHTML = preg_replace('/<4'.$value['el_field_name'].'>(.*?)<\/4'.$value['el_field_name'].'>/', '', $reportHTML);
					$reportHTML = preg_replace('/<5'.$value['el_field_name'].'>(.*?)<\/5'.$value['el_field_name'].'>/', '', $reportHTML);
				} else if($value['el_value'] == 4){
					$reportHTML = preg_replace('/<1'.$value['el_field_name'].'>(.*?)<\/1'.$value['el_field_name'].'>/', '', $reportHTML);
					$reportHTML = preg_replace('/<2'.$value['el_field_name'].'>(.*?)<\/2'.$value['el_field_name'].'>/', '', $reportHTML);
					$reportHTML = preg_replace('/<3'.$value['el_field_name'].'>(.*?)<\/3'.$value['el_field_name'].'>/', '', $reportHTML);
					$reportHTML = preg_replace('/<5'.$value['el_field_name'].'>(.*?)<\/5'.$value['el_field_name'].'>/', '', $reportHTML);
				} else if($value['el_value'] == 5){
					$reportHTML = preg_replace('/<1'.$value['el_field_name'].'>(.*?)<\/1'.$value['el_field_name'].'>/', '', $reportHTML);
					$reportHTML = preg_replace('/<2'.$value['el_field_name'].'>(.*?)<\/2'.$value['el_field_name'].'>/', '', $reportHTML);
					$reportHTML = preg_replace('/<3'.$value['el_field_name'].'>(.*?)<\/3'.$value['el_field_name'].'>/', '', $reportHTML);
					$reportHTML = preg_replace('/<4'.$value['el_field_name'].'>(.*?)<\/4'.$value['el_field_name'].'>/', '', $reportHTML);
				}
			}
		} 		
		
		$reportHTML = preg_replace('/<value>(.*?)<\/value>/', '', $reportHTML);
		$reportHTML = preg_replace('/<formatted>(.*?)<\/formatted>/', '', $reportHTML);
		
		$reportHTML = str_replace('<checked>', $checked, $reportHTML);
		$reportHTML = str_replace('<unchecked>', $unchecked, $reportHTML);
		
		$reportHTML = str_replace('<currentdate></currentdate>', date('F j, Y'), $reportHTML);
		
		$reportHTML = preg_replace('/<roiname>(.*?)<\/roiname>/', $roi_specifics[0]['roi_title'], $reportHTML);
		$report = '<html><head>' . $reportCSS . '</head><body class="pdfbody">' . $reportHTML . '</body></html>';

		$stylesheet = file_get_contents("$root/webapps/assets/css/pdfstyle.css");
		$comp_stylesheet = file_get_contents("$root/webapps/assets/css/style.css");
		
		$mpdf->showImageErrors = true;	
		
		switch ($orient) {
			case 0:
				$orient = '';
				break;
			case 1:
				$orient = '-L';
				break;
			default:
				$orient = '';
				
		}
		
		$mpdf = new mPDF('c', 'A4' . $orient);
		
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->WriteHTML($comp_stylesheet,1);
			
		$mpdf->WriteHTML($report);
		
		$mpdf->Output("$root/webapps/assets/customwb/10016/pdf/preview-" . $reportId . '.pdf','F');		
	}
	
	if( $_POST['action'] == 'resetTemplate' ) {
		
		$sql = "DELETE FROM ep_created_roi_array WHERE roi_id = :roi;";

		$stmt = $db->prepare($sql);
		$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
		$stmt->execute();	
	}
	
	if( $_POST['action'] == 'saveOpportunity' ){
		
		$sql = "UPDATE ep_created_rois SET sfdc_link = :link, instance = :instance
				WHERE roi_id = :roi";
				
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
		$stmt->bindParam(':link', $_POST['link'], PDO::PARAM_STR);
		$stmt->bindParam(':instance', $_POST['instance'], PDO::PARAM_STR);
		$stmt->execute();
		
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
		
		$updated_fields = '{"Use_Case_Link__c":"'.$_POST['full_link'].'"}';
		
		$curl = curl_init();
		
		$header = array();
		$header[] = 'Content-Type: application/json';
		$header[] = 'Authorization: Element '. $user['code'] .', User eMYXjLZ2v/DwUYYc+NnJ/MISDJ9nr/qvsBhls4+K8Dw=';
		
		curl_setopt_array( $curl, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CUSTOMREQUEST => 'PATCH',
			CURLOPT_POSTFIELDS => $updated_fields,
			CURLOPT_URL => 'https://console.cloud-elements.com:443/elements/api-v2/hubs/crm/' . $_POST['instance'] . '/' . $_POST['link'],
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_CAINFO => dirname(__FILE__) . '/cacert.pem',
			CURLOPT_HTTPHEADER => $header
		));

		$resp = curl_exec($curl);
		$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		
		if($resp === false) { echo curl_error($curl); } else { echo $httpcode; echo $resp; }
		curl_close($curl);
	}
?>