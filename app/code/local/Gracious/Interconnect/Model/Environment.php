<?php

/**
 * Class Gracious_Interconnect_Model_Environment
 */
class Gracious_Interconnect_Model_Environment implements JsonSerializable
{

    /**
     * @var static
     */
    private static $instance;

    /**
     * @var string
     */
    protected $magentoVersion;

    /**
     * @var string
     */
    protected $domain;

    /**
     * Gracious_Interconnect_Model_Environment constructor.
     */
    private function __construct()
    {
        $this->magentoVersion = Mage::getVersion();
        $domain = preg_replace('/^https?:\/\//', '', Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB));
        $domain = rtrim($domain, '/');
        $this->domain = $domain;
    }

    /**
     * @return static
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @return string
     */
    public function getMagentoVersion()
    {
        return $this->magentoVersion;
    }

    /**
     * @return mixed|string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'magentoVersion' => $this->magentoVersion,
            'domain' => $this->domain
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return json_encode($this->toArray());
    }

}