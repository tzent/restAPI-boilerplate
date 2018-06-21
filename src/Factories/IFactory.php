<?php
/**
 * @package UNO
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

namespace App\Base\Factories;


use Slim\Container;

interface IFactory
{
    public static function create(Container $container);
}