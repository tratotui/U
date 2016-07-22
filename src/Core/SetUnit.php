<?php

namespace U;

use U\Unit;

class SetUnit extends Unit
{
	protected $parent;

	public function setParent(SetUnit $parent)
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


	public function parentAction($deep)
	{
		if(!is_int($deep))
		{
			return false;
		}
		--$deep;
		
		return !$deep ? $this->parent : $this->parent->parent($deep);
	}
}