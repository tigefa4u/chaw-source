<?php
class DATABASE_CONFIG {

	var $default = array(
		'driver' => 'mysql',
		'persistent' => false,
		'host' => 'localhost',
		'port' => '',
		'login' => 'root',
		'password' => '',
		'database' => 'chaw_workflow',
		'schema' => '',
		'prefix' => '',
		'encoding' => ''
	);
	
	var $test = array(
		'driver' => 'mysql',
		'persistent' => false,
		'host' => 'localhost',
		'port' => '',
		'login' => 'root',
		'password' => '',
		'database' => 'chaw_tests',
		'schema' => '',
		'prefix' => '',
		'encoding' => ''
	);
}
?>