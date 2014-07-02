<?php namespace BFoxwell\Votifier;

use Exception;

class Crypt
{
	public function decrypt($msg, $filename, $passphrase = '')
	{
		$key = openssl_pkey_get_private($this->getKeyFromFile($filename), $passphrase);

		$decrypt = openssl_private_decrypt($msg, $decryptedMessage, $key);

        if($decrypt)
        {
            return $decryptedMessage;
        }

        throw new Exception('Unable to decrypt message.');
	}

    /**
     * Retrieve key from file.
     *
     * @param $filename
     * @return string
     * @throws \Exception
     */
    protected function getKeyFromFile($filename)
    {
        if( ! is_file($filename))
        {
            throw new \Exception('Could not retrieve private key, invalid file path.');
        }

        return file_get_contents($filename);
    }
} 