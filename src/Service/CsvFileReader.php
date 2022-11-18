<?php

namespace AkbarHossain\CommissionTask\Service;

use Exception;

class CsvFileReader
{
    protected $file;

    /**
     * @throws Exception
     */
    public function __construct(string $path)
    {
        try {
            if (!file_exists($path)) {
                throw new Exception('The file could not be found.');
            }

            $this->file = fopen($path, 'r');

            if (!$this->file) {
                throw new Exception('The file could not be opened.');
            }
        } catch (Exception $execption) {
            die($execption->getMessage() . PHP_EOL);
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
        fclose($this->file);
    }
}
