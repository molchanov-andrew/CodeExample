<?php
/**
 * Created by PhpStorm.
 * User: user5
 * Date: 2/28/18
 * Time: 4:15 PM
 */

namespace app\modules\rest\models;


use app\models\records\ImageManager;
use Yii;
use yii\helpers\Url;
use yii\rest\CreateAction;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

class ImageManagerCreateAction extends CreateAction
{
    public function run()
    {
        $model = new ImageManager();
        // No form for model here, so 2nd parameter empty string.
        $bodyParams = Yii::$app->getRequest()->getBodyParams();
        $model->load($bodyParams, '');
        $model->isNewRecord = true;

        if ($model->saveWithFile($bodyParams['file'])) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', array_values($model->getPrimaryKey(true)));
            $response->format = Response::FORMAT_JSON;
            $response->getHeaders()->set('Location', Url::toRoute(['view', 'id' => $id], true));
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        $model->absoluteUrl = $model->getAbsoluteUrl();
        return $model;
    }
}