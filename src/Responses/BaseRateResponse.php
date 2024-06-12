<?php

namespace App\Responses;

class BaseRateResponse
{
    public function __construct(
        private readonly int|float $rate,
    ) {
    }

    public function getRate(): float|int
    {
        return $this->rate;
    }
}