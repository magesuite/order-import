<?php

namespace MageSuite\OrderImport\Services\Import\ImportType;

class Ftp implements \MageSuite\OrderImport\Api\ImportTypeInterface
{

    const TYPE = 'FTP';

    const CONFIG_FTP_PATH = 'orderimport/ftp';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directoryList;

    /**
     * @var \MageSuite\OrderImport\Services\File\Converter\ConverterFactory
     */
    protected $orderCollectionConverter;

    /**
     * @var \MageSuite\OrderImport\Services\Import\Importer\ImporterFactory
     */
    protected $importerFactory;

    /**
     * @var \MageSuite\OrderImport\Repository\OrderRepository
     */
    protected $orderRepository;

    /**
     * @var \MageSuite\OrderImport\Repository\ImportRepository
     */
    protected $importRepository;

    /**
     * @var \Creativestyle\LFTP\File\Downloader
     */
    protected $downloader;

    /**
     * @var string
     */
    protected $importerName;
    /**
     * @var string
     */
    protected $converterName;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \MageSuite\OrderImport\Services\File\Converter\ConverterFactory $orderCollectionConverter,
        \MageSuite\OrderImport\Services\Import\Importer\ImporterFactory $importerFactory,
        \MageSuite\OrderImport\Repository\OrderRepository $orderRepository,
        \MageSuite\OrderImport\Repository\ImportRepository $importRepository,
        \Creativestyle\LFTP\File\Downloader $downloader,
        $importerName = null,
        $converterName = null
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->directoryList = $directoryList;
        $this->orderCollectionConverter = $orderCollectionConverter;
        $this->importerFactory = $importerFactory;
        $this->orderRepository = $orderRepository;
        $this->importRepository = $importRepository;
        $this->downloader = $downloader;

        $this->importerName = $importerName;
        $this->converterName = $converterName;
    }

    public function run($configuration)
    {
        $ftpScopeConfig = $this->scopeConfig->getValue(self::CONFIG_FTP_PATH);
        $this->downloader->configure(
            $ftpScopeConfig['protocol'],
            $ftpScopeConfig['host'],
            $ftpScopeConfig['login'],
            $ftpScopeConfig['password']
        );
        $remoteDirectory = $this->getRemoteDirectory($configuration, $ftpScopeConfig);

        $import = $this->importRepository->create();
        $lastImport = $this->importRepository->getLastImport();
        $lastImportFilename = $lastImport->getImportedFilename();

        $filename = $this->downloader->getNewestFileFromFolder($remoteDirectory);
        if ($filename == $lastImportFilename or $filename == '') {
            return false;
        }

        $remoteFilePath = $remoteDirectory . '/' . $filename;
        $targetFilePath = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR) . '/import/orders/' . $filename;
        $this->downloader->download($remoteFilePath, $targetFilePath);

        $orderCollectionConverter = $this->orderCollectionConverter->create($this->converterName);

        $incrementIds = $orderCollectionConverter->extractOrderIncrementIds($targetFilePath);
        $orders = $this->orderRepository->getOrdersByIncrementIds($incrementIds);

        $importer = $this->importerFactory->create($this->importerName);
        $result = $importer->import($orders);

        $import->setResult(self::TYPE, $result, null, $filename);
        $this->importRepository->save($import);

    }

    private function getRemoteDirectory($configuration, $ftpScopeConfig)
    {
        if (!empty($configuration->getRemoteDirectory())) {
            return $configuration->getRemoteDirectory();
        }

        if (isset($ftpScopeConfig['path'])) {
            return $ftpScopeConfig['path'];
        }

        return '/';
    }

}
