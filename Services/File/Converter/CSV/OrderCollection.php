<?php

namespace MageSuite\OrderImport\Services\File\Converter\CSV;

use Creativestyle\CSV\File\CsvReader;

class OrderCollection
{

    /**
     * @var CsvReader
     */
    protected $csvReader;

    public function __construct(
        \Creativestyle\CSV\File\CsvReader $csvReader
    )
    {
        $this->csvReader = $csvReader;
    }

    /**
     * @param $filePath
     * @return array
     */
    public function extractOrderIncrementIds($filePath)
    {
        $incrementIds = [];
        foreach ($this->csvReader->getLinesFromFile($filePath) as $line) {
            $incrementIds[] = $line['increment_id'];
        }

        return $incrementIds;
    }

}