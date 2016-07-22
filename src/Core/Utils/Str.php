<?php

namespace U\Utils;

use U\Utils\Arr;

class Str
{
	public static function camel($str)
	{
		return implode('', array_map('ucfirst', explode('_', $str)));
	}

	public static function path(array $path)
	{
		return implode(Arr::PATH_DEL, $path);
	}
}
