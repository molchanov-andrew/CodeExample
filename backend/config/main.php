<?php

use yii\caching\FileCache;
use backend\modules\translations\controllers\DefaultController;
use common\models\records\User;
use yii\log\FileTarget;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'name' => 'Localotto',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log', 'rest'],
    'modules' => [
        'i18n' => \backend\modules\translations\Module::class,
        'controllerMap' => [
            'default' => [
                'class' => DefaultController::class
            ]
        ],
        'db-manager' => [
            'class' => 'bs\dbManager\Module',
//            'controllerMap' => [
//                'default' => 'app\ttms\db_manager\controllers\DefaultController',
//            ],
            // path to directory for the dumps
            'path' => '@backend/backups/',
            // list of registerd db-components
            'dbList' => ['db'],
        ],
        'rest' => [
            'class' => \backend\modules\rest\Module::class,
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'storage' =>[
            'class' => 'backend\components\Storage'
        ],
        'user' => [
            'identityClass' => User::class,
            'enableAutoLogin' => true,
//            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => array(
                'home' => 'site/index',
                'login' => 'site/login',
                'logout' => 'site/logout',
                '<controller:[\w\-]+>/<id:\d+>' => '<controller>/view',
                '<controller:[\w\-]+>/<action:[\w\-]+>/<id:\d+>' => '<controller>/<action>',
                '<controller:[\w\-]+>/<action:[\w\-]+>' => '<controller>/<action>',
                // Lottery routes
                '<parentEntity:lottery>/<parentId:\d+>/<controller:lottery-result|lottery-timer|broker-to-lottery>/' => '<controller>/index',
                '<parentEntity:lottery>/<parentId:\d+>/<controller:lottery-result|lottery-timer|broker-to-lottery>/<id:\d+>' => '<controller>/view',
                '<parentEntity:lottery>/<parentId:\d+>/<controller:lottery-result|lottery-timer|broker-to-lottery>/<action:[\w\-]+>' => '<controller>/<action>',
                '<parentEntity:lottery>/<parentId:\d+>/<controller:lottery-result|lottery-timer|broker-to-lottery>/<action:[\w\-]+>/<id:\d+>' => '<controller>/<action>',
                // Page routes
                '<parentEntity:page>/<parentId:\d+>/<controller:page-content>/' => '<controller>/index',
                '<parentEntity:page>/<parentId:\d+>/<controller:page-content>/<id:\d+>' => '<controller>/view',
                '<parentEntity:page>/<parentId:\d+>/<controller:page-content>/<action:[\w\-]+>' => '<controller>/<action>',
                '<parentEntity:page>/<parentId:\d+>/<controller:page-content>/<action:[\w\-]+>/<id:\d+>' => '<controller>/<action>',
                // Broker routes
                '<parentEntity:broker>/<parentId:\d+>/<controller:broker-email|broker-phone|broker-to-lottery>/' => '<controller>/index',
                '<parentEntity:broker>/<parentId:\d+>/<controller:broker-email|broker-phone|broker-to-lottery>/<id:\d+>' => '<controller>/view',
                '<parentEntity:broker>/<parentId:\d+>/<controller:broker-email|broker-phone|broker-to-lottery>/<action:[\w\-]+>' => '<controller>/<action>',
                '<parentEntity:broker>/<parentId:\d+>/<controller:broker-email|broker-phone|broker-to-lottery>/<action:[\w\-]+>/<id:\d+>' => '<controller>/<action>',
                // Broker-lottery systematics and discounts routes
                '<parentEntity:broker|lottery>/<parentId:\d+>/<subParentEntity:broker-to-lottery>/<subParentId:\d+>/<controller:systematic|discount>/' => '<controller>/index',
                '<parentEntity:broker|lottery>/<parentId:\d+>/<subParentEntity:broker-to-lottery>/<subParentId:\d+>/<controller:systematic|discount>/<id:\d+>' => '<controller>/view',
                '<parentEntity:broker|lottery>/<parentId:\d+>/<subParentEntity:broker-to-lottery>/<subParentId:\d+>/<controller:systematic|discount>/<action:[\w\-]+>' => '<controller>/<action>',
                '<parentEntity:broker|lottery>/<parentId:\d+>/<subParentEntity:broker-to-lottery>/<subParentId:\d+>/<controller:systematic|discount>/<action:[\w\-]+>/<id:\d+>' => '<controller>/<action>',

            ),
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'frontendCache' => [
            'class' => FileCache::class,
            'cachePath' => Yii::getAlias('@frontend') . '/runtime/cache'
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
];
