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
        $this->helperConfig = Mage::helper('interconnect/config');
        $this->setBaseUrl($this->helperConfig->getInterconnectServiceBaseUrl());

        parent::__construct(null, null);
    }

    /**
     * @param string $baseUrl
     * @return static
     */
    public function setBaseUrl($baseUrl) {
        $this->baseUrl = rtrim($baseUrl, '/');

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
     * @throws Zend_Http_Client_Exception
     * @throws Gracious_Interconnect_System_Exception
     */
    public function sendData(array $data, $endPoint) {
        if ($this->baseUrl === null) {
            throw new Gracious_Interconnect_System_Exception('Unable to make request: base url not set');
        }

        if(Mage::getIsDeveloperMode()) {
            // Overcome ssl problems on local machine
            Mage::helper('interconnect/log')->notice(__METHOD__.'=> Local machine; disabling ssl checks...');
            $curlAdapter = new Zend_Http_Client_Adapter_Curl();
            $curlAdapter->setCurlOption(CURLOPT_SSL_VERIFYPEER, false);
            $curlAdapter->setCurlOption(CURLOPT_SSL_VERIFYHOST, false);
            $this->setAdapter($curlAdapter);
        }

        $metaData = Gracious_Interconnect_Model_Environment::getInstance();
        $json = json_encode($data);

        $this->setMethod(Zend_Http_Client::POST)
            ->setUri($this->baseUrl . '/' . $endPoint)
            ->setHeaders([
                'Content-Type'      => 'application/json',
                'X-Secret'          => $this->helperConfig->getApiKey(),
                'X-AppHandle'       => 'magento1',
                'X-AppVersion'      => $metaData->getMagentoVersion(),
                'X-Domain'          => $metaData->getDomain()
            ])
            ->setRawData($json);

        if(Mage::getIsDeveloperMode()) {
            Mage::helper('interconnect/log')->debug(str_repeat('*****', 30));
            Mage::helper('interconnect/log')->debug(__METHOD__ . ':: Posting to \'' . $this->baseUrl . '/' . $endPoint . '\'. Data = ' . $json);
        }

        $response = $this->request();

        $this->processResponse($response);
    }

    /**
     * @param Zend_Http_Response $response
     * @throws Gracious_Interconnect_System_Exception
     */
    protected function processResponse(Zend_Http_Response $response) {
        $statusCode = $response->getStatus();
        $success = ($statusCode == 200);

        if (!$success) {
            Mage::helper('interconnect/log')->alert('Response status = ' . $statusCode . ', response = ' . (string)$response);

            throw new Gracious_Interconnect_System_Exception('Error making request to \'' . $this->getUri(true) . '\' with http status code :' . $statusCode . ' and response ' . (string)$response);
        }

        if(Mage::getIsDeveloperMode()) {
            Mage::helper('interconnect/log')->info('Data sent to: '.$this->getUri(true) .'. All done here...');
        }
    }
}