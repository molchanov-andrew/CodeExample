<?php

namespace backend\controllers;

use Yii;
use common\models\records\SitemapChanges;
use backend\models\search\SitemapChangesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SitemapChangesController implements the CRUD actions for SitemapChanges model.
 */
class SitemapChangesController extends Controller
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
     * Lists all SitemapChanges models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SitemapChangesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SitemapChanges model.
     * @param string $type
     * @param string $identifier
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($type, $identifier)
    {
        return $this->render('view', [
            'model' => $this->findModel($type, $identifier),
        ]);
    }

    /**
     * Creates a new SitemapChanges model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SitemapChanges();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'type' => $model->type, 'identifier' => $model->identifier]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SitemapChanges model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $type
     * @param string $identifier
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($type, $identifier)
    {
        $model = $this->findModel($type, $identifier);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'type' => $model->type, 'identifier' => $model->identifier]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SitemapChanges model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $type
     * @param string $identifier
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($type, $identifier)
    {
        $this->findModel($type, $identifier)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SitemapChanges model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $type
     * @param string $identifier
     * @return SitemapChanges the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($type, $identifier)
    {
        if (($model = SitemapChanges::findOne(['type' => $type, 'identifier' => $identifier])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
