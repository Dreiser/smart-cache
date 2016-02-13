<?php

namespace Hadamcik\SmartCache\Utils\Filemanager;

require_once __DIR__ . '/File.php';

/**
 * Class Directory
 * @package Hadamcik\SmartCache\Utils\Filemanager
 * @author Jakub Hadamčík <jakub@hadamcik.cz>
 */
class Directory extends File
{
	/**
	 * Clean up dir
	 */
	public function cleanUp()
	{
        $files = scandir($this->getPath());
        foreach ($files as $file) {
        	if($file !== '.' && $file !== '..') {
            	unlink($this->getPath() . '/' . $file);
        	}
        }
	}
}
