<?php

namespace MageSuite\OrderImport\Api;

interface ShipmentInterface
{
    /**
     * @param \Magento\Sales\Model\Order $order $order
     */
    public function shipOrder(\Magento\Sales\Model\Order $order);
}
