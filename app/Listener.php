<?php namespace BFoxwell\Votifier;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use BFoxwell\Votifier\Models\Votes;

class Listener implements MessageComponentInterface
{
	protected $privateKey;
	protected $passphrase;

	public function __construct($privateKey, $passphrase = "")
	{
		$this->privateKey = $privateKey;
		$this->passphrase = $passphrase;
	}

	public function onMessage(ConnectionInterface $from, $msg)
	{
		$message = $this->decrypt($msg);
		$message = $this->decode($message);

		if(is_array($message))
		{
			echo json_encode($message);
			Votes::create($message);
		}
	}

	protected function decode($message)
	{
		$message = trim($message);
		$collection = preg_split("/\\r\\n|\\r|\\n/", $message);

		if(array_shift($collection) === 'VOTE')
		{
			return [
				'service_name' => $collection[0],
				'player' => $collection[1],
				'ip' => $collection[2],
				'voted_at' => date('Y-m-d h:i:s', $collection[3])
			];
		}

		return false;
	}

	protected function decrypt($message)
	{
		$decrypt = openssl_private_decrypt($message, $decryptedMessage, $this->privateKey);

		return $decrypt ? $decryptedMessage : false;
	}

	public function onOpen(ConnectionInterface $conn){}
	public function onClose(ConnectionInterface $conn){}
	public function onError(ConnectionInterface $conn, \Exception $e){}
}