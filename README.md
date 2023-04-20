# Supabase `functions-php`

PHP Client library to interact with Supabase Functions.

> **Note:** This repository is in Alpha and is not ready for production usage. API's will change as it progresses to initial release.


## Docs
[Link https://supabase.com/docs/reference/javascript/functions-invoke](https://supabase.com/docs/reference/javascript/functions-invoke)

## Quick Start Guide

### Installing the module

```bash
composer require supabase/functions-php
```
> **Note:** Rename the .env.example file to .env and modify your credentials REFERENCE_ID and API_KEY.

### Connecting to the functions backend

```php

use Supabase\Functions;

$api_key = getenv('API_KEY');
$reference_id = getenv('REFERENCE_ID');
$scheme = 'http';
$domain = 'functions.localhost:3000';
$options = [];
$client = new FunctionsClient($reference_id, $api_key, $options, $domain, $scheme);
```

### Handling resources

#### Handling Functions

- Invoke a Supabase Function.:

  ```php
    $functionName = 'hello-world';
    $body = ['name'=>'Supabase'];
    $result = $client->client->invoke($functionName, [
            'body'                => $body,
        ]);;
  ```



