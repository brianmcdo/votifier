<?php namespace BFoxwell\Votifier;

class Crypt
{
	public function decrypt($msg, $privateKey, $passphrase = '')
	{
		$key = openssl_pkey_get_private($privateKey, $passphrase);

		$decrypt = openssl_private_decrypt($msg, $decryptedMessage, $key);

		return $decrypt ? $decryptedMessage : '';
	}
} 