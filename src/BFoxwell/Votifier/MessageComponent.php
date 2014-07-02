<?php namespace BFoxwell\Votifier;

use Psr\Log\LoggerInterface;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class MessageComponent implements MessageComponentInterface
{
	/**
	 * Configuration
	 *
	 * @var array
	 */
	protected $config;

	/**
	 * Callback
	 *
	 * @var callable
	 */
	protected $callback;

	/**
	 * Crypto Library
	 *
	 * @var Crypt
	 */
	protected $crypt;

	/**
	 * Logger
	 *
	 * @var \Psr\Log\LoggerInterface
	 */
	protected $logger;

	/**
	 * Listener
	 *
	 * @param array $config
	 * @param callable $callback
	 * @param Crypt $crypt
	 * @param \Psr\Log\LoggerInterface $logger
	 */
	public function __construct(array $config, callable $callback, Crypt $crypt, LoggerInterface $logger)
	{
		$this->config = $config;
		$this->callback = $callback;
		$this->crypt = $crypt;
		$this->logger = $logger;
	}

	/**
	 * New Connection
	 *
	 * @param ConnectionInterface $conn
	 */
	public function onOpen(ConnectionInterface $conn)
	{
		$this->logger->notice('Connection established from ' . $conn->remoteAddress);
	}

	/**
	 * Receive Message
	 *
	 * @param ConnectionInterface $from
	 * @param string $message
	 */
	public function onMessage(ConnectionInterface $from, $message)
	{
		$decrypted = $this->crypt->decrypt(
			$message,
			$this->config['key'],
			$this->config['passphrase']
		);

		$msg = $this->decode($decrypted);

		if(is_array($msg))
		{
			call_user_func($this->callback, $msg, $this->logger);
		}

		if($msg === false)
		{
			$this->logger->error('Message from ' . $from->remoteAddress . ' is invalid.');
		}
	}

	/**
	 * On Error
	 *
	 * @param ConnectionInterface $conn
	 * @param \Exception $e
	 */
	public function onError(ConnectionInterface $conn, \Exception $e)
	{
		$this->logger->error($e->getMessage());
	}

	/**
	 * On Closed Connection
	 *
	 * @param ConnectionInterface $conn
	 */
	public function onClose(ConnectionInterface $conn)
	{
		$this->logger->notice('Connection closed for ' . $conn->remoteAddress);
	}

	/**
	 * Decode expected votifier string
	 *
	 * @param $msg
	 * @return array|bool
	 */
	public function decode($msg)
	{
		$collection = preg_split("/\\r\\n|\\r|\\n/", trim($msg));

		if(array_shift($collection) === 'VOTE')
		{
			return [
				'serviceName' => $collection[0],
				'username' => $collection[1],
				'address' => $collection[2],
				'timeStamp' => $collection[3]
			];
		}

		return false;
	}
} 