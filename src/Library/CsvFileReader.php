<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Library;

final class CsvFileReader
{
    public const EXTENSION = 'csv';

    private $file;

    /**
     * @throws \Exception
     */
    public function __construct(string $path)
    {
        if (!file_exists($path)) {
            throw new \Exception('The file could not be found.');
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);

        if ($extension !== self::EXTENSION) {
            throw new \Exception(sprintf('Required file format is [%s]', self::EXTENSION));
        }

        $this->file = fopen($path, 'r');

        if (!$this->file) {
            throw new \Exception('The file could not be opened.');
        }
    }

    public function readLines(): iterable
    {
        while ($line = fgetcsv($this->file)) {
            yield $line;
        }
    }

    public function __destruct()
    {
        if ($this->file !== null) {
            fclose($this->file);
        }
    }
}
