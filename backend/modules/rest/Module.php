<?php

namespace backend\modules\rest;

use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Module as BaseModule;
use yii\rest\UrlRule;

class Module extends BaseModule implements BootstrapInterface
{

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules([
            [
                'class' => UrlRule::class,
                'controller' => [
                    'rest' => '/rest/index',
                ],
                'patterns' => [
                    'PATCH,PUT,OPTIONS' => 'index',
                ]
            ],
        ], false);
    }
}