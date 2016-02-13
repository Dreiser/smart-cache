<?php

namespace Hadamcik\SmartCache;

require_once __DIR__ . '/FileCache.php';
require_once __DIR__ . '/MemoryCache.php';

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
	 * @throws FileOpenFailedException
	 * @throws FileWriteFailedException
	 */
	public function save($key, $value)
	{
		$this->memoryCache->save($key, $value);
		$this->fileCache->save($key, $value);
	}

	/**
	 * @param string $key
	 * @return mixed
	 * @throws KeyNotFoundException
	 */
	public function load($key)
	{
		if($this->memoryCache->hasKey($key)) {
			return $this->memoryCache->load($key);
		}
		else if($this->fileCache->hasKey($key)) {
			return $this->fileCache->load($key);
		}
		throw new KeyNotFoundException();
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
