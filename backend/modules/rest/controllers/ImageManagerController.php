<?php

namespace app\modules\rest\controllers;

use app\models\records\ImageManager;
use app\modules\rest\models\PActiveController;
use Yii;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

class ImageManagerController extends PActiveController
{
    public $modelClass = 'app\models\records\ImageManager';
    public $searchModelClass = 'app\models\search\ImageManagerSearch';

    /**
     * Rewrited create action for creating images.
     *
     * @param string $id
     * @throws ServerErrorHttpException
     */

    public function actions()
    {
        $actions = parent::actions();
        $actions['create']['class'] = 'app\modules\rest\models\ImageManagerCreateAction';
        $actions['update']['class'] = 'app\modules\rest\models\ImageManagerUpdateAction';
        return $actions;
    }
}