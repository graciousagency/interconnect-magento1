<?php

/**
 * Class RegEx
 * @package Gracious\Interconnect\Support
 */
abstract class Gracious_Interconnect_Support_Validation_RegEx {

    /**
     * A positive number of 1 or higher
     */
    const INT   = '/^[1-9]{1}[0-9]*$/';

    /**
     * A digit optionally followed by a period, optionally followed by one or more digits
     * @example 1234 OR: 0 OR: 3. OR: 89.3453 OR: .9. Not valid : 0093434 (leading zeros)
     */
    const FLOAT = '/^\-?(((([1-9]{1}[0-9]*)|0)((\.[0-9]*)|\.)?)|(\.[0-9]+))$/';

    /**
     * A digit optionally followed by a period, optionally followed by one or max 2 digits
     * or a period followed by 1 or 2 digits
     * @example 2. OR: 34.5  OR:.4  OR: .56  OR: 3.67  OR: .03  OR: 0.34
     */
    const DECIMAL = '/^\-?(((([1-9]{1}[0-9]*)|0)((\.[0-9]*)|\.)?)|(\.[0-9]{1,2}))$/';

    /**
     * Intended for money
     * A digit optionally followed by a period or comma, optionally followed by one or max 2 digits
     * or a period or comma followed by 1 or 2 digits
     * @example 2. OR: 34.5  OR:.4  OR: .56  OR: 3.67  OR: .03  OR: 0.34 (periods can be comma's in these examples)
     */
    const AMOUNT = '/^\-?(((([1-9]{1}[0-9]*)|0)(([\.\,]{1}[0-9]{0,2})|([\.\,]{1}))?)|([\.|\,]{1}[0-9]{1,2}))$/';

    /**
     * Precise decimal format : one or more digits followed by a period and two digits
     */
    const DECIMAL_STRICT = '/^\-?(([1-9]{1}[0-9]*)|0)\.[0-9]{2}$/';

    /**
     * A date format YYYY-MM-DD. Separators can be dashes or forward slashed. Months and days must be two digits
     * Only validates the format, not if it's a valid date
     */
    const DATE_YYYY_MM_DD  = '/^([0-9]{2}\-|\/){2}[0-9]{4}$/';


    /**
     * @param string $pattern
     * @param string $value
     * @return string
     */
    public static function test($pattern, $value) {
        return preg_match($pattern, $value) === 1;
    }
}