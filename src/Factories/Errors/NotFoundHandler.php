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
use Slim\Http\Request;
use Slim\Http\Response;

class NotFoundHandler implements IErrorHandler
{
    /**
     * @param Container $container
     * @return \Closure
     */
    public function __invoke(Container $container)
    {
        return function (Request $request, Response $response, \Throwable $exception = null) use ($container) {
            return $response->withStatus(is_null($exception) || empty($exception->getCode()) ? 404 : $exception->getCode());
        };
    }
}