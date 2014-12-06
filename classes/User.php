<?php
class User{
	protected $_db,
			$_data,
			$_sessionName,
			$_cookieName,
			$_isLoggedIn;
	
	public function __construct($user = null){
		$this->_db = DB::getInstance();
		
		$this->_sessionName = Config::get('session/session_name');
		$this->_cookieName = Config::get('remember/cookie_name');
		
		if(!$user){	
			if(Session::exists($this->_sessionName)){
				$user = Session::get($this->_sessionName);
				
				if($this->find($user)){
					$this->_isLoggedIn = true;
					$this->update(array(
							'LastLogin' => datetime()
						));
				}
			}
			
		}else{
			$this->find($user);
		}
	}
	
	public function update($fields = array(),$id = null){
		
		if(!$id && $this->isLoggedIn()){
			$id = $this->data()->ID;
		}
		
		if(!$this->_db->update('users',$id,$fields)){
			throw new Exception('There was a problem updating.');
		}
	}
	
	public function data(){
		return $this->_data;
	}
	
	
	public function isLoggedIn(){
		return $this->_isLoggedIn;
	}
	
	public function exists(){
		return (!empty($this->_data)) ? true : false;
	}
	
	public function create($fields = array()){
	
		if(!$this->_db->insert('users', $fields)){
			
			throw new Exception('There was a problem creating an account.');
		}
	}
	
	public function print_slip($data){
		echo '<!DOCTYPE>';
		echo '<html>';
			echo '<head>';
			echo '</head>';
			echo '<body>';
			echo '</body>';
		echo '</html>';
	
	}
	
	public function find($user = null){
		if($user){
			$field = (is_numeric($user)) ? 'ID' : 'Name';
			$data = $this->_db->get('users', array($field, '=', $user));
			 
			if($data->count()){
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}
	
	public function login($username=null, $password=null, $remember=false){
		
		
		if(!$username && !$password && $this->exists()){
			Session::put($this->_sessionName, $this->data()->ID);
		}else{
			$user = $this->find($username);
			if($user){
				Hash::make($password, $this->data()->salt);
				if($this->data()->Password === Hash::make($password, $this->data()->salt)){
					Session::put($this->_sessionName, $this->data()->ID);
				
					if($remember){
						$hash =  Hash::unique();
						$hashCheck = $this->_db->get('users_session', array('user_id','=',$this->data()->ID)); 
				
						if(!$hashCheck->count()){
							$this->_db->insert('user_session',array(
								'user_id' => $this-data()->ID,
								'hash' => $hash
								));
						}else{
							$hash = $hashCheck->first()->hash;
						}
					
						Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
					}
				
					return true;
				}
				
			}
		}
		return false;
	}
	
	public function Change_Password($password){
		$user_id = $this->data()->ID;
		$salt = Hash::salt(32);
		if(!$this->_db->update('users',$user_id,array(
									'Password' => Hash::make($password,$salt),
										'salt' => $salt
							),'ID')){
			throw new Exception("Couldn't update password");
		}
		return true;
	}
	
	
	public function logout(){
		
		$this->_db->delete('users_session', array('user_id','=',$this->data()->ID));
		
		Cookie::delete($this->_cookieName);
		Session::delete($this->_sessionName);
		
	}
	
}