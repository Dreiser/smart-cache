<?php

namespace Hadamcik\SmartCache;

require_once __DIR__ . '/../src/SmartCache.php';
require_once __DIR__ . '/../src/FileCache.php';
require_once __DIR__ . '/../src/MemoryCache.php';
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Class SmartCacheTest
 * @package Hadamcik\SmartCache
 * @author Jakub Hadammčík <jakub@hadamcik.cz>
 */
class SmartCacheTest extends \PHPUnit_Framework_TestCase
{
	/** @var FileCache */
	private $fileCache;

	/** @var MemoryCache */
	private $memoryCache;

	/** @var SmartCache */
	private $smartCache;

	/**
	 * Tests setUp
	 */
	public function setUp()
	{
		$this->fileCache = new FileCache(__DIR__ . '/temp');
		$this->memoryCache = new MemoryCache();
		$this->smartCache = new SmartCache($this->fileCache, $this->memoryCache);
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @dataProvider hasKeyProvider
	 */
	public function testHasKeyFileCache($key, $value)
	{
		$this->fileCache->save($key, $value);
		$this->assertTrue($this->smartCache->hasKey($key));
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @dataProvider hasKeyProvider
	 */
	public function testHasKeyMemoryCache($key, $value)
	{
		$this->memoryCache->save($key, $value);
		$this->assertTrue($this->smartCache->hasKey($key));
	}

	/**
	 * @return array
	 */
	public function hasKeyProvider()
	{
		return [
			['key', 'value'],
			['key', null],
			['key', []],
			['key', 0],
			['key', false]
		];
	}
}
