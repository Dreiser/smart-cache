<?php

namespace Hadamcik\SmartCache;

require_once __DIR__ . '/Utils/Filemanager/Filemanager.php';
require_once __DIR__ . '/Utils/Filemanager/RegularFile.php';
require_once __DIR__ . '/Utils/Filemanager/Directory.php';
require_once __DIR__ . '/Exceptions/DirNotExistsException.php';
require_once __DIR__ . '/Exceptions/DirNotWritableException.php';
require_once __DIR__ . '/Exceptions/NotDirException.php';
require_once __DIR__ . '/Exceptions/KeyNotFoundException.php';

use Hadamcik\SmartCache\Utils\Filemanager\Filemanager;
use Hadamcik\SmartCache\Utils\Filemanager\RegularFile;
use Hadamcik\SmartCache\Utils\Filemanager\Directory;

/**
 * Class FileCache
 * @package Hadamcik\SmartCache
 * @author Jakub Hadamčík <jakub@hadamcik.cz>
 */
class FileCache
{
	/** @var Directory */
	private $dir;

	/** @var Filemanager */
	private $filemanager;

	/**
	 * @param string $dir
	 * @param Filemanager $filemanager
	 * @throws DirNotExistsException
	 * @throws NotDirException
	 * @throws DirNotWritableException
	 */
	public function __construct($dir, Filemanager $filemanager)
	{
		$this->filemanager = $filemanager;
		$this->setDir($dir);
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @return RegularFile
	 */
	public function save($key, $value)
	{
		return $this->filemanager->createFile($this->getCacheFilePath($key), serialize($value));
	}

	/**
	 * @param string $key
	 * @return mixed
	 * @throws KeyNotFoundException
	 */
	public function load($key)
	{
		if($this->hasKey($key)) {
			$file = $this->filemanager->getRegularFile($this->getCacheFilePath($key));
			return unserialize($file->getContent());
		}
		throw new KeyNotFoundException();
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function hasKey($key)
	{
		return $this->filemanager->fileExists($this->getCacheFilePath($key));
	}

	/**
	 * @return string
	 */
	public function getDir()
	{
		return $this->dir->getPath();
	}

	/**
	 * @param string $dir
	 * @return FileCache
	 * @throws DirNotExistsException
	 * @throws NotDirException
	 * @throws DirNotWritableException
	 */
	public function setDir($path)
	{
		if(!$this->filemanager->fileExists($path)) {
			throw new DirNotExistsException();
		}
		if(!$this->filemanager->isDir($path)) {
			throw new NotDirException();
		}
		if(!$this->filemanager->isWritable($path)) {
			throw new DirNotWritableException();
		}
		$this->dir = $this->filemanager->getDirectory($path);
		return $this;
	}

	/**
	 * Return path to cached file
	 * @return string
	 */
	private function getCacheFilePath($key)
	{
		return $this->getDir() . '/' . $key;
	}
}
