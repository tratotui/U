<?php

namespace U\Core;

class Unit
{

	const GETTER = 'getter';

	/**
	 * Array with properties of unit
	 */
	protected $properties = [];

	public function __construct()
	{

	}

	public function __get($prop)
	{
		$_method = null;
		if(
			!array_key_exists($prop, $this->properties) && 
			!method_exists($this, $_method = self::GETTER . ucfirst($prop))
		)
		{
			return null;
		}

		return !is_null($_method) ? call_user_func([$this, $_method]) : $this->properties[$prop];
	}

	public function __set($prop, $val)
	{
		$this->properties[$prop] = $val;
	}

	public function getterFull_name()
	{
		return 'Hello';
	}




}
/*

$u = new Unit;

$u->full_name = 2131

$u->full_name = 3213213;



*/