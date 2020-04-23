<?php

namespace backend\controllers;

use backend\models\response\AjaxResponse;
use common\models\records\Lottery;
use Yii;
use common\models\records\LotteryResult;
use backend\models\search\LotteryResultSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LotteryResultController implements the CRUD actions for LotteryResult model.
 * @property null|Lottery $lottery
 */
class LotteryResultController extends Controller
{
    public $lottery;

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
     * @throws NotFoundHttpException
     */
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
     * Lists all LotteryResult models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LotteryResultSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'lottery' => $this->lottery,
        ]);
    }

    /**
     * Displays a single LotteryResult model.
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
     * Creates a new LotteryResult model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LotteryResult(['lotteryId' => $this->lottery->id]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'lottery' => $this->lottery,
        ]);
    }

    /**
     * Updates an existing LotteryResult model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $lotteryList = Lottery::find()->select('id, name')->orderBy('name')->asArray()->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'lottery' => $this->lottery,
            'lotteryList' => $lotteryList,
        ]);
    }

    /**
     * Deletes an existing LotteryResult model.
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
     * Finds the LotteryResult model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LotteryResult the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LotteryResult::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param $id
     * @return null|Lottery
     * @throws NotFoundHttpException
     */
    public function findLotteryModel($id)
    {
        if ($model = Lottery::findOne($id)) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionChangeMultiple()
    {
        $model = new LotteryResult();
        $model->isNewRecord = false;
        if(Yii::$app->request->post()) {
            $response = LotteryResult::changeMultiple(Yii::$app->request->post());
            return $response->render();
        }

        return $this->renderAjax('change-multiple', [
            'model' => $model,
            'lottery' => $this->lottery,
        ]);
    }

    public function actionDeleteMultiple()
    {
        if(Yii::$app->request->post()){
            $response = LotteryResult::deleteMultiple(Yii::$app->request->post());
        } else {
            $response = new AjaxResponse(['status' => 'error', 'message' => 'No post data']);
        }
        return $response->render();
    }
}
