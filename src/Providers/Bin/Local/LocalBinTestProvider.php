<?php

namespace App\Providers\Bin\Local;

use App\Providers\Bin\BinProviderInterface;
use App\Responses\BaseBinCountryResponse;

class LocalBinTestProvider implements BinProviderInterface
{
    private array $testList = [
        45717360 => ['name' => 'Denmark', 'alpha2' => 'DK'],
        516793 => ['name' => 'USA', 'alpha2' => 'USA'],
        41417360 => ['name' => 'USA', 'alpha2' => 'USA'],
        45417360 =>  ['name' => 'Japan', 'alpha2' => 'JP'],
        4745030 =>  ['name' => 'England', 'alpha2' => 'LT'],
    ];

    private BaseBinCountryResponse $baseBinCountryResponse;

    public function execute(int $bin): void
    {
        $result = $this->testList[$bin] ?? ['name' => 'England', 'alpha2' => 'GB'];

        $this->baseBinCountryResponse = new BaseBinCountryResponse(
            name: $result['name'],
            alpha2: $result['alpha2']
        );
    }

    public function getBinCountryResponse(): BaseBinCountryResponse
    {
        return $this->baseBinCountryResponse;
    }
}