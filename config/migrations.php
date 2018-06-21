<?php
/**
 *
 *
 * @package RestAPI
 * @author Tzvetelin Tzvetkov
 * @copyright 2018 Tzvetelin Tzvetkov
 */

return [
    'name'                 => 'Rest API Database Migrations',
    'migrations_namespace' => 'Storage\Migrations',
    'table_name'           => 'migrations',
    'migrations_directory' => sprintf('%s%s', ROOT_PATH, implode(DIRECTORY_SEPARATOR, ['storage', 'Migrations']))
];