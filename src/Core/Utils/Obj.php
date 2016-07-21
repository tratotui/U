<?php

namespace U\Utils;

use ReflectionObject;

class Obj
{
	private $reflect;

	private function __construct($object)
	{
		$this->reflect = new ReflectionObject($object);
	}


	public static function explore($object)
	{
		return new self($object);
	}

	public function canCall($method)
	{
		if(!$this->reflect->hasMethod($method))
		{
			return false;
		}
		return $this->reflect->getMethod($method)->isPublic();
	}



}
/*

Obj::explore($unit)
	->canCall('')
*/