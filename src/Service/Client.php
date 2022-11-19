<?php

namespace AkbarHossain\CommissionTask\Service;

abstract class Client
{
    public const OPERATION_TYPE_WITHDRAW = 'withdraw';
    public const OPERATION_TYPE_DEPOSIT = 'deposit';

    protected $config;
    protected $transaction;

    public function __construct(Config $config, Transaction $transaction)
    {
        $this->config = $config;
        $this->transaction = $transaction;
    }

    public function commission(): float
    {
        switch ($this->transaction->getOperationType()) {
            case self::OPERATION_TYPE_WITHDRAW:
                $fee = $this->calculateWithdrawFee();
                break;

            case self::OPERATION_TYPE_DEPOSIT:
                $fee = $this->calculateDepositFee();
                break;

            default:
                $fee = floatval(0);
                break;
        }

        return $this->revertToTransactionCurrency(
            $fee,
            $this->transaction->getCurrency()
        );
    }

    abstract protected function calculateWithdrawFee(): float;

    abstract protected function calculateDepositFee(): float;

    protected function getCommissionRate(string $type, string $client): float
    {
        return $this->config->get(sprintf('commission_rate.%s.%s', $client, $type)) ?? 1;
    }

    protected function calculateCommission(float $amount, $client, $operationType)
    {
        $rate = $this->getCommissionRate($operationType, $client);

        return $amount * ($rate / 100);
    }

    protected function revertToTransactionCurrency(float $amount, string $currency): float
    {
        if ($currency !== $this->config->get('base_currency', 'EUR')) {
            $currencyRateService = $this->config->get('currency_rate');

            return $amount / $currencyRateService->getRates()[$currency];
        }

        return $amount;
    }

    protected function getFreeWithdrawCountPerWeek(): int
    {
        return $this->config->get('free_withdraw.per_week', 3);
    }

    protected function getFreeWithdrawAmountPerWeek(): float
    {
        return floatval($this->config->get('free_withdraw.max_count', 1000));
    }
}
