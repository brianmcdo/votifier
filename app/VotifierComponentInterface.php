<?php namespace BFoxwell\Votifier;

interface VotifierComponentInterface
{
	public function decrypt($message);
	public function decode($decryptedMessage);
}