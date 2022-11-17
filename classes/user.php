<?php

class User {

	private $_database;
	private $_user_data,
			$_session_name,
			$_cookie_name,
			$_is_logged_in;

	public function __construct($user = null) {
		$this->_database = DataBase::getInstance();
		$this->_session_name = Config::get('session/session_name');
		$this->_cookie_name = Config::get('remember/cookie_name');

		if (!$user) {
			if(Session::exists($this->_session_name)) {
				$user = Session::get($this->_session_name);
				// check whether the user exists
				// meaning the user is logged in
				if ($this->find($user)) {
					$this->_is_logged_in = true;
				} else {
					// log the user out
				}
			} else {
				// get the user data of a user that is not currently logged in
				$this->find($user);
			}
		}
	}

	public function create($fields = array()) {
		if (!$this->_database->insert('users', $fields)) {
			throw new Exception("Error creating user account");
		}
	}

	public function find($user = null) {
		if ($user) {
			$field = (is_numeric($user)) ? 'id' : 'username';
			$data = $this->_database->getAll('users', array($field, '=', $user));
			if ($data->count()) {
				// taking the top result
				$this->_user_data = $data->first();
				return true;
			}
		}
		return false;
	}

	public function login($username = null, $password = null, $remember_me = false) {
		// if the user wanted to be remembered
		if (!$username && !$password && $this->exists()) {
			Session::put($this->_session_name, $this->data()->id);
		} else {
			// if the user is not logged in already meaning a cookie is not there
		    $user = $this->find($username);
		    if ($user) {
		    	if($this->data()->password === Hash::make($password, $this->data()->salt)) {
		    		// starting a session
		    		Session::put($this->_session_name, $this->data()->id);

		    		if ($remember_me) {
		    			// check whether the hash is stored in the database table user_sessions
		    			// if the hash is there then the user is already logged in
		    			$hashCheck = $this->_database->getAll('user_session', array('user_id', '=', $this->data()->id));

		    			// generate a hash
		    			$hash = Hash::unique();

		    			// if there are no hashes in the table then add a new hash associated to this user
		    			if (!$hashCheck->count()) {
		    				$this->_database->insert('user_session', array(
		    					'user_id' => $this->data()->id,
		    					'hash' => $hash
		    				));
		    			} else {
		    				$hash = $hashCheck->first()->hash;
		    			}
		    			Cookie::put($this->_cookie_name, $hash, Config::get('remember/cookie_expiry'));
		    		}
		    		return true; // successful login
		    	}
		    }
		}
		return false;
	}

	public function update($fields = array(), $id = null) {

		// the user can change their information as well as
		if(!$id && $this->isLoggedIn()) {
			$id = $this->data()->id;
		} // an administrator can change their information as well

		if(!$this->_database->update('users', $id, $fields)) {
			throw new Exception('There was a problem updating the user information');
		}
	}

	public function logout() {

		// delete the hash value stared in the database as well
		// relevant to the user
		$this->_database->delete('user_session', array('user_id', '=', $this->data()->id));

		// remove the cookie and the session
		Session::delete($this->_session_name);
		Cookie::delete($this->_cookie_name);
	}

	private function exists() {
		return (!empty($this->data));
	}

	public function data() {
		return $this->_user_data;
	}

	public function isLoggedIn() {
		return $this->_is_logged_in;
	}
}
