<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);

include_once ROOT_PATH . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Slim\Container;
use Symfony\Component\Console\Helper\QuestionHelper;

try {

    $container = new Container();
    $container
        ->register(new \App\Base\Providers\ConfigProvider())
        ->register(new \App\Base\Providers\ServicesProvider());

    $helperSet = ConsoleRunner::createHelperSet($container->get('em'));
    $helperSet->set(new QuestionHelper(), 'dialog');

    $cli = ConsoleRunner::createApplication(
        $helperSet,
        [
            new DiffCommand(),
            new ExecuteCommand(),
            new GenerateCommand(),
            new MigrateCommand(),
            new StatusCommand(),
            new VersionCommand()
        ]
    );

    return $cli->run();

} catch (\Throwable $exception) {
    error_log(sprintf('%s %s', $exception->getMessage(), $exception->getTraceAsString()));
    echo $exception->getMessage() . PHP_EOL;
    exit(255);
}