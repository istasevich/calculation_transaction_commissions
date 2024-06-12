<?php

use App\Exceptions\TransactionException;
use App\Providers\Rate\RateProviderInterface;
use App\Responses\BaseRateResponse;
use App\Services\Rate\BaseFetchRateService;
use App\ValueObjects\Transaction;
use PHPUnit\Framework\TestCase;

class BaseFetchRateServiceTest extends TestCase
{
    private BaseFetchRateService $baseFetchRateService;
    private Transaction $transaction;

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws TransactionException
     */
    public function setUp(): void
    {
        $testRate = mt_rand(0, 10) / 10;
        $rateResponse = new BaseRateResponse($testRate);

        $rateProvider = $this->createConfiguredStub(
            RateProviderInterface::class,
            [
                'execute' => '',
                'getRateResponse' => $rateResponse
            ]
        );

        $this->transaction = new Transaction(
            bin: 4441114,
            amount: 12,
            currency: 'EUR'
        );

        $this->baseFetchRateService = new BaseFetchRateService($rateProvider);
    }


    public function testGetRateDigitResponse()
    {
        $result = $this->baseFetchRateService->execute($this->transaction);
        $this->assertIsNumeric($result);
    }
}