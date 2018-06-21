<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

namespace App\Base\Factories;

use App\Base\Helpers\Arr;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;
use Slim\Container;

class Logger implements IFactory
{
    public static function create(Container $container)
    {
        $settings = Arr::get($container->get('settings'), 'logger');
        if (empty($settings)) {
            throw new \RuntimeException('Monolog configuration not found');
        }

        $logger = new MonologLogger(Arr::get($settings, 'name', 'app'));
        $logger->pushHandler(
            new StreamHandler(
                Arr::get($settings, 'path'),
                Arr::get($settings, 'level', MonologLogger::DEBUG)
            )
        );

        return $logger;
    }
}