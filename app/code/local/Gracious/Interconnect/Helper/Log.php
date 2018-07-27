<?php

/**
 * Class Gracious_Interconnect_Helper_Log
 * Adapter that adds more verbosity and formatting to the logging.
 */
class Gracious_Interconnect_Helper_Log extends Gracious_Interconnect_Helper_Data
{

    /**
     * @param string $message
     */
    public function debug($message) {
        Mage::log($message, Zend_Log::DEBUG);
    }

    /**
     * @param string $message
     */
    public function notice($message) {
        Mage::log($message, Zend_Log::NOTICE);
    }

    /**
     * @param string $message
     */
    public function error($message) {
        Mage::log($message, Zend_Log::ERR);
    }

    /**
     * @param string $message
     */
    public function info($message) {
        Mage::log($message, Zend_Log::INFO);
    }

    /**
     * @param string $message
     */
    public function alert($message) {
        Mage::log($message, Zend_Log::ALERT);
    }

    /**
     * @param Exception $exception
     * @param array $args
     */
    public function exception(Throwable $exception) {
        if(Mage::getIsDeveloperMode()) {
            Mage::log('*** EXCEPTION ' . str_repeat('*****', 20), Zend_Log::CRIT);
            Mage::log('*** Type: ' . get_class($exception), Zend_Log::CRIT);
            Mage::log('*** File: ' . $exception->getFile(), Zend_Log::CRIT);
            Mage::log('*** Line: ' . $exception->getLine(), Zend_Log::CRIT);
            Mage::log('*** Message: ' . $exception->getMessage(), Zend_Log::CRIT);
            Mage::log('*** Trace:  ' . $exception->getTraceAsString(), Zend_Log::CRIT);
        } else {
            Mage::log($exception->getMessage(), Zend_Log::CRIT);
        }
    }
}