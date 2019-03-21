<?php

namespace MageSuite\OrderImport\Repository;

class ImportRepository
{
    /**
     * @var \MageSuite\OrderImport\Model\ResourceModel\Import
     */
    protected $importResourceModel;

    /**
     * @var \MageSuite\OrderImport\Model\ImportFactory
     */
    protected $importFactory;

    /**
     * @var \MageSuite\OrderImport\Model\Collections\ImportFactory
     */
    protected $importCollectionFactory;
    
    public function __construct(
        \MageSuite\OrderImport\Model\ResourceModel\Import $importResourceModel,
        \MageSuite\OrderImport\Model\ImportFactory $importFactory,
        \MageSuite\OrderImport\Model\Collections\ImportFactory $importCollectionFactory
    )
    {
        $this->importResourceModel = $importResourceModel;
        $this->importFactory = $importFactory;
        $this->importCollectionFactory = $importCollectionFactory;
    }

    public function create()
    {
        $import = $this->importFactory->create();
        $import->setStartedAt(new \DateTime());

        return $import;
    }

    public function getById($id)
    {
        $import = $this->importFactory->create();

        return $import->load($id);
    }

    public function getLastImport()
    {
        $collection = $this->importCollectionFactory->create();
        $collection->addFieldToSelect('*');
        $collection->addOrder('finished_at');

        return $collection->getFirstItem();
    }

    public function save(\MageSuite\OrderImport\Model\Import $import)
    {
        return $this->importResourceModel->save($import);
    }

}