<?php
// this file will be included in all the files
// we are creating the includer
// setting up the initial start up

// staring a session
session_start();

// store the configs in the $GLOBALS and use them any where
$GLOBALS['config'] = array(
	// mysql credentials 
	'mysql' => array(
		'host' => '127.0.0.1',
		'username' => 'root',
		'password' => '',
		'db' => 'group_project'
	),
	 // loging page remember functionality storage to store the cookie
	'remember' => array(
		'cookie_name' => 'hash',
		// set the expiry for the cookies in seconds
		'cookie_expiry' => 86400,
	),
	// session settings
	// stores the session name and the token
	'session' => array(
		'session_name' => 'user',
		'token_name' => 'token'
	)
);

// auto loading classing rather than creating multiple requires or require_once ''
// use spl_autoload_register(); instead

spl_autoload_register(function($class) {
	require_once 'classes/' . $class . '.php';
});

require_once 'functions/sanitize.php';

if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
	// if the user is logged in
	$hash = Cookie::get(Config::get('remember/cookie_name'));
	$hash_check = DataBase::getInstance()->getAll('user_session', array('hash', '=', $hash));

	if ($hash_check->count()) {
		$user  = new User($hash_check->first()->user_id);
		$user->login();
	}
}