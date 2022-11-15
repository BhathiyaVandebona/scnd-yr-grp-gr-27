<?php
// to sanitize data before adding data to the database
// can buid custom functions as well in here

function escape($string) {
	return htmlentities($string, ENT_QUOTES, 'UTF-8');
}