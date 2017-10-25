<?php

/**
 * Class Gracious_Interconnect_Http_Client
 */
class Gracious_Interconnect_Http_Request_Client extends Zend_Http_Client {
    const ENDPOINT_CUSTOMER                 = 'customer/register';
    const ENDPOINT_NEWSLETTER_SUBSCRIBER    = 'newsletter/subscribe/popup';
    const ENDPOINT_ORDER                    = 'order/process';
    const ENDPOINT_QUOTE                    = 'quote/process';

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var Gracious_Interconnect_Helper_Config
     */
    protected $helperConfig;

    /**
     * Client constructor.
     */
    public function __construct() {
        $this->helperConfig = Mage::helper('gracious_interconnect/config');
        $this->setBaseUrl($this->helperConfig->getInterconnectServiceBaseUrl());

        parent::__construct(null, null);
    }

    /**
     * @param string $baseUrl
     * @return static
     */
    public function setBaseUrl($baseUrl) {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getBaseUrl() {
        return $this->baseUrl;
    }

    /**
     * @param array $data
     * @param string $endPoint
     * @return bool
     * @throws Exception
     */
    public function sendData(array $data, $endPoint) {
        if ($this->baseUrl === null) {
            throw new Exception('Unable to make request: base url not set');
        }

        // add data about the sender.
        $data['app'] = Gracious_Interconnect_Foundation_Environment::getInstance()->toArray();
        $json = json_encode($data);

        $this->setMethod(Zend_Http_Client::POST)
            ->setUri($this->baseUrl . '/' . $endPoint)
            ->setHeaders([
                'Content-Type' => 'application/json',
                'X-Secret' => $this->helperConfig->getApiKey()
            ])
            ->setRawData($json);

        if(Mage::getIsDeveloperMode()) {
            Gracious_Interconnect_Reporting_Log::debug(str_repeat('*****', 30));
            Gracious_Interconnect_Reporting_Log::debug(__METHOD__ . ':: Posting to \'' . $this->baseUrl . '/' . $endPoint . '\'. Data = ' . $json);
        }

        $response = $this->request();

        $this->processResponse($response);
    }

    /**
     * @param Zend_Http_Response $response
     */
    protected function processResponse(Zend_Http_Response $response) {
        $statusCode = $response->getStatus();
        $success = ($statusCode == 200);

        if (!$success) {
            Gracious_Interconnect_Reporting_Log::alert('Response status = ' . $statusCode . ', response = ' . (string)$response);

            throw new Exception('Error making request to \'' . $this->getUri(true) . '\' with http status code :' . $statusCode . ' and response ' . (string)$response);
        }
    }
}