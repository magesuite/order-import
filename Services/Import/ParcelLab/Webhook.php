<?php

namespace MageSuite\OrderImport\Services\Import\ParcelLab;


class Webhook implements \MageSuite\OrderImport\Api\ParcelLabWebhookHandlerInterface
{

    const EVENT_AFTER_ON_DISPATCH = 'orderimport_parcellab_on_dispatch_after';
    const CONFIG_PARCELLAB_WEBHOOK_ENABLED_PATH = 'orderimport/parcellab/webhook_active';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\Event\Manager
     */
    protected $eventManager;

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
        \Magento\Framework\Event\Manager $eventManager,
        \MageSuite\OrderImport\Services\Import\ImportType\ImportTypeFactory $importTypeFactory,
        $importTypeName = null
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->eventManager = $eventManager;
        $this->importTypeFactory = $importTypeFactory;

        $this->importTypeName = $importTypeName;
    }

    public function handle($event, $references, $data)
    {
        $active = $this->scopeConfig->getValue(self::CONFIG_PARCELLAB_WEBHOOK_ENABLED_PATH);
        if (!$active) {
            return;
        }

        $importer = $this->importTypeFactory->create($this->importTypeName);

        if ($event == 'onDispatch') {
            $configuration = new \Magento\Framework\DataObject(
                [
                    'increment_id' => $references['orderNo'],
                    'tracking_number' => $references['tracking_number'],
                ]
            );
            $importer->run($configuration);
            $this->eventManager->dispatch(self::EVENT_AFTER_ON_DISPATCH, ['webhook_data' => $configuration]);
        }
    }
}