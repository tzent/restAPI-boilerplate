<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

namespace App\Base\Middlewares;


use App\Base\Helpers\Arr;
use Slim\Http\Request;
use Slim\Http\Response;

class Benchmark extends Middleware
{
    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return Response
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $start = microtime(true);

        $response = $next($request, $response);

        if (Arr::get($this->container->get('settings'), 'benchmark', false)) {
            $this->container->get('logger')->info(sprintf('Script time: %.6f', (microtime(true) - $start)), [
                'request' => sprintf('%s %s://%s%s', $_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_SCHEME'], $_SERVER['SERVER_NAME'], $_SERVER['REQUEST_URI']),
                'params'  => file_get_contents('php://input')
            ]);
        }

        return $response;
    }
}