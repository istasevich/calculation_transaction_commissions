<?php

namespace App\Collections;

use App\ValueObjects\Transaction;

class TransactionsCollection implements CollectionInterface
{
    private array $collection;

    public function add(Transaction $transaction): void
    {
        $this->collection[] = $transaction;
    }

    public function getCollection(): array
    {
        return $this->collection;
    }
}