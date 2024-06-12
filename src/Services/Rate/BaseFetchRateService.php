<?php

namespace App\Services\Rate;

use App\Providers\Rate\RateProviderInterface;
use App\ValueObjects\Transaction;

class BaseFetchRateService
{
    public function __construct(
        private readonly RateProviderInterface $rateProvider
    ) {
    }

    public function execute(Transaction $transaction): float|int
    {
        $this->rateProvider->execute($transaction->currency);

        return $this->rateProvider
            ->getRateResponse()
            ->getRate();
    }
}