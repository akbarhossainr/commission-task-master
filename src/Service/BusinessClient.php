<?php

namespace AkbarHossain\CommissionTask\Service;

class BusinessClient extends Client
{
    protected $config;
    protected $transaction;

    public function __construct(Config $config, Transaction $transaction)
    {
        parent::__construct($config);

        $this->transaction = $transaction;
    }

    public function commission(): float
    {
        $fee = 0;

        if ($this->transaction->getOperationType() == 'withdraw') {
            $fee = $this->calculateWithdrawFee();
        }

        if ($this->transaction->getOperationType() == 'deposit') {
            $fee = $this->calculateDepositFee();
        }

        return $this->feeRevertToTransactionCurrency($fee, $this->transaction->getCurrency());
    }

    protected function calculateWithdrawFee()
    {
        return $this->calculateCommission(
            $this->transaction->getAmountInBaseCurrency(),
            $this->transaction->getClient(),
            $this->transaction->getOperationType()
        );
    }

    protected function calculateDepositFee()
    {
        return $this->calculateCommission(
            $this->transaction->getAmountInBaseCurrency(),
            $this->transaction->getClient(),
            $this->transaction->getOperationType()
        );
    }
}
