<?php

namespace App\Collections;

use App\ValueObjects\Transaction;
use ArrayIterator;

interface CollectionInterface
{
    public function add(Transaction $transaction): void;
    public function getCollection(): ArrayIterator;
}