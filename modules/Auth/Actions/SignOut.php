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
use Slim\Http\Request;
use Slim\Http\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignOut extends Action
{
    /**
     * @Route(
     *     "/logout",
     *     name = "logout"
     *     methods = {"GET"}
     * )
     */
    public function __invoke(Request $request, Response $response, $args)
    {

    }
}