<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Service;

use AkbarHossain\CommissionTask\Entity\Transaction;
use DI\Container;

class PrivateClient extends Client
{
    public function __construct(Container $container, Transaction $transaction)
    {
        parent::__construct($container, $transaction);
    }

    protected function calculateWithdrawFee(): float
    {
        $userId = $this->transaction->getUserId();
        $transactionAt = $this->transaction->getTransactionAt();
        $amountInBaseCurrency = $this->transaction->getAmountInBaseCurrency();

        /** @var WithdrawLedger $withdrawLedger */
        $withdrawLedger = $this->container->get('withdraw_ledger');
        $withdrawLedger->addToLedger($userId, $transactionAt, $amountInBaseCurrency);

        $commissionableAmount = $this->getCommissionableAmount(
            $amountInBaseCurrency,
            $userId,
            $transactionAt,
            $withdrawLedger
        );

        return $this->calculateCommission(
            $commissionableAmount,
            $this->transaction->getClient(),
            $this->transaction->getOperationType()
        );
    }

    protected function calculateDepositFee(): float
    {
        return $this->calculateCommission(
            $this->transaction->getAmountInBaseCurrency(),
            $this->transaction->getClient(),
            $this->transaction->getOperationType()
        );
    }

    private function isFreeOfChargeApplicable(
        int $userId,
        string $transactionAt,
        WithdrawLedger $withdrawLedger
    ): bool {
        return $withdrawLedger->withdrawInAWeek($this->container, $userId, $transactionAt) <= $this->getFreeWithdrawAmountPerWeek()
            && $withdrawLedger->withdrawAmountInAWeek($this->container, $userId, $transactionAt) <= $this->getFreeWithdrawAmountPerWeek();
    }

    private function getCommissionableAmount(
        float $amount,
        int $userId,
        string $transactionAt,
        WithdrawLedger $withdrawLedger
    ): float {
        if ($this->isFreeOfChargeApplicable($userId, $transactionAt, $withdrawLedger)) {
            return floatval(0);
        }

        return $amount > $this->getFreeWithdrawAmountPerWeek()
            ? $amount - $this->getFreeWithdrawAmountPerWeek()
            : $amount;
    }
}
