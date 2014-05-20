<?php namespace BFoxwell\Votifier;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Ratchet\Server\IoServer;

class Votifier implements LoggerAwareInterface
{
	/**
	 * Running Configuration
	 *
	 * @var array
	 */
	protected $config = [];

	/**
	 * Default Configuration Values
	 *
	 * @var array
	 */
	protected $defaults = [
		'address' => '0.0.0.0',
		'port' => 8192,
		'key' => '',
		'passphrase' => '',
		'log' => false
	];

	/**
	 * Logger
	 *
	 * @var \Psr\Log\LoggerInterface
	 */
	protected $logger;

	/**
	 * Task
	 *
	 * @var callable
	 */
	protected $task;

	/**
	 * Votifier
	 *
	 * @param array $config
	 * @param callable $task
	 */
	public function __construct(array $config, callable $task)
	{
		$this->config = $this->setDefaults($config);
		$this->task = $task;
	}

	/**
	 * Start Server
	 */
	public function run()
	{
		if(is_null($this->logger))
		{
			$this->logger = new NullLogger;
		}

		$listener = new Listener($this->config, $this->task, new Crypt, $this->logger);

		$server = IoServer::factory($listener, $this->config['port'], $this->config['address']);

		$server->run();
	}

	/**
	 * Set and validate configuration
	 *
	 * @param $config
	 * @return mixed
	 */
	protected function setDefaults($config)
	{
		foreach($this->defaults as $key => $value)
		{
			if( ! array_key_exists($key, $config))
			{
				$config[$key] = $value;
			}
		}

		$config['key'] = $this->getPrivateKey($config['key']);

		return $config;
	}

	/**
	 * Get Private Key
	 *
	 * @param $file
	 * @return string
	 * @throws \Exception
	 */
	protected function getPrivateKey($file)
	{
		if(empty($file))
		{
			throw new \Exception("The private key path must be set.");
		}

		if( ! is_file($file))
		{
			throw new \Exception("$file is not a valid file path.");
		}

		return file_get_contents($file);
	}

	/**
	 * Set Logger
	 *
	 * @param LoggerInterface $logger
	 * @return null|void
	 */
	public function setLogger(LoggerInterface $logger)
	{
		$this->logger = $logger;
	}
}