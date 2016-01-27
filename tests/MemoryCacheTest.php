<?php

require_once __DIR__ . '/../src/MemoryCache.php';
require_once __DIR__ . '/../vendor/autoload.php';

class MemoryCacheTest extends PHPUnit_Framework_TestCase
{
	/** @var MemoryCache */
	private $memoryCache;

	/**
	 * Test setUp
	 */
	public function setUp()
	{
		$this->memoryCache = new MemoryCache();
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @dataProvider hasKeyProvider
	 */
	public function testHasKey($key, $value) 
	{
		$this->assertFalse($this->memoryCache->hasKey($key));
		$this->memoryCache->save($key, $value);
		$this->assertTrue($this->memoryCache->hasKey($key));
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
