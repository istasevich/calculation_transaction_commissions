<?php

namespace App\Providers\Bin;

use App\Exceptions\BinClientException;

interface BinClientInterface
{
    /**
     * @throws BinClientException
     */
    public function get(string $bin): mixed;
}