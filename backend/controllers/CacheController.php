<?php

namespace backend\controllers;

use backend\models\response\AjaxResponse;
use Yii;
use yii\web\Controller;

class CacheController extends Controller
{
    public function actionFlush()
    {
        Yii::$app->frontendCache->flush();
        $response = new AjaxResponse(['message' => 'Cache flushed.']);
        return $response->render();
    }
}