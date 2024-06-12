<?php

namespace App\Providers\Bin\BinList;

use App\Exceptions\BinClientException;
use App\Providers\Bin\BinClientInterface;
use App\Providers\Bin\BinProviderInterface;
use App\Responses\BaseBinCountryResponse;

class BinListProvider implements BinProviderInterface
{
    private BaseBinCountryResponse $binCountryResponse;

    public function __construct(
        private readonly BinClientInterface $binClient
    ) {
    }

    /**
     * @throws BinClientException
     */
    public function execute(int $bin): void
    {
        $result = $this->binClient->get($bin);

        if ($result['country']) {
            $this->binCountryResponse = new BaseBinCountryResponse(
                name: $result['country']['name'] ?? '',
                alpha2:  $result['country']['alpha2'] ?? ''
            );
        }
    }

    public function getBinCountryResponse(): BaseBinCountryResponse
    {
        return $this->binCountryResponse;
    }
}