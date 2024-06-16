<?php

namespace App\Providers\Clients;

use GuzzleHttp\Client as GuzzleClient;

abstract class BaseClient
{
    private const API_TIMEOUT = 2.0;

    protected function createClient(string $url, array $headers = []): GuzzleClient
    {
        return new GuzzleClient(
            [
                'base_uri' => $url,
                'timeout'  => self::API_TIMEOUT,
                'allow_redirects' => false,
                'headers' => $headers
            ]
        );
    }
}