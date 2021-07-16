<?php
	
	class RoiInfo {
		
		private $_db;
		const TABLE_NAME = 'ep_created_rois';
		
		public function __construct($db=NULL) {
			if(is_object($db)) {
				$this->_db = $db;
			} else {
				$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
				$this->_db = new PDO($dsn, DB_USER, DB_PASS);
			}
		}

		public function roi_data() {
			
			$sql = "select * from " . self::TABLE_NAME . " where roi_id = :id";
			
			$stmt = $this->_db->prepare($sql);
			$stmt->execute( array( ':id' => $_GET['roi'] ) );
			$roi_data = $stmt->fetch(PDO::FETCH_ASSOC);
			
			return $roi_data;		
		}
		
		
		
	}

?>