<?php

namespace MageSuite\OrderImport\Console\Command;

class ImportParcelLabApi extends \Symfony\Component\Console\Command\Command
{

    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \MageSuite\OrderImport\Services\Import\ImportType\ImportTypeFactory
     */
    protected $importTypeFactory;

    /**
     * @var string
     */
    protected $importTypeName;

    public function __construct(
        \Magento\Framework\App\State $state,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \MageSuite\OrderImport\Services\Import\ImportType\ImportTypeFactory $importTypeFactory,
        $importTypeName = null
    )
    {
        parent::__construct();

        $this->state = $state;
        $this->importTypeFactory = $importTypeFactory;
        $this->scopeConfig = $scopeConfig;

        $this->importTypeName = $importTypeName;
    }

    protected function configure()
    {
        $this->addArgument(
            'increment-id',
            \Symfony\Component\Console\Input\InputArgument::OPTIONAL,
            'Order Increment Id'
        );

        $this
            ->setName('order:import:parcellab_api')
            ->setDescription('Manual run order import from ParcelLab API');
    }

    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    )
    {
        try {
            $this->state->getAreaCode();
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);
        }

        $incrementId = $input->getArgument('increment-id');

        $importer = $this->importTypeFactory->create($this->importTypeName);
        $configuration = new \Magento\Framework\DataObject(['increment_id' => $incrementId]);

        $importer->run($configuration);
    }

}
