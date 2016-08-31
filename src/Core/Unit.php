<?php

namespace U;

use Closure;

use U\State;

use U\Utils\Str;
use U\Utils\Arr;
use U\Utils\Obj;
use U\Utils\Func;

class Unit
{
	const GETTER = 'getter';

	const SETTER = 'setter';

	const ACTION = 'action';

	const BEFORE_ACTION = 'before';

	const AFTER_ACTION  = 'after';

	const EVENT_BLINK = 'blink';

	const EVENT_LISTENERS = 'callbacks';

	/**
	 * Array with properties of unit
	 */
	protected $properties = [];

	public static function getActionName($action)
	{
		return Str::camel(self::ACTION . Str::UNDERSCORE . $action);
	}

	public function __construct(array $properties = [])
	{
		$this->properties = $properties;
	}

	public function __get($prop)
	{
		$_method = self::GETTER . Str::camel(ucfirst($prop));

		$_refl = Obj::explore($this);

		if(
			!$_refl->hasMethod($_method) &&
			!Arr::exists($prop, $this->properties)
		)
		{
			return null;
		}

		return $_refl->hasMethod($_method) ?
				Func::call([$this, $_method]) :
				$this->properties[$prop];
	}


	/**
	 * Setter
	 */
	public function __set($prop, $val)
	{
		return !method_exists($this, $_method = Str::camel(self::SETTER  . Str::UNDERSCORE . ucfirst($prop))) ?
				$this->properties[$prop] = $val :
				Func::call([$this, $_method], [$val]);
	}


	/**
	 * Dynamic methods
	 */
	public function __call($method, $args)
	{
		$_result = true;

		$_canBubble = true;
		// Explore instance
		$_refl = Obj::explore($this);

		if(
			!$_refl->hasMethod($_method = static::getActionName($method)) ||
			!$_refl->canCall($_method)
		)
		{
			return null;
		}

		// Before callback
		if($_refl->hasMethod($_before = self::BEFORE_ACTION . $_method))
		{
			$_result = Func::call([$this, $_before]);
		}

		// Inspect result of method
		if(
			is_bool($_result) && !$_result || 
			($_result instanceof State) && !$_result->isValid()
		)
		{
			return $_result;
		}

		// Result
		$_result = Func::call([$this, $_method], $args);

		// After callback
		if($_refl->hasMethod($_after = self::AFTER_ACTION . $_method))
		{
			Func::call([$this, $_after]);
		}


		return $_result;
	}
}
