<?php

namespace MageSuite\OrderImport\Api;

interface ImportTypeInterface
{
    /**
     * @param \Magento\Framework\DataObject $configuration
     */
    public function run($configuration);
}
