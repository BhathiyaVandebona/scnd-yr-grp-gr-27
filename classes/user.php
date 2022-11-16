<?php

class User {

	private $_database;

	public function __construct($user = null) {
		$this->_database = DataBase::getInstance();
	}

	public function create($fields = array()) {
		if (!$this->_database->insert('users', $fields)) {
			throw new Exception("Error creating user account");
		}
	}
}
