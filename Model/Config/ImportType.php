<?php

namespace MageSuite\OrderImport\Model\Config;

class ImportType implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Get file type
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            'default_csv' => 'Default CSV',
            'default_xml' => 'Default XML'
        ];
    }
}