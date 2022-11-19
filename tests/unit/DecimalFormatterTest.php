<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Test\Unit;

use AkbarHossain\CommissionTask\Formatter\DecimalFormatter;
use AkbarHossain\CommissionTask\Test\TestCase;

class DecimalFormatterTest extends TestCase
{
    public function dataProviderForDifferentCurrency(): array
    {
        return [
            'default currency' => [
                [
                    'transaction' => $this->createTransactionObject()->setAmount(100.8999),
                    'expected' => '100.90',
                ],
            ],
            'JPY currency' => [
                [
                    'transaction' => $this->createTransactionObject()
                        ->setCurrency('JPY')
                        ->setAmount(100.8999),
                    'expected' => '101',
                ],
            ],
            'USD currency' => [
                [
                    'transaction' => $this->createTransactionObject()
                        ->setCurrency('USD')
                        ->setAmount(100.8999),
                    'expected' => '100.90',
                ],
            ],
        ];
    }

    /** @dataProvider  dataProviderForDifferentCurrency */
    public function testFormatOutputIsCorrect(array $data)
    {
        $formatter = new DecimalFormatter(
            $this->getDefaultConfig(),
            $data['transaction']
        );

        $amount = $data['transaction']->getAmount();

        $this->assertSame($data['expected'], $formatter->format($amount));
    }
}
