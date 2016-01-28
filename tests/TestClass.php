<?php

namespace Hadamcik\SmartCache;

/**
 * Class TestClass
 * @package Hadamcik\SmartCache;
 * @author Jakub Hadamčík <jakub@hadamcik.cz>
 */
class TestClass
{
	/** @var mixed */
	public $publicParam;

	/** @var mixed */
	private $privateParam;

	/**
	 * @param mixed
	 */
	public function __construct($privateParam)
	{
		$this->privateParam = $privateParam;
	}
	
	/**
	 * @param $privateParam
	 * @return TestClass
	 */
	public function setPrivateParam($privateParam)
	{
		$this->privateParam = $privateParam;
		return $this;
	}
}
