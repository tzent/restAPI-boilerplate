<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

namespace App\Modules\Auth\Actions;


use App\Base\Action\Action;
use App\Base\Action\IAction;
use App\Base\Helpers\Arr;
use Doctrine\ORM\EntityManager;
use Entities\Users;
use Slim\Http\Request;
use Slim\Http\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Exception\ValidatorException;

class SignUp extends Action implements IAction
{
    const GROUP = 'signup';

    /**
     * @var array
     */
    private $body_params = [];

    /**
     * @Route(
     *     "/signup",
     *     name = "registration",
     *     methods = {"POST"}
     * )
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->body_params = $request->getParsedBody();
        $user              = Users::fromArray($this->body_params);
        $this->validate($user, self::GROUP);
        $user->encryptPassword();

        /** @var $em EntityManager */
        $em = $this->container->get('em');
        $em->persist($user);
        $em->flush();

        return $response->withStatus(201);
    }

    /**
     * @param $entity
     * @param array|string $groups
     * @throws \Interop\Container\Exception\ContainerException
     */
    protected function validate($entity, $groups)
    {
        parent::validate($entity, $groups);

        if (strcmp(Arr::get($this->body_params, 'password'), Arr::get($this->body_params, 'confirm_password'))) {
            throw new ValidatorException(json_encode(['password' => $this->container->get('translator')->trans('user.password.not_confirmed')]), 400);
        }

        if (!is_null($this->container->get('em')->getRepository(Users::class)->findOneByEmail($entity->getEmail()))) {
            throw new ValidatorException(json_encode(['email' => $this->container->get('translator')->trans('user.email.not_unique')]), 400);
        }
    }
}