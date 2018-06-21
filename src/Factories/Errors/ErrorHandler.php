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

class ErrorHandler implements IErrorHandler
{
    /**
     * @param Container $container
     * @return \Closure
     */
    public function __invoke(Container $container)
    {
        return function (Request $request, Response $response, \Throwable $exception) use ($container) {
            $code = $exception->getCode();
            empty($code) && $code = 400;
            $container->get('logger')->error(sprintf('[%d] %s %s', $code, $exception->getMessage(), $exception->getTraceAsString()), [
                'request' => $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'],
                'params'  => file_get_contents('php://input')
            ]);

            return $response
                ->withHeader('Content-Type', 'application/json; charset=utf-8')
                ->withStatus($code)
                ->write($exception->getMessage());
        };
    }
}