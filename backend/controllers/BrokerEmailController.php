<?php

namespace backend\controllers;

use backend\models\response\AjaxResponse;
use common\models\records\Broker;
use Yii;
use common\models\records\BrokerEmail;
use backend\models\search\BrokerEmailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BrokerEmailController implements the CRUD actions for BrokerEmail model.
 */
class BrokerEmailController extends Controller
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
     * Lists all BrokerEmail models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BrokerEmailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BrokerEmail model.
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
     * Creates a new BrokerEmail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BrokerEmail(['brokerId' => $this->broker->id]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BrokerEmail model.
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

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BrokerEmail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BrokerEmail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BrokerEmail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BrokerEmail::findOne($id)) !== null) {
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
        $model = new BrokerEmail();
        $model->isNewRecord = false;
        if(Yii::$app->request->post()) {
            $response = BrokerEmail::changeMultiple(Yii::$app->request->post());
            return $response->render();
        }

        return $this->renderAjax('@app/views/ui/change-multiple', [
            'model' => $model,
            'formFile' => '@app/views/broker-email/_form',
        ]);
    }

    public function actionDeleteMultiple()
    {
        if(Yii::$app->request->post()){
            $response = BrokerEmail::deleteMultiple(Yii::$app->request->post());
        } else {
            $response = new AjaxResponse(['status' => 'error', 'message' => 'No post data']);
        }
        return $response->render();
    }
}
