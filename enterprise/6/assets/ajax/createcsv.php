<?php
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="sample.csv"');

	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once("$root/db/constants.php");
	require_once("$root/db/connection.php");
	
	class RoiBuilder {
		
		private $_db;
		
		public function __construct($db=NULL) {
			
			if(is_object($db)) {
				$this->_db = $db;
			} else {
				$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
				$this->_db = new PDO($dsn, DB_USER, DB_PASS);
			}
		}
		
		public function ep_fields(){

			$sql = "SELECT * FROM a_fields
					WHERE version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					)";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$ep_fields = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			$sql = "SELECT * FROM a_created_fields
					WHERE roi_id = :roi";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$ep_created_fields = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			$sql = "SELECT * FROM a_choices
					WHERE version_id = (
						SELECT roi_version_id FROM ep_created_rois
						WHERE roi_id = :roi
					)";
					
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
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
				
				return $ep_fields;
			}
			
			$j = count($ep_fields);
			for($i = 0; $i < $j; $i++){
				$field = $ep_fields[$i];
				
				$choice_keys = array_keys(array_column($field_choices,'choice_id'), $field['choice_id']);
				foreach($choice_keys as $choice){
					$ep_fields[$i]['choices'][] = $field_choices[$choice];
				}				
			}
			
			return $ep_fields;	
		}
	}
	
	$roiBuilder = new RoiBuilder($db);
	$ep_fields = $roiBuilder->ep_fields();

$fp = fopen('php://output', 'wb');
foreach ($ep_fields as $line) {
    fputcsv($fp, $line, ',');
}
fclose($fp);