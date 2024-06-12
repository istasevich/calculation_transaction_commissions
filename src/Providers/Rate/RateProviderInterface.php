<?php

namespace App\Providers\Rate;

use App\Responses\BaseRateResponse;

interface RateProviderInterface
{
    public function execute(string $currency);
    public function getRateResponse(): BaseRateResponse;
}