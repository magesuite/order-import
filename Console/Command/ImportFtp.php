<?php

namespace MageSuite\OrderImport\Console\Command;

class ImportFtp extends \Symfony\Component\Console\Command\Command
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
            'remote-dir',
            \Symfony\Component\Console\Input\InputArgument::OPTIONAL,
            'Remote dir'
        );

        $this
            ->setName('order:import:ftp_manual')
            ->setDescription('Manual run order import from FTP');
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

        $remoteDirectory = $input->getArgument('remote-dir');

        $importer = $this->importTypeFactory->create($this->importTypeName);
        $configuration = new \Magento\Framework\DataObject(['remote_directory' => $remoteDirectory]);

        $importer->run($configuration);
    }

}
