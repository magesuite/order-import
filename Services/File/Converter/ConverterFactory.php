<?php

namespace MageSuite\OrderImport\Services\File\Converter;

class ConverterFactory
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
     * @param string $converterName
     * @return \MageSuite\OrderImport\Api\OrderCollectionConverter
     */
    public function create(string $converterName)
    {
        if (!isset($this->classMapping[$converterName])) {
            return null;
        }

        return $this->objectManager->create($this->classMapping[$converterName]);
    }
}
