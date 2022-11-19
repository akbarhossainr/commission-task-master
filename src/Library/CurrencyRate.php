<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Library;

interface CurrencyRate
{
    public function getRates(): array;
}
