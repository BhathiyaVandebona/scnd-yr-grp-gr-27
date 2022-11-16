<?php
class Session {
	// set the token in a session
	public static function put($name, $value) {
		return $_SESSION['session ' . $name] = $value;
	}

	// check whether a token exists in the session
	public static function exists($name) {
		return (isset($_SESSION['session ' . $name])) ? true : false;	
	}

	// get the token stored in the session
	public static function get($name) {
		return $_SESSION['session ' . $name];
	}

	// destroy the token stored in the session
	public static function delete($name) {
		if(self::exists($name)) {
			unset($_SESSION['session ' . $name]);
		}
	}
}
