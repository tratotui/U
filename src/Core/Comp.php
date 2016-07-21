<?php

namespace U;

use U\CompUnit;

use U\Utils\Str;
use U\Utils\Arr;

class Comp extends CompUnit
{
	protected $includes = [];

	public function add($name, CompUnit $u)
	{
		$this->includes[$name] = $u;

		// Fire event
		$u->setParent($this);
	}

	public function remove($name)
	{
		unset($this->includes[$name]);
	}

	public function in($path)
	{
		if(count($_path = Arr::path($path)) > 1)
		{
			if(
				($_comp = Arr::get(array_shift($_path), $this->includes)) instanceof CompUnit
			)
			{
				return $_comp->in(Str::path($_path));
			}
			return null;
		}

		return Arr::get($path, $this->includes);
	}



}

/*
	$map = new Comp;
	
	$map->add(new Tank)	

*/