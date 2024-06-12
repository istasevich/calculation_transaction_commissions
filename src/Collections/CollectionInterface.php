<?php

namespace App\Collections;

use App\ValueObjects\Transaction;

interface CollectionInterface
{
    public function add(Transaction $transaction): void;
    public function getCollection(): array;
}