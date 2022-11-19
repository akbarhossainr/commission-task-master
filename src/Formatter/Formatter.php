<?php

namespace AkbarHossain\CommissionTask\Formatter;

interface Formatter
{
    public function format(float $amount): string;
}
