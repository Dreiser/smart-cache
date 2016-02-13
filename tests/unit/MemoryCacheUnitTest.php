<?php

namespace Hadamcik\SmartCache;

require_once __DIR__ . '/../../src/MemoryCache.php';
require_once __DIR__ . '/../../vendor/autoload.php';

/**
 * Class MemoryCacheUnitTest
 * @package Hadamcik\SmartCache
 * @author Jakub Hadammčík <jakub@hadamcik.cz>
 */
class MemoryCacheUnitTest extends \PHPUnit_Framework_TestCase
{
    const KEY = 'key';
    const VALUE = 'value';

	/** @var MemoryCache */
	private $memoryCache;

	/**
	 * Tests setUp
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

	/**
	 * Test load method correctly
	 */
	public function testLoad()
	{
		$this->memoryCache->save(self::KEY, self::VALUE);
		$this->assertSame(self::VALUE, $this->memoryCache->load(self::KEY));
	}

	/**
	 * Test load method with exception
	 */
	public function testLoadException()
	{
		$this->setExpectedException('Hadamcik\SmartCache\KeyNotFoundException');
		$this->memoryCache->load(self::KEY);
	}

	/**
	 * Test load method correctly
	 */
	public function testLoadObject()
	{
		$object = new TestClass('value');
		$object->publicParam = 'public';
		$this->memoryCache->save(self::KEY, $object);
		$this->assertEquals($object, $this->memoryCache->load(self::KEY));
		$object->setPrivateParam('new value');
		$this->assertNotEquals($object, $this->memoryCache->load(self::KEY));
	}
}
