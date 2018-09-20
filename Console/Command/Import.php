<?php

namespace MageSuite\OrderImport\Console\Command;

class Import extends \Symfony\Component\Console\Command\Command
{

    /**
     * @var \Magento\Framework\App\State
     */
    private $state;

    /**
     * @var \MageSuite\OrderImport\Services\Import\ImporterTypeFactory
     */
    private $importerTypeFactory;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        \Magento\Framework\App\State $state,
        \MageSuite\OrderImport\Services\Import\ImporterTypeFactory $importerTypeFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        parent::__construct();

        $this->state = $state;
        $this->importerTypeFactory = $importerTypeFactory;
        $this->scopeConfig = $scopeConfig;
    }

    protected function configure()
    {
        $this->addArgument(
            'remote-dir',
            \Symfony\Component\Console\Input\InputArgument::OPTIONAL,
            'Remote dir'
        );

        $this
            ->setName('orderimport:import:manual')
            ->setDescription('Manual run order import');
    }

    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    )
    {
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMIN);

        $remoteDirectory = $input->getArgument('remote-dir');

        $importer = $this->importerTypeFactory->create('default_importer');

        $importer->run(\MageSuite\OrderImport\Model\Import::TYPE_MANUAL, $remoteDirectory);
    }

}
