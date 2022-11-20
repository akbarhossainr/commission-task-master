<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Test;

use AkbarHossain\CommissionTask\Entity\Transaction;
use DI\Container;
use PHPUnit\Framework\TestCase as FrameworkTestCase;

class TestCase extends FrameworkTestCase
{
    public function getContainer(): Container
    {
        return require __DIR__.'/../bootstrap/container.php';
    }

    public function createTransactionObject(array $data = []): Transaction
    {
        $data = empty($data)
            ? [
                'transaction_at' => date('Y-m-d'),
                'user_id' => rand(1, 10),
                'client' => 'private',
                'operation_type' => 'withdraw',
                'amount' => 1500,
                'currency' => 'EUR',
            ]
            : $data;

        return (new Transaction($this->getContainer()))->build($data);
    }
}
