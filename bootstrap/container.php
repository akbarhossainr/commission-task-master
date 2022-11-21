<?php

declare(strict_types=1);

use AkbarHossain\CommissionTask\Service\Container;

$container = new Container();

$configs = require __DIR__.'/../config/config.php';

foreach ($configs as $id => $config) {
    $container->set($id, $config);
}

return $container;
