<?php

namespace MageSuite\OrderImport\Repository;

class OrderRepository
{

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $orderCollectionFactory;

    public function __construct(
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $collectionFactory
    )
    {
        $this->orderCollectionFactory = $collectionFactory;
    }

    /**
     * @param array $incrementIds
     * @return \Magento\Framework\DataObject[]
     */
    public function getOrdersByIncrementIds($incrementIds)
    {
        $collection = $this->orderCollectionFactory->create()->addFieldToSelect('*');
        $collection->addFieldToFilter('increment_id', $incrementIds);

        return $collection->getItems();
    }
}
