<?php

namespace U\Utils;

class Arr
{
	const PATH_DEL = '.';

	public static function path($str)
	{
		return explode(self::PATH_DEL, $str);
	}

	public static function exists($key, $array)
	{
		return array_key_exists($key, $array);
	}

	
}