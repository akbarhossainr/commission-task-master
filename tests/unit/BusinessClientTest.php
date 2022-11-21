<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Test\Unit;

use AkbarHossain\CommissionTask\Service\BusinessClient;
use AkbarHossain\CommissionTask\Test\TestCase;

class BusinessClientTest extends TestCase
{
    public function dataProviderForBusinessClient(): array
    {
        return [
            'Commission fee is 0.5% from withdrawn amount' => [
                [
                    'transactions' => [
                        $this->createTransactionObject()
                            ->setTransactionAt('2022-01-03')
                            ->setUserId(1)
                            ->setClient('business')
                            ->setOperationType('withdraw')
                            ->setAmount(5000)
                            ->setCurrency('JPY'),
                    ],
                    'expected' => 5000 * (0.5 / 100),
                ],
            ],
            'Deposits are charged 0.03% of deposit amount' => [
                [
                    'transactions' => [
                        $this->createTransactionObject()
                            ->setTransactionAt('2022-01-06')
                            ->setUserId(2)
                            ->setClient('business')
                            ->setOperationType('deposit')
                            ->setAmount(1000)
                            ->setCurrency('EUR'),
                    ],
                    'expected' => 1000 * (0.03 / 100),
                ],
            ],
        ];
    }

    /**
     * @test
     *
     * @dataProvider dataProviderForBusinessClient
     */
    public function calculateWithdrawFeeOrDepositFeeCalculateAccordingTheBusinessRule(array $data): void
    {
        $container = $this->getContainerMock();

        foreach ($data['transactions'] as $transaction) {
            $businessClient = new BusinessClient($container, $transaction);

            $this->assertEquals($data['expected'], $businessClient->commission());
        }
    }
}
