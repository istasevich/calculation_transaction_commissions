<?php

namespace App\Providers\Bin\BinList\Clients;

use App\Exceptions\BinClientException;
use App\Providers\Bin\BinClientInterface;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class BinListClient implements BinClientInterface
{
    private const API_VERSION_HEADER = 'Accept-Version';
    private const API_VERSION = '3';
    private const API_METHOD = 'POST';
    private const API_TIMEOUT = 2.0;

    private GuzzleClient $client;

    public function __construct(?GuzzleClient $client = null)
    {
        $this->client = $client === null ? $this->createClient() : $client;
    }

    /**
     * @throws BinClientException
     */
    public function get(string $bin): mixed
    {
        try {
            $result = $this->client->request(self::API_METHOD, $bin)->getBody()
                ->getContents();

            return json_decode($result, true);

        } catch (GuzzleException $e) {
            throw new BinClientException($e->getMessage());
        }
    }

    private function createClient(): GuzzleClient
    {
        return new GuzzleClient(
            [
                'base_uri' => $_ENV['BIN_LIST_API_URL'],
                'timeout'  => self::API_TIMEOUT,
                'allow_redirects' => false,
                'headers' => [self::API_VERSION_HEADER => self::API_VERSION]
            ]
        );
    }
}