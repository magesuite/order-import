<?php

namespace MageSuite\OrderImport\Block\Adminhtml\Import;

class Show extends \Magento\Backend\Block\Template
{
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data
    )
    {
        parent::__construct($context, $data);
        $this->registry = $registry;
    }

    public function getImport() {
        return $this->registry->registry('shippedorder_import');
    }

}