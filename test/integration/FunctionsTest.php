<?php

declare(strict_types=1);
require __DIR__.'/../../vendor/autoload.php';

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

final class FunctionsTest extends TestCase
{
    private $client;

    public function setup(): void
    {
        parent::setUp();
        $dotenv = Dotenv::createUnsafeImmutable(__DIR__, '/../../.env.test');
        $dotenv->load();
        $api_key = getenv('API_KEY');
        $reference_id = getenv('REFERENCE_ID');
        $scheme = 'https';
        $domain = 'functions.supabase.co';
        $this->client = new  \Supabase\Functions\FunctionsClient($reference_id, $api_key, [], $domain, $scheme);
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
            'body'                => ['name'=>'Supabase'],
        ]);
        $this->assertNull($result['error']);
        $this->assertArrayHasKey('data', $result);
    }
}
