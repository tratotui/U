<?php

namespace U;

use U\Utils\Str;

class Unit
{
	const GETTER = 'getter';

	const SETTER = 'setter';

	const ACTION = 'action';

	const BEFORE_ACTION = 'before';

	const AFTER_ACTION = 'after';

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
			!method_exists($this, $_method = Str::camel(self::GETTER  . '_' . ucfirst($prop))) &&
			!array_key_exists($prop, $this->properties)
		)
		{
			return null;
		}

		return !is_null($_method) ? call_user_func([$this, $_method]) : $this->properties[$prop];
	}


	/**
	 * Setter
	 */
	public function __set($prop, $val)
	{
		return !method_exists($this, $_method = Str::camel(self::SETTER  . '_' . ucfirst($prop))) ?
				$this->properties[$prop] = $val :
				call_user_func_array([$this, $_method], [$val]);
	}


	/**
	 * Dynamic methods
	 */
	public function __call($method, $args)
	{
		if(!method_exists($this, $_method = Str::camel($method . '_' . self::ACTION)))
		{
			return null;
		}

		// Before callback
		if(method_exists($this, $_before = self::BEFORE_ACTION . $_method))
		{
			call_user_func([$this, $_before]);
		}

		// Result
		$_result = count($args)
			? call_user_func_array([$this, $_method], $args)
			: call_user_func([$this, $_method]);

		// After callback
		if(method_exists($this, $_after = self::AFTER_ACTION . $_method))
		{
			call_user_func([$this, $_after]);
		}

		return $_result;
	}

}