<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Supabase\Functions\FunctionsClient;

class FunctionsUnitTest extends TestCase
{
	public function tearDown(): void
	{
		parent::tearDown();
		\Mockery::close();
	}

	public function testConstructor()
	{
		$client = new FunctionsClient('qweqrwsdfs', '1231322');

		$this->assertEquals('https://qweqrwsdfs.functions.supabase.co', $client->__getUrl());
		$this->assertEquals(['Authorization' => 'Bearer 1231322'], $client->__getHeaders());
	}

	public function testInvoke()
	{
		$mock = \Mockery::mock(
			'Supabase\Functions\FunctionsClient[__request]',
			['mokerymock', 'keyofallthekeys']
		);

		$mock->shouldReceive('__request')->withArgs(function ($scheme, $url, $headers, $body) {
			$this->assertEquals('POST', $scheme);
			$this->assertEquals('https://mokerymock.functions.supabase.co/test-function', $url);
			$this->assertEquals([
				'Authorization' => 'Bearer keyofallthekeys',
				//	'Content-Type'  => 'application/json',
			], $headers);

			$this->assertEquals('{"test":"thing"}', $body);

			return true;
		})
		->andReturn(new \GuzzleHttp\Psr7\Response(
			200,
			['Content-Type'  => 'application/json'],
			'{"foo-bar": 12345}',
		));

		$result = $mock->invoke(
			'test-function',
			['test' => 'thing']
		);

		$this->assertEquals(12345, $result->{'foo-bar'});
	}
}
