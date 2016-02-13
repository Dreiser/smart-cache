<?php

namespace Hadamcik\SmartCache\Utils\Filemanager;

require_once __DIR__ . '/File.php';
require_once __DIR__ . '/../../Exceptions/Utils/Filemanager/FileReadContentException.php';

/**
 * Class RegularFile
 * @package Hadamcik\SmartCache\Utils\Filemanager
 * @author Jakub Hadamčík <jakub@hadamcik.cz>
 */
class RegularFile extends File
{
	/**
	 * @return string
	 * @throws FileReadContentException
	 */
	public function getContent()
	{
		$content = file_get_contents($this->getPath());
		if($content === false) {
			throw new FileReadContentException();
		}
		return $content;
	}
}
