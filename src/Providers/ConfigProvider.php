<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

namespace App\Base\Providers;


use App\Base\Helpers\Arr;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ConfigProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     */
    public function register(Container $container)
    {
        $folder = ROOT_PATH . 'config' . DIRECTORY_SEPARATOR;
        $file   = $folder . 'app_config.php';
        if (!file_exists($file)) {
            throw new \RuntimeException('File ' . $file . ' is required', 500);
        }

        $config = include_once $file;
        if (!is_array($config)) {
            throw new \RuntimeException($file . ' not contains valid array data', 500);
        }

        $file = $folder . 'app_' . Arr::get($config, 'env', '') . '_config.php';
        if (file_exists($file)) {
            $custom = include_once $file;
            is_array($custom) && $config = Arr::mergeRecursive($config, $custom);
        }

        foreach ($config as $key => $param) {
            $container->offsetSet($key, $param);
        }
    }
}