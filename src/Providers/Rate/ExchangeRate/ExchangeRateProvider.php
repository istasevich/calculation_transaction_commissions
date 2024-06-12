<?php

namespace App\Providers\Rate\ExchangeRate;

use App\Exceptions\ExchangeRateClientException;
use App\Providers\Rate\RateClientInterface;
use App\Providers\Rate\RateProviderInterface;
use App\Responses\BaseRateResponse;

class ExchangeRateProvider implements RateProviderInterface
{
    private BaseRateResponse $baseRateResponse;

    public function __construct(
        private  readonly RateClientInterface $client
    ) {
    }

    /**
     * @throws ExchangeRateClientException
     */
    public function execute(string $currency): void
    {
        $result = $this->client->get();
        $this->baseRateResponse = new BaseRateResponse($result['rates'][$currency]);
    }

    public function getRateResponse(): BaseRateResponse
    {
        return $this->baseRateResponse;
    }
}