<?php
function datetime(){
	
	if(date_default_timezone_get() != Config::get('timesetting/time_zone')){
	
		date_default_timezone_set(Config::get('timesetting/time_zone'));
	}
	
	return date('Y-m-d H:i:S');
	
}

function TheDate($type = null){
	
	if(date_default_timezone_get() != Config::get('timesetting/time_zone')){
	
		date_default_timezone_set(Config::get('timesetting/time_zone'));
	}
	
	switch($type){
		
		case 'month':
			return date('m');
		break;
		
		case 'year':
			return date('Y');
		break;
		
		case 'day':
			return date('d');
		break;
		
		default:
			return date('Y-m-d');
		break;
	}
}
?>