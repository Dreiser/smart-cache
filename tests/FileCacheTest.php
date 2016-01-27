<?php

namespace Hadamcik\SmartCache;

require_once __DIR__ . '/../src/FileCache.php';
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Class FileCacheTest
 * @package Hadamcik\SmartCache
 * @author Jakub Hadammčík <jakub@hadamcik.cz>
 */
class FileCacheTest extends \PHPUnit_Framework_TestCase
{
	/** @var FileCache */
	private $fileCache;

	public function setUp()
	{
		$this->fileCache = new FileCache(__DIR__ . '/temp');
	}

	/**
	 * Test construct method correctly
	 */
	public function testConstruct()
	{
		$fileCache = new FileCache(__DIR__ . '/temp');
		$this->assertSame(__DIR__ . '/temp', $fileCache->getDir());
	}

	/**
	 * Test construct with not existing directory
	 */
	public function testConstructDirNotExist()
	{
		$this->setExpectedException('Hadamcik\SmartCache\DirNotExistsException');
		$fileCache = new FileCache('not exist');
	}

	/**
	 * Test construct with not directory
	 */
	public function testConstructNotDir()
	{
		$this->setExpectedException('Hadamcik\SmartCache\NotDirException');
		$fileCache = new FileCache(__DIR__ . '/FileCacheTest.php');
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @dataProvider hasKeyProvider
	 */
	public function testHasKey($key, $value)
	{
		$this->fileCache->save($key, $value);
		$this->assertTrue($this->fileCache->hasKey($key));
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
