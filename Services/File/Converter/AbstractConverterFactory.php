<?php
namespace MageSuite\OrderImport\Services\File\Converter;

abstract class AbstractConverterFactory
{
    /**
     * @var array
     */
    protected $classMappings = [
        'default_csv' => \MageSuite\OrderImport\Services\File\Converter\CSV\OrderCollection::class,
        'default_xml' => \MageSuite\OrderImport\Services\File\Converter\XML\OrderCollection::class,
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