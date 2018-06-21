<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

namespace App\Base\Helpers;


class Hydrator
{
    /**
     * @param $object
     * @param array $params
     * @return mixed
     */
    public static function fromArray($object, array $params)
    {
        foreach ($params as $property => $value) {
            $method = sprintf('set%s', str_replace('_', '', ucwords($property, '_')));
            if (method_exists($object, $method)) {
                $object->$method($value);
            } else if (property_exists($object, $property)) {
                $object->{$property} = $value;
            }
        }

        return $object;
    }
}