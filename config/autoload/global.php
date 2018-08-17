<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

use Zend\Http\Client;
use Doctrine\Common\Persistence\Mapping\Driver as MappingDriver;
use Doctrine\DBAL\Driver\Mysqli\Driver as MysqliDriver;

return [
    'client' => [
        'options' => [
            'adapter' => Client\Adapter\Curl::class,
        ],
    ],
    'doctrine' => [
        'configuration' => [
            'orm_default' => [
                'driver' => 'orm_default_chain',
            ],
        ],
        'driver'        => [
            'orm_default_static_php' => [
                'class' => MappingDriver\StaticPHPDriver::class,
                'paths' => [
                    __DIR__ . '/../../module/Application/src/Model/Entity',
                ],
            ],
            'orm_default_chain'      => [
                'class'   => MappingDriver\MappingDriverChain::class,
                'drivers' => [
                    'Application\Model\Entity' => 'orm_default_static_php',
                ],
            ],
        ],
    ],
];
