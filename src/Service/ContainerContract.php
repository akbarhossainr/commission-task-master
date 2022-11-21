<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Service;

use Psr\Container\ContainerInterface;

interface ContainerContract extends ContainerInterface
{
    public function set(string $id, mixed $resolver): static;
}
