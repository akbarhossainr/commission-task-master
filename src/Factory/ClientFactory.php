<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Factory;

use AkbarHossain\CommissionTask\Entity\Transaction;
use AkbarHossain\CommissionTask\Service\BusinessClient;
use AkbarHossain\CommissionTask\Service\ClientContract;
use AkbarHossain\CommissionTask\Service\ContainerContract;
use AkbarHossain\CommissionTask\Service\PrivateClient;
use Symfony\Component\Console\Exception\InvalidOptionException;

class ClientFactory
{
    protected $container;

    public function __construct(ContainerContract $container)
    {
        $this->container = $container;
    }

    /**
     * @throws InvalidOptionException
     */
    public function getClient(Transaction $transaction): ClientContract
    {
        switch ($transaction->getClient()) {
            case 'private':
                return new PrivateClient($this->container, $transaction);

            case 'business':
                return new BusinessClient($this->container, $transaction);

            default:
                throw new InvalidOptionException('Unknown client provided');
        }
    }
}
