<?php

namespace App\Providers\Bin\BinList\Clients;

use App\Exceptions\BinClientException;
use App\Providers\Bin\BinClientInterface;
use App\Providers\Clients\BaseClient;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class BinListClient extends BaseClient implements BinClientInterface
{
    private const API_VERSION_HEADER = 'Accept-Version';
    private const API_VERSION = '3';
    private const API_METHOD = 'POST';

    private GuzzleClient $client;

    public function __construct(?GuzzleClient $client = null)
    {
        $url = $_ENV['BIN_LIST_API_URL'];
        $headers = [self::API_VERSION_HEADER => self::API_VERSION];
        $this->client = $client === null ? $this->createClient($url, $headers) : $client;
    }

    /**
     * @throws BinClientException
     */
    public function get(string $bin): string
    {
        try {
            return $this->client->request(self::API_METHOD, $bin)
                ->getBody()
                ->getContents();

        } catch (GuzzleException $e) {
            throw new BinClientException($e->getMessage());
        }
    }
}