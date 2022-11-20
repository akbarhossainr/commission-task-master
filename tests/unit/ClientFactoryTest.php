<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Test\Unit;

use AkbarHossain\CommissionTask\Factory\ClientFactory;
use AkbarHossain\CommissionTask\Service\BusinessClient;
use AkbarHossain\CommissionTask\Service\PrivateClient;
use AkbarHossain\CommissionTask\Test\TestCase;
use Symfony\Component\Console\Exception\InvalidOptionException;

class ClientFactoryTest extends TestCase
{
    public function dataProviderForClient(): array
    {
        return [
            'Private Client' => [
                [
                    'transaction' => $this->createTransactionObject(),
                    'expected' => new PrivateClient($this->getContainer(), $this->createTransactionObject()),
                ],
            ],
            'Business Client' => [
                [
                    'transaction' => $this->createTransactionObject()
                        ->setClient('business'),
                    'expected' => new BusinessClient($this->getContainer(), $this->createTransactionObject()),
                ],
            ],
            'Unknown Client' => [
                [
                    'transaction' => $this->createTransactionObject()->setClient('unknown'),
                    'expected' => new InvalidOptionException(),
                ],
            ],
        ];
    }

    /** @dataProvider  dataProviderForClient */
    public function testClientFactoryReturnCorrectClient(array $data): void
    {
        try {
            $client = (new ClientFactory($this->getContainer()))->getClient($data['transaction']);
            $this->assertTrue($client instanceof $data['expected']);
        } catch (\Exception $ex) {
            $this->assertTrue($ex instanceof InvalidOptionException);
        }
    }
}
