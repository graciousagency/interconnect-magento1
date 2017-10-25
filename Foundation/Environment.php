<?php

/**
 * Class Gracious_Interconnect_Foundation_Environment
 */
class Gracious_Interconnect_Foundation_Environment implements JsonSerializable {

    /**
     * @var static
     */
    private static $instance;

    /**
     * @var string
     */
    protected $moduleVersion;

    /**
     * @var string
     */
    protected $magentoVersion;

    /**
     * @var string
     */
    protected $domain;

    /**
     * Gracious_Interconnect_Foundation_Environment constructor.
     */
    private function __construct() {
        $this->moduleVersion = static::detectModuleVersion();
        $this->magentoVersion = Mage::getVersion();
        $domain = preg_replace('/^https?:\/\//', '', Mage::getBaseUrl (Mage_Core_Model_Store::URL_TYPE_WEB));
        $domain = rtrim($domain, '/');
        $this->domain = $domain;
    }

    /**
     * @return null|string
     */
    public function getModuleVersion() {
        return $this->moduleVersion;
    }

    /**
     * @return string
     */
    public function getMagentoVersion() {
        return $this->magentoVersion;
    }

    /**
     * @return mixed|string
     */
    public function getDomain() {
        return $this->domain;
    }

    /**
     * @return array
     */
    public function toArray() {
        return [
            'moduleVersion'     => $this->moduleVersion,
            'magentoVersion'    => $this->magentoVersion,
            'domain'            => $this->domain
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() {
        return json_encode($this->toArray());
    }







    /* S T A T I C  M E T H O D S * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    /**
     * @return static
     */
    public static function getInstance() {
        if(null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @return null|string
     */
    public static function detectModuleVersion() {
        $composerFileHandle = __DIR__.DS.'..'.DS.'composer.json';

        if(!file_exists($composerFileHandle) || !is_readable($composerFileHandle)) {
            return null;
        }

        $data = json_decode(file_get_contents($composerFileHandle));

        if(!isset($data->version)) {
            return null;
        }

        return $data->version;
    }

}