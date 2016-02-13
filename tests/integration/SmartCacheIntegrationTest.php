<?php

namespace Hadamcik\SmartCache;

require_once __DIR__ . '/../../src/SmartCache.php';
require_once __DIR__ . '/../../src/FileCache.php';
require_once __DIR__ . '/../../src/MemoryCache.php';
require_once __DIR__ . '/../../src/Utils/Filemanager/Filemanager.php';
require_once __DIR__ . '/../../src/Utils/Filemanager/Directory.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Hadamcik\SmartCache\Utils\Filemanager\Filemanager;
use Hadamcik\SmartCache\Utils\Filemanager\Directory;

/**
 * Class SmartCacheIntegrationTest
 * @package Hadamcik\SmartCache
 * @author Jakub Hadammčík <jakub@hadamcik.cz>
 */
class SmartCacheIntegrationTest extends \PHPUnit_Framework_TestCase
{
    const KEY = 'key';
    const VALUE = 'value';

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
		@mkdir($this->getTempPath());		
        $filemanager = new Filemanager();
		$tempDir = new Directory($this->getTempPath());
		$tempDir->cleanUp();
		$this->fileCache = new FileCache($this->getTempPath(), $filemanager);
		$this->memoryCache = new MemoryCache();
		$this->smartCache = new SmartCache($this->fileCache, $this->memoryCache);
	}

	/**
	 * Test save method
	 */
	public function testSave()
	{
		$this->smartCache->save(self::KEY, self::VALUE);
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
		$this->memoryCache->save(self::KEY, self::VALUE);
		$this->assertSame(self::VALUE, $this->smartCache->load(self::KEY));
	}

	/**
	 * Test load method correctly by file cache
	 */
	public function testLoadFileCache()
	{
		$this->fileCache->save(self::KEY, self::VALUE);
		$this->assertSame(self::VALUE, $this->smartCache->load(self::KEY));
	}

	/**
	 * Test load method with key not found exception
	 */
	public function testLoadKeyNotFound()
	{
		$this->setExpectedException('Hadamcik\SmartCache\KeyNotFoundException');
		$this->assertSame(self::VALUE, $this->smartCache->load(self::KEY));
	}

    /**
     * @return string
     */
    private function getTempPath()
    {
        return __DIR__ . '/../temp/FileCache';
    }
}
