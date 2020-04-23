<?php
namespace backend\modules\translations\controllers;

use backend\models\response\AjaxResponse;
use backend\modules\translations\models\search\SourceMessageSearch;
use common\models\records\SourceMessage;
use Yii;
use yii\base\Model;

class DefaultController extends \vintage\i18n\controllers\DefaultController
{
    public function actionIndex()
    {
        $searchModel = new SourceMessageSearch;
        $dataProvider = $searchModel->search(Yii::$app->getRequest()->get());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionUpdate($id)
    {
        /** @var SourceMessage $model */
        $model = $this->findModel($id);
        $model->initMessages();
        if (Model::loadMultiple($model->messages, Yii::$app->getRequest()->post()) && Model::validateMultiple($model->messages)) {
            $model->saveMessages();
            if(Yii::$app->request->post('fromAjax',null) !== null){
                $response = new AjaxResponse(['message' => 'Translation updated.']);
                return $response->render();
            } else {
                Yii::$app->getSession()->setFlash('success', 'Translation updated');
            }
        } else {
            if(Yii::$app->request->isAjax){
                return $this->renderAjax('update-ajax', ['model' => $model]);
            }
        }
        return $this->render('@vendor/vintage/yii2-i18n/src/views/default/update', ['model' => $model]);
    }
}