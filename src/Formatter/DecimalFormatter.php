<?php

namespace AkbarHossain\CommissionTask\Formatter;

use AkbarHossain\CommissionTask\Service\Config;
use AkbarHossain\CommissionTask\Service\Transaction;

class DecimalFormatter
{
    protected $config;
    protected $transaction;

    public function __construct(Config $config, Transaction $transaction)
    {
        $this->config = $config;
        $this->transaction = $transaction;
    }

    public function format(float $number): void
    {
        $currency = $this->transaction->getCurrency() == 'JPY'
            ? $this->transaction->getCurrency()
            : 'default';

        $decimalPlace = $this->config->get(sprintf('decimal_place.%s', $currency));

        echo number_format($number, $decimalPlace, '.', '') . PHP_EOL;
    }
}
