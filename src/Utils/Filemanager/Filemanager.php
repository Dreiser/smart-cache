<?php

namespace Hadamcik\SmartCache\Utils\Filemanager;

require_once __DIR__ . '/../../Exceptions/Utils/Filemanager/FileOpenFailedException.php';
require_once __DIR__ . '/../../Exceptions/Utils/Filemanager/FileWriteFailedException.php';
require_once __DIR__ . '/Directory.php';
require_once __DIR__ . '/RegularFile.php';

/**
 * Class Filemanager
 * @package Hadamcik\SmartCache\Utils\Filemanager
 * @author Jakub Hadamčík <jakub@hadamcik.cz>
 */
class Filemanager
{
	/**
	 * @param string $path
	 * @param string $content
	 * @return RegularFile
	 * @throws FileOpenFailedException
	 * @throws FileWriteFailedException
	 */
	public function createFile($path, $content = null)
	{
		$file = fopen($path, 'w');
		if($file === false) {
			throw new FileOpenFailedException();
		}
		if(fwrite($file, $content) === false) {
			throw new FileWriteFailedException();
		}
		fclose($file);
		return new RegularFile($path);
	}

	/**
	 * @param string $path
	 * @return bool
	 */
	public function fileExists($path)
	{
		return file_exists($path);
	}

	/**
	 * @return bool
	 */
	public function isDir($path)
	{
		return is_dir($path);
	}

	/**
	 * @return bool
	 */
	public function isWritable($path)
	{
		return is_writable($path);
	}

	/**
	 * @return Directory
	 */
	public function getDirectory($path)
	{
		return new Directory($path);
	}

	/**
	 * @return RegularFile
	 */
	public function getRegularFile($path)
	{
		return new RegularFile($path);
	}
}
