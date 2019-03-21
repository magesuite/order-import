<?php

namespace MageSuite\OrderImport\Services\Import\ImportType;


class ImportTypeFactory
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
     * @param string $importTypeName
     * @return \MageSuite\OrderImport\Api\ImportTypeInterface
     */
    public function create(string $importTypeName)
    {
        if (!isset($this->classMapping[$importTypeName])) {
            return null;
        }

        return $this->objectManager->create($this->classMapping[$importTypeName]);
    }
}