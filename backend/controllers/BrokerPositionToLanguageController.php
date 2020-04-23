<?php

namespace backend\controllers;

use common\models\records\Language;
use Yii;
use common\models\records\BrokerPositionToLanguage;
use backend\models\search\BrokerPositionToLanguageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BrokerPositionToLanguageController implements the CRUD actions for BrokerPositionToLanguage model.
 */
class BrokerPositionToLanguageController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all BrokerPositionToLanguage models.
     * @return mixed
     * @throws \yii\db\Exception
     */
    public function actionIndex()
    {
        if(Yii::$app->request->post() && Yii::$app->request->post('savePositions',0) !== 0){
            $data = Yii::$app->request->post('BrokerPositionToLanguage');
            $languageId = reset($data)['languageId'];
            BrokerPositionToLanguage::updatePositions($data,$languageId);
        }

        $searchModel = new BrokerPositionToLanguageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $languages = Language::find()->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'languages'=> $languages,
        ]);
    }



    /**
     * Finds the BrokerPositionToLanguage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $brokerId
     * @param integer $languageId
     * @return BrokerPositionToLanguage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($brokerId, $languageId)
    {
        if (($model = BrokerPositionToLanguage::findOne(['brokerId' => $brokerId, 'languageId' => $languageId])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
