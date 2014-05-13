<?php
return [
	'key' => __DIR__ . '/private.pem',
	'database' => [
		'driver'    => 'mysql',
		'host'      => 'localhost',
		'database'  => 'votifier',
		'username'  => 'vote',
		'password'  => 'password',
		'charset'   => 'utf8',
		'collation' => 'utf8_swedish_ci',
		'prefix'    => 'vi_'
	]
];