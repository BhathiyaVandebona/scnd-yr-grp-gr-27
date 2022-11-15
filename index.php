<?php
require_once 'core/init.php';

$user = DataBase::getInstance()->insert('users', array('username' => 'BillyTheKid', 'password' => 123, 'salt' => 'salt', 'joined' => date('Y/m/d'), 'user_group' => 1, 'first_name' => 'Billy', 'last_name' => 'Kid', 'mail' => 'BillyTheKid@gmail.com'));

if (!$user) {
	echo 'Error';
} else {
	echo 'All is well';
}
