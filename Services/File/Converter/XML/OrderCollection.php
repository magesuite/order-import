<?php

namespace MageSuite\OrderImport\Services\File\Converter\XML;

class OrderCollection
{
    /**
     * @param $filePath
     * @return array
     */
    public function extractOrderIncrementIds($filePath)
    {
        $incrementIds = [];

        $xmlToArray = simplexml_load_string(file_get_contents($filePath));

        foreach ($xmlToArray->Order as $order) {
            $incrementIds[] = (string) $order->Head->MagentoOrderIncrementId;
        }

        return $incrementIds;
    }

}