<?php

namespace MageSuite\OrderImport\Services\Import;


abstract class AbstractImporterTypeFactory
{
    /**
     * @var array
     */
    protected $classMappings = [
        'default_importer' => \MageSuite\OrderImport\Services\Import\Generic\ImportShipped::class,
    ];

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager
    )
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param string $importerName
     * @return \MageSuite\OrderImport\Services\Import\ImporterType
     */
    public function create(string $importerName)
    {
        if (!isset($this->classMappings[$importerName])) {
            return null;
        }

        return $this->objectManager->create($this->classMappings[$importerName]);
    }
}