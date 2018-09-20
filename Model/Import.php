<?php

namespace MageSuite\OrderImport\Model;

class Import extends \Magento\Framework\Model\AbstractModel
{
    const TYPE_CRON = 'cron';
    const TYPE_MANUAL = 'manual';

    protected function _construct()
    {
        $this->_init('MageSuite\OrderImport\Model\ResourceModel\Import');
    }

    /**
     * @param string $type
     * @param string $filename
     * @param array $result
     */
    public function setResult($type, $filename, $result)
    {
        $this->setType($type);
        $this->setImportedFilename($filename);
        $this->setSuccess($result['success']);
        $this->setFail($result['fail']);
        $this->setSuccessIds(implode(', ', $result['successIds']));
        $this->setFailIds(implode(', ', $result['failIds']));
        $this->setFinishedAt(new \DateTime());
    }
}