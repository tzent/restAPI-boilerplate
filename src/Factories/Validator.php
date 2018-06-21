<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

namespace App\Base\Factories;

use Slim\Container;
use Symfony\Component\Validator\Validation;

class Validator implements IFactory
{
    /**
     * @param Container $container
     * @return \Symfony\Component\Validator\Validator\ValidatorInterface
     * @throws \Interop\Container\Exception\ContainerException
     */
    public static function create(Container $container)
    {
        return Validation::createValidatorBuilder()
            ->setTranslator($container->get('translator'))
            ->enableAnnotationMapping($container->get('em')->getConfiguration()->getMetadataDriverImpl()->getReader())
            ->getValidator();
    }
}