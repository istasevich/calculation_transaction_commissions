<?php

namespace App\Factories;

use App\Collections\CollectionInterface;
use App\Collections\TransactionsCollection;
use App\Exceptions\TransactionException;
use App\ValueObjects\Transaction;

class TransactionCollectionFactory
{
    public function __construct(
        private readonly CollectionInterface $collection
    ) {
    }

    /**
     * @throws TransactionException
     */
    public function createFromArray(array $data): TransactionsCollection
    {
        foreach ($data as $transactionRaw) {
            $transaction = $this->createTransaction($transactionRaw);
            $this->collection->add($transaction);
        }

        return $this->collection;
    }

    /**
     * @throws TransactionException
     */
    private function createTransaction(object $transactionRaw): Transaction
    {
        return new Transaction(
            bin: $transactionRaw->bin,
            amount: $transactionRaw->amount,
            currency: $transactionRaw->currency
        );
    }
}