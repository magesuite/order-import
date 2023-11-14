<?php

namespace MageSuite\OrderImport\Block\Adminhtml\Import;

class Grid extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_orderimport';
        $this->_blockGroup = 'MageSuite_OrderImport';

        parent::_construct();

        $this->_headerText = __('Shipped Orders Import Logs');
        $this->removeButton('add');
    }

}
