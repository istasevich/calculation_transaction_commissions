<?php

namespace App\Services\Bin;

use App\Providers\Bin\BinProviderInterface;
use App\Responses\BaseBinCountryResponse;
use App\ValueObjects\Transaction;

class BaseFetchBinCountryService
{
    public function __construct(
        private readonly BinProviderInterface $binProvider
    ) {
    }

    public function execute(Transaction $transaction): BaseBinCountryResponse
    {
        $this->binProvider->execute($transaction->bin);

        return $this->binProvider->getBinCountryResponse();
    }
}