<?php

namespace backend\controllers;

use backend\models\response\AjaxResponse;
use common\models\records\Country;
use common\models\records\Image;
use Yii;
use common\models\records\Lottery;
use backend\models\search\LotterySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * LotteryController implements the CRUD actions for Lottery model.
 */
class LotteryController extends Controller
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
     * Lists all Lottery models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LotterySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Lottery model.
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
     * Creates a new Lottery model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Lottery();

        if ($model->load(Yii::$app->request->post())) {

            $model->lotteryImage = UploadedFile::getInstance($model, 'lotteryImage');
            $model->logoImageId = Yii::$app->storage->saveUploadedFile($model->lotteryImage, $model::CATEGORY);
            if ($model->save()) return $this->redirect(['view', 'id' => $model->id]);
        }
        $images = Image::find()->lotteries()->all();
        $lotteries = Lottery::find()->all();
        $countries = Country::find()->all();
        return $this->render('create', [
            'model' => $model,
            'images' => $images,
            'lotteries' => $lotteries,
            'countries' => $countries,
        ]);
    }

    /**
     * Updates an existing Lottery model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            if ($model->lotteryImage = UploadedFile::getInstance($model, 'lotteryImage')) {

                $imageModel = Lottery::find($id)->one()->logoImage;
                $model->logoImageId = Yii::$app->storage->saveUploadedFile($model->lotteryImage, $model::CATEGORY, $imageModel);
            }
            if ($model->save(false)) return $this->redirect(['view', 'id' => $model->id]);
        }

        $countries = Country::find()->all();
        return $this->render('update', [
            'model' => $model,
            'countries' => $countries,
        ]);
    }

    /**
     * Deletes an existing Lottery model.
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
     * Finds the Lottery model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Lottery the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lottery::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionChangeMultiple()
    {
        $model = new Lottery();
        $model->isNewRecord = false;
        if (Yii::$app->request->post()) {
            $response = Lottery::changeMultiple(Yii::$app->request->post());
            return $response->render();
        }

        $images = Image::find()->lotteries()->all();
        $lotteries = Lottery::find()->all();
        $countries = Country::find()->all();

        return $this->renderAjax('change-multiple', [
            'model' => $model,
            'images' => $images,
            'lotteries' => $lotteries,
            'countries' => $countries,
        ]);
    }

    public function actionDeleteMultiple()
    {
        if (Yii::$app->request->post()) {
            $response = Lottery::deleteMultiple(Yii::$app->request->post());
        } else {
            $response = new AjaxResponse(['status' => 'error', 'message' => 'No post data']);
        }
        return $response->render();
    }
}
