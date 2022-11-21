<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Test\Integration\Service;

use AkbarHossain\CommissionTask\Service\PrivateClient;
use AkbarHossain\CommissionTask\Test\TestCase;

class PrivateClientTest extends TestCase
{
    public function dataProviderForPrivateClient()
    {
        return [
            'withdraw commission fee' => [
                [
                    'transaction' => $this->createTransactionObject()
                        ->setOperationType('withdraw')
                        ->setAmount(1500),
                    'expected' => 1.5,
                ],
            ],
            'deposit commission fee' => [
                [
                    'transaction' => $this->createTransactionObject()
                        ->setOperationType('deposit')
                        ->setAmount(1500),
                    'expected' => 1500 * 0.0003,
                ],
            ],
        ];
    }

    /** @dataProvider dataProviderForPrivateClient */
    public function testCommissionMethodReturnCorrectResult(array $data)
    {
        $privateClient = new PrivateClient(
            $this->getContainerMock(),
            $data['transaction']
        );

        $this->assertEquals($data['expected'], $privateClient->commission());
    }
}
