<?php

namespace MageSuite\OrderImport\Cron;

class ImportFtp
{

    const CONFIG_FTP_CRON_ENABLED_PATH = 'orderimport/ftp/cron_active';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \MageSuite\OrderImport\Services\Import\ImportType\ImportTypeFactory
     */
    protected $importTypeFactory;

    /**
     * @var string
     */
    protected $importTypeName;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \MageSuite\OrderImport\Services\Import\ImportType\ImportTypeFactory $importTypeFactory,
        $importTypeName = null
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->importTypeFactory = $importTypeFactory;

        $this->importTypeName = $importTypeName;
    }

    public function execute()
    {
        $active = $this->scopeConfig->getValue(self::CONFIG_FTP_CRON_ENABLED_PATH);
        if (!$active) {
            return;
        }

        $importer = $this->importTypeFactory->create($this->importTypeName);
        $configuration = new \Magento\Framework\DataObject();
        $importer->run($configuration);
    }
}