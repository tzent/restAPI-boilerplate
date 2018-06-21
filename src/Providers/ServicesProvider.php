<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

namespace App\Base\Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServicesProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $folder = ROOT_PATH . 'services' . DIRECTORY_SEPARATOR;
        $file   = $folder . 'app_services.php';
        if (!file_exists($file)) {
            throw new \RuntimeException('File ' . $file . ' is required', 500);
        }

        $services = include_once $file;
        if (!is_array($services)) {
            throw new \RuntimeException($file . ' not contains valid array data', 500);
        }

        $file      = $folder . 'app_' . $container->raw('env') . '_services.php';
        if (file_exists($file)) {
            $custom = include_once $file;
            is_array($custom) && $services = array_merge($services, $custom);
        }

        foreach ($services as $key => $service) {
            $container[$key] = $service($container);
        }
    }
}