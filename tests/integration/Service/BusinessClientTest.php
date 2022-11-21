<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Test\Integration\Service;

use AkbarHossain\CommissionTask\Service\BusinessClient;
use AkbarHossain\CommissionTask\Test\TestCase;

class BusinessClientTest extends TestCase
{
    public function dataProviderForBusinessClient()
    {
        return [
            'withdraw commission fee' => [
                [
                    'transaction' => $this->createTransactionObject()
                        ->setClient('business')
                        ->setOperationType('withdraw')
                        ->setAmount(1500),
                    'expected' => 7.5,
                ],
            ],
            'deposit commission fee' => [
                [
                    'transaction' => $this->createTransactionObject()
                        ->setClient('business')
                        ->setOperationType('deposit')
                        ->setAmount(1500),
                    'expected' => 1500 * 0.0003,
                ],
            ],
        ];
    }

    /** @dataProvider dataProviderForBusinessClient */
    public function testCommissionMethodReturnCorrectResult(array $data)
    {
        $businessClient = new BusinessClient(
            $this->getContainerMock(),
            $data['transaction']
        );

        $this->assertEquals($data['expected'], $businessClient->commission());
    }
}
