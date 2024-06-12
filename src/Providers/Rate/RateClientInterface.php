<?php

namespace App\Providers\Rate;

use App\Exceptions\ExchangeRateClientException;

interface RateClientInterface
{
    /**
     * @throws ExchangeRateClientException
     */
    public function get(): mixed;
}