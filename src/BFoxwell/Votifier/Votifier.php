<?php namespace BFoxwell\Votifier;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

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
		'passphrase' => ''
	];

	/**
	 * PSR-3 Compatible Logger
	 *
	 * @var \Psr\Log\LoggerInterface
	 */
	protected $logger;

	/**
	 * Closure passed to Message Component
	 *
	 * @var callable
	 */
	protected $closure;

	/**
	 * Votifier Server
	 *
	 * @param array $config
	 * @param callable $closure
	 */
	public function __construct(array $config, \Closure $closure)
	{
		$this->config = $this->setDefaults($config);

		$this->closure = $closure;
	}

	/**
	 * Start Server
	 */
	public function run()
	{
		if( ! $this->logger instanceof LoggerInterface)
		{
			$this->logger = new NullLogger;
		}

		$listener = new MessageComponent($this->config, $this->closure, new Crypt, $this->logger);

		$server = new Server(
			$listener,
			$this->config['port'],
			$this->config['address']
		);

		$server->start();
	}

	/**
	 * Set and validate configuration
	 *
	 * @param $config
	 * @return array
	 */
	protected function setDefaults(array $config)
	{
		foreach($this->defaults as $key => $value)
		{
			if( ! array_key_exists($key, $config))
			{
				$config[$key] = $value;
			}
		}

		return $config;
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