<?php

namespace MageSuite\OrderImport\Test\Integration\Services\Import\Shipment;

class GenericTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \MageSuite\OrderImport\Services\Import\Shipment\Generic
     */
    protected $shipment;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->shipment = $this
            ->objectManager
            ->create(
                \MageSuite\OrderImport\Services\Import\Shipment\Generic::class
            );
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Sales/_files/order.php
     */
    public function testShipOrder()
    {
        $this->objectManager->get('Magento\Framework\App\State')->setAreaCode('frontend');
        $order = $this->objectManager->create('Magento\Sales\Model\Order');
        $order->loadByIncrementId('100000001');

        $result = $this->shipment->shipOrder($order);
        $this->assertEquals(true, $result);
    }

}