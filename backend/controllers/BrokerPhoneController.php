<?php

namespace backend\controllers;

use backend\models\response\AjaxResponse;
use common\models\records\Broker;
use common\models\records\Country;
use Yii;
use common\models\records\BrokerPhone;
use backend\models\search\BrokerPhoneSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BrokerPhoneController implements the CRUD actions for BrokerPhone model.
 */
class BrokerPhoneController extends Controller
{
    public $broker;

    public function init()
    {
        $this->broker = $this->findBrokerModel(Yii::$app->request->get('parentId',null));
        $this->view->params['breadcrumbs'][] = ['label' => 'Brokers', 'url' => ['/broker']];
        $this->view->params['breadcrumbs'][] = ['label' => $this->broker->name, 'url' => ['/broker/view','id' => $this->broker->id]];
        parent::init();
    }

    public function getUniqueId()
    {
        if($this->broker instanceof Broker){
            return "broker/{$this->broker->id}/{$this->id}";
        }
        return parent::getUniqueId();
    }
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
     * Lists all BrokerPhone models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BrokerPhoneSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BrokerPhone model.
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
     * Creates a new BrokerPhone model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BrokerPhone(['brokerId' => $this->broker->id]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $countries = Country::find()->all();
        return $this->render('create', [
            'model' => $model,
            'countries' => $countries,
        ]);
    }

    /**
     * Updates an existing BrokerPhone model.
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

        $countries = Country::find()->all();
        return $this->render('update', [
            'model' => $model,
            'countries' => $countries,
        ]);
    }

    /**
     * Deletes an existing BrokerPhone model.
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
     * Finds the BrokerPhone model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BrokerPhone the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BrokerPhone::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findBrokerModel($id)
    {
        if (($model = Broker::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionChangeMultiple()
    {
        $model = new BrokerPhone();
        $model->isNewRecord = false;
        if(Yii::$app->request->post()) {
            $response = BrokerPhone::changeMultiple(Yii::$app->request->post());
            return $response->render();
        }
        $countries = Country::find()->all();
        return $this->renderAjax('change-multiple', [
            'model' => $model,
            'countries' => $countries,
        ]);
    }

    public function actionDeleteMultiple()
    {
        if(Yii::$app->request->post()){
            $response = BrokerPhone::deleteMultiple(Yii::$app->request->post());
        } else {
            $response = new AjaxResponse(['status' => 'error', 'message' => 'No post data']);
        }
        return $response->render();
    }
}
