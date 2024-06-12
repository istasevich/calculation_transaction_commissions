<?php

namespace App\ValueObjects;

use App\Enums\CurrenciesEnum;
use App\Enums\EuroCountriesEnum;
use App\Exceptions\TransactionException;

final class Transaction
{
    private const EU_COMMISSION = 0.01;
    private const NOT_EU_COMMISSION = 0.02;

    /**
     * @throws TransactionException
     */
    public function __construct(
        public readonly int $bin,
        public readonly float $amount,
        public readonly string $currency
    ) {
        $this->validateBin();
        $this->validateAmount();
    }

    public function getFixedAmountByRate(float|int $rate): float|int
    {
        if ($this->currency !== CurrenciesEnum::EUR->name && $rate > 0) {
            return $this->amount / $rate;
        }

        return $this->amount;
    }

    public function calculateCommission(string $country, float|int $rate): float
    {
        $commission = EuroCountriesEnum::isEU($country) ? self::EU_COMMISSION : self::NOT_EU_COMMISSION;

        return $this->ceiling($this->getFixedAmountByRate($rate) * $commission);
    }

    /**
     * @throws TransactionException
     */
    public function validateBin(): void
    {
        if (! preg_match("/[0-9]{6,8}/", $this->bin)) {
            throw new TransactionException('Invalid BIN: ' . $this->bin);
        }
    }

    /**
     * @throws TransactionException
     */
    public function validateAmount(): void
    {
        if ($this->amount <= 0) {
            throw new TransactionException('Invalid Amount. BIN: ' . $this->bin);
        }
    }

    public function ceiling(float $value): float
    {
        return ceil($value * 100) / 100;
    }
}