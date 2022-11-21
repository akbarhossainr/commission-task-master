<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Test\Unit;

use AkbarHossain\CommissionTask\Service\WithdrawLedger;
use AkbarHossain\CommissionTask\Test\TestCase;

class WithdrawLedgerTest extends TestCase
{
    public function dataProviderForWithdawTransaction(): array
    {
        $userId = 2;

        return [
            'No transaction added yet' => [
                [
                    'transactions' => [],
                    'count' => 0,
                    'amount' => 0,
                    'week_date' => '2022-01-04',
                ],
            ],
            'Two transactions in a week' => [
                [
                    'transactions' => [
                        $this->createTransactionObject()
                            ->setTransactionAt('2022-01-04')
                            ->setUserId($userId)
                            ->setClient('private')
                            ->setOperationType('withdraw')
                            ->setAmount(300)
                            ->setCurrency('EUR'),
                        $this->createTransactionObject()
                            ->setTransactionAt('2022-01-05')
                            ->setUserId($userId)
                            ->setClient('private')
                            ->setOperationType('withdraw')
                            ->setAmount(300)
                            ->setCurrency('EUR'),
                        $this->createTransactionObject()
                            ->setTransactionAt('2022-02-06')
                            ->setUserId($userId)
                            ->setClient('private')
                            ->setOperationType('withdraw')
                            ->setAmount(100)
                            ->setCurrency('EUR'),
                    ],
                    'user_id' => $userId,
                    'count' => 2,
                    'amount' => 600,
                    'week_date' => '2022-01-04',
                ],
            ],
        ];
    }

    /** @dataProvider dataProviderForWithdawTransaction */
    public function testWithdrawLedgerCalculateCountAndAmount(array $data): void
    {
        $container = $this->getContainerMock();
        $ledger = new WithdrawLedger();

        foreach ($data['transactions'] as $transaction) {
            $ledger->addToLedger(
                $transaction->getUserId(),
                $transaction->getTransactionAt(),
                $transaction->getAmount()
            );
        }

        $withdrawInAWeek = $ledger->withdrawInAWeek(
            $container,
            $data['user_id'] ?? 1,
            $data['week_date']
        );
        $withdrawAmountInAWeek = $ledger->withdrawAmountInAWeek(
            $container,
            $data['user_id'] ?? 1,
            $data['week_date']
        );

        $this->assertEquals($data['count'], $withdrawInAWeek);
        $this->assertEquals($data['amount'], $withdrawAmountInAWeek);
    }
}
