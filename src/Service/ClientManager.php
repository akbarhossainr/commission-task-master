<?php

namespace AkbarHossain\CommissionTask\Service;

use Symfony\Component\Console\Exception\InvalidOptionException;

class ClientManager
{
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function getClient(Transaction $transaction)
    {
        switch ($transaction->getClient()) {
            case 'private':
                return new PrivateClient($this->config, $transaction);
                break;

            case 'business':
                return new BusinessClient($this->config, $transaction);
                break;

            default:
                throw new InvalidOptionException('Unknown client provided');
                break;
        }
    }
}
