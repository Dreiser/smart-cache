<?php

namespace Hadamcik\SmartCache\Utils\Filemanager;

require_once __DIR__ . '/File.php';

/**
 * Class RegularFile
 * @package Hadamcik\SmartCache\Utils\Filemanager
 * @author Jakub Hadamčík <jakub@hadamcik.cz>
 */
class RegularFile extends File
{
	/**
	 * @return string
	 */
	public function getContent()
	{
		return file_get_contents($this->getPath());
	}
}
