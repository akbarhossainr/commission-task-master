<?php

namespace AkbarHossain\CommissionTask\Command;

use AkbarHossain\CommissionTask\Formatter\DecimalFormatter;
use AkbarHossain\CommissionTask\Service\Client;
use AkbarHossain\CommissionTask\Service\ClientManager;
use AkbarHossain\CommissionTask\Service\Config;
use AkbarHossain\CommissionTask\Service\CsvFileReader;
use AkbarHossain\CommissionTask\Service\Transaction;

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

        foreach ($fileReader->readLines() as $key => $line) {
            $transaction = (new Transaction($this->config))->build([
                'transaction_at' => $line[0],
                'user_id' => $line[1],
                'client' =>  $line[2],
                'operation_type' => $line[3],
                'amount' => $line[4],
                'currency' => $line[5],
            ]);

            $commissionFee = $this->getClient($this->config, $transaction)->commission();

            (new DecimalFormatter($this->config, $transaction))->format($commissionFee);
        }
    }

    protected function getClient(Config $config, Transaction $transaction): Client
    {
        return (new ClientManager($config))->getClient($transaction);
    }
}
