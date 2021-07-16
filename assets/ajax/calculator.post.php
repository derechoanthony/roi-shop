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
	
	if( $_POST['action'] == 'storeRoiOptions' ) {

		$array_to_store = base64_encode( serialize($_POST['options']) );
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
			
			$sql = "INSERT INTO ep_created_roi_array (roi_id, roi_options, roi_values, stored_dt)
					VALUES (:roi,:options,:values,NOW());";
										
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->bindParam(':options', $array_to_store, PDO::PARAM_STR);
			$stmt->bindParam(':values', $values_to_store, PDO::PARAM_STR);
			$stmt->execute();
			
		} else {
			
			$sql = "UPDATE ep_created_roi_array
					SET roi_options = :options, roi_values = :values, stored_dt = NOW()
					WHERE roi_id = :roi;";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->bindParam(':options', $array_to_store, PDO::PARAM_STR);
			$stmt->bindParam(':values', $values_to_store, PDO::PARAM_STR);
			$stmt->execute();				
		}
		
		return json_decode(unserialize( base64_decode(gzuncompress($array_to_store)) ));
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
		echo 'create';
		require_once("$root/webapps/mpdf/mpdf.php");
		require_once("$root/webapps/core/functions/general.php");
		echo 'created';
		$GeneralFunctions = new GeneralFunctions();
		
		$reportId = $_POST['reportId'];
		$wbRoiId = $GeneralFunctions->Dlookup('wb_roi_ID','wb_roi_reports','wb_roi_report_ID=' . $reportId);
		$orient = $GeneralFunctions->Dlookup('PDForientation','wb_roi_reports','wb_roi_report_ID=' . $reportId);
		$reportCSS 	= $GeneralFunctions->Dlookup('CSS','wb_roi_reports','wb_roi_report_ID=' . $reportId);
		$reportHTML = $GeneralFunctions->Dlookup('html','wb_roi_reports','wb_roi_report_ID=' . $reportId);
		$roiValues = $GeneralFunctions->Dlookup('roi_values','ep_created_roi_array','roi_id=' . $_POST['roi']);
		
		$roiTitle = $GeneralFunctions->Dlookup('roi_title','ep_created_rois','roi_id=' . $_POST['roi']);
		$reportHTML = str_replace('<tag>Companyname</tag>', $roiTitle, $reportHTML);
		
		$userId = $GeneralFunctions->Dlookup('user_id','ep_created_rois','roi_id=' . $_POST['roi']);
		$roiOwner = $GeneralFunctions->Dlookup('first_name','roi_users','user_id=' . $userId) . ' ' . $GeneralFunctions->Dlookup('last_name','roi_users','user_id=' . $userId);
		$reportHTML = str_replace('<tag>Preparedby</tag>', $roiOwner, $reportHTML);
		$reportHTML = str_replace('<tag>roi_id</tag>', $_POST['roi'], $reportHTML);

		$username = $GeneralFunctions->Dlookup('username','roi_users','user_id=' . $userId);
		$reportHTML = str_replace('<tag>Email</tag>', $username, $reportHTML);

		$phone = $GeneralFunctions->Dlookup('phone','roi_users','user_id=' . $userId);
		$reportHTML = str_replace('<tag>Phone</tag>', $phone, $reportHTML);

		$reportHTML = str_replace('<tag>DatePrepared</tag>',  date("F j, Y"), $reportHTML);
		$reportHTML = str_replace('<tag>LinktoCalculator</tag>', '<a href="' . $_POST['roiPath'] . '">Link to the ROI</a>', $reportHTML);

		$values = json_decode(unserialize(base64_decode(gzuncompress($roiValues))));
		
		foreach($values as $value){
			$objects = get_object_vars($value);
			$reportHTML = str_replace('<formatted>'.$objects['el_field_name'].'</formatted>', $objects['el_formatted_value'], $reportHTML);
			
			echo $objects['el_field_name'] . ' ' . $objects['el_formatted_value'] . '/n';
		} 		
		
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
?>