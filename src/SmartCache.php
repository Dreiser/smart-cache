<?php

namespace Hadamcik\SmartCache;

require_once __DIR__ . '/FileCache.php';
require_once __DIR__ . '/MemoryCache.php';
require_once __DIR__ . '/Exceptions/KeyNotFoundException.php';

/**
 * Class SmartCache
 * @package Hadamcik\SmartCache
 * @author Jakub Hadamčík <jakub@hadamcik.cz>
 */
class SmartCache
{
	/** @var FileCache */
	private $fileCache;

	/** @var MemoryCache */
	private $memoryCache;

	/**
	 * @param FileCache $fileCache
	 * @param MemoryCache|null $memoryCache
	 */
	public function __construct(FileCache $fileCache, MemoryCache $memoryCache = null)
	{
		$this->fileCache = $fileCache;
		$this->memoryCache = ($memoryCache !== null ? $memoryCache : new MemoryCache());
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	public function save($key, $value)
	{

	}

	/**
	 * @param string $key
	 * @return mixed
	 * @throws KeyNotFoundException
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
		if($this->memoryCache->hasKey($key)) {
			return true;
		}
		return $this->fileCache->hasKey($key);
	}
}
