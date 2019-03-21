<?php

namespace MageSuite\OrderImport\Model;

class Import extends \Magento\Framework\Model\AbstractModel
{
    const TYPE_FTP_CRON = 'FTP (cron)';
    const TYPE_FTP_MANUAL = 'FTP (manual)';
    const TYPE_PARCELLAB_API = 'ParcelLab API';
    const TYPE_PARCELLAB_WEBHOOK = 'ParcelLab Webhook';

    protected function _construct()
    {
        $this->_init('MageSuite\OrderImport\Model\ResourceModel\Import');
    }

    /**
     * @param string $type
     * @param array $result
     * @param null|string $comment
     * @param null|string $filename
     * @throws \Exception
     */
    public function setResult($type, $result, $comment = null, $filename = null)
    {
        $this->setType($type);
        $this->setComment($comment);
        $this->setImportedFilename($filename);
        $this->setSuccess($result['success']);
        $this->setFail($result['fail']);
        $this->setSuccessIds(implode(', ', $result['successIds']));
        $this->setFailIds(implode(', ', $result['failIds']));
        $this->setFinishedAt(new \DateTime());
    }

    /**
     * @param string $type
     * @param null|string $comment
     * @param null|string $filename
     * @throws \Exception
     */
    public function setResultFailed($type, $comment = null, $filename = null)
    {
        $this->setType($type);
        $this->setComment($comment);
        $this->setImportedFilename($filename);
        $this->setFinishedAt(new \DateTime());
    }
}