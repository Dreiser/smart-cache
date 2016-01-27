<?php

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
