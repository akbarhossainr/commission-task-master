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
        ];
    }

    /** @dataProvider  dataProviderForClient */
    public function testClientFactoryReturnCorrectClient(array $data): void
    {
        $clientFactory = new ClientFactory($this->getContainer());

        $this->assertTrue(
            $clientFactory->getClient($data['transaction']) instanceof $data['expected']
        );
    }

    public function testClientTestFactoryThrowException(): void
    {
        $this->expectException(InvalidOptionException::class);

        $clientFactory = new ClientFactory($this->getContainer());

        $clientFactory->getClient($this->createTransactionObject()->setClient('Unknown'));
    }
}
