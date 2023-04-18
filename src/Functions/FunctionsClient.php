<?php

namespace Supabase\Functions;

use Psr\Http\Message\ResponseInterface;
use Supabase\Util\FunctionsError;
use Supabase\Util\Request;

class FunctionsClient
{
    protected $url;
    protected $headers;

    public function __construct($reference_id, $api_key, $options = [], $domain = 'supabase.co', $scheme = 'https')
    {
        $headers = ['Authorization' => "Bearer {$api_key}", 'apikey' => $api_key];
        $this->url = !empty($reference_id) ? "{$scheme}://{$reference_id}.{$domain}" : "{$scheme}://{$domain}}";

        if (!$this->url) {
            throw new \Exception('No URL provided');
        }

        $this->headers = $headers ?? null;
    }


    public function __request($method, $url, $headers, $body = null): ResponseInterface
    {
        return Request::request($method, $url, $headers, $body);
    }

    public function invoke($functionName, $options = [])
    {
        try {

            $functionArgs = $options['body'];
            $method = $options['method'] ?? 'POST';
            //$this->headers = array_merge($this->headers, ['Content-Type' => 'application/json', 'noResolveJson' => true]);


            if (
                (class_exists('Blob') && $functionArgs instanceof Blob) ||
                $functionArgs instanceof ArrayBuffer
            ) {
                // will work for File as File inherits Blob
                // also works for ArrayBuffer as it is the same underlying structure as a Blob
                $this->headers['Content-Type'] = 'application/octet-stream';
                $body = $functionArgs;
            } else if (is_string($functionArgs)) {
                // plain string
                $this->headers['Content-Type'] = 'text/plain';
                $body = $functionArgs;
            } else if (class_exists('FormData') && $functionArgs instanceof FormData) {
                // don't set content-type headers
                // Request will automatically add the right boundary value
                $body = $functionArgs;
            } else {
                // default, assume this is JSON
                $this->headers['Content-Type'] = 'application/json';
                $body = json_encode($functionArgs);
            }

            $url = "{$this->url}/{$functionName}";
            $headers = $this->headers;
            $response = $this->__request($method, $url, $headers, $body);
            $responseType = (explode(';', $response->getHeader('content-type')[0] ?? 'text/plain')[0]);
            $data = null;
            if ($responseType === 'application/json') {
                $data = json_decode($response->getBody());
            } else if ($responseType === 'application/octet-stream') {
                $data = $response->getBody()->getContents();
            } else if ($responseType === 'multipart/form-data') {
                $data = $response->getBody()->getContents();
            } else {
                // default to text
                $data = $response->getBody()->getContents();
            }

            return ['data' => $data, 'error' => null];
        } catch (\Exception $e) {
            if (FunctionsError::isFunctionsError($e)) {
                return ['data' => ['user' => null], 'error' => $e];
            }
            throw $e;
        }
    }
}
