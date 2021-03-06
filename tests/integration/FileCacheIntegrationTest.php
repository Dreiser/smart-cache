<?php

namespace Hadamcik\SmartCache;

require_once __DIR__ . '/../TestClass.php';
require_once __DIR__ . '/../../src/FileCache.php';
require_once __DIR__ . '/../../src/Utils/Filemanager/Filemanager.php';
require_once __DIR__ . '/../../src/Utils/Filemanager/Directory.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Hadamcik\SmartCache\Utils\Filemanager\Filemanager;
use Hadamcik\SmartCache\Utils\Filemanager\Directory;

/**
 * Class FileCacheIntegrationTest
 * @package Hadamcik\SmartCache
 * @author Jakub Hadammčík <jakub@hadamcik.cz>
 */
class FileCacheIntegrationTest extends \PHPUnit_Framework_TestCase
{
    const KEY = 'key';
    const VALUE = 'value';

    /** @var Filemanager */
    private $filemanager;

    /** @var Directory */
    private $tempDir;

    /**
     * Tests setUp
     */
    public function setUp()
    {
        parent::setUp();
        @mkdir($this->getTempPath());
        $this->filemanager = new Filemanager();
        $this->tempDir = new Directory($this->getTempPath());  
        $this->tempDir->cleanUp();
    }

    /**
     * Test setDir method correctly
     */
    public function testSetDir()
    { 
        $fileCache = new FileCache($this->getTempPath(), $this->filemanager);
        $this->assertInstanceOf('Hadamcik\\SmartCache\\FileCache', $fileCache);
        $this->assertInstanceOf('Hadamcik\\SmartCache\\FileCache', $fileCache->setDir($this->getTempPath()));
    }

    /**
     * Test setDir method with not existing directory
     */
    public function testsSetDirDirNotExist()
    {
        $fileCache = new FileCache($this->getTempPath(), $this->filemanager);
        $this->assertInstanceOf('Hadamcik\\SmartCache\\FileCache', $fileCache);
        $this->setExpectedException('Hadamcik\SmartCache\DirNotExistsException');
        $fileCache = new FileCache('not exist', $this->filemanager);
    }

    /**
     * Test setDir with not directory
     */
    public function testSetDirNotDir()
    {
        $fileCache = new FileCache($this->getTempPath(), $this->filemanager);
        $this->assertInstanceOf('Hadamcik\\SmartCache\\FileCache', $fileCache);
        $this->setExpectedException('Hadamcik\SmartCache\NotDirException');
        $fileCache = new FileCache(__DIR__ . '/FileCacheIntegrationTest.php', $this->filemanager);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @dataProvider saveProvider
     */
    public function testSave($key, $value)
    {
        $fileCache = new FileCache($this->getTempPath(), $this->filemanager);
        $this->assertInstanceOf('Hadamcik\\SmartCache\\Utils\\Filemanager\\RegularFile', $fileCache->save($key, $value));
    }

    /**
     * @param string $key
     * @param mixed $value
     * @dataProvider saveProvider
     */
    public function testHasKey($key, $value)
    {
        $fileCache = new FileCache($this->getTempPath(), $this->filemanager);
        $fileCache->save($key, $value);
        $this->assertTrue($fileCache->hasKey($key));
    }

    /**
     * @return array
     */
    public function saveProvider()
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
        $fileCache = new FileCache($this->getTempPath(), $this->filemanager);
        $fileCache->save(self::KEY, self::VALUE);
        $this->assertSame(self::VALUE, $fileCache->load(self::KEY));
    }

    /**
     * test load method with key not found exception
     */
    public function testLoadKeyNotFound()
    {
        $fileCache = new FileCache($this->getTempPath(), $this->filemanager);
        $this->setExpectedException('Hadamcik\SmartCache\KeyNotFoundException');
        $fileCache->load(self::KEY);
    }

    /**
     * Test load method correctly
     */
    public function testLoadObject()
    {
        $fileCache = new FileCache($this->getTempPath(), $this->filemanager);
        $object = new TestClass('value');
        $object->publicParam = 'public';
        $fileCache->save(self::KEY, $object);
        $this->assertEquals($object, $fileCache->load(self::KEY));
        $object->setPrivateParam('new value');
        $this->assertNotEquals($object, $fileCache->load(self::KEY));
    }

    /**
     * @return string
     */
    private function getTempPath()
    {
        return __DIR__ . '/temp/FileCache';
    }
}
