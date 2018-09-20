<?php

namespace MageSuite\OrderImport\Services\Import\Generic;

class ImportShipped implements \MageSuite\OrderImport\Services\Import\ImporterType
{
    /**
     * @var \Creativestyle\LFTP\File\Downloader
     */
    private $downloader;

    /**
     * @var \MageSuite\OrderImport\Services\File\Converter\ConverterFactory
     */
    private $orderCollectionConverter;

    /**
     * @var \MageSuite\OrderImport\Services\Import\Generic\Importer\ImporterFactory
     */
    private $importerFactory;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    private $directoryList;

    /**
     * @var \MageSuite\OrderImport\Repository\OrderRepository
     */
    private $orderRepository;

    /**
     * @var \MageSuite\OrderImport\Repository\ImportRepository
     */
    private $importRepository;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Magento\Framework\App\State
     */
    private $state;

    public function __construct(
        \Creativestyle\LFTP\File\Downloader $downloader,
        \MageSuite\OrderImport\Services\File\Converter\ConverterFactory $orderCollectionConverter,
        \MageSuite\OrderImport\Repository\OrderRepository $orderRepository,
        \MageSuite\OrderImport\Repository\ImportRepository $importRepository,
        \MageSuite\OrderImport\Services\Import\Generic\Importer\ImporterFactory $importerFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\State $state
    )
    {
        $this->downloader = $downloader;
        $this->orderCollectionConverter = $orderCollectionConverter;
        $this->orderRepository = $orderRepository;
        $this->importRepository = $importRepository;
        $this->importerFactory = $importerFactory;
        $this->directoryList = $directoryList;
        $this->scopeConfig = $scopeConfig;
        $this->state = $state;
    }

    public function run($type, $remoteDirectory)
    {
        $this->downloader->configure(
            $this->scopeConfig->getValue('orderimport/automatic/ftp_protocol'),
            $this->scopeConfig->getValue('orderimport/automatic/ftp_host'),
            $this->scopeConfig->getValue('orderimport/automatic/ftp_login'),
            $this->scopeConfig->getValue('orderimport/automatic/ftp_password')
        );

        $import = $this->importRepository->create();
        $lastImport = $this->importRepository->getLastImport();
        $lastImportFilename = $lastImport->getImportedFilename();

        $filename = $this->downloader->getNewestFileFromFolder($remoteDirectory);
        if ($filename != $lastImportFilename and $filename != '') {
            $remoteFilePath = $remoteDirectory . '/' . $filename;
            $targetFilePath = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR) . '/import/' . $filename;
            $this->downloader->download($remoteFilePath, $targetFilePath);

            $importType = $this->scopeConfig->getValue('orderimport/automatic/import_type');

            $orderCollectionConverter = $this->orderCollectionConverter->create($importType);

            $incrementIds = $orderCollectionConverter->extractOrderIncrementIds($targetFilePath);
            $orders = $this->orderRepository->getOrdersByIncrementIds($incrementIds);

            $importer = $this->importerFactory->create('default_importer');
            $result = $importer->import($orders);

            $import->setResult($type, $filename, $result);
            $this->importRepository->save($import);
        }
    }
}