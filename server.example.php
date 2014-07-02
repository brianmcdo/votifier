<?php

require __DIR__ . '/vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use BFoxwell\Votifier\Votifier;
use Psr\Log\LoggerInterface;

// Set Configuration
$config = [
	'key' => __DIR__ . '/private.pem', // Required File Path
	'passphrase' => '', // Default: empty
	'address' => '0.0.0.0', // Default: localhost
	'port' => 8192, // Default: 8192
];

// Set Callable Function
$callback = function(array $message, LoggerInterface $log)
{
	var_dump($message);
	$log->notice('Logging an event.');
};

// Instantiate Votifier Server
$server = new Votifier($config, $callback);

// Setup Logger (Optional)
$logger = new Logger('Votifier');
$logger->pushHandler(new StreamHandler(__DIR__ . '/votifier.log'));
$server->setLogger($logger);

// Start Server
$server->run();