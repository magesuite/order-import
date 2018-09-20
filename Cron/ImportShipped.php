<?php

namespace MageSuite\OrderImport\Cron;

class ImportShipped
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \MageSuite\OrderImport\Services\Import\ImporterTypeFactory
     */
    private $importerTypeFactory;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \MageSuite\OrderImport\Services\Import\ImporterTypeFactory $importerTypeFactory
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->importerTypeFactory = $importerTypeFactory;
    }

    public function execute()
    {
        $active = $this->scopeConfig->getValue('orderimport/automatic/active');
        if (!$active) {
            return;
        }

        $remoteDirectory = $this->scopeConfig->getValue('orderimport/automatic/ftp_path');

        $importer = $this->importerTypeFactory->create('default_importer');

        $importer->run(\MageSuite\OrderImport\Model\Import::TYPE_CRON, $remoteDirectory);
    }
}