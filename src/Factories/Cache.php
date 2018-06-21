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
use Slim\Container;

class Cache implements IFactory
{
    public static function create(Container $container)
    {
        $settings = Arr::get($container->get('settings'), 'redis');
        if (empty($settings)) {
            throw new \RuntimeException('Redis configuration not found');
        }

        $redis = new \Redis();
        $redis->connect(
            Arr::get($settings, 'host', 'localhost'),
            Arr::get($settings, 'port', 6379)
        );

        $redis->auth(Arr::get($settings, 'password'));
        $redis->setOption(\Redis::OPT_PREFIX, Arr::get($settings, 'prefix', ''));

        return $redis;
    }
}