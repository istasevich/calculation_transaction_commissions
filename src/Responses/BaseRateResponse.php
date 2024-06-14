<?php

namespace App\Responses;

class BaseRateResponse
{
    public function __construct(
        private readonly float $rate,
    ) {
    }

    public function getRate(): float
    {
        return $this->rate;
    }
}