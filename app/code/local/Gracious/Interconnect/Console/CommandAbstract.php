<?php

require_once(getcwd() . '/shell/abstract.php');

/**
 * Class Gracious_Interconnect_Console_CommandAbstract
 */
abstract class Gracious_Interconnect_Console_CommandAbstract extends Mage_Shell_Abstract {

    public function __construct() {
        parent::__construct();

        return $this;
    }

    /**
     * @param string $message
     */
    protected final function line($message) {
        $this->write($message, 37);
    }

    /**
     * @param string $message
     */
    protected final function info($message) {
        $this->write($message, 37);
    }

    /**
     * @param string $message
     */
    protected final function error($message) {
        $this->write($message, 31);
    }

    /**
     * @param $message
     */
    private function write($message, $colorCode) {
        $STDOUT = fopen("php://stdout", "w");
        fwrite($STDOUT, "\033[" . $colorCode . "m" . $message . "\033[0m\r\n");
        fclose($STDOUT);
    }

    /**
     * @param mixed $value
     * @throws Gracious_Interconnect_System_Exception
     */
    protected function evalInt($value) {
        if (!is_numeric($value)) {
            throw new Gracious_Interconnect_System_Exception('Expected integer but got ' . gettype($value));
        }
    }
}