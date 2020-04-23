<?php

namespace backend\controllers;

use backend\models\response\AjaxResponse;
use common\models\records\Broker;
use common\models\records\BrokerToLottery;
use common\models\records\Lottery;
use Yii;
use common\models\records\Systematic;
use backend\models\search\SystematicSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SystematicController implements the CRUD actions for Systematic model.
 * @property BrokerToLottery|null $brokerToLottery
 */
class SystematicController extends Controller
{
    public $parentEntity;
    public $brokerToLottery;

    /**
     * @throws NotFoundHttpException
     */
    public function init()
    {
        $parentId = Yii::$app->request->get('parentId',null);
        $this->parentEntity = Yii::$app->request->get('parentEntity',null);
        $subParentId = Yii::$app->request->get('subParentId',null);
        $subParentEntity = Yii::$app->request->get('subParentEntity',null);
        if($subParentEntity === 'broker-to-lottery'){
            $this->brokerToLottery = $this->findBrokerToLottery($subParentId);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if($this->parentEntity === 'broker'){
            $this->view->params['breadcrumbs'][] = ['label' => 'Brokers', 'url' => ["/{$this->parentEntity}"]];
            $this->view->params['breadcrumbs'][] = ['label' => $this->brokerToLottery->broker->name, 'url' => ['/broker/view','id' => $this->brokerToLottery->broker->id]];
        } elseif($this->parentEntity === 'lottery'){
            $this->view->params['breadcrumbs'][] = ['label' => 'Lotteries', 'url' => ["/{$this->parentEntity}"]];
            $this->view->params['breadcrumbs'][] = ['label' => $this->brokerToLottery->lottery->name, 'url' => ['/lottery/view','id' => $this->brokerToLottery->lottery->id]];
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $this->view->params['breadcrumbs'][] = ['label' => 'Broker-to-lottery', 'url' => ["/{$this->parentEntity}/{$parentId}/{$subParentEntity}"]];
        $this->view->params['breadcrumbs'][] = ['label' => "{$this->brokerToLottery->lottery->name} on {$this->brokerToLottery->broker->name}", 'url' => ["/{$this->parentEntity}/{$parentId}/{$subParentEntity}/view",'id' => $this->brokerToLottery->id]];

        parent::init();
    }

    public function getUniqueId()
    {
        if($this->brokerToLottery instanceof BrokerToLottery){
            $uniqueId = '';
            if($this->parentEntity === 'broker'){
                $uniqueId .= "broker/{$this->brokerToLottery->lottery->id}";
            }
            if($this->parentEntity === 'lottery') {
                $uniqueId .= "lottery/{$this->brokerToLottery->lottery->id}";
            }
            $uniqueId .= "/broker-to-lottery/{$this->brokerToLottery->id}/{$this->id}";
            return $uniqueId;
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
     * Lists all Systematic models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SystematicSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Systematic model.
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
     * Creates a new Systematic model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Systematic(['brokerToLotteryId' => $this->brokerToLottery->id]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Systematic model.
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
     * Deletes an existing Systematic model.
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
     * Finds the Systematic model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Systematic the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Systematic::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function findBrokerToLottery($id)
    {
        if ($model = BrokerToLottery::find()->andWhere(['id' => $id])->with(['broker','lottery'])->one()) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionChangeMultiple()
    {
        $model = new Systematic();
        $model->isNewRecord = false;
        if(Yii::$app->request->post()) {
            $response = Systematic::changeMultiple(Yii::$app->request->post());
            return $response->render();
        }

        return $this->renderAjax('@app/views/ui/change-multiple', [
            'model' => $model,
            'formFile' => '@app/views/systematic/_form',
        ]);
    }

    public function actionDeleteMultiple()
    {
        if(Yii::$app->request->post()){
            $response = Systematic::deleteMultiple(Yii::$app->request->post());
        } else {
            $response = new AjaxResponse(['status' => 'error', 'message' => 'No post data']);
        }
        return $response->render();
    }
}
