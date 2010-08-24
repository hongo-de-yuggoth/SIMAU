<?php
class DATABASE_CONFIG {

	var $default = array(
		'driver' => 'mysql',
		'persistent' => false,
		'host' => 'localhost',
		'port' => 3306,
		'login' => 'root',
		'password' => '',
		'database' => 'smuqlab',
	);
	var $test = array(
		'driver' => 'mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'user',
		'password' => 'password',
		'database' => 'test_database_name',
	);
	var $smuqlab = array(
		'driver' => 'mysql',
		'persistent' => false,
		'host' => 'localhost',
		'port' => 3306,
		'login' => 'root',
		'password' => '',
		'database' => 'smuqlab',
		'encoding' => 'UTF-8'
	);
	var $cencos = array(
		'driver' => 'mysql',
		'persistent' => false,
		'host' => 'localhost',
		'port' => 3306,
		'login' => 'root',
		'password' => '',
		'database' => 'inventario',
	);
}
?>
