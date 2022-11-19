<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Service\CurrencyRate;

interface CurrencyRate
{
    public function getRates(): array;
}
