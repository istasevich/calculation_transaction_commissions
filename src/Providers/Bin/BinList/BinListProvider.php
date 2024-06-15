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
        if ( ! $this->cacheService->get($bin)) {
            $clientResult = $this->binClient->get($bin);
            $this->cacheService->ensure($bin, $clientResult);
        }

        $result = $this->cacheService->get($bin);

        if ($result['country']) {
            $this->binCountryResponse = new BaseBinCountryResponse(
                name: $result['country']['name'] ?? '',
                alpha2:  $result['country']['alpha2'] ?? ''
            );
        }
    }

    public function getBinCountryResponse(): BaseBinCountryResponse
    {
        return $this->binCountryResponse;
    }
}