<?php

use App\Exceptions\TransactionException;
use App\ValueObjects\Transaction;
use PHPUnit\Framework\TestCase;

class TransactionsTest extends TestCase
{
    /**
     * @throws TransactionException
     */
    public function testCalculateGbpCommission()
    {
        $transactionGbp = new Transaction(
            bin: 45717360,
            amount: 100,
            currency: 'EUR'
        );

        $rate = 1;

        $result = $transactionGbp->calculateCommission('LT', $rate);
        $resultUsa = $transactionGbp->calculateCommission('USA', $rate);

        $this->assertEquals(1, $result);
        $this->assertEquals(2.0, $resultUsa);
    }

    /**
     * @throws TransactionException
     */
    public function testCalculateEurCommission()
    {
        $transactionGbp = new Transaction(
            bin: 4745030,
            amount: 2000.0,
            currency: 'GBP'
        );

        $rate = 0.84;

        $GBCardResult = $transactionGbp->calculateCommission('GB', $rate);
        $LTCardResult = $transactionGbp->calculateCommission('LT', $rate);

        $this->assertEquals(47.62, $GBCardResult);
        $this->assertEquals(23.81, $LTCardResult);
    }

    /**
     * @throws TransactionException
     */
    public function testCalculateJPYCommission()
    {
        $transactionGbp = new Transaction(
            bin: 516793,
            amount: 10000,
            currency: 'JPY'
        );

        $rate = 169.045494;

        $result = $transactionGbp->calculateCommission('JP', $rate);

        $this->assertEquals(1.19, $result);
    }

    public function testFailureTransaction()
    {
        try {
            new Transaction(
                bin: 4745030,
                amount: -1,
                currency: 'GBP'
            );

            $this->fail('Invalid Transaction');

        } catch (TransactionException $exception) {
            $this->assertEquals('Invalid Amount. BIN: 4745030', $exception->getMessage());
        }

        try {
            new Transaction(
                bin: 4745,
                amount: 1,
                currency: 'GBP'
            );
            $this->fail('Invalid Transaction');

        } catch (TransactionException $exception) {
            $this->assertEquals('Invalid BIN: 4745', $exception->getMessage());
        }
    }
}