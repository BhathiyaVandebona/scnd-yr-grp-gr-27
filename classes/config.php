<?php
// this sets the config file
// extracting them and making accessing process simple
class Config {
	public static function get($path = null) {
		if ($path) {
			$config = $GLOBALS['config'];
			$path = explode('/', $path); //this gives an array back(like split)

			// iterate over all the sub strings of the splitted string
			foreach ($path as $key) {
				if(isset($config[$key])){ // if the key is set(has a value associated with it then execute the following commands)
					$config = $config[$key]; // if there is some value associated with this key then return that value
				} else {
					return false;
				}
			}
			return $config;
		}
		return false;
	}
}