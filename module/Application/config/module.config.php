<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Controller\Plugin\Dates;
use Application\Factory\BaseFactory;
use Application\Factory\DownloaderFactory;
use Application\Factory\WebPageFactory;
use Application\Model\EventsTable;
use Application\Model\WebPage;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'log'             => [
        'MyLogger' => [
            'writers'    => [
                'syslog' => [
                    'name'     => 'syslog',
                    'priority' => \Zend\Log\Logger::ALERT,
                    'options'  => [
                        'formatter' => [
                            'name'    => \Zend\Log\Formatter\Simple::class,
                            'options' => [
                                'format'         => '%timestamp% %priorityName% (%priority%): %message% %extra%',
                                'dateTimeFormat' => 'r',
                            ],
                        ],
                        'filters'   => [
                            'priority' => [
                                'name'    => 'priority',
                                'options' => [
                                    'operator' => '<=',
                                    'priority' => \Zend\Log\Logger::INFO,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'processors' => [
                'backtrace' => [
                    'name' => \Zend\Log\Processor\Backtrace::class,
                ],
            ],
        ],
    ],
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'restful'     => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/api',
                    'defaults' => [
                        'controller' => Controller\DownloaderController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\DownloaderController::class => DownloaderFactory::class
        ],
    ],
    'controller_plugins' => [
        'factories' => [
            Dates::class => InvokableFactory::class,
        ],
        'aliases' => [
            'dates' => Dates::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'service_manager' => [
        'abstract_factories' => [
            \Zend\Log\LoggerAbstractServiceFactory::class,
        ],
        'factories'          => [
            EventsTable::class => BaseFactory::class,
            WebPage::class => WebPageFactory::class,
        ],
        'aliases'            => [
            WebPageFactory::class => WebPage::class,
            BaseFactory::class => EventsTable::class,
        ],
    ],
];
