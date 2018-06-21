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

class CORS extends Middleware
{
    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return mixed
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $methods = [];
        $route   = $request->getAttribute("route");

        if (!is_null($route)) {
            $pattern = $route->getPattern();

            foreach ($this->container->get('router')->getRoutes() as $r) {
                if ($pattern === $r->getPattern()) {
                    $methods = array_merge_recursive($methods, $r->getMethods());
                }
            }
        } else {
            $methods[] = $request->getMethod();
        }

        $response = $next($request, $response);

        $serverParams = $request->getServerParams();
        $origin       = Arr::get($serverParams, 'HTTP_ORIGIN');
        $referer      = Arr::get($serverParams, 'HTTP_REFERER');

        if (empty($origin) && !empty($referer)) {
            $host   = parse_url($referer);
            $origin = sprintf('%s%s%s', $host['scheme'], $host['host'], (!empty($host['port']) ? ':' . $host['port'] : ''));
        }

        return $response
            ->withHeader('Access-Control-Allow-Origin', $origin)
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Origin, Accept, Authorization')
            ->withHeader("Access-Control-Allow-Methods", implode(',', $methods));
    }
}