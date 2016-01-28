<?php

namespace Hadamcik\SmartCache;

require_once __DIR__ . '/../src/FileCache.php';
require_once __DIR__ . '/Utils/Temp.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Hadamcik\SmartCache\Tests\Utils\Temp;

/**
 * Class FileCacheTest
 * @package Hadamcik\SmartCache
 * @author Jakub Hadammčík <jakub@hadamcik.cz>
 */
class FileCacheTest extends \PHPUnit_Framework_TestCase
{
    /** @var FileCache */
    private $fileCache;

    /** @var string */
    private $temp;

    public function setUp()
    {
        $this->setTempDir(__DIR__ . '/temp/FileCache');
        Temp::cleanUp($this->getTempDir());
        $this->fileCache = new FileCache($this->getTempDir());
    }

    /**
     * Test construct method correctly
     */
    public function testConstruct()
    {
        $fileCache = new FileCache($this->getTempDir());
        $this->assertSame($this->getTempDir(), $fileCache->getDir());
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

    /**
     * Test load method correctly
     */
    public function testLoad()
    {
        $this->fileCache->save('key', 'value');
        $this->assertSame('value', $this->fileCache->load('key'));
    }

    /**
     * test load method with exception
     */
    public function testLoadException()
    {
        $this->setExpectedException('Hadamcik\SmartCache\KeyNotFoundException');
        $this->fileCache->load('unknown key');
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
