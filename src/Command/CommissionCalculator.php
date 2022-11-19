<?php

namespace AkbarHossain\CommissionTask\Command;

use AkbarHossain\CommissionTask\Formatter\DecimalFormatter;
use AkbarHossain\CommissionTask\Service\Client;
use AkbarHossain\CommissionTask\Service\Config;
use AkbarHossain\CommissionTask\Library\CsvFileReader;
use AkbarHossain\CommissionTask\Entity\Transaction;
use AkbarHossain\CommissionTask\Factory\ClientFactory;

class CommissionCalculator
{
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function execute(string $filePath)
    {
        $fileReader = new CsvFileReader($filePath);

        foreach ($fileReader->readLines() as $line) {
            $transaction = (new Transaction($this->config))->build([
                'transaction_at' => $line[0],
                'user_id' => $line[1],
                'client' =>  $line[2],
                'operation_type' => $line[3],
                'amount' => $line[4],
                'currency' => $line[5],
            ]);

            $commissionFee = $this->getClient($this->config, $transaction)->commission();

            echo (new DecimalFormatter($this->config, $transaction))->format($commissionFee) . PHP_EOL;
        }
    }

    private function getClient(Config $config, Transaction $transaction): Client
    {
        return (new ClientFactory($config))->getClient($transaction);
    }
}
