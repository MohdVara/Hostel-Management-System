<?php
session_start();

$GLOBALS['config'] = array(
	'mysql' => array(
			'host' => '127.0.0.1',
		'username' => 'XXX',
		'password' => 'XXXXXXX',
			  'db' => 'fakehosteldb'
	
	),
	//Disclaimer  :  The information on top is not the real connection details of my server.
	'remember' => array(
		  'cookie_name' => 'hash',
		'cookie_expiry' => 604800
	),
	
	'session' => array(
		'session_name' => 'user',
		  'token_name' => 'token' 
	),
	
	'timesetting' => array(
		'time_zone' => 'Asia/Kuala_Lumpur'
	)
);

spl_autoload_register(function($class) {
	require_once 'classes/' .$class. '.php';
});

require_once 'functions/sanitize.php';
require_once 'functions/time.php';
require_once 'functions/UI.php';
?>
