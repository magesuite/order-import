<?php

namespace MageSuite\OrderImport\Services\Import\Shipment;


class ShipmentFactory
{
    /**
     * @var array
     */
    protected $classMapping;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        array $classMapping = []
    )
    {
        $this->objectManager = $objectManager;
        $this->classMapping = $classMapping;
    }

    /**
     * @param string $shipmentName
     * @return \MageSuite\OrderImport\Api\ShipmentInterface
     */
    public function create(string $shipmentName)
    {
        if (!isset($this->classMapping[$shipmentName])) {
            return null;
        }

        return $this->objectManager->create($this->classMapping[$shipmentName]);
    }
}