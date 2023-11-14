<?php

namespace MageSuite\OrderImport\Test\Integration\Model;


class ImportTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
    }

    public function testSetResult()
    {
        $importModel = $this->objectManager->create(\MageSuite\OrderImport\Model\Import::class);

        $result = ['success' => 1, 'fail' => 2, 'successIds' => ['100000001'], 'failIds' => ['100000002', '100000003']];

        $importModel->setResult('manual', $result, 'comment', 'import.csv');
        $this->assertEquals('manual', $importModel->getType());
        $this->assertEquals('comment', $importModel->getComment());
        $this->assertEquals('import.csv', $importModel->getImportedFilename());
        $this->assertEquals('1', $importModel->getSuccess());
        $this->assertEquals('2', $importModel->getFail());
        $this->assertEquals('100000001', $importModel->getSuccessIds());
        $this->assertEquals('100000002, 100000003', $importModel->getFailIds());
        $this->assertInstanceOf('DateTime', $importModel->getFinishedAt());
    }

}
