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

        $userId = $this->transaction->getUserId();
        $transactionAt = $this->transaction->getTransactionAt();
        $amountInBaseCurrency = $this->transaction->getAmountInBaseCurrency();

        $withdrawLedger->addToWithdrawLedger($userId, $transactionAt, $amountInBaseCurrency);

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

    protected function calculateDepositFee()
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
        return $withdrawLedger->withdrawInAWeek($userId, $transactionAt) <= $this->freeWithdrawCountPerWeek
            && $withdrawLedger->withdrawAmountInAWeek($userId, $transactionAt) <= $this->freeWithdrawAmountPerWeek;
    }

    private function getCommissionableAmount(
        float $amount,
        int $userId,
        string $transactionAt,
        WithdrawLedger $withdrawLedger
    ): float
    {
        if ($this->isFreeOfChargeApplicable($userId, $transactionAt, $withdrawLedger)) {
            return floatval(0);
        }

        return $amount > $this->freeWithdrawAmountPerWeek
            ? $amount - $this->freeWithdrawAmountPerWeek
            : $amount;
    }
}
