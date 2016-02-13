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

    /**
     * Test setDir method
     */
    public function testSetDir()
    {
        $directoryMock = Mockista\mock('Hadamcik\\SmartCache\\Utils\\Filemanager\\Directory');
        $directoryMock->freeze();

        $filemanagerMock = Mockista\mock('Hadamcik\\SmartCache\\Utils\\Filemanager\\Filemanager');
        $filemanagerMock->fileExists(self::PATH)->once()->andReturn(true);
        $filemanagerMock->isDir(self::PATH)->once()->andReturn(true);
        $filemanagerMock->isWritable(self::PATH)->once()->andReturn(true);
        $filemanagerMock->getDirectory(self::PATH)->once()->andReturn($directoryMock);
        $filemanagerMock->freeze();

        $fileCache = new FileCache(self::PATH, $filemanagerMock);
        $this->assertInstanceOf('Hadamcik\\SmartCache\\FileCache', $fileCache);
    }

    /**
     * Test save method
     */
    public function testSave()
    {
        $regularFileMock = Mockista\mock('Hadamcik\\SmartCache\\Utils\\Filemanager\\RegularFile');
        $regularFileMock->freeze();

        $directoryMock = Mockista\mock('Hadamcik\\SmartCache\\Utils\\Filemanager\\Directory');
        $directoryMock->getPath()->once()->andReturn(self::PATH);
        $directoryMock->freeze();

        $filemanagerMock = Mockista\mock('Hadamcik\\SmartCache\\Utils\\Filemanager\\Filemanager');
        $filemanagerMock->fileExists(self::PATH)->once()->andReturn(true);
        $filemanagerMock->isDir(self::PATH)->once()->andReturn(true);
        $filemanagerMock->isWritable(self::PATH)->once()->andReturn(true);
        $filemanagerMock->getDirectory(self::PATH)->once()->andReturn($directoryMock);
        $filemanagerMock->createFile($this->getCachedFilePath(), self::SERIALIZE_VALUE)->andReturn($regularFileMock);
        $filemanagerMock->freeze();

        $fileCache = new FileCache(self::PATH, $filemanagerMock);
        $this->assertInstanceOf('Hadamcik\\SmartCache\\Utils\\Filemanager\\RegularFile', $fileCache->save(self::KEY, self::VALUE));
    }

    /**
     * @return string
     */
    private function getCachedFilePath()
    {
        return self::PATH . '/' . self::KEY;
    }
}
