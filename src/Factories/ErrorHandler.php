<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

namespace App\Base\Factories;


class ErrorHandler
{
    /**
     * @param string $type
     * @return mixed
     * @throws \ReflectionException
     */
    public static function create($type)
    {
        $class           = sprintf('%s\Errors\%s', __NAMESPACE__, ucfirst($type));
        $reflectionClass = new \ReflectionClass($class);
        if (!$reflectionClass->isInstantiable()) {
            throw new \RuntimeException(sprintf('Class %s not exists.', $class), 500);
        }

        return $reflectionClass->newInstance();
    }
}