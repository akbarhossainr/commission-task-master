<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Test;

use AkbarHossain\CommissionTask\Entity\Transaction;
use AkbarHossain\CommissionTask\Service\ContainerContract;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase as FrameworkTestCase;

class TestCase extends FrameworkTestCase
{
    public function getContainer(): ContainerContract
    {
        return require __DIR__.'/../bootstrap/container.php';
    }

    public function getContainerMock(): MockObject
    {
        $configs = require __DIR__.'/../config/config.php';

        $container = $this->getMockBuilder(ContainerContract::class)->getMock();
        $container->method('get')->will(
            $this->returnCallback(function ($id) use ($configs) {
                return $configs[$id] ?? throw new \InvalidArgumentException();
            })
        );

        return $container;
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

        return (new Transaction($this->getContainerMock()))->build($data);
    }
}
