<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

namespace App\Base\Providers;


use App\Base\Action\IAction;
use App\Base\Helpers\Arr;
use App\Base\Helpers\AnnotationsRoute;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Routing\Annotation\Route;

class RoutesProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \ReflectionException
     */
    public function register(Container $container)
    {
        $rotes_file = Arr::get($container->offsetGet('settings'), 'routerCacheFile');

        if (!file_exists($rotes_file)) {
            $this->routes($container);
        }
    }

    /**
     * @param Container $container
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \ReflectionException
     */
    private function routes(Container $container)
    {
        $creator = new AnnotationsRoute($container);

        foreach ($this->getModuleFiles() as $file) {
            if ($file->isFile()) {
                preg_match('#(namespace)(\\s+)([A-Za-z0-9\\\\]+?)(\\s*);#sm', file_get_contents($file->getRealPath()), $matches);
                $namespace = Arr::get($matches, 3);

                if (!empty($namespace)) {
                    $reflector = new \ReflectionClass(sprintf('%s\%s', $namespace, $file->getBasename('.php')));
                    if ($reflector->isInstantiable() && $reflector->implementsInterface(IAction::class)) {
                        $creator->addAnnotationRoute($reflector);
                    }
                }
            }
        }
    }

    /**
     * @return \RecursiveIteratorIterator
     */
    private function getModuleFiles()
    {
        return new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                sprintf('%smodules%s', ROOT_PATH, DIRECTORY_SEPARATOR),
                \RecursiveDirectoryIterator::KEY_AS_FILENAME | \RecursiveDirectoryIterator::CURRENT_AS_FILEINFO
            )
        );
    }
}