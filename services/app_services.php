<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

use App\Base\Factories\Cache as CacheFactory;
use App\Base\Factories\EntityManager as EntityManagerFactory;
use App\Base\Factories\ErrorHandler as ErrorHandlerFactory;
use App\Base\Factories\Logger as LoggerFactory;
use App\Base\Factories\Translator as TranslatorFactory;
use App\Base\Factories\Validator as ValidatorFactory;
use Slim\Container;

return [
    'logger'            => function (Container $container) {
        return LoggerFactory::create($container);
    },
    'cache'             => function (Container $container) {
        return CacheFactory::create($container);
    },
    'em'                => function (Container $container) {
        return EntityManagerFactory::create($container);
    },
    'translator'        => function (Container $container) {
        return TranslatorFactory::create($container);
    },
    'validator'         => function (Container $container) {
        return ValidatorFactory::create($container);
    },
    // errors exceptions
    'notAllowedHandler' => function (Container $container) {
        return ErrorHandlerFactory::create('notAllowedHandler');
    },
    'notFoundHandler'   => function (Container $container) {
        return ErrorHandlerFactory::create('notFoundHandler');
    },
    'errorHandler'      => function (Container $container) {
        return ErrorHandlerFactory::create('errorHandler');
    },
    'phpErrorHandler'   => function (Container $container) {
        return ErrorHandlerFactory::create('phpErrorHandler');
    }
];