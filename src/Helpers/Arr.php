<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

namespace App\Base\Helpers;


class Arr
{
    /**
     * @param array $array
     * @param string|int $key
     * @param mixed|null $default
     * @return mixed|null
     */
    public static function get($array, $key, $default = null)
    {
        if ($array instanceof \ArrayObject) {
            return $array->offsetExists($key) ? $array->offsetGet($key) : $default;
        } else {
            return isset($array[$key]) ? $array[$key] : $default;
        }
    }

    /**
     * @param array $merged
     * @param array $array
     * @return array
     */
    public static function mergeRecursive(array $merged, array $array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value) && isset($merged[$key])) {
                $merged[$key] = self::mergeRecursive($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}