<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Formatter;

interface Formatter
{
    public function format(float $amount): string;
}
