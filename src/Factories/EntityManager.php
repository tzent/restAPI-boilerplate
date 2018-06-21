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
use App\Base\Helpers\EnvironmentEnum;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\RedisCache;
use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManager as ORMEntityManager;
use Doctrine\ORM\Tools\Setup;
use Gedmo\SoftDeleteable\Filter\SoftDeleteableFilter;
use Gedmo\SoftDeleteable\SoftDeleteableListener;
use Gedmo\Timestampable\TimestampableListener;
use Slim\Container;

class EntityManager implements IFactory
{
    /**
     * @param Container $container
     * @return ORMEntityManager
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Interop\Container\Exception\ContainerException
     */
    public static function create(Container $container)
    {
        $settings = Arr::get($container->get('settings'), 'doctrine');
        if (empty($settings)) {
            throw new \RuntimeException('Doctrine ORM configuration not found');
        }

        $is_dev = !strcmp($container->raw('env'), EnvironmentEnum::DEV);

        if (!$is_dev) {
            $cache = new RedisCache();
            $cache->setRedis($container->get('cache'));
        }

        $loader = require sprintf('%s%s', ROOT_PATH, implode(DIRECTORY_SEPARATOR, ['vendor', 'autoload.php']));
        AnnotationRegistry::registerLoader([$loader, 'loadClass']);

        $config = Setup::createAnnotationMetadataConfiguration([
            Arr::get($settings, 'entities_dir')
        ],
            $is_dev,
            Arr::get($settings, 'proxies_dir'),
            (isset($cache) ? $cache : null),
            false);

        $config->setProxyNamespace(Arr::get($settings, 'proxies_namespace'));
        $config->addFilter('soft-deleteable', SoftDeleteableFilter::class);

        $em = ORMEntityManager::create(
            Arr::get($settings, 'connection'),
            $config,
            self::createEventManager($config->getMetadataDriverImpl()->getReader()));

        $em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
        $em->getFilters()->enable('soft-deleteable');

        return $em;
    }

    private static function createEventManager(CachedReader $reader)
    {
        $eventManager = new EventManager();

        //Timestampable
        $timestampableListener = new TimestampableListener();
        $timestampableListener->setAnnotationReader($reader);
        $eventManager->addEventSubscriber($timestampableListener);

        //SoftDeleteable
        $softDeleteableListener = new SoftDeleteableListener();
        $softDeleteableListener->setAnnotationReader($reader);
        $eventManager->addEventSubscriber($softDeleteableListener);

        return $eventManager;
    }
}