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

	public function __construct()
	{

	}

	public function __get($prop)
	{
		$_method = null;
		if(
			!method_exists($this, $_method = Str::camel(self::GETTER  . '_' . ucfirst($prop))) &&
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
		return !method_exists($this, $_method = Str::camel(self::SETTER  . '_' . ucfirst($prop))) ?
				$this->properties[$prop] = $val :
				Func::call([$this, $_method], [$val]);
	}


	/**
	 * Dynamic methods
	 */
	public function __call($method, $args)
	{
		if(
			!method_exists($this, $_method = Str::camel($method . '_' . self::ACTION)) ||
			!Obj::explore($this)->canCall($_method)
		)
		{
			return null;
		}

		// Before callback
		if(method_exists($this, $_before = self::BEFORE_ACTION . $_method))
		{
			Func::call([$this, $_before]);
		}

		// Result
		$_result = Func::call([$this, $_method], $args);

		// Fire event's callbacks
		if($this->canBubbleEvent($method))
		{
			$this->event($method);
		}

		// After callback
		if(method_exists($this, $_after = self::AFTER_ACTION . $_method))
		{
			Func::call([$this, $_after]);
		}

		return $_result;
	}


	public function listenEvent($event, $callback)
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

		$this->events[$event][self::EVENT_LISTENERS][] = $callback;
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
	$u = new Unit;

	class ... 
	{

		protected function TestEventScen()
		{
	
		}
	
	}


*/