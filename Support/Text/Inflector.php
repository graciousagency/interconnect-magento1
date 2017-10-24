<?php


abstract class Gracious_Interconnect_Support_Text_Inflector
{
    /**
     * @param $text
     * @return mixed
     */
    public static function unSnakeCase($text) {
        if($text === null) {
            return null;
        }

        return preg_replace('/_/', ' ', $text);
    }
}