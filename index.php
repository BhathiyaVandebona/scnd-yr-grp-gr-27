<?php
require_once 'core/init.php';

$user = DataBase::getInstance()->getAll('users', array('username', '=', 'JonSnow'));

if ($user && !$user->count()) {
	echo 'No user';
} else {
	echo 'OK', '<br>';
	foreach ($user->results() as $value) {
		echo $value->username, '<br>';
	}
}
