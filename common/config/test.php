<?php

use common\models\User;

return [
    'id' => 'app-common-tests',
    'basePath' => dirname(__DIR__),
    'components' => [
        'user' => [
            'class' => \yii\web\User::class,
            'identityClass' => User::class,
        ],
        'request' => [
            'cookieValidationKey' => 'test',
        ],
    ],
];
