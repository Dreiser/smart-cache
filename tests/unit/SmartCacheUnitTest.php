<?php

namespace Hadamcik\SmartCache;

require_once __DIR__ . '/../../src/SmartCache.php';
require_once __DIR__ . '/../../src/FileCache.php';
require_once __DIR__ . '/../../src/MemoryCache.php';
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../vendor/jiriknesl/mockista/bootstrap.php';

use Mockista;

/**
 * Class SmartCacheUnitTest
 * @package Hadamcik\SmartCache
 * @author Jakub Hadammčík <jakub@hadamcik.cz>
 */
class SmartCacheUnitTest extends \PHPUnit_Framework_TestCase
{
    const KEY = 'key';
    const VALUE = 'value';

	/** @var Mockista\Mock */
	private $fileCacheMock;

	/** @var Mockista\Mock */
	private $memoryCacheMock;

	/**
	 * Tests setUp
	 */
	public function setUp()
	{
		$this->fileCacheMock = Mockista\mock('Hadamcik\\SmartCache\\FileCache');
		$this->memoryCacheMock = Mockista\mock('Hadamcik\\SmartCache\\MemoryCache');
	}

	/**
	 * Test save method
	 */
	public function testSave()
	{
		$this->memoryCacheMock->save(self::KEY, self::VALUE)->once();
		$this->memoryCacheMock->freeze();

		$this->fileCacheMock->save(self::KEY, self::VALUE)->once();
		$this->fileCacheMock->freeze();

		$smartCache = new SmartCache($this->fileCacheMock, $this->memoryCacheMock);
		$smartCache->save(self::KEY, self::VALUE);		
	}

	/**
	 * Test save method with file open failed exception
	 */
	public function testSaveFileOpenFailed()
	{
		$this->memoryCacheMock->save(self::KEY, self::VALUE)->once();
		$this->memoryCacheMock->freeze();

		$this->fileCacheMock->save(self::KEY, self::VALUE)->once()->andThrows('Hadamcik\\SmartCache\\Utils\\Filemanager\\FileOpenFailedException');
		$this->fileCacheMock->freeze();

		$smartCache = new SmartCache($this->fileCacheMock, $this->memoryCacheMock);
		$smartCache->save(self::KEY, self::VALUE);	
	}

	/**
	 * Test save method with file write failed exception
	 */
	public function testSaveFileWriteFailed()
	{
		$this->memoryCacheMock->save(self::KEY, self::VALUE)->once();
		$this->memoryCacheMock->freeze();

		$this->fileCacheMock->save(self::KEY, self::VALUE)->once()->andThrows('Hadamcik\\SmartCache\\Utils\\Filemanager\\FileWriteFailedException');
		$this->fileCacheMock->freeze();

		$smartCache = new SmartCache($this->fileCacheMock, $this->memoryCacheMock);
		$smartCache->save(self::KEY, self::VALUE);	
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @dataProvider hasKeyProvider
	 */
	public function testHasKeyFileCache($key, $value)
	{
		$this->memoryCacheMock->hasKey($key)->once()->andReturn(false);
		$this->memoryCacheMock->freeze();

		$this->fileCacheMock->hasKey($key)->once()->andReturn(true);
		$this->fileCacheMock->freeze();

		$smartCache = new SmartCache($this->fileCacheMock, $this->memoryCacheMock);
		$this->assertTrue($smartCache->hasKey($key));
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @dataProvider hasKeyProvider
	 */
	public function testHasKeyMemoryCache($key, $value)
	{
		$this->memoryCacheMock->hasKey($key)->once()->andReturn(true);
		$this->memoryCacheMock->freeze();

		$this->fileCacheMock->hasKey($key)->once()->andReturn(false);
		$this->fileCacheMock->freeze();

		$smartCache = new SmartCache($this->fileCacheMock, $this->memoryCacheMock);
		$this->assertTrue($smartCache->hasKey($key));
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
		$this->memoryCacheMock->hasKey(self::KEY)->once()->andReturn(true);
		$this->memoryCacheMock->load(self::KEY)->once()->andReturn(self::VALUE);
		$this->memoryCacheMock->freeze();

		$this->fileCacheMock->hasKey(self::KEY)->never();
		$this->fileCacheMock->load(self::KEY)->never();
		$this->fileCacheMock->freeze();

		$smartCache = new SmartCache($this->fileCacheMock, $this->memoryCacheMock);
		$this->assertSame(self::VALUE, $smartCache->load(self::KEY));
	}

	/**
	 * Test load method correctly by file cache
	 */
	public function testLoadFileCache()
	{
		$this->memoryCacheMock->hasKey(self::KEY)->once()->andReturn(false);
		$this->memoryCacheMock->load(self::KEY)->never();
		$this->memoryCacheMock->freeze();

		$this->fileCacheMock->hasKey(self::KEY)->once()->andReturn(true);
		$this->fileCacheMock->load(self::KEY)->once()->andReturn(self::VALUE);
		$this->fileCacheMock->freeze();

		$smartCache = new SmartCache($this->fileCacheMock, $this->memoryCacheMock);
		$this->assertSame(self::VALUE, $smartCache->load(self::KEY));
	}

	/**
	 * Test load method key not found exception
	 */
	public function testLoadKeyNotFound()
	{
		$this->memoryCacheMock->hasKey(self::KEY)->once()->andReturn(false);
		$this->memoryCacheMock->load(self::KEY)->never();
		$this->memoryCacheMock->freeze();

		$this->fileCacheMock->hasKey(self::KEY)->once()->andReturn(false);
		$this->fileCacheMock->load(self::KEY)->never();
		$this->fileCacheMock->freeze();

		$smartCache = new SmartCache($this->fileCacheMock, $this->memoryCacheMock);
		$this->setExpectedException('Hadamcik\SmartCache\KeyNotFoundException');
		$smartCache->load(self::KEY);
	}
}
