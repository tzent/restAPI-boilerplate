<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

namespace App\Base\Middlewares;

use App\Base\Security\Guard;
use Slim\Http\Request;
use Slim\Http\Response;

class Firewall extends Middleware
{
    public function __invoke(Request $request, Response $response, callable $next)
    {
        /** @var $route \Slim\Route */
        $route = $request->getAttribute('route');
        if (!is_null($route)) {
            (new Guard($this->container))->granted(new \ReflectionClass($route->getCallable()));
        }

        return $next($request, $response);
    }
}