<?php


/**
 * Class Formatter
 * @package Gracious\Interconnect\Helper
 * Utility class for formatting data and strings to desired format before sending it to the server
 */
class Gracious_Interconnect_Helper_Formatter extends Mage_Core_Helper_Abstract {
    /**
     * @param int|string $ID
     * @param string $entityTypeHandle
     * @param string $merchantHandle
     * @return string
     */
    public function prefixID($ID, $entityTypeHandle, $merchantHandle) {
        $entityTypeHandle = preg_replace('/_/', '-', $entityTypeHandle);

        return strtoupper($merchantHandle) . '-' . strtoupper($entityTypeHandle) . '-' . (string)$ID;
    }

    /**
     * Formats a date string to ISO8601 format
     * @param string $dateString
     * @return string
     */
    public function formatDateStringToIso8601($dateString) {
        if ($dateString === null) {
            return null;
        }

        $dateTime = new DateTime($dateString);

        return $dateTime->format(DateTime::ATOM);
    }

    /**
     * @param string $lastName
     * @param string $prefix
     * @return string
     */
    public function prefixLastName($lastName, $prefix) {
        if (is_string($prefix) && trim($prefix) != '') {
            return $prefix . ' ' . $lastName;
        }

        return $lastName;
    }

    /**
     * @param $text
     * @return mixed
     */
    public function unSnakeCase($text) {
        if ($text === null) {
            return null;
        }

        return preg_replace('/_/', ' ', $text);
    }
}