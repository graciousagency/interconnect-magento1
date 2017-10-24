<?php
/**
 * Created by PhpStorm.
 * User: justinvanschaick
 * Date: 24/10/2017
 * Time: 11:33
 */

abstract class Gracioust_Interconnect_Model_ObserverAbstract
{
    /**
     * @var Gracious_Interconnect_Helper_Config
     */
    protected $config;

    public function __construct()
    {
        $this->helperConfig = Mage::helper('gracious_interconnect/config');
    }
}