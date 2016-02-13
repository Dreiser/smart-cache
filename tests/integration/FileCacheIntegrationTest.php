<?php

namespace Hadamcik\SmartCache;

require_once __DIR__ . '/TestClass.php';
require_once __DIR__ . '/../../src/FileCache.php';
require_once __DIR__ . '/../../src/Utils/Filemanager/Filemanager.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Hadamcik\SmartCache\Utils\Filemanager\Filemanager;

/**
 * Class FileCacheIntegrationTest
 * @package Hadamcik\SmartCache
 * @author Jakub Hadammčík <jakub@hadamcik.cz>
 */
class FileCacheIntegrationTest extends \PHPUnit_Framework_TestCase
{
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
        $this->tempDir = $this->filemanager->getDirectory($this->getTempPath());  
        $this->tempDir->cleanUp();
    }

    /**
     * Test construct with not existing directory
     */
    public function testConstructDirNotExist()
    {
        $this->setExpectedException('Hadamcik\SmartCache\DirNotExistsException');
        $fileCache = new FileCache('not exist', $this->filemanager);
    }

    /**
     * Test construct with not directory
     */
    public function testConstructNotDir()
    {
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
        $fileCache->save('key', 'value');
        $this->assertSame('value', $fileCache->load('key'));
    }

    /**
     * test load method with exception
     */
    public function testLoadException()
    {
        $this->setExpectedException('Hadamcik\SmartCache\KeyNotFoundException');
        $fileCache = new FileCache($this->getTempPath(), $this->filemanager);
        $fileCache->load('unknown key');
    }

    /**
     * Test load method correctly
     */
    public function testLoadObject()
    {
        $fileCache = new FileCache($this->getTempPath(), $this->filemanager);
        $object = new TestClass('value');
        $object->publicParam = 'public';
        $fileCache->save('object', $object);
        $this->assertEquals($object, $fileCache->load('object'));
        $object->setPrivateParam('new value');
        $this->assertNotEquals($object, $fileCache->load('object'));
    }

    /**
     * @return string
     */
    private function getTempPath()
    {
        return __DIR__ . '/../temp/FileCache';
    }
}
