<?php

namespace backend\controllers;

use common\models\records\Language;
use Yii;
use common\models\records\LotteryPositionToLanguage;
use backend\models\search\LotteryPositionToLanguageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LotteryPositionToLanguageController implements the CRUD actions for LotteryPositionToLanguage model.
 */
class LotteryPositionToLanguageController extends Controller
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
     * Lists all LotteryPositionToLanguage models.
     * @return mixed
     * @throws \yii\db\Exception
     */
    public function actionIndex()
    {
        if(Yii::$app->request->post() && Yii::$app->request->post('savePositions',0) !== 0){
            $data = Yii::$app->request->post('LotteryPositionToLanguage');
            $languageId = reset($data)['languageId'];
            LotteryPositionToLanguage::updatePositions($data,$languageId);
        }

        $searchModel = new LotteryPositionToLanguageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $languages = Language::find()->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'languages'=> $languages,
        ]);
    }

    /**
     * Finds the LotteryPositionToLanguage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $lotteryId
     * @param integer $languageId
     * @return LotteryPositionToLanguage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($lotteryId, $languageId)
    {
        if (($model = LotteryPositionToLanguage::findOne(['lotteryId' => $lotteryId, 'languageId' => $languageId])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
