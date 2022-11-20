<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Test\Unit;

use AkbarHossain\CommissionTask\Library\CsvFileReader;
use AkbarHossain\CommissionTask\Test\TestCase;

class CsvFileReaderTest extends TestCase
{
    public function dataProviderForInvalidFile(): array
    {
        return [
            'empty file path' => [
                [
                    'path' => '',
                ],
            ],
            'unkown file path' => [
                [
                    'path' => 'unkown/file/path.csv',
                ],
            ],
            'invalid file extension' => [
                [
                    'path' => __DIR__.'/../../README.md',
                ],
            ],
        ];
    }

    /** @dataProvider  dataProviderForInvalidFile */
    public function testCsvFileReaderThrowException(array $data): void
    {
        $this->expectException(\Exception::class);

        new CsvFileReader($data['path']);
    }

    public function testCsvFileReaderReadAllLines(): void
    {
        $filePath = __DIR__.'/../../input.csv';

        $fileReader = new CsvFileReader($filePath);

        $this->assertCount(13, $fileReader->readLines());
    }
}
