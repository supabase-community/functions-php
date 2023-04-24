<?php

include __DIR__.'../../header.php';
use Supabase\Functions\FunctionsClient;

$scheme = 'https';
$domain = 'functions.supabase.co';

$client = new FunctionsClient($reference_id, $api_key, [], $domain, $scheme);

$response = $client->invoke('hello-world', [
	'body'                => ['name'=>'Supabase'],
]);
print_r($response);
