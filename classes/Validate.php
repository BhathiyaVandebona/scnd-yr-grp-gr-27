<?php
// This defines the validation methods used to validate the user inputs

class Validate {

	private $_passed = false,
			$_errors = array(),
			$_database = null;

	function __construct() {
		$this->_database = DataBase::getInstance();	
	}

	// item here is the name of the input field
	// and each item in this arrays contains the
	// is associated with a set of rules and the 
	// inputs(values) must conform to these rules
	// for them to be valid
	// source here contains the values(actual input values)
	// in order they appear
	public function check($source, $items = array()) { // items here are rules
		foreach ($items as $item => $rules) {
			foreach ($rules as $rule => $rule_value) {
				$value = $source[$item];
				if ($rule == 'required' && empty($value)) {
					$this->add_error("{$item} is required");
				} else {
					// you don't have to have code here
				}
			}
		}
		if (empty($this->_errors)) {
			$this->_passed = true;
		}
		return $this;
	}

	// add the error(if there is any) to the error array
	private function add_error($error) {
		$this->_errors[] = $error;
	}

	public function errors() {
		return $this->_errors;
	}

	public function passed() {
		return $this->_passed;
	}
}
