<?php
/**
 * Created by PhpStorm.
 * User: user5
 * Date: 3/3/18
 * Time: 3:49 PM
 */

namespace app\modules\rest\models;


use Yii;
use yii\helpers\Url;
use yii\rest\UpdateAction;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

class ImageManagerUpdateAction extends UpdateAction
{
    public function run($id)
    {
        $model = $this->findModel($id);

        $oldImageFileName = $model->getImagePathPrivate();
        $bodyParams = Yii::$app->getRequest()->getBodyParams();
        // No form for model here, so 2nd parameter empty string.
        $model->load($bodyParams, '');

        if (isset($bodyParams['file']) && !empty($bodyParams['file']) && $model->saveFile($bodyParams['file'])) {

            // Remove old file.
            if (file_exists($oldImageFileName)) {
                unlink($oldImageFileName);
            }
        } elseif($model->save()){

        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        $model->absoluteUrl = $model->getAbsoluteUrl();
        return $model;
    }
}