<?php

declare(strict_types=1);

use DI\ContainerBuilder;

$definations = [];

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__.'/../config/config.php');

return $containerBuilder->build();
