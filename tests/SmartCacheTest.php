<?php

namespace Hadamcik\SmartCache;

require_once __DIR__ . '/../src/SmartCache.php';
require_once __DIR__ . '/../src/FileCache.php';
require_once __DIR__ . '/../src/MemoryCache.php';
require_once __DIR__ . '/Utils/Temp.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Hadamcik\SmartCache\Tests\Utils\Temp;

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

	/** @var string */
	private $temp;

	/**
	 * Tests setUp
	 */
	public function setUp()
	{
		$this->setTempDir(__DIR__ . '/temp/SmartCache');
        Temp::cleanUp($this->getTempDir());
		$this->fileCache = new FileCache($this->getTempDir());
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

	/**
	 * Test load method correctly by memory cache
	 */
	public function testLoadMemoryCache()
	{
		$this->memoryCache->save('key', 'value');
		$this->assertSame('value', $this->smartCache->load('key'));
	}

	/**
	 * Test load method correctly by file cache
	 */
	public function testLoadFileCache()
	{
		$this->fileCache->save('key', 'value');
		$this->assertSame('value', $this->smartCache->load('key'));
	}

	/**
	 * Test load method exception
	 */
	public function testLoadException()
	{
		$this->setExpectedException('Hadamcik\SmartCache\KeyNotFoundException');
		$this->assertSame('value', $this->smartCache->load('key'));
	}

    /**
     * @return string
     */
    private function getTempDir()
    {
    	return $this->temp;
    }

    /**
     * @param string $temp
     * @return FileCacheTest
     */
    private function setTempDir($temp)
    {
    	$this->temp = $temp;
    	return $this;
    }
}
