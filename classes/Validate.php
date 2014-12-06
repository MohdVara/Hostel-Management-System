<?php
class Validate{
	private $_passed = false,
			$_errors = array(),
				$_db = null;
	
	public function __construct(){
		$this->_db = DB::getInstance();	
	}
	
	public function check($source, $items = array()){
		foreach($items as $item => $rules){
			foreach($rules as $rule => $rule_value){
				
				if(isset($source[$item])){
					$value = trim($source[$item]);
					$item = escape($item);
				}
				
				if($rule === 'required' && empty($value)){
					$this->addError("{$item} is required");
					break;
				}else{
					switch($rule){
						case 'min':
							if(strlen($value) < $rule_value)
								$this->addError("{$item} must be a minimum of {$rule_value}");
						break;
						case 'max':
							if(strlen($value) > $rule_value)
								$this->addError("{$item} must be a maximum of {$rule_value}");
						break;
						case 'matches':
							if($value != $source[$rule_value])
								$this->addError("{$rule_value} must be match {$item}");
						break;
						case 'unique':
							$check = $this->_db->get($rule_value, array($item, '=', $value));
							if($check->count()){
								$this->addError("{$item} has already registered");
							}
						break;
						case 'numbered':
							if(!ctype_digit($value)){
								$this->addError("{$item} must only have numbers");
							}
						break;
						case 'email form':
							if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
								$this->addError("{$item} must contain a valid email address");
							}
						break;
						case 'valid date':
							$d = DateTime::createFromFormat('Y-m-d', $value);
							if(!$d && $d->format('Y-m-d') == $date){
								$this->addError("{$item} must contain a valid date");
							}
						break;
							
					}
				}
			}	
		}
		
		if(empty($this->_errors)){
			$this->_passed = true;
		}
		return $this;
	}
	
	private function addError($error){
		$this->_errors[] = $error;
	}
	
	public function errors(){
		return $this->_errors;
	}
	
	public function passed(){
		return $this->_passed;
	}
}