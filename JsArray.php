<?php

namespace phpdoc;

/**
 * Class JsArray
 */
class JsArray
{
    public static function jsStr($s)
    {
        return '"' . addcslashes($s, "\0..\37\"\\") . '"';
    }

    public static function jsArray($array)
    {
        $temp = array_map('self::jsStr', $array);
        return '[' . implode(',', $temp) . ']';
    }
}