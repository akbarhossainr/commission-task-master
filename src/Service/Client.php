<?php

namespace AkbarHossain\CommissionTask\Service;

abstract class Client
{
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    abstract public function commission(): float;

    protected function getCommissionRate(string $type, string $client): float
    {
        return $this->config->get(sprintf('commission_rate.%s.%s', $client, $type)) ?? 1;
    }

    protected function calculateCommission(float $amount, $client, $operationType)
    {
        $rate = $this->getCommissionRate($operationType, $client);

        return $amount * ($rate / 100);
    }

    protected function feeRevertToTransactionCurrency(float $amount, string $currency): float
    {
        $currencyRateService = $this->config->get('currency_rate');

        if ($currencyRateService->getBaseCurrency() !== $currency) {
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