<?php

use App\Collections\TransactionsCollection;
use App\Exceptions\TransactionException;
use App\Factories\TransactionCollectionFactory;
use App\Providers\Bin\BinClientInterface;
use App\Providers\Bin\BinList\BinListProvider;
use App\Providers\Rate\ExchangeRate\ExchangeRateProvider;
use App\Providers\Rate\RateClientInterface;
use App\Services\Bin\BaseFetchBinCountryService;
use App\Services\CalculateTransactionsService;
use App\Services\Rate\BaseFetchRateService;
use PHPUnit\Framework\TestCase;

class CalculateTransactionsServiceTest extends TestCase
{
    private CalculateTransactionsService $calculateTransactionsService;

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function setUp(): void
    {
        $binClient = $this->createConfiguredStub(
            BinClientInterface::class,
            [
                'get' => [
                    'country' => [
                        'name' => 'Denmark',
                        'alpha2' => 'DK'
                    ]],
            ]
        );

        $binListProvider = new BinListProvider($binClient);

        $rateClient = $this->createConfiguredStub(
            RateClientInterface::class,
            [
                'get' => [
                    'rates' => [
                        'EUR' => 1,
                        'USD' => mt_rand(0, 10) / 10
                    ]],
            ]
        );

        $exchangeRateProvider = new ExchangeRateProvider($rateClient);
        $baseFetchRateService = new BaseFetchRateService($exchangeRateProvider);
        $baseFetchBinService = new BaseFetchBinCountryService($binListProvider);

        $transactionFactory = new TransactionCollectionFactory(new TransactionsCollection());

        $this->calculateTransactionsService = new CalculateTransactionsService(
            $baseFetchBinService, $baseFetchRateService, $transactionFactory
        );
    }

    /**
     * @throws TransactionException
     */
    public function testTransactionResult()
    {
        $data = json_decode(file_get_contents('tests/inputTest.json'));

        $result = $this->calculateTransactionsService->execute($data);

        $this->assertEquals(1.0, $result[0]);

        $this->assertIsFloat($result[1]);
    }
}