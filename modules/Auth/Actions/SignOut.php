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
use App\Base\Security\Annotations\Granted;
use Entities\RefreshTokens;
use Slim\Http\Request;
use Slim\Http\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/signout",
 *     name = "logout",
 *     methods = {"GET"}
 * )
 * @Granted
 */
class SignOut extends Action implements IAction
{
    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return mixed
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        $em             = $this->container->get('em');
        $access_token   = $this->container->get('token');
        $refresh_tokens = $em->getRepository(RefreshTokens::class)->findBy([
            'user'   => $access_token->getUser(),
            'client' => $access_token->getClient()
        ]);

        $em->remove($access_token);

        foreach ($refresh_tokens as $r_token) {
            $em->remove($r_token);
        }

        $em->flush();

        return $response->withJson(null, 204);
    }
}