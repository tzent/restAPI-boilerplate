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
use App\Base\Factories\Oauth2Server;
use OAuth2\Request as OAuth2Request;
use Slim\Http\Request;
use Slim\Http\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Exception\ValidatorException;

class SignIn extends Action implements IAction
{
    /**
     * @Route(
     *     "/signin",
     *     name = "login",
     *     methods = {"POST"}
     * )
     *
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        $oauth_response = Oauth2Server::create($this->container)->handleTokenRequest(OAuth2Request::createFromGlobals());
        if (!$oauth_response->isSuccessful()) {
            throw new ValidatorException(json_encode(['error' => $oauth_response->getParameter('error_description')]), 400);
        }

        return $response->withJson($oauth_response->getParameters());
    }
}