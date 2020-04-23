<?php

namespace backend\controllers;

use common\models\records\Lottery;
use Yii;
use common\models\records\LotteryTimer;
use backend\models\search\LotteryTimerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LotteryTimerController implements the CRUD actions for LotteryTimer model.
 */
class LotteryTimerController extends Controller
{
    public $lottery;

    public function init()
    {
        $this->lottery = $this->findLotteryModel(Yii::$app->request->get('parentId',null));
        $this->view->params['breadcrumbs'][] = ['label' => 'Lotteries', 'url' => ['/lottery']];
        $this->view->params['breadcrumbs'][] = ['label' => $this->lottery->name, 'url' => ['/lottery/view','id' => $this->lottery->id]];
        parent::init();
    }

    public function getUniqueId()
    {
        if($this->lottery instanceof Lottery){
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
     * Lists all LotteryTimer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LotteryTimerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'lottery' => $this->lottery,
        ]);
    }

    /**
     * Displays a single LotteryTimer model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        return $this->render('view', [
            'model' => $this->findModel($id),
            'lottery' => $this->lottery,
        ]);
    }

    /**
     * Creates a new LotteryTimer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LotteryTimer(['lotteryId' => $this->lottery->id]);

        if ($model->load(Yii::$app->request->post()) && $model->saveMultiple()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'lottery' => $this->lottery,
        ]);
    }

    /**
     * Updates an existing LotteryTimer model.
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
            'lottery' => $this->lottery,
        ]);
    }

    /**
     * Deletes an existing LotteryTimer model.
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
     * Finds the LotteryTimer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LotteryTimer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LotteryTimer::findOne($id)) !== null) {
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
}
