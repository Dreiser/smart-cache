<?php

require_once __DIR__ . '/../src/MemoryCache.php';
require_once __DIR__ . '/../vendor/autoload.php';

class MemoryCacheTest extends PHPUnit_Framework_TestCase
{
	/** @var MemoryCache */
	private $memoryCache;

	public function setUp()
	{
		$this->memoryCache = new MemoryCache();
	}

	public function testHasKey() 
	{
		$key = 'key';
		$value = 'value';
		$this->assertFalse($this->memoryCache->hasKey($key));
		$this->memoryCache->save($key, $value);
		$this->assertTrue($this->memoryCache->hasKey($key));
	}
}
