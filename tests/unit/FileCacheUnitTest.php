<?php

namespace Hadamcik\SmartCache;

require_once __DIR__ . '/../../src/FileCache.php';
require_once __DIR__ . '/../../src/Utils/Filemanager/Filemanager.php';
require_once __DIR__ . '/../Utils/Temp.php';
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../vendor/jiriknesl/mockista/bootstrap.php';

use Mockista;

/**
 * Class FileCacheUnitTest
 * @package Hadamcik\SmartCache
 * @author Jakub Hadammčík <jakub@hadamcik.cz>
 */
class FileCacheUnitTest extends \PHPUnit_Framework_TestCase
{
    const PATH = 'path';
    const KEY = 'key';
    const VALUE = 'value';
    const SERIALIZED_VALUE = 's:5:"value";';

    /** @var Mockista\Mock */
    private $regularFileMock;

    /** @var Mockista\Mock */
    private $directoryMock;

    /** @var Mockista\Mock */
    private $filemanagerMock;

    /**
     * FileCacheUnitTest constructor
     */
    public function __construct()
    {
        $this->regularFileMock = Mockista\mock('Hadamcik\\SmartCache\\Utils\\Filemanager\\RegularFile');
        $this->directoryMock = Mockista\mock('Hadamcik\\SmartCache\\Utils\\Filemanager\\Directory');
        $this->filemanagerMock = Mockista\mock('Hadamcik\\SmartCache\\Utils\\Filemanager\\Filemanager');   
        $this->setDir();     
    }

    /**
     * Test setDir method
     */
    public function testSetDir()
    {
        $this->regularFileMock->freeze();
        $this->directoryMock->freeze();
        $this->filemanagerMock->freeze();
        $fileCache = new FileCache(self::PATH, $this->filemanagerMock);
        $this->assertInstanceOf('Hadamcik\\SmartCache\\FileCache', $fileCache);
    }

    /**
     * Test save method
     */
    public function testSave()
    {
        $this->regularFileMock->freeze();

        $this->directoryMock->getPath()->once()->andReturn(self::PATH);
        $this->directoryMock->freeze();

        $this->filemanagerMock->createFile($this->getCachedFilePath(), self::SERIALIZED_VALUE)->andReturn($this->regularFileMock);
        $this->filemanagerMock->freeze();

        $fileCache = new FileCache(self::PATH, $this->filemanagerMock);
        $this->assertInstanceOf('Hadamcik\\SmartCache\\Utils\\Filemanager\\RegularFile', $fileCache->save(self::KEY, self::VALUE));
    }

    /**
     * Test load method
     */
    public function testLoad()
    {
        $this->directoryMock->getPath()->once()->andReturn(self::PATH);
        $this->directoryMock->freeze();
        $this->filemanagerMock->fileExists($this->getCachedFilePath())->once()->andReturn(true);
        $this->filemanagerMock->getRegularFile($this->getCachedFilePath())->once()->andReturn($this->regularFileMock);
        $this->filemanagerMock->freeze();
        $this->regularFileMock->getContent()->once()->andReturn(self::SERIALIZED_VALUE);
        $this->regularFileMock->freeze();
        $fileCache = new FileCache(self::PATH, $this->filemanagerMock);
        $this->assertSame(self::VALUE, $fileCache->load(self::KEY));
    }

    /**
     * @return string
     */
    private function getCachedFilePath($path = self::PATH, $key = self::KEY)
    {
        return $path . '/' . $key;
    }

    /**
     * Sets mock expectations for called method setDir in FileCache
     * @return void
     */
    private function setDir($path = self::PATH)
    {
        $this->filemanagerMock->fileExists($path)->once()->andReturn(true);
        $this->filemanagerMock->isDir($path)->once()->andReturn(true);
        $this->filemanagerMock->isWritable($path)->once()->andReturn(true);
        $this->filemanagerMock->getDirectory($path)->once()->andReturn($this->directoryMock);
    }
}
