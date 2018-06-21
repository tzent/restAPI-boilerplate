<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

namespace App\Base\Action;

use Slim\Http\Request;
use Slim\Http\Response;

interface IAction
{
    public function __invoke(Request $request, Response $response, $args);
}