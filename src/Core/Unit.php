<?php

namespace U;

use Closure;

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

	protected $events = [];


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
		$_method = null;
		if(
			!method_exists($this, $_method = Str::camel(self::GETTER  . Str::UNDERSCORE . ucfirst($prop))) &&
			!Arr::exists($prop, $this->properties)
		)
		{
			return null;
		}

		return !is_null($_method) ? Func::call([$this, $_method]) : $this->properties[$prop];
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

		/**
		 * @todo
		 * return state
		 */
		if(is_bool($_result) && !$_result)
		{
			return;
		}

		// Result
		$_result = Func::call([$this, $_method], $args);

		// Fire event's callbacks
		if($this->canBubbleEvent($method))
		{
			$this->event($method);
		}

		// After callback
		if($_refl->hasMethod($_after = self::AFTER_ACTION . $_method))
		{
			Func::call([$this, $_after]);
		}

		return $_result;
	}


	public function listen($event, Closure $callback)
	{
		if(!is_callable($callback))
		{
			return false;
		}

		if(!Arr::exists($event, $this->events))
		{
			$this->events[$event] = [
				self::EVENT_BLINK => false,
				self::EVENT_LISTENERS => []
			];
		}

		$this->events[$event][self::EVENT_LISTENERS][] = Closure::bind($callback, $this);
	}


	/**
	 * Fire event
	 */
	protected function event($event)
	{
		// Callback listeners
		if(
			!$this->isRegEvent($event) ||
			!Arr::exists(self::EVENT_LISTENERS, $this->events[$event])
		)
		{
			return false;
		}

		// Call callbacks
		array_map(function($closure) {
			Func::call($closure);
		}, $this->events[$event][self::EVENT_LISTENERS]);

		return true;
	}


	protected function isRegEvent($event)
	{
		return Arr::exists($event, $this->events);
	}

	protected function canBubbleEvent($event)
	{
		if(
			$this->isRegEvent($event) &&
			Arr::exists(self::EVENT_BLINK, $this->events[$event]) &&
			!empty($this->events[$event][self::EVENT_BLINK])
		)
		{
			return false;
		}

		return true;
	}
}

/*
	
*/