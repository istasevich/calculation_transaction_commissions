<?php

use App\Exceptions\BinClientException;
use App\Providers\Bin\BinClientInterface;
use App\Providers\Bin\BinList\BinListProvider;
use App\Responses\BaseBinCountryResponse;
use PHPUnit\Framework\TestCase;

class BinListProviderTest extends TestCase
{
    private BinListProvider $binListProvider;

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

        $this->binListProvider = new BinListProvider($binClient);
    }


    /**
     * @throws BinClientException
     */
    public function testGetRateResponse()
    {
        $this->binListProvider->execute(45717360);
        $result = $this->binListProvider->getBinCountryResponse();

        $this->assertInstanceOf(BaseBinCountryResponse::class, $result);
    }

    /**
     * @throws BinClientException
     */
    public function testGetRateResponseDKResult()
    {
        $this->binListProvider->execute(45717360);
        $result = $this->binListProvider->getBinCountryResponse();

        $this->assertStringContainsString('DK', $result->getAlpha2());
    }
}