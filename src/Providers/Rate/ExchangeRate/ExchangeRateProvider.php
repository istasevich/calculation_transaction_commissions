<?php

namespace App\Providers\Rate\ExchangeRate;

use App\Exceptions\ExchangeRateClientException;
use App\Providers\Rate\RateClientInterface;
use App\Providers\Rate\RateProviderInterface;
use App\Responses\BaseRateResponse;
use App\Services\Cache\CacheServiceInterface;

class ExchangeRateProvider implements RateProviderInterface
{
    private BaseRateResponse $baseRateResponse;

    public function __construct(
        private readonly RateClientInterface $client,
        private readonly CacheServiceInterface $cacheService
    ) {
    }

    /**
     * @throws ExchangeRateClientException
     */
    public function execute(string $currency): void
    {
        $cacheKey = $_ENV['RATE_LIST_CACHE_NAME'];

        if ( ! $this->cacheService->get($cacheKey)) {
            $clientResult = $this->client->get();
            $this->cacheService->ensure($cacheKey, $clientResult);
        }

        $result = $this->cacheService->get($cacheKey);

        $this->baseRateResponse = new BaseRateResponse($result['rates'][$currency]);
    }

    public function getRateResponse(): BaseRateResponse
    {
        return $this->baseRateResponse;
    }
}