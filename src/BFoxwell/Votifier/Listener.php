<?php namespace BFoxwell\Votifier;

use Psr\Log\LoggerInterface;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class Listener implements MessageComponentInterface
{
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

			$this->logger->info(json_encode($msg));
		}

		if($msg === false)
		{
			$this->logger->notice('Message from ' . $from->remoteAddress . ' is invalid.');
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
		$msg = trim($msg);

		$collection = preg_split("/\\r\\n|\\r|\\n/", $msg);

		if(array_shift($collection) === 'VOTE')
		{
			return [
				'service_name' => $collection[0],
				'player' => $collection[1],
				'ip' => $collection[2],
				'voted_at' => $collection[3]
			];
		}

		return false;
	}
} 