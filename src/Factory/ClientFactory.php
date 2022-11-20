<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Factory;

use AkbarHossain\CommissionTask\Entity\Transaction;
use AkbarHossain\CommissionTask\Service\BusinessClient;
use AkbarHossain\CommissionTask\Service\PrivateClient;
use DI\Container;
use Symfony\Component\Console\Exception\InvalidOptionException;

class ClientFactory
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getClient(Transaction $transaction)
    {
        switch ($transaction->getClient()) {
            case 'private':
                return new PrivateClient($this->container, $transaction);
                break;

            case 'business':
                return new BusinessClient($this->container, $transaction);
                break;

            default:
                throw new InvalidOptionException('Unknown client provided');
                break;
        }
    }
}
