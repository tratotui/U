<?php

namespace U\Utils;

use U\Utils\Arr;

class Str
{

	const UNDERSCORE = '_';

	public static function camel($str)
	{
		return implode('', array_map('ucfirst', explode(self::UNDERSCORE, $str)));
	}

	public static function path(array $path)
	{
		return implode(Arr::PATH_DEL, $path);
	}
}
