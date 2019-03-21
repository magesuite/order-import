<?php

namespace MageSuite\OrderImport\Services\Import\ImportType;


class ParcelLabApi implements \MageSuite\OrderImport\Api\ImportTypeInterface
{

    const TYPE = 'ParcelLab API';

    const REQUEST_TIMEOUT = 30;
    const PARCELLAB_API_URL = 'https://api.parcellab.com/v2/checkpoints';

    const CONFIG_PARCELLAB_USER_ID_PATH = 'orderimport/parcellab/user_id';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $http;

    /**
     * @var \MageSuite\OrderImport\Services\Import\Importer\ImporterFactory
     */
    protected $importerFactory;

    /**
     * @var \MageSuite\OrderImport\Repository\OrderRepository
     */
    protected $orderRepository;

    /**
     * @var \MageSuite\OrderImport\Repository\ImportRepository
     */
    protected $importRepository;

    /**
     * @var string
     */
    protected $importerName;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \MageSuite\OrderImport\Services\Import\Importer\ImporterFactory $importerFactory,
        \MageSuite\OrderImport\Repository\OrderRepository $orderRepository,
        \MageSuite\OrderImport\Repository\ImportRepository $importRepository,
        $importerName = null
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->importerFactory = $importerFactory;
        $this->orderRepository = $orderRepository;
        $this->importRepository = $importRepository;

        $this->importerName = $importerName;

        $this->http = new \GuzzleHttp\Client([
            'timeout' => self::REQUEST_TIMEOUT,
            'allow_redirects' => true,
            'http_errors' => false,
        ]);
    }

    public function run($configuration)
    {
        if (empty($configuration->getIncrementId())) {
            return false;
        }

        $import = $this->importRepository->create();

        $parcelLabUserId = $this->scopeConfig->getValue(self::CONFIG_PARCELLAB_USER_ID_PATH);
        $params = ['u' => $parcelLabUserId, 'orderNo' => $configuration->getIncrementId()];

        $response = $this->http->get(self::PARCELLAB_API_URL, [
            'query' => $params,
            'timeout' => self::REQUEST_TIMEOUT
        ]);

        if ($response->getStatusCode() != 200) {
            $import->setResultFailed(self::TYPE, sprintf('API issue: %s', $response->getBody()->getContents()));
            $this->importRepository->save($import);

            return false;
        }

        $order = $this->orderRepository->getOrdersByIncrementIds($configuration->getIncrementId());

        $importer = $this->importerFactory->create($this->importerName);
        $result = $importer->import($order);

        $import->setResult(self::TYPE, $result);
        $this->importRepository->save($import);
    }

}