<?php

namespace U\Utils;

class Func
{
	public static function call($func, $args = null)
	{
		$args = !is_null($args) && !is_array($args) ? [$args] : $args;
		return is_array($args) ? call_user_func_array($func, $args) : call_user_func($func);
	}

}
/*
	Func::call('U\Utils\Str::camel', 'Hello_world')

	Func::reflect()
*/