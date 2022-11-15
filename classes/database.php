<?php
// uses PDOs
class DataBase {
	// uses the singleton pattern to restrict the instatiated object of the connection
	// to a single instance
	// the underscore is a notation used to make the private variables

	// all the private variables
	private static $_instance = null;
	private $_pdo, $_query, $_error = false, $_results, $_count = 0;

	private function __construct() {
		try {
			$this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'),
				Config::get('mysql/username'),
				Config::get('mysql/password'));
		} catch(PDOException $error) {
			die($error->getMessage()); // kill the page and return the error message
		}
	}

	public static function getInstance() {
		if (!isset(self::$_instance)) {
			self::$_instance = new DataBase();
		}
		return self::$_instance;
	}

	// move this to another class CrudUtil or something that extends the interface
	// CrudUtil or otherwise change this to varargs as well
	public function query($sql_statement, ...$params) {
		$this->error = false;
		// prepare the sql statement this is to stop sqlinjection
		if ($this->_query = $this->_pdo->prepare($sql_statement)) {
			// if the count is not 0
			if(count($params) != 0) { 
				$x = 1;
				foreach($params as $param) {
					// if there are parameters passed in then iterate over them and add them as well
					$this->_query->bindValue($x, $param);
					$x++;
				}
			}
			// execute the query and check for any errors
			if($this->_query->execute()) {
				// this is the success case

				// set the result to the result returned from the database
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				// set the number of results returned from the database
				$this->_count = $this->_query->rowCount();
			} else {
				// this is the failure condition	
				// check the failure condition on
				$this->_error = true;
			}
		}
		return $this;
	}

	public function action($action, $table, $where) {
		if (count($where) == 3) { // we require field, operator and a value

			$operators = array('=', '>', '<', '>=', '<=');
			$field = $where[0];
			$operator = $where[1];
			$value = $where[2];

			if (in_array($operator, $operators)) { // check whether the operator is inside of the valid operators
				$sql_string = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
				// if this is valid then return the current object with fields set
				if (!$this->query($sql_string, $value)->error()) {
					return $this;
				}
			}
		}
		return false;
	}

	public function getAll($table, $where) {
		// echo $where, '<br>';
		return $this->action('SELECT *',  $table, $where);
	}

	public function delete($table, $where) {
		echo $where, '<br>';
		return $this->action('DELETE *',  $table, $where);
	}

	public function results() {
		return $this->_results;
	}

	// return the result count
	// number of results the query got
	public function count() {
		return $this->_count;
	}

	// since error is private this method will be used to return the value
	// stored in it.
	public function error() {
		return $this->_error;
	}
}