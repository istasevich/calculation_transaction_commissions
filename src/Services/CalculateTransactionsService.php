<?php

namespace App\Services;

use App\Exceptions\TransactionException;
use App\Factories\TransactionCollectionFactory;
use App\Services\Bin\BaseFetchBinCountryService;
use App\Services\Rate\BaseFetchRateService;
use App\ValueObjects\Transaction;

class CalculateTransactionsService
{
    private array $commissions;

    public function __construct(
        private readonly BaseFetchBinCountryService $baseFetchBinCountryService,
        private readonly BaseFetchRateService $baseFetchRateService,
        private readonly TransactionCollectionFactory $transactionCollectionFactory
    ) {
    }

    /**
     * @throws TransactionException
     */
    public function execute(array $transactions): array
    {
        $this->commissions = [];
        $transactionsCollection = $this->transactionCollectionFactory->createFromArray($transactions);

        /** @var Transaction $transactionVO */
        foreach ($transactionsCollection->getCollection() as $transactionVO) {
            $rateResponse = $this->baseFetchRateService->execute($transactionVO);
            $baseBinCountryResponse = $this->baseFetchBinCountryService->execute($transactionVO);

            $commission = $transactionVO->calculateCommission(
                country: $baseBinCountryResponse->getAlpha2(),
                rate: $rateResponse->getRate()
            );

            echo $commission . "\n";

            //Doesn't necessarily need to be stored in memory, made for ease of testing
            $this->commissions[] = $commission;
        }

        return $this->commissions;
    }

    public function __toString()
    {
        return implode("\n", $this->commissions);
    }
}