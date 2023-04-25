<?php

/**
 * A PHP client library to interact with Supabase Edge Functions.
 */

namespace Supabase\Functions;

use Psr\Http\Message\ResponseInterface;
use Supabase\Util\FunctionsError;
use Supabase\Util\Request;

class FunctionsClient
{
	/**
	 * Location to call the function endpoint.
	 *
	 * @var string
	 */
	protected string $url;

	/**
	 * A header Bearer Token generated by the server in response to a login request
	 * [service key, not anon key].
	 *
	 * @var array
	 */
	protected array $headers = [];

	/**
	 * Get the url.
	 */
	public function __getUrl(): string
	{
		return $this->url;
	}

	/**
	 * Get the headers.
	 */
	public function __getHeaders(): array
	{
		return $this->headers;
	}

	/**
	 * FunctionsClient constructor.
	 *
	 * @param  string  $reference_id  Reference ID
	 * @param  string  $api_key  The anon or service role key
	 * @param  string  $domain  The domain pointing to api
	 * @param  string  $scheme  The api sheme
	 *
	 * @throws Exception
	 */
	public function __construct($reference_id, $api_key, $domain = 'supabase.co', $scheme = 'https')
	{
		$headers = ['Authorization' => "Bearer {$api_key}"];
		$this->url = "{$scheme}://{$reference_id}.functions.{$domain}";

		$this->headers = $headers ?? null;
	}

	public function __request($method, $url, $headers, $body = null): ResponseInterface
	{
		return Request::request($method, $url, $headers, $body);
	}

	/**
	 * Invoke a edge function.
	 *
	 * @param  string  $functionName  The name of the function.
	 * @param  array  $options  The options for invoke a function.
	 * @return mixed 
	 *
	 * @throws Exception
	 */
	public function invoke($functionName, $options = []): mixed
	{
		// @TODO - why do we not pass the body as param 2 and why is $options not well described
		try {
			$functionArgs = $options['body'];
			$method = $options['method'] ?? 'POST';

			// @TODO - what in the world are we doing here!?
			if (! is_array($functionArgs)) {
				if (base64_decode($functionArgs, true) === false) {
					$body = file_get_contents($functionArgs);
				} else {
					$body = base64_decode($functionArgs);
				}
			} elseif (is_string($functionArgs)) {
				$this->headers['Content-Type'] = 'text/plain';
				$body = $functionArgs;
			} elseif (is_array($functionArgs)) {
				$body = json_encode($functionArgs);
			} else {
				$this->headers['Content-Type'] = 'application/json';
				$body = json_encode($functionArgs);
			}

			$url = "{$this->url}/{$functionName}";

			// Send the request
			$response = $this->__request($method, $url, $this->headers, $body);
			$responseType = explode(';', $response->getHeader('content-type')[0] ?? 'text/plain')[0];
			$data = null;
			$body = $response->getBody()->getContents();
			if ($responseType === 'application/json') {
				$data = json_decode($body);
			} else {
				$data = $body;
			}
			return $data;
		} catch (\Exception $e) {
			throw $e;
		}
	}
}
