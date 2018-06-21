<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

namespace App\Base\Middlewares;


use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

abstract class Middleware
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * Middleware constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    abstract public function __invoke(Request $request, Response $response, callable $next);
}