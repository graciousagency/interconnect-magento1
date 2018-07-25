<?php
/**
 * Class Gracious_Interconnect_Observer_ObserverAbstract
 */
abstract class Gracious_Interconnect_Observer_ObserverAbstract {
    /**
     * @var Gracious_Interconnect_Helper_Config
     */
    protected $config;

    public function __construct() {
        $this->config = Mage::helper('gracious_interconnect/config');
    }
}