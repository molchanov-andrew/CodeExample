<?php

use yii\i18n\PhpMessageSource;
use yii\caching\FileCache;

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(__DIR__, 2) . '/vendor',
    'components' => [
        'cache' => [
            'class' => FileCache::class,
        ],
        'i18n' => [
            'class'=> common\components\I18N::class,
            'sourceMessageTable' => 'SourceMessage',
            'messageTable' => 'Message',
            'translations' => [
                '*' => [
                    'class' => \backend\modules\translations\models\DbMessageSource::class,
                    'enableCaching' => true,
                    'sourceLanguage' => 'en-US',
                    'sourceMessageTable' => 'SourceMessage',
                    'messageTable' => 'Message',
                    'on missingTranslation' => [backend\modules\translations\Module::class, 'missingTranslation'],
                ],
                'yii' => [
                    'class' => PhpMessageSource::class,
                    'sourceLanguage' => 'en-US',
                    'basePath' => '@yii/messages',
                ],
            ],
            'enableCaching' => true,
            'cachingDuration' => 3600,
        ],
        'pageData' => [
            'class' => \common\components\PageData::class,
        ],
        'resultData' => [
            'class' => \common\components\ResultData::class,
        ],
        'imageManager' => [
            'class' => \common\components\ImageManager::class,
            //set media path (outside the web folder is possible)
            'mediaPath' => '/images',
            //path relative web folder to store the cache images
            'cachePath' => 'assets/images',
            //use filename (seo friendly) for resized images else use a hash
            'useFilename' => true,
            //show full url (for example in case of a API)
            'absoluteUrl' => false,
        ],
    ],
];
