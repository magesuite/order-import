<?php

namespace MageSuite\OrderImport\Services\File\Converter\Csv;


class OrderCollection implements \MageSuite\OrderImport\Api\OrderCollectionConverter
{

    /**
     * @var \Creativestyle\CSV\File\CsvReader
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
