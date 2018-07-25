<?php

require_once(getcwd() . '/shell/abstract.php');

/**
 * Class Gracious_Interconnect_Console_CommandAbstract
 */
abstract class Gracious_Interconnect_Console_CommandAbstract extends Mage_Shell_Abstract {

    /**
     * @var Gracious_Interconnect_Helper_Config
     */
    protected $config;

    /**
     * @var string[]
     */
    private $options = [];


    public function __construct() {
        parent::__construct();

        $this->parseOptions();
        $this->config = Mage::helper('gracious_interconnect/config');

        return $this;
    }

    protected final function parseOptions() {
        $current = null;

        foreach ($_SERVER['argv'] as $arg) {
            $matches = [];

            if (preg_match('/^\-\-([a-zA-Z]+[a-zA-Z0-9_\-]*)=(.+)$/', $arg, $matches)) {
                if (count($matches) === 3) {
                    $this->options[$matches[1]] = $matches[2];
                }
            }
        }
    }

    /**
     * @param string $key
     * @param null $defaultValue
     * @return null|string
     */
    protected function getOption($key, $defaultValue = null) {
        return isset($this->options[$key]) ? $this->options[$key] : $defaultValue;
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
    protected final function notice($message) {
        $this->write($message, 33);
    }

    /**
     * @param string $message
     */
    protected final function error($message) {
        $this->write($message, 31);
    }

    /**
     * @param string $message
     */
    protected final function alert($message) {
        $this->write($message, 31);
    }

    /**
     * @param string $message
     */
    protected final function emergency($message) {
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
        // Cast to string if it's a numeric type because regex evaluates strings
        $value = is_numeric($value) ? (string)$value : $value;

        if (!is_string($value) || !Gracious_Interconnect_Support_Validation_RegEx::test(Gracious_Interconnect_Support_Validation_RegEx::INT, $value)) {
            throw new Gracious_Interconnect_System_Exception('Expected integer but got ' . gettype($value));
        }
    }
}