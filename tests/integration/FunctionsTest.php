<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Supabase\Functions\FunctionsClient;
use Supabase\Functions\Util\EnvSetup;

final class FunctionsTest extends TestCase
{
	private $client;

	public function setup(): void
	{
		parent::setUp();
		$keys = EnvSetup::env(__DIR__.'/../');
		$api_key = $keys['API_KEY'];
		$reference_id = $keys['REFERENCE_ID'];
		$this->client = new FunctionsClient($reference_id, $api_key);
	}

	/**
	 * Test Invoke Invalid function.
	 *
	 * @return void
	 */
	public function testInvokeInvalidFunction(): void
	{
		$this->expectException("Supabase\Functions\Util\FunctionsApiError");
		$this->expectExceptionCode(404);
		$this->expectExceptionMessage('Function not found');

		$result = $this->client->invoke('not-real-function');
	}

	/**
	 * Test Invoke a function.
	 *
	 * @return void
	 */
	public function testInvoke(): void
	{
		$result = $this->client->invoke(
			'hello-world',
			['name'=>'Supabase'],
		);
		$this->assertSame('Hello Players!', $result->{'message'});
	}

	/**
	 * Test for CORS OPTIONS.
	 *
	 * @return void
	 */
	public function testCorsOptions(): void
	{
		$result = $this->client->invoke(
			'cors',
			[],
			['method'=>'OPTIONS'],
		);

		$resp = $this->client->__getLastResponse();
		$headers = $resp->getHeaders();
		$this->assertSame('ok', $result);
		$this->assertSame([0 => '*'], $headers['Access-Control-Allow-Origin']);
		$this->assertSame(
			[0 => 'authorization, x-client-info, apikey'],
			$headers['access-control-allow-headers']
		);

		$result = $this->client->invoke(
			'cors',
			[],
			['method'=>'OPTIONS'],
		);

		$result = $this->client->invoke(
			'cors',
		);

		$resp = $this->client->__getLastResponse();
		$headers = $resp->getHeaders();
		$this->assertSame("all your base belong to us\n", $result);
		$this->assertSame([0 => 'text/plain'], $headers['Content-Type']);
		$this->assertSame([0 => '*'], $headers['Access-Control-Allow-Origin']);
		$this->assertSame(
			[0 => 'authorization, x-client-info, apikey'],
			$headers['access-control-allow-headers']
		);
	}
}
