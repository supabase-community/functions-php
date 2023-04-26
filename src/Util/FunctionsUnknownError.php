<?php

namespace Supabase\Functions\Util;

class FunctionsUnknownError extends FunctionsError
{
	public function __construct($message, $originalError)
	{
		parent::__construct($message);
		$this->name = 'FunctionsUnknownError';
		$this->originalError = $originalError;
	}
}
