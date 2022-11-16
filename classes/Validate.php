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
			$value = trim($source[$item]);
			$item = escape($item);
			foreach ($rules as $rule => $rule_value) {
				// echo $value, '<br>';
				if ($rule == 'required' && empty($value)) {
					$this->add_error("{$item} is required");
				} else if(!empty($value)) {
					switch ($rule) {
						case 'min':
							if(strlen($value) < $rule_value) {
								$this->add_error("{$item} must be a minimum of {$rule_value} characters");
							}
							break;
						case 'max':
							if(strlen($value) > $rule_value) {
								$this->add_error("{$item} must be a maximum of {$rule_value} characters");
							}	
							break;
						case 'matches':
							if ($value != $source[$rule_values]) {
								$this->add_error("{$rule} value must match {$item}");
							}
							break;
						case 'unique':
							$check = $this->_database->get($rule_value, array($item, '=', $value));
							if($check->count()) {
								$this->add_error("{$item} already exists.");
							}
							break;
						default:
							// nothing
							break;
					}
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
