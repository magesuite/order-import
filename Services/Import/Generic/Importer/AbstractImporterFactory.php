<?php

namespace MageSuite\OrderImport\Services\Import\Generic\Importer;


abstract class AbstractImporterFactory
{
    /**
     * @var array
     */
    protected $classMappings = [
        'default_importer' => \MageSuite\OrderImport\Services\Import\Generic\Importer\Importer::class,
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
     * @return Importer
     */
    public function create(string $importerName)
    {
        if (!isset($this->classMappings[$importerName])) {
            return null;
        }

        return $this->objectManager->create($this->classMappings[$importerName]);
    }
}