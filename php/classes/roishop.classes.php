<?php
	
	class db_connection {
		
		protected $where;
		protected $id;
		protected $sql;
		protected $execute = array();
		
		function sql_builder () {
			
			$this->execute = array();
			$called_class = get_called_class();
			$where = $this->where;
			
			if(!$where){
				
				$this->sql = "select * from " . $called_class;
			} else {
				
				$where_clause = '';
				
				if(is_array($where)){
					
					foreach($where as $key => $value){
						$where_clause .= ( $where_clause == '' ? '' : ' AND ' ) . $key . " = :" . $key;
						$this->execute[':' . $key] = $value;
					}
				} else {
					
					$where_clause = $this->id . " = :" . $this->id;
					$this->execute[":" . $this->id] = $where;
				}
				
				$this->sql = "select * from " . $called_class . " where " . $where_clause;			
			}
		}
	}
	
	class ep_created_rois extends db_connection {
		
		protected $_db;
		
		public function __construct($db=NULL) {
			$this->_db = $db;
		}
		
		public function get_data($where) {
			
			$this->where = $where;
			$this->id = 'roi_id';
			
			parent::sql_builder();

			$stmt = $this->_db->prepare( $this->sql );
			$stmt->execute( $this->execute );
			$result = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $result;
		}
	}
	
	class roi_users extends db_connection {
		
		protected $_db;
		
		public function __construct($db=NULL) {
			$this->_db = $db;
		}	
		
		public function get_data($where) {

			$this->where = $where;
			$this->id = 'user_id';
			
			parent::sql_builder();

			$stmt = $this->_db->prepare( $this->sql );
			$stmt->execute( $this->execute );
			$result = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $result;	
		}
	}
	
	class roi_user_companies extends db_connection {
		
		protected $_db;
		
		public function __construct($db=NULL) {
			$this->_db = $db;
		}	
		
		public function get_data($where) {

			$this->where = $where;
			$this->id = 'user_company_id';
			
			parent::sql_builder();

			$stmt = $this->_db->prepare( $this->sql );
			$stmt->execute( $this->execute );
			$result = $stmt->fetchall(PDO::FETCH_ASSOC);
			
			return $result;	
		}
	}

?>