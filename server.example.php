<?php

require __DIR__ . '/vendor/autoload.php';

use BFoxwell\Votifier\Votifier;

$config = [
	'key' => __DIR__ . '/private.pem', // Required File Path
	'passphrase' => '', // Default: empty
	'address' => '0.0.0.0', // Default: localhost
	'port' => 8192, // Default: 8192
];

$server = new Votifier($config, function($message)
{
	echo json_encode($message), PHP_EOL;
});

$server->setLogger(new \Psr\Log\NullLogger()); // Set Logger (optional)

$server->run();