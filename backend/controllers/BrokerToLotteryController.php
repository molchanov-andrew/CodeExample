<?php

namespace backend\controllers;

use backend\models\response\AjaxResponse;
use common\models\records\Broker;
use common\models\records\Lottery;
use Yii;
use common\models\records\BrokerToLottery;
use backend\models\search\BrokerToLotterySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BrokerToLotteryController implements the CRUD actions for BrokerToLottery model.
 * @property Broker|null $broker
 * @property Lottery|null $lottery
 */
class BrokerToLotteryController extends Controller
{
    public $broker;
    public $lottery;

    /**
     * @throws NotFoundHttpException
     */
    public function init()
    {
        $parentId = Yii::$app->request->get('parentId',null);
        $parentEntity = Yii::$app->request->get('parentEntity',null);
        if($parentEntity === 'broker'){
            $this->broker = $this->findBrokerModel($parentId);
            $this->view->params['breadcrumbs'][] = ['label' => 'Brokers', 'url' => ['/broker']];
            $this->view->params['breadcrumbs'][] = ['label' => $this->broker->name, 'url' => ['/broker/view','id' => $this->broker->id]];
            $this->view->params['breadcrumbs'][] = ['label' => $this->broker->name . ' to lotteries'];
        } elseif($parentEntity === 'lottery'){
            $this->lottery = $this->findLotteryModel($parentId);
            $this->view->params['breadcrumbs'][] = ['label' => 'Lotteries', 'url' => ['/lottery']];
            $this->view->params['breadcrumbs'][] = ['label' => $this->lottery->name, 'url' => ['/lottery/view','id' => $this->lottery->id]];
            $this->view->params['breadcrumbs'][] = ['label' => 'Brokers to ' . $this->lottery->name];
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        parent::init();
    }

    public function getUniqueId()
    {
        if($this->broker instanceof Broker){
            return "broker/{$this->broker->id}/{$this->id}";
        }

        if($this->lottery instanceof Lottery) {
            return "lottery/{$this->lottery->id}/{$this->id}";
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
     * Lists all BrokerToLottery models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BrokerToLotterySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'parentModel' => $this->_getParentModel(),
        ]);
    }

    /**
     * Displays a single BrokerToLottery model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'parentModel' => $this->_getParentModel(),
        ]);
    }

    /**
     * Creates a new BrokerToLottery model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $brokerId = $this->broker === null ? null : $this->broker->id;
        $lotteryId = $this->lottery === null ? null : $this->lottery->id;

        $model = new BrokerToLottery(['brokerId' => $brokerId, 'lotteryId' => $lotteryId]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $brokers = $this->broker === null ? $this->lottery->getNotRelatedBrokers() : [];
        $lotteries = $this->lottery === null ? $this->broker->getNotRelatedLotteries() : [];

        return $this->render('create', [
            'model' => $model,
            'brokers' => $brokers,
            'lotteries' => $lotteries,
            'parentModel' => $this->_getParentModel(),
        ]);
    }

    /**
     * Updates an existing BrokerToLottery model.
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
        $currentBrokerData = $model->brokerId !== null ? [$model->brokerId] : [];
        $currentLotteryData = $model->lotteryId !== null ? [$model->lotteryId] : [];
        $brokers = $this->broker === null ? $this->lottery->getNotRelatedBrokers($currentBrokerData) : [];
        $lotteries = $this->lottery === null ? $this->broker->getNotRelatedLotteries($currentLotteryData) : [];

        return $this->render('update', [
            'model' => $model,
            'brokers' => $brokers,
            'lotteries' => $lotteries,
            'parentModel' => $this->_getParentModel(),
        ]);
    }

    /**
     * Deletes an existing BrokerToLottery model.
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
     * Finds the BrokerToLottery model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BrokerToLottery the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BrokerToLottery::findOne($id)) !== null) {
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

    public function findLotteryModel($id)
    {
        if ($model = Lottery::findOne($id)) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    private function _getParentModel()
    {
        if($this->broker !== null){
            return $this->broker;
        }
        if($this->lottery !== null){
            return $this->lottery;
        }
        return false;
    }

    public function actionChangeMultiple()
    {
        $model = new BrokerToLottery();
        $model->isNewRecord = false;
        if(Yii::$app->request->post()) {
            $response = BrokerToLottery::changeMultiple(Yii::$app->request->post());
            return $response->render();
        }

        $brokers = $this->broker === null ? $this->lottery->getNotRelatedBrokers() : [];
        $lotteries = $this->lottery === null ? $this->broker->getNotRelatedLotteries() : [];

        return $this->renderAjax('change-multiple', [
            'model' => $model,
            'brokers' => $brokers,
            'lotteries' => $lotteries,
            'parentModel' => $this->_getParentModel(),
        ]);
    }

    public function actionDeleteMultiple()
    {
        if(Yii::$app->request->post()){
            $response = BrokerToLottery::deleteMultiple(Yii::$app->request->post());
        } else {
            $response = new AjaxResponse(['status' => 'error', 'message' => 'No post data']);
        }
        return $response->render();
    }
}
