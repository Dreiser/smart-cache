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
    const SERIALIZE_VALUE = 's:5:"value";';

    /** @var Mockista\Mock */
    private $regularFileMock;

    /** @var Mockista\Mock */
    private $directoryMock;

    /** @var Mockista\Mock */
    private $filemanagerMock;

    public function __construct()
    {
        $this->regularFileMock = Mockista\mock('Hadamcik\\SmartCache\\Utils\\Filemanager\\RegularFile');
        $this->directoryMock = Mockista\mock('Hadamcik\\SmartCache\\Utils\\Filemanager\\Directory');
        $this->filemanagerMock = Mockista\mock('Hadamcik\\SmartCache\\Utils\\Filemanager\\Filemanager');
    }

    /**
     * Test setDir method
     */
    public function testSetDir()
    {
        $this->directoryMock->freeze();

        $this->setDir();
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

        $this->setDir();
        $this->filemanagerMock->createFile($this->getCachedFilePath(), self::SERIALIZE_VALUE)->andReturn($this->regularFileMock);
        $this->filemanagerMock->freeze();

        $fileCache = new FileCache(self::PATH, $this->filemanagerMock);
        $this->assertInstanceOf('Hadamcik\\SmartCache\\Utils\\Filemanager\\RegularFile', $fileCache->save(self::KEY, self::VALUE));
    }

    /**
     * @return string
     */
    private function getCachedFilePath()
    {
        return self::PATH . '/' . self::KEY;
    }


    private function setDir()
    {
        $this->filemanagerMock->fileExists(self::PATH)->once()->andReturn(true);
        $this->filemanagerMock->isDir(self::PATH)->once()->andReturn(true);
        $this->filemanagerMock->isWritable(self::PATH)->once()->andReturn(true);
        $this->filemanagerMock->getDirectory(self::PATH)->once()->andReturn($this->directoryMock);
    }
}
