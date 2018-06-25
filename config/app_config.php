<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

return [
    //prod, test, dev
    'env'      => 'dev',
    'settings' => [
        'httpVersion'                       => '1.1',
        'responseChunkSize'                 => '4096',
        'outputBuffering'                   => 'append',
        'determineRouteBeforeAppMiddleware' => true,
        'displayErrorDetails'               => false,
        'addContentLengthHeader'            => true,
        'routerCacheFile'                   => sprintf('%s%s', ROOT_PATH, implode(DIRECTORY_SEPARATOR, ['storage', 'cache', 'routes.php'])),
        'benchmark'                         => false,
        'logger'                            => [
            'name'  => 'app',
            'level' => Monolog\Logger::DEBUG,
            'path'  => sprintf('%s%s', ROOT_PATH, implode(DIRECTORY_SEPARATOR, ['storage', 'logs', 'app.log'])),
        ],
        'doctrine'                          => [
            'dev_mode'          => false,
            'entities_dir'      => sprintf('%s%s', ROOT_PATH, implode(DIRECTORY_SEPARATOR, ['storage', 'Entities'])),
            'proxies_dir'       => sprintf('%s%s', ROOT_PATH, implode(DIRECTORY_SEPARATOR, ['storage', 'Proxies'])),
            'proxies_namespace' => 'Proxies',
            'connection'        => [
                'driver'        => 'pdo_mysql',
                'host'          => 'localhost',
                'port'          => 3306,
                'dbname'        => '',
                'user'          => '',
                'password'      => '',
                'charset'       => 'utf8',
                'driverOptions' => [
                    1002 => 'SET NAMES utf8'
                ]
            ],
        ],
        'redis'                             => [
            'host'     => 'localhost',
            'port'     => 6379,
            'password' => '',
            'prefix'   => ''
        ],
        'oauth'                            => [
            'token_type'                     => 'Bearer',
            'auth_code_lifetime'             => 30,
            'access_lifetime'                => 3600,
            'refresh_token_lifetime'         => 7200,
            'always_issue_new_refresh_token' => true
        ],
        'translation'                       => [
            'default'        => 'en',
            'resources_path' => sprintf('%s%s', ROOT_PATH, implode(DIRECTORY_SEPARATOR, ['storage', 'translations']))
        ]
    ]
];