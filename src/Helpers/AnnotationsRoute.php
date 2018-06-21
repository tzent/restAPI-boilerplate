<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

namespace App\Base\Helpers;


use Slim\Container;
use Symfony\Component\Routing\Annotation\Route;

class AnnotationsRoute
{
    /**
     * @var Container
     */
    private $container;

    /**
     * Annotation constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param \ReflectionClass $reflector
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \ReflectionException
     */
    public function addAnnotationRoute(\ReflectionClass $reflector)
    {
        $parts = $this->read($reflector);
        $route = $this->container->get('router')->map($parts['methods'], $parts['path'], $reflector->getName());
        if (is_callable([$route, 'setContainer'])) {
            $route->setContainer($this->container);
        }

        if (is_callable([$route, 'setOutputBuffering'])) {
            $route->setOutputBuffering($this->container->get('settings')['outputBuffering']);
        }

        $name = Arr::get($parts, 'name');
        if (!empty($name)) {
            $route->setName($name);
        }
    }

    /**
     * @param \ReflectionClass $reflector
     * @return array
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \ReflectionException
     */
    public function read(\ReflectionClass $reflector)
    {
        /** @var @var $reader \Doctrine\Common\Annotations\Reader */
        $reader            = $this->container->get('em')->getConfiguration()->getMetadataDriverImpl()->getReader();
        $class_annotations = $reader->getClassAnnotations($reflector);
        empty($class_annotations) && $class_annotations = [new Route([])];

        $method_annotations = $reader->getMethodAnnotations(
            new \ReflectionMethod($reflector->getName(), '__invoke')
        );
        empty($method_annotations) && $method_annotations = [new Route([])];

        return $this->merge(reset($class_annotations), reset($method_annotations));
    }

    /**
     * @param Route $group
     * @param Route $method
     * @return array
     */
    private function merge(Route $group, Route $method)
    {
        $group  = array_filter($this->toArray($group));
        $method = array_filter($this->toArray($method));
        $path   = sprintf(
            '%s/%s',
            rtrim(Arr::get($group, 'path', ''), '/'),
            ltrim(Arr::get($method, 'path', ''), '/')
        );

        //add requirements
        $requirements = array_merge(Arr::get($group, 'requirements', []), Arr::get($method, 'requirements', []));
        foreach ($requirements as $placeholder => $rule) {
            $path = str_replace(
                sprintf('{%s}', $placeholder),
                sprintf('{%s:%s}', $placeholder, $rule),
                $path
            );
        }

        //add options
        $optional = '';
        $options  = array_merge(Arr::get($group, 'options', []), Arr::get($method, 'options', []));
        foreach (array_reverse($options, true) as $option) {
            $optional = sprintf('[/{%s}%s]', $option, $optional);
        }
        $place = strpos($path, ']');
        if ($place !== false) {
            $path = substr_replace($path, $optional . ']', $place, 1);
        } else {
            $path = sprintf('%s%s', $path, $optional);
        }

        return [
            'path'         => $path,
            'name'         => Arr::get($method, 'name', Arr::get($group, 'name')),
            'requirements' => $requirements,
            'options'      => $options,
            'defaults'     => array_merge(Arr::get($group, 'defaults', []), Arr::get($method, 'defaults', [])),
            'host'         => Arr::get($method, 'host', Arr::get($group, 'host')),
            'methods'      => Arr::get($method, 'methods', Arr::get($group, 'methods'), ['GET']),
            'schemes'      => Arr::get($method, 'schemes', Arr::get($group, 'schemes'), []),
            'condition'    => Arr::get($method, 'condition', Arr::get($group, 'condition'))
        ];
    }

    /**
     * @param Route $route
     * @return array
     */
    private function toArray(Route $route)
    {
        return [
            'path'         => $route->getPath(),
            'name'         => $route->getName(),
            'requirements' => $route->getRequirements(),
            'options'      => $route->getOptions(),
            'defaults'     => $route->getDefaults(),
            'host'         => $route->getHost(),
            'methods'      => $route->getMethods(),
            'schemes'      => $route->getSchemes(),
            'condition'    => $route->getCondition()
        ];
    }
}