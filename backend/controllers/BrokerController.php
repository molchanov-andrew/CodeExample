<?php

namespace backend\controllers;

use backend\models\response\AjaxResponse;
use common\models\records\Bonus;
use common\models\records\BrokerStatus;
use common\models\records\Image;
use common\models\records\Language;
use common\models\records\PaymentMethod;
use Yii;
use common\models\records\Broker;
use backend\models\search\BrokerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BrokerController implements the CRUD actions for Broker model.
 */
class BrokerController extends Controller
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
     * Lists all Broker models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BrokerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $statuses = BrokerStatus::find()->all();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Displays a single Broker model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Broker model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Broker();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $images = Image::find()->brokers()->all();
        $statuses = BrokerStatus::find()->all();
        $bonuses = Bonus::find()->all();
        $languages = Language::find()->all();
        $paymentMethods = PaymentMethod::find()->all();
        return $this->render('create', [
            'model' => $model,
            'images' => $images,
            'statuses' => $statuses,
            'bonuses' => $bonuses,
            'languages' => $languages,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    /**
     * Updates an existing Broker model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $images = Image::find()->brokers()->all();
        $statuses = BrokerStatus::find()->all();
        $bonuses = Bonus::find()->all();
        $languages = Language::find()->all();
        $paymentMethods = PaymentMethod::find()->all();
        return $this->render('update', [
            'model' => $model,
            'images' => $images,
            'statuses' => $statuses,
            'bonuses' => $bonuses,
            'languages' => $languages,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    /**
     * Deletes an existing Broker model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Broker model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Broker the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Broker::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionChangeMultiple()
    {
        $model = new Broker();
        $model->isNewRecord = false;
        if(Yii::$app->request->post()) {
            $response = Broker::changeMultiple(Yii::$app->request->post());
            return $response->render();
        }

        $images = Image::find()->brokers()->all();
        $statuses = BrokerStatus::find()->all();
        $bonuses = Bonus::find()->all();
        $languages = Language::find()->all();
        $paymentMethods = PaymentMethod::find()->all();
        return $this->renderAjax('change-multiple', [
            'model' => $model,
            'images' => $images,
            'statuses' => $statuses,
            'bonuses' => $bonuses,
            'languages' => $languages,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    public function actionDeleteMultiple()
    {
        if(Yii::$app->request->post()){
            $response = Broker::deleteMultiple(Yii::$app->request->post());
        } else {
            $response = new AjaxResponse(['status' => 'error', 'message' => 'No post data']);
        }
        return $response->render();
    }
}
