<?php

namespace MageSuite\OrderImport\Controller\Adminhtml\Import;

class Show extends \Magento\Backend\App\Action
{
    protected $resultPageFactory = false;
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * @var \MageSuite\OrderImport\Repository\ImportRepository
     */
    private $importRepository;
    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    private $redirectFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \MageSuite\OrderImport\Repository\ImportRepository $importRepository,
        \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory
    ) {
        parent::__construct($context);

        $this->resultPageFactory = $resultPageFactory;
        $this->registry = $registry;
        $this->importRepository = $importRepository;
        $this->redirectFactory = $redirectFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();

        $importId = $this->getRequest()->getParam('id', 0);

        $import = $this->importRepository->getById($importId);

        $this->registry->register('shippedorder_import', $import);

        $resultPage->getConfig()->getTitle()->prepend(__('Shipped Order Import Log'));

        if(empty($import)) {
            $redirect = $this->redirectFactory->create();
            $redirect->setPath('*/*/index');
            return $redirect;
        }

        return $resultPage;
    }
}
