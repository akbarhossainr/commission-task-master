<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Test\Integration;

use AkbarHossain\CommissionTask\Formatter\DecimalFormatter;
use AkbarHossain\CommissionTask\Service\BusinessClient;
use AkbarHossain\CommissionTask\Test\TestCase;

class BusinessClientTest extends TestCase
{
    protected $config;
    protected $transaction;

    public function setUp(): void
    {
        $this->config = $this->getDefaultConfig();
    }

    public function dataProviderForBusinessClient()
    {
        return [
            'default' => [
                [
                    'transaction' => $this->createTransactionObject()
                        ->setClient('business'),
                    'expected' => 7.5,
                ],
            ],
            'withdraw commission fee' => [
                [
                    'transaction' => $this->createTransactionObject()
                        ->setClient('business')
                        ->setOperationType('withdraw'),
                    'expected' => 7.5,
                ]
            ],
            'deposit commission fee' => [
                [
                    'transaction' => $this->createTransactionObject()
                        ->setClient('business')
                        ->setOperationType('deposit'),
                    'expected' => 0.45,
                ]
            ]
        ];
    }

    /** @dataProvider dataProviderForBusinessClient */
    public function testCommissionMethodReturnCorrectResult(array $data)
    {
        $businessClient = new BusinessClient(
            $this->config,
            $data['transaction']
        );

        $actual = (new DecimalFormatter($this->config, $data['transaction']))
            ->format($businessClient->commission());

        $this->assertEquals($data['expected'], $actual);
    }
}
