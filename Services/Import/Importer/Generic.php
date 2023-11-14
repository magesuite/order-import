<?php

namespace MageSuite\OrderImport\Services\Import\Importer;

class Generic implements \MageSuite\OrderImport\Api\ImporterInterface
{
    /**
     * @var \MageSuite\OrderImport\Services\Import\Shipment\Generic
     */
    protected $shipmentFactory;

    /**
     * @var string
     */
    protected $shipmentName;

    public function __construct(
        \MageSuite\OrderImport\Services\Import\Shipment\ShipmentFactory $shipmentFactory,
        $shipmentName = null
    )
    {
        $this->shipmentFactory = $shipmentFactory;

        $this->shipmentName = $shipmentName;
    }

    /**
     * @param \Magento\Sales\Model\Order[] $orders
     * @return array
     */
    public function import($orders)
    {
        $shipment = $this->shipmentFactory->create($this->shipmentName);

        $importResult = ['success' => 0, 'fail' => 0, 'successIds' => [], 'failIds' => []];
        foreach ($orders as $order) {
            $result = $shipment->shipOrder($order);
            if ($result) {
                $importResult['success'] += 1;
                $importResult['successIds'][] = $order->getIncrementId();
            } else {
                $importResult['fail'] += 1;
                $importResult['failIds'][] = $order->getIncrementId();
            }
        }

        return $importResult;
    }

}
