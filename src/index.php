<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

use AkbarHossain\CommissionTask\Command\CommissionCalculator;

$config = require_once __DIR__ . '/../bootstrap/config.php';

$input = $argv[1] ?? '';

(new CommissionCalculator($config))->execute($input);
