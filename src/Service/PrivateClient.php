<?php

namespace AkbarHossain\CommissionTask\Service;

class PrivateClient extends Client
{
    protected $config;
    protected $transaction;
    protected $freeWithdrawCountPerWeek;
    protected $freeWithdrawAmountPerWeek;

    public function __construct(Config $config, Transaction $transaction)
    {
        parent::__construct($config);

        $this->transaction = $transaction;
        $this->freeWithdrawCountPerWeek = $this->getFreeWithdrawCountPerWeek();
        $this->freeWithdrawAmountPerWeek = $this->getFreeWithdrawAmountPerWeek();
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
        /** @var WithdrawLedger $withdrawLedger */
        $withdrawLedger = $this->config->get('withdaw_ledger');
        /**
         * @ToDo: calculate number of transaction
         * and sum of transaction in a week by a user
         */
        $uid = $this->transaction->getUserId();
        $transactionAt = $this->transaction->getTransactionAt();
        $commissionableAmount = $amountInBaseCurrency = $this->transaction->getAmountInBaseCurrency();

        $withdrawLedger->addWithdrawal($uid, $transactionAt, $amountInBaseCurrency);

        if (
            $withdrawLedger->withdrawInAWeek($uid, $transactionAt) <= $this->freeWithdrawCountPerWeek
            && $withdrawLedger->withdrawAmountInAWeek($uid, $transactionAt) <= $this->freeWithdrawAmountPerWeek
        ) {
            $commissionableAmount = 0;
        }

        $commissionableAmount = $commissionableAmount > $this->freeWithdrawAmountPerWeek
            ? $commissionableAmount - $this->freeWithdrawAmountPerWeek
            : $commissionableAmount;

        return $this->calculateCommission(
            $commissionableAmount,
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
