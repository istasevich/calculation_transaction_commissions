<?php

namespace App\Providers\Bin;

use App\Responses\BaseBinCountryResponse;

interface BinProviderInterface
{
    public function execute(int $bin);
    public function getBinCountryResponse(): BaseBinCountryResponse;
}