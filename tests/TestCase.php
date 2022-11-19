<?php

namespace AkbarHossain\CommissionTask\Test;

use AkbarHossain\CommissionTask\Service\Config;
use AkbarHossain\CommissionTask\Service\Transaction;
use PHPUnit\Framework\TestCase as FrameworkTestCase;

class TestCase extends FrameworkTestCase
{
    public function getActualConfig(): Config
    {
        return require __DIR__ . '/../bootstrap/config.php';
    }

    public function createTransactionObject(array $data = []): Transaction
    {
        $data = empty($data)
            ? [
                'transaction_at' => date('Y-m-d'),
                'user_id' => rand(1, 10),
                'client' =>  'private',
                'operation_type' => 'withdraw',
                'amount' => 1500,
                'currency' => 'EUR',
            ]
            : $data;

        return (new Transaction($this->getActualConfig()))->build($data);
    }
}
