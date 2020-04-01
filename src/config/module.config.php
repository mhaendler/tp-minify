<?php
/**
 * TpMinify - third-party module for the Zend Framework 2
 *
 * @category Module
 * @package  TpMinify
 * @author   Kanstantsin A Kamkou (2ka.by)
 * @license  http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link     http://github.com/kkamkou/tp-minify/
 */

use TpMinify\Factory\Controller\IndexControllerFactory;
use TpMinify\Controller\IndexController;
use TpMinify\Factory\View\Helper\HeadScriptFactory;
use TpMinify\View\Helper\HeadScript;

return [
    'router' => [
        'routes' => [
            'TpMinify' => [
                'type' => 'Literal',
                'may_terminate' => true,
                'options' => [
                    'route'    => '/min',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action'     => 'index'
					]
				]
			]
		]
	],
    'controllers' => [
        'factories' => [
        	IndexController::class => IndexControllerFactory::class,
		]
	],
	'view_helpers' => [
		'factories' => [
			HeadScript::class => HeadScriptFactory::class,
		]
	],
    'TpMinify' => [
        'documentRoot' => false,
        'errorLogger' => false,
        'allowDebugFlag' => false,
        'cacheFileLocking' => true,
        'uploaderHoursBehind' => 0,
        'cachePath' => false,
        'symlinks' => [],
        'serveOptions' => [
            'bubbleCssImports' => false,
            'maxAge' => 1800,
            'minApp' => [
                'groupsOnly' => false,
                'groups' => [
                    // your groups list
				]
			]
		],
        'helpers' => [ // $this->headScript()->CaptureStart/CaptureEnd()
            'headScript' => [
                'enabled' => false,
                'options' => [
                    'maxAge' => 86400 // and other serveOptions options
				]
			]
		]
	]
];
