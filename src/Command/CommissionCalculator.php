<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Command;

use AkbarHossain\CommissionTask\Entity\Transaction;
use AkbarHossain\CommissionTask\Factory\ClientFactory;
use AkbarHossain\CommissionTask\Formatter\DecimalFormatter;
use AkbarHossain\CommissionTask\Library\CsvFileReader;
use AkbarHossain\CommissionTask\Service\Client;
use DI\Container;

final class CommissionCalculator
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function execute(string $filePath)
    {
        try {
            $fileReader = new CsvFileReader($filePath);
        } catch (\Exception $exception) {
            exit($exception->getMessage().PHP_EOL);
        }

        foreach ($fileReader->readLines() as $line) {
            $transaction = (new Transaction($this->container))->build([
                'transaction_at' => $line[0],
                'user_id' => $line[1],
                'client' => $line[2],
                'operation_type' => $line[3],
                'amount' => $line[4],
                'currency' => $line[5],
            ]);

            $commission = $this->getClient($transaction)->commission();
            $formatter = (new DecimalFormatter($this->container, $transaction));

            echo $formatter->format($commission).PHP_EOL;
        }
    }

    private function getClient(Transaction $transaction): Client
    {
        return (new ClientFactory($this->container))->getClient($transaction);
    }
}
