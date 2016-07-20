<?php

namespace U\Utils;

class Str
{
	public static function camel($str)
	{
		return implode('', array_map('ucfirst', explode('_', $str)));
	}

	
}
