<?php

namespace Hadamcik\SmartCache;

require_once __DIR__ . '/../../src/FileCache.php';
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
    /** @var Mockista\Mock */
    private $filemanagerMock;

    /** @var FileCache */
    private $fileCache;

    /**
     * Tests setUp
     */
    public function setUp()
    {
        $this->filemanagerMock = Mockista\mock("NejakaTrida");
        $this->fileCache = new FileCache($this->getTempDir(), $this->filemanagerMock);
    }

    /**
     * Test construct method correctly
     */
    public function testConstruct()
    {
        $this->filemanagerMock->fileExists->once();
        $this->filemanagerMock->isDir->once();
        $this->filemanagerMock->isWritable->once();
        $this->assertInstanceOf('Hadamcik\\SmartCache\\Utils\\Filemanager\\Directory', $fileCache->getDir());
    }
}
