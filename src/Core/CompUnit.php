<?php

namespace U;

use U\Unit;

class CompUnit extends Unit
{
	protected $parent;

	public function setParent(Unit $parent)
	{
		$this->parent = &$parent;
	}

	/**
	 * Getter
	 */
	public function getterParent()
	{
		return $this->parent;
	}

}