<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Library;

final class CsvFileReader
{
    public const EXTENSION = 'csv';

    protected $file;

    /**
     * @throws \Exception
     */
    public function __construct(string $path)
    {
        try {
            if (!file_exists($path)) {
                throw new \Exception('The file could not be found.');
            }

            $extention = pathinfo($path, PATHINFO_EXTENSION);

            if ($extention !== self::EXTENSION) {
                throw new \Exception(sprintf('Expecting file format is [%s]', self::EXTENSION));
            }

            $this->file = fopen($path, 'r');

            if (!$this->file) {
                throw new \Exception('The file could not be opened.');
            }
        } catch (\Exception $execption) {
            exit($execption->getMessage().PHP_EOL);
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
        if ($this->file) {
            fclose($this->file);
        }
    }
}
