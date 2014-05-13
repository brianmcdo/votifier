<?php require __DIR__ . '/vendor/autoload.php';

use Ratchet\Server\IoServer;
use BFoxwell\Votifier\Listener;
use Illuminate\Database\Capsule\Manager as Capsule;

$config = include __DIR__ . '/config.php';

$capsule = new Capsule;
$capsule->addConnection($config['database']);
$capsule->bootEloquent();

$server = IoServer::factory(
	new Listener(
		file_get_contents($config['key'])
	),
	8192
);
$server->run();