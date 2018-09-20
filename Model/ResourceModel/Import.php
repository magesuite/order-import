<?php

namespace MageSuite\OrderImport\Model\ResourceModel;

class Import extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('orderimport_log', 'import_id');
    }
}