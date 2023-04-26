<?php

include __DIR__.'../../header.php';
use Supabase\Functions\FunctionsClient;

$scheme = 'https';
$domain = 'functions.supabase.co';
// @TODO - what in the world??
$client = new FunctionsClient($reference_id, $api_key, [
	'autoRefreshToken'   => false,
	'persistSession'     => true,
	'storageKey'         => $api_key,
], $domain, $scheme);

$response = $client->invoke('hello-world2', [
	'body'                => ['name'=>'Supabase'],
	'headers'             => ['my-custom-header'=>'my-custom-header-value'],
]);
print_r($response);
