<?php

namespace Hadamcik\SmartCache;

require_once __DIR__ . '/Exceptions/KeyNotFoundException.php';

/**
 * Class MemoryCache
 * @author Jakub Hadamčík <jakub@hadamcik.cz>
 */
class MemoryCache
{
	/** @var array */
	private $data = [];

	/**
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	public function save($key, $value)
	{
		$this->data[$key] = $value;
	}

	/**
	 * @param string $key
	 * @return mixed
	 */
	public function load($key)
	{
		if($this->hasKey($key)) {
			return $this->data[$key];
		}
		throw new KeyNotFoundException();
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function hasKey($key)
	{
		return (isset($this->data[$key]) || array_key_exists($key, $this->data));
	}
}
