<?php

namespace Supabase\Functions\Util;

use Psr\Http\Message\ResponseInterface;

class Request
{
	public static function request($method, $url, $headers, $body = null): ResponseInterface
	{
		try {
			$request = new \GuzzleHttp\Psr7\Request($method, $url, $headers, $body);
			$client = new \GuzzleHttp\Client();
			$promise = $client->sendAsync($request)->then(function ($response) {
				return $response;
			});

			$response = $promise->wait();

			return $response;
		} catch (\Exception $e) {
			throw self::handleError($e);
		}
	}

	public static function handleError($error)
	{
		if (method_exists($error, 'getResponse')) {
			$response = $error->getResponse();
			$data = $response->getBody()->getContents();
			$code = $response->getStatusCode();
			$error = new FunctionsApiError($code, $data);
		} else {
			$error = new FunctionsUnknownError($error->getMessage(), $error->getCode());
		}

		return $error;
	}
}
