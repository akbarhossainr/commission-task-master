<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Service;

interface ClientContract
{
    public function commission(): float;
}
