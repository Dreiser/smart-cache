<?php

namespace Hadamcik\SmartCache\Utils\Filemanager;

require_once __DIR__ . '/../../Exceptions/Utils/Filemanager/FileDoNotExistsException.php';

/**
 * Class File
 * @package Hadamcik\SmartCache\Utils\Filemanager
 * @author Jakub Hadamčík <jakub@hadamcik.cz>
 */
abstract class File
{
	/** @var string */
	protected $path;

	/**
	 * @param string $path
	 * @throws FileDoNotExistsException
	 */
	public function __construct($path)
	{
		$filemanager = new Filemanager();
		if(!$filemanager->fileExists($path)) {
			throw new FileDoNotExistsException();
		}
		$this->path = $path;
	}

	/**
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}
}
