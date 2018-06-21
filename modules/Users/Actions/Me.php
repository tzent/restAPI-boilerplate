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
use Slim\Http\Request;
use Slim\Http\Response;

class Me extends Action
{
    public function __invoke(Request $request, Response $response, $args)
    {
        return $response->withJson(['msg' => 'Tove e s ' . $request->getMethod()]);
    }
}