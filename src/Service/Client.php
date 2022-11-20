<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Service;

use AkbarHossain\CommissionTask\Entity\Transaction;
use DI\Container;

abstract class Client
{
    public const OPERATION_TYPE_WITHDRAW = 'withdraw';
    public const OPERATION_TYPE_DEPOSIT = 'deposit';

    protected $container;
    protected $transaction;

    public function __construct(Container $container, Transaction $transaction)
    {
        $this->container = $container;
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
        return $this->container->get(sprintf('commission_rate.%s.%s', $client, $type)) ?? 1;
    }

    protected function calculateCommission(float $amount, $client, $operationType): float
    {
        $rate = $this->getCommissionRate($operationType, $client);

        return $amount * ($rate / 100);
    }

    protected function revertToTransactionCurrency(float $amount, string $currency): float
    {
        if ($currency !== $this->container->get('base_currency')) {
            $currencyRateService = $this->container->get('currency_rate');
            $rate = $currencyRateService->getRates()[$currency] ?? 1;

            return $amount / $rate;
        }

        return $amount;
    }

    protected function getFreeWithdrawCountPerWeek(): int
    {
        return $this->container->get('free_withdraw.per_week');
    }

    protected function getFreeWithdrawAmountPerWeek(): float
    {
        return floatval($this->container->get('free_withdraw.max_amount'));
    }
}
