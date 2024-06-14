<?php

namespace App\Collections;

use App\ValueObjects\Transaction;
use ArrayObject;

class TransactionsCollection extends ArrayObject implements CollectionInterface
{
    public function add(Transaction $transaction): void
    {
        $this->append($transaction);
    }

    public function getCollection(): \ArrayIterator
    {
        return $this->getIterator();
    }
}