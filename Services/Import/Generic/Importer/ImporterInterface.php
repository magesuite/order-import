<?php

namespace MageSuite\OrderImport\Services\Import\Generic\Importer;

interface ImporterInterface
{
    /**
     * @param array $orders
     */
    public function import($orders);
}