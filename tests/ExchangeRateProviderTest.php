<?php

use App\Enums\CurrenciesEnum;
use App\Exceptions\ExchangeRateClientException;
use App\Providers\Rate\ExchangeRate\ExchangeRateProvider;
use App\Providers\Rate\RateClientInterface;
use App\Responses\BaseRateResponse;
use PHPUnit\Framework\TestCase;

class ExchangeRateProviderTest extends TestCase
{
    private ExchangeRateProvider $exchangeRateProvider;

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function setUp(): void
    {
        $rateClient = $this->createConfiguredStub(
            RateClientInterface::class,
            [
                'get' => [
                    'rates' => [
                        'EUR' => 1,
                        'USD' => 0.8
                ]],
            ]
        );

        $this->exchangeRateProvider = new ExchangeRateProvider($rateClient);
    }

    /**
     * @throws ExchangeRateClientException
     */
    public function testGetRateResponse()
    {
        $this->exchangeRateProvider->execute(CurrenciesEnum::EUR->name);
        $result = $this->exchangeRateProvider->getRateResponse();

        $this->assertInstanceOf(BaseRateResponse::class, $result);
    }

    /**
     * @throws ExchangeRateClientException
     */
    public function testGetRateResponseEurResult()
    {
        $this->exchangeRateProvider->execute(CurrenciesEnum::EUR->name);
        $result = $this->exchangeRateProvider->getRateResponse();

        $this->assertIsInt($result->getRate());
    }

    /**
     * @throws ExchangeRateClientException
     */
    public function testGetRateResponseUsdResult()
    {
        $this->exchangeRateProvider->execute(CurrenciesEnum::USD->name);
        $result = $this->exchangeRateProvider->getRateResponse();

        $this->assertIsFloat($result->getRate());
    }
}