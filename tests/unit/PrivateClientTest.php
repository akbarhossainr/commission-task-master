<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Test\Unit;

use AkbarHossain\CommissionTask\Service\PrivateClient;
use AkbarHossain\CommissionTask\Test\TestCase;

class PrivateClientTest extends TestCase
{
    public function dataProviderForPrivateClient(): array
    {
        return [
            '1000.00 EUR in a week is free of charge' => [
                [
                    'transactions' => [
                        $this->createTransactionObject()
                            ->setTransactionAt('2022-01-03')
                            ->setUserId(1)
                            ->setClient('private')
                            ->setOperationType('withdraw')
                            ->setAmount(600)
                            ->setCurrency('EUR'),
                        $this->createTransactionObject()
                            ->setTransactionAt('2022-01-04')
                            ->setUserId(1)
                            ->setClient('private')
                            ->setOperationType('withdraw')
                            ->setAmount(400)
                            ->setCurrency('EUR'),
                    ],
                    'expected' => 0,
                ],
            ],
            'Only for the first 3 withdraw operations per a week is free of charge' => [
                [
                    'transactions' => [
                        $this->createTransactionObject()
                            ->setTransactionAt('2022-01-04')
                            ->setUserId(2)
                            ->setClient('private')
                            ->setOperationType('withdraw')
                            ->setAmount(300)
                            ->setCurrency('EUR'),
                        $this->createTransactionObject()
                            ->setTransactionAt('2022-01-05')
                            ->setUserId(2)
                            ->setClient('private')
                            ->setOperationType('withdraw')
                            ->setAmount(300)
                            ->setCurrency('EUR'),
                        $this->createTransactionObject()
                            ->setTransactionAt('2022-01-06')
                            ->setUserId(2)
                            ->setClient('private')
                            ->setOperationType('withdraw')
                            ->setAmount(100)
                            ->setCurrency('EUR'),
                    ],
                    'expected' => 0,
                ],
            ],
        ];
    }

    /**
     * @test
     *
     * @dataProvider dataProviderForPrivateClient
     */
    public function calculateWithdrawFeeOutputIsFollowingTheBusinessRule(array $data): void
    {
        $container = $this->getContainerMock();

        foreach ($data['transactions'] as $transaction) {
            $privateClient = new PrivateClient($container, $transaction);

            $this->assertEquals($data['expected'], $privateClient->commission());
        }
    }
}
