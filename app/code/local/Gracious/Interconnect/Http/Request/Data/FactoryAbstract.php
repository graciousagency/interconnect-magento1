<?php
/**
 * Class FactoryAbstract
 * @package Gracious\Interconnect\Http\Request\Data
 */
abstract class Gracious_Interconnect_Http_Request_Data_FactoryAbstract {

    /**
     * @var Gracious_Interconnect_Helper_Config
     */
    protected $config;

    /**
     * FactoryAbstract constructor.
     */
    public function __construct() {
        $this->config = Mage::helper('interconnect/config');
    }

    /**
     * @param int|string $id
     * @param string $entityPrefix
     * @return string
     * @throws Gracious_Interconnect_System_Exception
     * The exception throwing is necessary because the id must be unique and thus complete. We really want to let
     * the calling class know when that fails.
     */
    protected final function generateEntityId($id, $entityPrefix) {
        if ($id === null || trim($id) == '') {
            // Throw an exception because formatting a unique handle is a critical step
            throw new Gracious_Interconnect_System_InvalidArgumentException('Unable to format prefixed ID: invalid entity id!');
        }

        if (!is_string($entityPrefix) || trim($entityPrefix) == '') {
            // Throw an exception because formatting a unique handle is a critical step
            throw new Gracious_Interconnect_System_InvalidArgumentException('Unable to format prefixed ID: invalid entity prefix!');
        }

        $prefix = $this->config->getInterconnectPrefix();

        if (!is_string($prefix) || trim($prefix) == '') {
            // Throw an exception because formatting a unique handle is a critical step
            throw new Gracious_Interconnect_System_Exception('Unable to format prefixed ID: Interconnect handle not set!');
        }

        return Mage::helper('interconnect/formatter')->prefixID($id, $entityPrefix, $prefix);
    }
}