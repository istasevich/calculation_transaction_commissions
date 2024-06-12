<?php

namespace App\Providers\Rate\ExchangeRate\Clients;

use App\Exceptions\ExchangeRateClientException;
use App\Providers\Rate\RateClientInterface;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class ExchangeRateClient implements RateClientInterface
{
    private const API_METHOD = 'GET';
    private const API_TIMEOUT = 2.0;

    private GuzzleClient $client;

    public function __construct(?GuzzleClient $client = null)
    {
        $this->client = $client === null ? $this->createClient() : $client;
    }

    /**
     * @throws ExchangeRateClientException
     */
    public function get(): mixed
    {
        try {
            $result = $this->client->request(self::API_METHOD)->getBody()
                ->getContents();
            return json_decode($result, true);
        } catch (GuzzleException $e) {
            throw new ExchangeRateClientException($e->getMessage());
        }
    }

    private function createClient(): GuzzleClient
    {
        return new GuzzleClient(
            [
                'base_uri' => $_ENV['RATE_API_URL'] . '?access_key=' . $_ENV['RATE_API_ACCESS_KEY'],
                'timeout'  => self::API_TIMEOUT,
                'allow_redirects' => false,
            ]
        );
    }
}