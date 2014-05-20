<?php

require __DIR__ . '/vendor/autoload.php';

use BFoxwell\Votifier\Votifier;

$server = new Votifier([
	'key' => __DIR__ . '/private.pem', // Required File Path
	'passphrase' => '', // Default: empty
	'address' => '0.0.0.0', // Default: localhost
	'port' => 8192, // Default: 8192
]);

$server->setLogger(new \Psr\Log\NullLogger()); // Set Logger (optional)

$server->run(function($message)
{
	echo json_encode($message), PHP_EOL;
});