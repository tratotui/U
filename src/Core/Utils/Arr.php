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
		return !is_null(self::get($key, $array));
	}


	public static function get($path, array $array)
	{
		$_iter = $array;

		foreach (self::path($path) as $key)
		{
			if(!array_key_exists($key, $_iter))
			{
				$_iter = null;
				break;
			}

			$_iter = $_iter[$key];
		}

		return $_iter;
	}

	
}