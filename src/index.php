<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

use AkbarHossain\CommissionTask\Command\CommissionCalculator;

$container = require_once __DIR__.'/../bootstrap/container.php';

$input = $argv[1] ?? '';

(new CommissionCalculator($container))->execute($input);
