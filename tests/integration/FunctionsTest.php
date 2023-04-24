<?php

declare(strict_types=1);
require __DIR__.'/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Supabase\Util\EnvSetup;

final class FunctionsTest extends TestCase
{
	private $client;

	public function setup(): void
	{
		parent::setUp();
		$keys = EnvSetup::env(__DIR__.'/../');
		$api_key = $keys['API_KEY'];
		$reference_id = $keys['REFERENCE_ID'];
		$this->client = new  \Supabase\Functions\FunctionsClient($reference_id, $api_key);
	}

	/**
	 * Test Invoke Invailid function.
	 *
	 * @return void
	 */
	public function testInvokeInvalidIdFunction(): void
	{
		$result = $this->client->invoke('not-real-function');
		$this->assertInstanceOf('\\Supabase\\Util\\FunctionsApiError', $result);
	}

	/**
	 * Test Invoke a function.
	 *
	 * @return void
	 */
	public function testInvoke(): void
	{
		$result = $this->client->invoke('hello-world', [
			'body' => ['name'=>'Supabase'],
		]);
		$this->assertNull($result['error']);
		$this->assertArrayHasKey('data', $result);
	}
}
