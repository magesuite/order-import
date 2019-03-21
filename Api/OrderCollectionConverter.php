<?php

namespace MageSuite\OrderImport\Api;


interface OrderCollectionConverter
{

    /**
     * @param string $filePath
     * @return array
     */
    public function extractOrderIncrementIds($filePath);
}