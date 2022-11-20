<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Formatter;

use AkbarHossain\CommissionTask\Entity\Transaction;
use DI\Container;

final class DecimalFormatter implements Formatter
{
    private $container;
    private $transaction;

    public function __construct(Container $container, Transaction $transaction)
    {
        $this->container = $container;
        $this->transaction = $transaction;
    }

    public function format(float $number): string
    {
        $currency = $this->transaction->getCurrency() === 'JPY'
            ? $this->transaction->getCurrency()
            : 'default';

        $decimalPlace = $this->container->get(sprintf('decimal_place.%s', $currency));

        return number_format($number, $decimalPlace, '.', '');
    }
}
