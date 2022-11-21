<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Formatter;

use AkbarHossain\CommissionTask\Entity\Transaction;
use AkbarHossain\CommissionTask\Service\ContainerContract;

final class DecimalFormatter implements Formatter
{
    private $container;
    private $transaction;

    public function __construct(ContainerContract $container, Transaction $transaction)
    {
        $this->container = $container;
        $this->transaction = $transaction;
    }

    public function format(float $number): string
    {
        return number_format($number, $this->getDecimalPlaceForCurrency(), '.', '');
    }

    /**
     * According to the discussion in the task,
     * the decimal position may change for different currencies.
     * i.e. JPY is one of the currencies formally having no decimal cents.
     *
     * Discussion Link:
     * https://gist.github.com/PayseraGithub/ef2a59d0a6d6e680af2e46ccff1bca37?permalink_comment_id=4164518#gistcomment-4164518
     */
    private function getDecimalPlaceForCurrency(): int
    {
        $decimalPlace = $this->container->get('decimal_place.default');

        try {
            $decimalPlace = $this->container->get(
                sprintf('decimal_place.%s', $this->transaction->getCurrency())
            );
        } catch (\Throwable $th) {
        }

        return intval($decimalPlace);
    }
}
