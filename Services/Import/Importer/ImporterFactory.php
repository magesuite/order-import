<?php

namespace MageSuite\OrderImport\Services\Import\Importer;


class ImporterFactory
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
     * @param string $importerName
     * @return \MageSuite\OrderImport\Api\ImporterInterface
     */
    public function create(string $importerName)
    {
        if (!isset($this->classMapping[$importerName])) {
            return null;
        }

        return $this->objectManager->create($this->classMapping[$importerName]);
    }
}
