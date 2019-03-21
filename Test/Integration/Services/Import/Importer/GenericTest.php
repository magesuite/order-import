<?php

namespace MageSuite\OrderImport\Test\Integration\Services\Import\Importer;


class GenericTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \MageSuite\OrderImport\Services\Import\Importer\Generic
     */
    protected $importer;

    /**
     * @var \MageSuite\OrderImport\Services\Import\Shipment\ShipmentFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $shipmentFactoryStub;

    /**
     * @var \MageSuite\OrderImport\Services\Import\Shipment\Generic|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $shipmentDouble;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->shipmentFactoryStub = $this->getMockBuilder(\MageSuite\OrderImport\Services\Import\Shipment\ShipmentFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->shipmentDouble = $this->getMockBuilder(\MageSuite\OrderImport\Services\Import\Shipment\Generic::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->importer = $this
            ->objectManager
            ->create(
                \MageSuite\OrderImport\Services\Import\Importer\Generic::class,
                [
                    'shipmentFactory' => $this->shipmentFactoryStub,
                    'shipmentName' => 'default_shipment'
                ]
            );
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Sales/_files/two_orders_for_two_diff_customers.php
     */
    public function testImport()
    {
        $repository = $this->objectManager->create('Magento\Sales\Api\OrderRepositoryInterface');
        $searchCriteriaBuilder = $this->objectManager->create('Magento\Framework\Api\SearchCriteriaBuilder');
        $searchCriteria = $searchCriteriaBuilder->create();
        $orders = $repository->getList($searchCriteria)->getItems();

        $this->shipmentFactoryStub->method('create')->willReturn($this->shipmentDouble);
        $this->shipmentDouble->expects($this->at(0))->method('shipOrder')->willReturn(true);
        $this->shipmentDouble->expects($this->at(1))->method('shipOrder')->willReturn(false);

        $result = $this->importer->import($orders);
        $expected = ['success' => 1, 'fail' => 1, 'successIds' => ['100000001'], 'failIds' => ['100000002']];
        $this->assertEquals($expected, $result);
    }

}