<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

use AkbarHossain\CommissionTask\Command\CommissionCalculator;

$container = require_once __DIR__.'/../bootstrap/container.php';

$input = $argv[1];

if ($input === null) {
    exit('Input path required'.PHP_EOL);
}

(new CommissionCalculator($container))->execute($input);
