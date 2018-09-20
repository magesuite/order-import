<?php

namespace MageSuite\OrderImport\Model\Collections;

class Import extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {

    protected function _construct()
    {
        $this->_init('MageSuite\OrderImport\Model\Import','MageSuite\OrderImport\Model\ResourceModel\Import');
    }
}
