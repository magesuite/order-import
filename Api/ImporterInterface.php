<?php

namespace MageSuite\OrderImport\Api;

interface ImporterInterface
{
    /**
     * @param array $orders
     */
    public function import($orders);
}
