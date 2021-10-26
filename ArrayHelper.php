<?php

namespace phpdoc;

/**
 * Class ArrayHelper
 */
class ArrayHelper
{
    private const SET_VALUE_SET = 0;
    private const SET_VALUE_PUSH = 1;
    private const SET_VALUE_MERGE = 2;

    /**
     * @param array $array
     * @param string $keyOrPath 0-9n(numeric key), 'key', 'path.to.nested.level'
     * @return array|mixed
     */
    public static function getValue(array $array, string $keyOrPath)
    {
        if (is_numeric($keyOrPath)) {
            return $array[(int) $keyOrPath];
        } elseif (is_string($keyOrPath)) {
            $path = (array) explode('.', $keyOrPath);
            $destination = &$array;
            foreach ($path as $key) {
                if (is_array($destination) && array_key_exists($key, $destination)) {
                    $destination = &$destination[$key];
                } else {
                    return null;
                }
            }

            return $destination;
        }

        throw new \InvalidArgumentException('Value of property "keyOrPath" must be of type string or int');
    }

    /**
     * @param array $array
     * @param string $keyOrPath 0-9n(numeric key), 'key', 'path.to.nested.level'
     * @param $value
     * @return array|mixed previous value
     */
    public static function setValue(array &$array, string $keyOrPath, $value)
    {
        return self::setByPath($array,  $keyOrPath, $value);
    }

    /**
     * @param array $array
     * @param string $keyOrPath 0-9n(numeric key), 'key', 'path.to.nested.level'
     * @param $value
     * @return array|mixed previous value
     */
    public static function mergeValue(array &$array, string $keyOrPath, $value)
    {
        return self::setByPath($array,  $keyOrPath, $value, self::SET_VALUE_MERGE);
    }

    /**
     * @param array $array
     * @param string $keyOrPath 0-9n(numeric key), 'key', 'path.to.nested.level'
     * @param $value
     * @return array|mixed previous value
     */
    public static function pushValue(array &$array, string $keyOrPath, $value)
    {
        return self::setByPath($array,  $keyOrPath, $value, self::SET_VALUE_PUSH);
    }

    /**
     * @param array $array
     * @param string $keyOrPath
     * @param $value
     * @param bool $merge
     * @return array|mixed
     */
    private static function setByPath(array &$array, string $keyOrPath, $value, int $action = self::SET_VALUE_SET)
    {
        $mergeClosure = function (&$destination, $value, int $action) {
            switch ($action) {
                case self::SET_VALUE_PUSH:
                    $destination = (array)$destination;
                    $destination[] = $value;
                    break;
                case self::SET_VALUE_MERGE:
                    $destination = array_merge($destination, (array) $value);
                    break;
                case self::SET_VALUE_SET:
                default:
                    $destination = $value;
                    break;
            }
        };

        if (is_numeric($keyOrPath)) {
            $old = $array[(int) $keyOrPath];
            $mergeClosure($array[(int) $keyOrPath], $value, $action);

            return $old;
        }  elseif (is_string($keyOrPath)) {
            $path = (array) explode('.', $keyOrPath);
            $destination = &$array;
            foreach ($path as $key) {
                if (isset($destination) && !is_array($destination)) {
                    $destination = array();
                }

                $destination = &$destination[$key];
            }

            $old = $destination;
            $mergeClosure($destination, $value, $action);

            return $old;
        }

        throw new \InvalidArgumentException('Value of property "keyOrPath" must be of type string or int');
    }
}