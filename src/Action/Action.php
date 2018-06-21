<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

namespace App\Base\Action;


use App\Base\Helpers\Str;
use Slim\Container;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validation;

abstract class Action
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * Action constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param $entity
     * @param string|array $groups
     * @throws \Interop\Container\Exception\ContainerException
     */
    protected function validate($entity, $groups)
    {
        !is_array($groups) && $groups = [$groups];
        /** @var $violations Validation */
        $violations = $this->container->get('validator')->validate($entity, null, $groups);
        if (count($violations)) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[Str::camelCaseToUnderscore($violation->getPropertyPath())] = $violation->getMessage();
            }
            throw new ValidatorException(json_encode($errors), 400);
        }
    }
}