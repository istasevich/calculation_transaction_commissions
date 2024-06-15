<?php

namespace App\Providers\Bin\BinList;

use App\Exceptions\BinClientException;
use App\Providers\Bin\BinClientInterface;
use App\Providers\Bin\BinProviderInterface;
use App\Responses\BaseBinCountryResponse;
use App\Services\Cache\CacheServiceInterface;

class BinListProvider implements BinProviderInterface
{
    private BaseBinCountryResponse $binCountryResponse;

    public function __construct(
        private readonly BinClientInterface $binClient,
        private readonly CacheServiceInterface $cacheService
    ) {
    }

    /**
     * @throws BinClientException
     */
    public function execute(int $bin): void
    {
        if ($_ENV['PROVIDERS_CACHE_ENABLED']) {
            $result = $this->getWithCache($bin);
        } else {
            $result = json_decode($this->binClient->get($bin), true);
        }

        if ($result['country']) {
            $this->binCountryResponse = new BaseBinCountryResponse(
                name: $result['country']['name'] ?? '',
                alpha2:  $result['country']['alpha2'] ?? ''
            );
        }
    }

    /**
     * @throws BinClientException
     */
    private function getWithCache(string $bin): ?array
    {
        if ( ! $this->cacheService->get($bin)) {
            $clientResult = $this->binClient->get($bin);
            $this->cacheService->ensure($bin, $clientResult);
        }

        return $this->cacheService->get($bin);
    }

    public function getBinCountryResponse(): BaseBinCountryResponse
    {
        return $this->binCountryResponse;
    }
}