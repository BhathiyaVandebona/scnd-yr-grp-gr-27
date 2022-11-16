<?php
// this class defines the form data retrieving methods
class Input {
	public static function exists($type = 'POST') {
		// check whether the values are sent
		switch ($type) {
			case 'POST':
				return (!empty($_POST)) ? true : false;
				break;
			case 'GET':
				return (!empty($_GET)) ? true : false;
				break;
			default:
				return false;
				break;
		}
	}

	public static function get($item) {
		if(isset($_POST[$item])) {
			return $_POST[$item];
		} elseif (isset($_GET[$item])) {
			return $_GET[$item];
		} else {
			return '';
		}
	}
}