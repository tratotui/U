<?php

namespace U;

use U\Unit;

class State extends Unit
{
	const MESSAGE_PROP = 'message';

	const VALID_PROP = 'valid';

	public static function stop($message)
	{
		if(
			is_array($message) &&
			!isset($message[static::MESSAGE_PROP])
		)
		{
			throw new \Exception("State must has [message]");
		}

		$message = array_merge(
			(is_array($message) ? $message : [static::MESSAGE_PROP => $message]),
			['valid' => false]
		);

		return new static($message);
	}

	public function getMessage()
	{
		return $this->properties[static::MESSAGE_PROP];
	}

	public function isValid()
	{
		return $this->properties[static::VALID_PROP];
	}
}

/*

State::stop([
	'message' => 'not found',
	'code' => 404
])

State::stop('Not found')

*/