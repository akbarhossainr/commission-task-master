<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Service;

use AkbarHossain\CommissionTask\Entity\Transaction;
use DI\Container;

class BusinessClient extends Client
{
    public function __construct(Container $container, Transaction $transaction)
    {
        parent::__construct($container, $transaction);
    }

    protected function calculateWithdrawFee(): float
    {
        return $this->calculateCommission(
            $this->transaction->getAmountInBaseCurrency(),
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
}
