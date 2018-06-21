<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

namespace App\Base\Middlewares;

use App\Base\Helpers\AnnotationsRoute;
use App\Base\Helpers\Arr;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

class Route extends Middleware
{
    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return mixed
     * @throws NotFoundException
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \ReflectionException
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        /** @var $route \Slim\Route */
        $route = $request->getAttribute('route');
        if (!is_null($route)) {
            $uri         = $request->getUri();
            $annotations = (new AnnotationsRoute($this->container))->read(new \ReflectionClass($route->getCallable()));

            //check schemes
            $schemes = Arr::get($annotations, 'schemes', []);
            if (!empty($schemes) && !in_array($uri->getScheme(), $schemes)) {
                throw new NotFoundException($request, $response);
            }

            //check host
            $host = Arr::get($annotations, 'host');
            if (!empty($host) && $host !== $uri->getHost()) {
                throw new NotFoundException($request, $response);
            }

            //check condition
            $condition = Arr::get($annotations, 'condition');
            if (!empty($condition) && is_string($condition) && !eval($condition)) {
                throw new NotFoundException($request, $response);
            }

            //add defaults
            $route->setArguments(array_merge(Arr::get($annotations, 'defaults', []), $route->getArguments()));
        }

        return $next($request, $response);
    }
}