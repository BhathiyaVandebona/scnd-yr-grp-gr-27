<?php
// This token is used to prevent cross site request forgery

class Token {
	public static function generate() {
		return Session::put(Config::get('session/token_name'), md5(uniqid()));
	}

	public static function check_token($token) {
		$tokenName = Config::get('session/token_name');

		if (Session::exists($tokenName) && $token === Session::get($tokenName)) {
			Session::delete($tokenName);
			return true;
		} else {
			return false;
		}
	}
}