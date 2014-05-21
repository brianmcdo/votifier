<?php

require __DIR__ . '/vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use BFoxwell\Votifier\Votifier;

$config = [
	'key' => __DIR__ . '/private.pem', // Required File Path
	'passphrase' => '', // Default: empty
	'address' => '0.0.0.0', // Default: localhost
	'port' => 8192, // Default: 8192
];

$server = new Votifier($config, function($message, $log) // $message returns array
{
	var_dump($message);
	echo PHP_EOL;

	$log->notice('This is a test notice');
});

$log = new Logger('Votifier');

$log->pushHandler(new StreamHandler(__DIR__ . '/votifier.log'));

$server->setLogger($log); // Set Logger (optional)

$server->run();