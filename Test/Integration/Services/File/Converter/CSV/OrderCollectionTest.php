<?php

namespace MageSuite\OrderImport\Test\Integration\Services\File\Converter\CSV;

class OrderCollectionTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \MageSuite\OrderImport\Services\File\Converter\CSV\OrderCollection
     */
    protected $orderCollectionConverter;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->orderCollectionConverter = $this
            ->objectManager
            ->create(
                \MageSuite\OrderImport\Services\File\Converter\CSV\OrderCollection::class
            );
    }

    public function testExtractIncrementIds()
    {
        $filePath = __DIR__ . '/../../../assets/csv_converter_test.csv';

        $result = $this->orderCollectionConverter->extractOrderIncrementIds($filePath);
        $expected = ['100000001', '100000002', '100000003', '100000004'];

        $this->assertEquals($expected, $result);
    }

}