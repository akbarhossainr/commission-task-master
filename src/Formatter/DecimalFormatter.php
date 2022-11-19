<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Formatter;

use AkbarHossain\CommissionTask\Entity\Transaction;
use AkbarHossain\CommissionTask\Service\Config;

final class DecimalFormatter implements Formatter
{
    private $config;
    private $transaction;

    public function __construct(Config $config, Transaction $transaction)
    {
        $this->config = $config;
        $this->transaction = $transaction;
    }

    public function format(float $number): string
    {
        $currency = $this->transaction->getCurrency() === 'JPY'
            ? $this->transaction->getCurrency()
            : 'default';

        $decimalPlace = $this->config->get(sprintf('decimal_place.%s', $currency));

        return number_format($number, $decimalPlace, '.', '');
    }
}
