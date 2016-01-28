<?php

namespace Hadamcik\SmartCache\Tests\Utils;

/**
 * Class Temp
 * @package Hadamcik\SmartCache\Tests\Utils
 * @author Jakub Hadamčík <jakub@hadamcik.cz>
 */
class Temp
{
	/**
	 * Clean up dir
	 * @param string $dir
	 */
	public static function cleanUp($dir)
	{
        @mkdir($dir);
        $files = scandir($dir);
        foreach ($files as $file) {
        	if($file !== '.' && $file !== '..') {
            	unlink($dir . '/' . $file);
        	}
        }
	}
}
