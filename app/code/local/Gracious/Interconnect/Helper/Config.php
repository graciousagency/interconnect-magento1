<?php

/**
 * Class Gracious_Interconnect_Helper_Config
 */
class Gracious_Interconnect_Helper_Config extends Mage_Core_Helper_Abstract {
    const XML_PATH_SERVICE_BASE_URL     = 'gracious_interconnect/settings/baseurl';
    const XML_PATH_PREFIX               = 'gracious_interconnect/settings/prefix';
    const XML_PATH_API_KEY              = 'gracious_interconnect/settings/apikey';

    protected $lazyData = [];

    /**
     * Returns the base url for the webservice from the application's main config
     * @return string
     */
    public function getInterconnectServiceBaseUrl() {
        return $this->getLazy(static::XML_PATH_SERVICE_BASE_URL);
    }

    /**
     * Returns the base url for the webservice from the application's main config
     * @return string
     */
    public function getInterconnectPrefix() {
        return $this->getLazy(static::XML_PATH_PREFIX);
    }

    /**
     * @return string
     */
    public function getApiKey() {
        return $this->getLazy(static::XML_PATH_API_KEY);
    }

    /**
     * Lazy loading
     * @param string $xmlPath
     * @return string
     */
    protected function getLazy($xmlPath) {
        if (!isset($this->lazyData[$xmlPath])) {
            $this->lazyData[$xmlPath] = Mage::getStoreConfig($xmlPath);
        }

        return $this->lazyData[$xmlPath];
    }

    /**
     * @return bool
     * Returns whether the required config values are set
     */
    public function isComplete() {
        $serviceBaseUrl = $this->getInterconnectServiceBaseUrl();
        $interconnectPrefix = $this->getInterconnectPrefix();
        $apiKey = $this->getApiKey();

        return (is_string($serviceBaseUrl) && trim($serviceBaseUrl) != '') &&
            (is_string($interconnectPrefix) && trim($interconnectPrefix) != '') &&
            (is_string($apiKey) && trim($apiKey) != '');
    }
}