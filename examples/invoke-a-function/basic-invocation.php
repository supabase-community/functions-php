<?php

include __DIR__.'../../header.php';
use Supabase\Functions\FunctionsClient;

$client = new FunctionsClient($reference_id, $api_key);

$response = $client->invoke('hello-world', ['name'=>'Supabase']);
print_r($response);
