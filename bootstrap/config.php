<?php

declare(strict_types=1);

use AkbarHossain\CommissionTask\Library\DefaultCurrencyRate;
use AkbarHossain\CommissionTask\Service\Config;
use AkbarHossain\CommissionTask\Service\WithdrawLedger;

$config = new Config();

$settings = [
    'base_currency' => 'EUR',
    'base_currency.rate' => 1,
    'commission_rate.business.deposit' => 0.03,
    'commission_rate.business.withdraw' => 0.5,
    'commission_rate.private.deposit' => 0.03,
    'commission_rate.private.withdraw' => 0.3,
    'currency_rate' => new DefaultCurrencyRate(),
    'decimal_place.default' => 2,
    'decimal_place.JPY' => 0,
    'free_withdraw.max_amount' => 1000,
    'free_withdraw.per_week' => 3,
    'week.end_day' => 'sunday',
    'week.start_day' => 'monday',
    'withdaw_ledger' => new WithdrawLedger($config),
];

foreach ($settings as $key => $value) {
    $config->set($key, $value);
}

return $config;
