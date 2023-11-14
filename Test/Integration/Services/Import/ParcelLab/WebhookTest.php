<?php

namespace MageSuite\OrderImport\Test\Integration\Services\Import\ParcelLab;


class WebhookTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var \MageSuite\OrderImport\Services\Import\ParcelLab\Webhook
     */
    protected $webhook;

    /**
     * @var \Magento\Framework\Event\Manager|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $eventManagerDouble;

    /**
     * @var \MageSuite\OrderImport\Services\Import\ImportType\ImportTypeFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $importTypeFactoryStub;

    /**
     * @var \MageSuite\OrderImport\Services\Import\ImportType\ParcelLabWebhook|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $importDouble;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        $this->eventManagerDouble = $this->getMockBuilder(\Magento\Framework\Event\Manager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->importTypeFactoryStub = $this->getMockBuilder(\MageSuite\OrderImport\Services\Import\ImportType\ImportTypeFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->importDouble = $this->getMockBuilder(\MageSuite\OrderImport\Services\Import\ImportType\ParcelLabWebhook::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->webhook = $this
            ->objectManager
            ->create(
                \MageSuite\OrderImport\Services\Import\ParcelLab\Webhook::class,
                [
                    'eventManager' => $this->eventManagerDouble,
                    'importTypeFactory' => $this->importTypeFactoryStub,
                    'importTypeName' => 'parcellab_webhook'
                ]
            );
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoAdminConfigFixture orderimport/parcellab/webhook_active 1
     */
    public function testOnDispatchEvent()
    {
        $expectedConfiguration = new \Magento\Framework\DataObject(['increment_id' => 100000001, 'tracking_number' => 123456789,]);

        $this->importTypeFactoryStub->method('create')->with('parcellab_webhook')->willReturn($this->importDouble);
        $this->importDouble->expects($this->once())->method('run')->with($expectedConfiguration);
        $this->eventManagerDouble->expects($this->once())->method('dispatch')->with(
            'orderimport_parcellab_on_dispatch_after',
            ['webhook_data' => $expectedConfiguration]
        );

        $this->webhook->handle('onDispatch', ['orderNo' => 100000001, 'tracking_number' => 123456789], []);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoConfigFixture current_store orderimport/parcellab/webhook_active 1
     */
    public function testWrongEvent()
    {
        $this->importTypeFactoryStub->method('create')->with('parcellab_webhook')->willReturn($this->importDouble);
        $this->importDouble->expects($this->never())->method('run');

        $this->webhook->handle('onScheduled', ['orderNo' => 100000001, 'tracking_number' => 123456789], []);
    }
}
