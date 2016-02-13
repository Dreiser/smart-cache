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
    /**
     * Test setDir method
     */
    public function testSetDir()
    {
        $directoryMock = new Mockista\mock('Hadamcik\\SmartCache\\Utils\\Filemanager\\Directory');
        $directoryMock->freeze();
        $filemanagerMock = Mockista\mock('Hadamcik\\SmartCache\\Utils\\Filemanager\\Filemanager');
        $filemanagerMock->fileExists('')->once()->andReturn(true);
        $filemanagerMock->isDir('')->once()->andReturn(true);
        $filemanagerMock->isWritable('')->once()->andReturn(true);
        $filemanagerMock->getDirectory('')->once()->andReturn($directoryMock);
        $filemanagerMock->freeze();
        $fileCache = new FileCache('', $filemanagerMock);
        $this->assertInstanceOf('Hadamcik\\SmartCache\\FileCache', $fileCache);
    }
}
