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
		if(!is_object($object))
		{
			throw new \Exception("Explore only objects");
		}
		return new self($object);
	}


	public function hasMethod($method)
	{
		return $this->reflect->hasMethod($method);
	}



	public function canCall($method)
	{
		if(!$this->hasMethod($method))
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