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
	/**
	 * Test construct method correctly
	 */
	public function testConstruct()
	{
		$fileCache = new FileCache(__DIR__ . '/../temp');
		$this->assertSame(__DIR__ . '/../temp', $fileCache->getDir());
	}

	/**
	 * Test construct with not existing directory
	 */
	public function testConstructDirNotExist()
	{
		$this->setExpectedException('Hadamcik\SmartCache\DirNotExistsException');
		$fileCache = new FileCache('not exist');
	}
}
