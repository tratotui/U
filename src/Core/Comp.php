<?php

namespace U;

use U\Unit;

use U\Utils\Arr;

class Comp extends Unit
{
	protected $includes = [];


	public function listen(Unit $u, $event, $callback)
	{
		$u->attachListener(
			$event, 
			(is_string($callback) ? [$this, $callback] : $callback )
		);
	}


	public function in($path)
	{
		var_dump(Arr::path($path));
	}

	public function insert($path, Unit $unit)
	{

	}


}

/*
	$map = new Comp;
		$map->in('team1.T-34')->fire()
		$map->add('team.T-34', $unit);
	$map->listen('fire', $u, function())

*/