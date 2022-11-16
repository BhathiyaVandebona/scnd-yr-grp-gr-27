<?php
class Session {
	// set the token in a session
	public static function put($name, $value) {
		return $_SESSION['_' . $name] = $value;
	}

	// check whether a token exists in the session
	public static function exists($name) {
		return (isset($_SESSION['_' . $name])) ? true : false;	
	}

	// get the token stored in the session
	public static function get($name) {
		return $_SESSION['_' . $name];
	}

	// destroy the token stored in the session
	public static function delete($name) {
		if(self::exists($name)) {
			unset($_SESSION['_' . $name]);
		}
	}

	// This method is used to flash data to the user
	// Just quick messages and so on stored in a session
	// only valid until the brower is refreshed
	// after the browser is refreshed the session variable
	// is destroyed
	public static function flash($name, $string = '') {
		if(self::exists($name)) {
			$session = self::get($name);
			self::delete($name);
			return $session;
		} else {
			self::put($name, $string);
		}
	}
}
