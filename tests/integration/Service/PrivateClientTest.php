<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Test\Integration\Service;

use AkbarHossain\CommissionTask\Formatter\DecimalFormatter;
use AkbarHossain\CommissionTask\Service\PrivateClient;
use AkbarHossain\CommissionTask\Test\TestCase;

class PrivateClientTest extends TestCase
{
    protected $config;
    protected $transaction;

    public function setUp(): void
    {
        $this->config = $this->getContainer();
    }

    public function dataProviderForPrivateClient()
    {
        return [
            'default' => [
                [
                    'transaction' => $this->createTransactionObject(),
                    'expected' => 1.5,
                ],
            ],
            'withdraw commission fee' => [
                [
                    'transaction' => $this->createTransactionObject()->setOperationType('withdraw'),
                    'expected' => 1.5,
                ],
            ],
            'deposit commission fee' => [
                [
                    'transaction' => $this->createTransactionObject()->setOperationType('deposit'),
                    'expected' => 0.45,
                ],
            ],
        ];
    }

    /** @dataProvider dataProviderForPrivateClient */
    public function testCommissionMethodReturnCorrectResult(array $data)
    {
        $privateClient = new PrivateClient(
            $this->config,
            $data['transaction']
        );

        $actual = (new DecimalFormatter($this->config, $data['transaction']))
            ->format($privateClient->commission());

        $this->assertEquals($data['expected'], $actual);
    }
}
