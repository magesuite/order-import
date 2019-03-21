<?php

namespace MageSuite\OrderImport\Services\Import\ImportType;


class ParcelLabWebhook implements \MageSuite\OrderImport\Api\ImportTypeInterface
{

    const TYPE = 'ParcelLab Webhook';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

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
     * @var string
     */
    protected $importerName;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \MageSuite\OrderImport\Services\Import\Importer\ImporterFactory $importerFactory,
        \MageSuite\OrderImport\Repository\OrderRepository $orderRepository,
        \MageSuite\OrderImport\Repository\ImportRepository $importRepository,
        $importerName = null
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->importerFactory = $importerFactory;
        $this->orderRepository = $orderRepository;
        $this->importRepository = $importRepository;

        $this->importerName = $importerName;
    }

    public function run($configuration)
    {
        if (empty($configuration->getIncrementId())) {
            return false;
        }

        $import = $this->importRepository->create();

        $order = $this->orderRepository->getOrdersByIncrementIds($configuration->getIncrementId());

        $importer = $this->importerFactory->create($this->importerName);
        $result = $importer->import($order);

        $import->setResult(self::TYPE, $result);
        $this->importRepository->save($import);
    }
}