<?php
class Token{
	public static function generate(){
		$tokenName = Config::get('session/token_name');
		
		if(Session::exists($tokenName)){
			return Session::get($tokenName);
		}
		
		return Session::put(Config::get('session/token_name'), md5(uniqid()));
	}
	
	public static function check($token){
		$tokenName = Config::get('session/token_name');
		
		if(Session::exists($tokenName) && $token === Session::get($tokenName)){
			return true;
		}
		return false;
	}

}
?>