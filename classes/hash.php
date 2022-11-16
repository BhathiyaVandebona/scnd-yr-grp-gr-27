<?php
class Hash {
	// random string used to make a stronger password hash => salt
	public static function make($string, $salt = '') {
		return hash('sha256', $string . $salt);
	}

	public static function salt($length) {
		return random_bytes($length);
	}

	public static function unique() {
		return self::make(uniqid());
	}
}