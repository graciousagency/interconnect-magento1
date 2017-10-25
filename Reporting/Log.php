<?php

/**
 * Class Gracious_Interconnect_Reporting_Log
 * Adapter that adds more verbosity and formatting to the logging.
 */
abstract class Gracious_Interconnect_Reporting_Log {

    /**
     * @param string $message
     */
    public static function debug($message) {
        Mage::log($message, Zend_Log::DEBUG);
    }

    /**
     * @param string $message
     */
    public static function notice($message) {
        Mage::log($message, Zend_Log::NOTICE);
    }

    /**
     * @param string $message
     */
    public static function error($message) {
        Mage::log($message, Zend_Log::ERR);
    }

    /**
     * @param string $message
     */
    public static function info($message) {
        Mage::log($message, Zend_Log::INFO);
    }

    /**
     * @param string $message
     */
    public static function warning($message) {
        Mage::log($message, Zend_Log::WARN);
    }

    /**
     * @param string $message
     */
    public static function alert($message) {
        Mage::log($message, Zend_Log::ALERT);
    }

    /**
     * @param string $message
     */
    public static function emergency($message) {
        Mage::log($message, Zend_Log::EMERG);
    }

    /**
     * @param string $message
     */
    public static function critical($message) {
        Mage::log($message, Zend_Log::CRIT);
    }

    /**
     * @param Exception $exception
     * @param array $args
     */
    public static function exception(Throwable $exception) {
        Mage::log('*** EXCEPTION ' . str_repeat('*****', 20), Zend_Log::CRIT);
        Mage::log('*** Type: ' . get_class($exception), Zend_Log::CRIT);
        Mage::log('*** File: ' . $exception->getFile(), Zend_Log::CRIT);
        Mage::log('*** Line: ' . $exception->getLine(), Zend_Log::CRIT);
        Mage::log('*** Message: ' . $exception->getMessage(), Zend_Log::CRIT);
        Mage::log('*** Trace:  ' . $exception->getTraceAsString(), Zend_Log::CRIT);
    }
}