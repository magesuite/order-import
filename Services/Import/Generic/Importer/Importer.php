<?php

namespace MageSuite\OrderImport\Services\Import\Generic\Importer;

use Magento\Sales\Model\Order;

class Importer implements \MageSuite\OrderImport\Services\Import\Generic\Importer\ImporterInterface
{
    /**
     * @var \MageSuite\OrderImport\Services\Import\Generic\Shipment\Shipment
     */
    private $shipment;

    public function __construct(\MageSuite\OrderImport\Services\Import\Generic\Shipment\Shipment $shipment)
    {
        $this->shipment = $shipment;
    }

    /**
     * @param Order[] $orders
     * @return array
     */
    public function import($orders)
    {
        $importResult = ['success' => 0, 'fail' => 0, 'successIds' => [], 'failIds' => []];
        foreach ($orders as $order) {
            $result = $this->shipment->shipOrder($order);
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