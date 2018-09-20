<?php

namespace MageSuite\OrderImport\Test\Integration\Services\Import;

use Magento\TestFramework\Bootstrap;

class ImporterTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \MageSuite\OrderImport\Services\Import\Generic\Importer\Importer
     */
    private $importer;

    /**
     * @var \MageSuite\OrderImport\Services\Import\Generic\Shipment\Shipment|\PHPUnit_Framework_MockObject_MockObject
     */
    private $shipmentDouble;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->shipmentDouble = $this->getMockBuilder(\MageSuite\OrderImport\Services\Import\Generic\Shipment\Shipment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->importer = $this
            ->objectManager
            ->create(
                \MageSuite\OrderImport\Services\Import\Generic\Importer\Importer::class,
                ['shipment' => $this->shipmentDouble]
            );
    }

    /**
     * @magentoDataFixture Magento/Sales/_files/two_orders_for_two_diff_customers.php
     */
    public function testImport()
    {
        $repository = $this->objectManager->create('Magento\Sales\Api\OrderRepositoryInterface');
        $searchCriteriaBuilder = $this->objectManager->create('Magento\Framework\Api\SearchCriteriaBuilder');
        $searchCriteria = $searchCriteriaBuilder->create();
        $orders = $repository->getList($searchCriteria)->getItems();

        $this->shipmentDouble->expects($this->at(0))->method('shipOrder')->willReturn(true);
        $this->shipmentDouble->expects($this->at(1))->method('shipOrder')->willReturn(false);

        $result = $this->importer->import($orders);
        $expected = ['success' => 1, 'fail' => 1, 'successIds' => ['100000001'], 'failIds' => ['100000002']];
        $this->assertEquals($expected, $result);
    }

}