<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class FunctionsTest extends TestCase
{
    public function tearDown(): void
    {
        parent::tearDown();
        \Mockery::close();
    }

    /**
     * Test the request parameters for call a function.
     */
    public function testNewStorageFile()
    {
        $mock = \Mockery::mock(
            'Supabase\Functions\FunctionsClient[__request]',
            ['123123123', 'mokerymock']
        );

        $mock->shouldReceive('__request')->withArgs(function ($scheme, $url, $headers) {
            $this->assertEquals('GET', $scheme);
            $this->assertEquals('https://mokerymock.functions.supabase.co/test-function', $url);
            $this->assertEquals([
                'X-Client-Info' => 'functions-php/0.0.1',
                'Authorization' => 'Bearer 123123123',
                'Content-Type'  => 'application/json',
            ], $headers);

            return true;
        });
        $mock->invoke('test-function');
    }
}
