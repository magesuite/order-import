<?php

namespace MageSuite\OrderImport\Test\Integration\Services\File\Converter\XML;

class OrderCollectionTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \MageSuite\OrderImport\Services\File\Converter\XML\OrderCollection
     */
    protected $orderCollectionConverter;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->orderCollectionConverter = $this
            ->objectManager
            ->create(
                \MageSuite\OrderImport\Services\File\Converter\XML\OrderCollection::class
            );
    }

    public function testExtractIncrementIds()
    {
        $filePath = __DIR__ . '/../../../assets/xml_converter_test.xml';

        $result = $this->orderCollectionConverter->extractOrderIncrementIds($filePath);
        $expected = ['000000004', '000000005', '000000006'];

        $this->assertEquals($expected, $result);
    }

}