<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

namespace App\Base\Factories\Errors;


use Slim\Container;

interface IErrorHandler
{
    public function __invoke(Container $container);
}