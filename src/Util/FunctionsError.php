<?php

<<<<<<< HEAD
namespace Supabase\Util;
=======
namespace Supabase\Functions\Util;
>>>>>>> grab-fixes

class FunctionsError extends \Exception
{
	protected bool $isFunctionsError = true;
	protected string $name;

	public function __construct($message)
	{
		parent::__construct($message);
		$this->name = 'FunctionsError';
	}

	public static function isFunctionsError($e)
	{
		return $e != null && isset($e->isFunctionsError);
	}
}
