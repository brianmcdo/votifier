<?php

use \Phpmig\Pimple\Pimple;
use \Illuminate\Database\Capsule\Manager as Capsule;

$config = include __DIR__ . '/config.php';

$container = new Pimple();

$container['config'] = $config['database'];

$container['db'] = $container->share(function($c) {
	return new PDO($c['config']['driver'] . ":host=" . $c['config']['host'] . ";dbname=" . $c['config']['database'], $c['config']['username'], $c['config']['password']);
});

$container['schema'] = $container->share(function($c) {
	/* Bootstrap Eloquent */
	$capsule = new Capsule;
	$capsule->addConnection($c['config']);
	$capsule->setAsGlobal();
	/* Bootstrap end */

	return Capsule::schema();
});

$container['phpmig.adapter'] = $container->share(function() use ($container) {
	return new Phpmig\Adapter\PDO\Sql($container['db'], 'migrations');
});

$container['phpmig.migrations_path'] = __DIR__ . DIRECTORY_SEPARATOR . 'migrations';

return $container;