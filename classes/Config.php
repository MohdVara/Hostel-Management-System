<?php
class Config {
	public static function get($path = null) {
		if($path){
			$config = $GLOBALS['config'];
			$path = explode('/',$path);
			
			foreach($path as $lol) {
				if(isset($config[$lol])) {
					$config = $config[$lol];
				}	
			}
			
			return $config;
		}
	
		return false;
	}

}