<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

namespace App\Base;


use App\Base\Middlewares\Benchmark;
use App\Base\Middlewares\CORS;
use App\Base\Middlewares\Firewall;
use App\Base\Middlewares\Route;
use App\Base\Providers\ConfigProvider;
use App\Base\Providers\RoutesProvider;
use App\Base\Providers\ServicesProvider;
use Slim\App;
use Slim\Container;
use Slim\Http\Response;

class Server
{
    /**
     * @var App
     */
    private $app;

    /**
     * @return void
     */
    public function __invoke()
    {
        $container = new Container();
        $this->app = new App($container);

        try {
            $container
                ->register(new ConfigProvider())
                ->register(new ServicesProvider())
                ->register(new RoutesProvider());

            $this->app
                ->add(new Benchmark($container))
                ->add(new CORS($container))
                ->add(new Route($container))
                ->add(new Firewall($container));

            $this->app->run();
        } catch (\Throwable $e) {
            error_log(sprintf(
                '%s %s [request: %s][params: %s]',
                $e->getMessage(),
                $e->getTraceAsString(),
                $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'],
                file_get_contents('php://input')
            ));

            $this->app->respond(new Response(!empty($e->getCode()) ?: 500));
        }
    }
}