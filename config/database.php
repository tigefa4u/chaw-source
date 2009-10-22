<?php
class DATABASE_CONFIG {

	var $default = array(
		'driver' => 'mysql',
		'persistent' => false,
		'host' => 'localhost',
		'port' => '',
		'login' => 'l3dba',
		'password' => 'lith3um',
		'database' => 'l3api',
		'schema' => '',
		'prefix' => '',
		'encoding' => ''
	);
	
	var $test = array(
		'driver' => 'mysql',
		'persistent' => false,
		'host' => 'localhost',
		'port' => '',
		'login' => 'l3dba',
		'password' => 'lith3um',
		'database' => 'l3apiTest',
		'schema' => '',
		'prefix' => '',
		'encoding' => ''
	);
}
?>
