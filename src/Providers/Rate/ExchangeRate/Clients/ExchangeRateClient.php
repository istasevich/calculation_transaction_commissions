<?php

namespace App\Providers\Rate\ExchangeRate\Clients;

use App\Exceptions\ExchangeRateClientException;
use App\Providers\Clients\BaseClient;
use App\Providers\Rate\RateClientInterface;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class ExchangeRateClient extends BaseClient implements RateClientInterface
{
    private const API_METHOD = 'GET';
    private GuzzleClient $client;

    public function __construct(?GuzzleClient $client = null)
    {
        $url = $_ENV['RATE_API_URL'] . '?access_key=' . $_ENV['RATE_API_ACCESS_KEY'];
        $this->client = $client === null ? $this->createClient($url) : $client;
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
}