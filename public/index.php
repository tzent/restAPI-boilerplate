<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);

include ROOT_PATH . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$server = new \App\Base\Server();
$server();
