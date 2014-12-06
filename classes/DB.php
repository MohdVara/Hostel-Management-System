<?php
class DB{

	private static $_instance = null;
	private $_pdo,
			$_query,
			$_error = false,
			$_results,
			$_count = 0;
			
	private function __construct() {
		try{
			$this->_pdo = new PDO('mysql:host='.Config::get('mysql/host').';dbname='.Config::get('mysql/db'),Config::get('mysql/username'),Config::get('mysql/password'));
		}catch(PDOException $e){
			die($e->getMessage());
		}
	}
	
	public static function getInstance() {
		if(!isset(self::$_instance)){
			self::$_instance = new DB();
		}
		return self::$_instance;
	}
	
	public function query($sql, $params = array()){
		$this->error = false;
		if($this->_query = $this->_pdo->prepare($sql)){
			$x = 1;
			if(count($params)){
				foreach($params as $param){
					$this->_query->bindValue($x, $param);
					$x++;
				}	
			}
			
			if($this->_query->execute()){
				$this->_result = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
			}else{
				$this->_error = true;
				var_dump($this->_query->errorInfo());
		
			}
		}
		return $this;
	}
	
	public function actionV2($action, $table, $wheres = array()){
		$x=0;
		$sql_comp="";
		if($wheres){
			foreach($wheres as $where){			
					$operators = array('=','<=','>=','!='); 
						$field = $where[0]; 
					 $operator = $where[1];
					$value[$x] = $where[2];
					@$compound = $where[3];
					$x++;
					if(in_array($operator, $operators)){
						$sql_comp .= " {$compound} {$field} {$operator} ?";
					}else{}
				}
	
			$sql = "{$action} FROM {$table} WHERE".$sql_comp;
			
			if(count($value)<3){
				if(!$this->query($sql,array($value[0],$value[1]))->error()){
					return $this;
				}else{
					return false;
				}
			}else if(count($value)==3){	
				if(!$this->query($sql,array($value[0],$value[1],$value[2],))->error()){
					return $this;
				}else{
					return false;
				}
			}else if(count($value)<=4){
				if(!$this->query($sql,array($value[0],$value[1],$value[2],$value[3]))->error()){
					return $this;
				}else{
					return false;
				}
		
		}else{
			echo 'No where; ';
			return false;
		}

		}
	}
	
	public function get2($table, $where = array()){
		return $this->actionV2('SELECT *',$table,$where);
	}
	
	

	private function action($action, $table, $where){
		//Note : check on performance
		//Create nested loop for extracting where parameters
		//Use implode function
		//Save formula into variables
		
			if(count($where)==3){
			    $operators = array('=','<=','>=','!=','');
				    $field = $where[0];
				 $operator = $where[1];
				    $value = $where[2];
				
				if(in_array($operator, $operators)){
				
						$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
				
					if(!$this->query($sql, array($value))->error()){
						return $this;
					}
				}
			}else{
			
				$sql = "SELECT * FROM {$table}";
				if(!$this->query($sql)->error()){
					return $this;
				}
		
			}
		return false;
	}
	
	
	
	public function get($table, $where = null){
		return $this->action('SELECT *',$table,$where);
	}
	
	
	public function delete($table, $where){
		return $this->action('DELETE', $table,$where);
	}
	
	public function insert($table, $fields = array()){
		if(count($fields)){
			$keys = array_keys($fields);
			$values = null;
				 $x = 1;
				 
			foreach($fields as $field){
				$values .= '?';
				if($x < count($fields)){
					$values .= ', ';
				}
				$x++;
			}
				$sql ="INSERT INTO {$table} (`".implode('`,`',$keys)."`) VALUES ({$values})";
			
			if(!$this->query($sql, $fields)->error()){
				return true;
			}
		}			
		return false;
	}
	
	
	public function update($table, $id, $fields,$type=null){
		$set = '';
		  $x = 1;
		  
		foreach($fields as $name => $value){
			$set .= "{$name} = ?";
			if($x < count($fields)){
				$set .= ',';
			}
			$x++;
		}
		
		if(!$type){
			$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";  
		}else{
			$sql = "UPDATE {$table} SET {$set} WHERE {$type} = {$id}";
		}
		
		if(!$this->query($sql, $fields)->error()){
			return true;
		}
		
		return false;
	}
	

	
	public function error(){
		return $this->_error;
	}
	
	public function result(){
		return $this->_result;
	}
	
	public function first(){
		return $this->result()[0];
	}	
	
	public function count(){
		return $this->_count;
	}
		
}
