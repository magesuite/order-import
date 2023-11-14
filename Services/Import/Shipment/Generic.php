<?php

namespace MageSuite\OrderImport\Services\Import\Shipment;

class Generic implements \MageSuite\OrderImport\Api\ShipmentInterface
{

    /**
     * @var \Magento\Sales\Model\Convert\Order
     */
    protected $convertOrder;

    public function __construct(
        \Magento\Sales\Model\Convert\Order $convertOrder
    )
    {
        $this->convertOrder = $convertOrder;
    }

    /**
     * @param \Magento\Sales\Model\Order $order
     * @return bool
     */
    public function shipOrder(\Magento\Sales\Model\Order $order)
    {
        if (!$this->checkShipPossibility($order)) {
            return false;
        }
        $shipment = $this->convertOrder->toShipment($order);

        foreach ($order->getAllItems() AS $orderItem) {
            if (!$orderItem->getQtyToShip() || $orderItem->getIsVirtual()) {
                continue;
            }

            $qtyShipped = $orderItem->getQtyToShip();

            $shipmentItem = $this->convertOrder->itemToShipmentItem($orderItem)->setQty($qtyShipped);
            $shipment->addItem($shipmentItem);
        }

        $shipment->register();
        $shipment->getOrder()->setIsInProcess(true);

        try {
            $shipment->save();
            $shipment->getOrder()->addStatusToHistory(false, 'Order has been shipped');
            $shipment->getOrder()->save();
        } catch (\Exception $e) {
           return false;
        }

        return true;
    }

    /**
     * @param \Magento\Sales\Model\Order $order
     * @return bool
     */
    private function checkShipPossibility(\Magento\Sales\Model\Order $order)
    {
        if ($order->canUnhold() || $order->isPaymentReview()) {
            $order->addStatusToHistory(false, 'Order cannot be shipped (on hold or payment review)');
            $order->save();
            return false;
        }

        if ($order->getIsVirtual() || $order->isCanceled()) {
            $order->addStatusToHistory(false, 'Order cannot be shipped (virtual or canceled)');
            $order->save();
            return false;
        }

        if ($order->getActionFlag(\Magento\Sales\Model\Order::ACTION_FLAG_SHIP) === false) {
            $order->addStatusToHistory(false, 'Order cannot be shipped');
            $order->save();
            return false;
        }

        foreach ($order->getAllItems() as $item) {
            if ($item->getQtyToShip() > 0 && !$item->getIsVirtual() && !$item->getLockedDoShip()) {
                return true;
            }
        }
        $order->addStatusToHistory(false, 'Order cannot be shipped (no items to ship or completed)');
        $order->save();
        return false;
    }
}
