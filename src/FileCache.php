<?php

namespace Hadamcik\SmartCache;

require_once __DIR__ . '/Exceptions/DirNotExistsException.php';
require_once __DIR__ . '/Exceptions/DirNotWritableException.php';
require_once __DIR__ . '/Exceptions/NotDirException.php';
require_once __DIR__ . '/Exceptions/KeyNotFoundException.php';

/**
 * Class FileCache
 * @package Hadamcik\SmartCache
 * @author Jakub Hadamčík <jakub@hadamcik.cz>
 */
class FileCache
{
	/** @var string */
	private $dir;

	/**
	 * @param string $dir
	 * @throws DirNotExistsException
	 * @throws NotDirException
	 * @throws DirNotWritableException
	 */
	public function __construct($dir)
	{
		$this->setDir($dir);
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	public function save($key, $value)
	{
		file_put_contents($this->getDir() . '/' . $key, json_encode($value));
	}

	/**
	 * @param string $key
	 * @return mixed
	 * @throws KeyNotFoundException
	 */
	public function load($key)
	{
		if($this->hasKey($key)) {
			return json_decode(file_get_contents($this->getDir() . '/' . $key));
		}
		throw new KeyNotFoundException();
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function hasKey($key)
	{
		return (file_exists($this->getDir() . '/' . $key));
	}

	/**
	 * @return string
	 */
	public function getDir()
	{
		return $this->dir;
	}

	/**
	 * @param string $dir
	 * @return FileCache
	 * @throws DirNotExistsException
	 * @throws NotDirException
	 * @throws DirNotWritableException
	 */
	private function setDir($dir)
	{
		if(!file_exists($dir)) {
			throw new DirNotExistsException();
		}
		if(!is_dir($dir)) {
			throw new NotDirException();
		}
		if(!is_writable($dir)) {
			throw new DirNotWritableException();
		}
		$this->dir = $dir;
		return $this;
	}
}
