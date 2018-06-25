<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

namespace App\Modules\Users\Actions;


use App\Base\Action\Action;
use App\Base\Action\IAction;
use App\Base\Security\Annotations\Granted;
use Slim\Http\Request;
use Slim\Http\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class Me
 *
 * @Route(
 *     "/me",
 *     name = "me",
 *     methods = {"GET"}
 * )
 * @Granted
 */
class Me extends Action implements IAction
{
    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return static
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        return $response->withJson(['user' => $this->container->get('token')->getUser()->toArray()]);
    }
}