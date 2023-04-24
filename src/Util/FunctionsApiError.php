<?php

namespace Supabase\Util;

class FunctionsApiError extends FunctionsError
{
	//protected string $code;
	protected string $name;

	public function __construct($code, $message)
	{
		parent::__construct($code, $message);
		$this->code = $code;
		$this->message = $message;
		$this->name = 'FunctionsApiError';
	}
}
