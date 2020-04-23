<?php
namespace backend\controllers;

use common\models\records\Broker;
use common\models\records\Lottery;
use common\models\records\Page;
use common\models\records\PageContent;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\web\ErrorAction;

/**
 * Site controller
 */
class SiteController extends Controller
{
    const NOTES_LIMIT = 5;
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $lotteries = Lottery::find()->limit(self::NOTES_LIMIT)->orderBy(['updated' => SORT_DESC])->all();
        $lotteriesCount = Lottery::find()->count();
        $brokers = Broker::find()->limit(self::NOTES_LIMIT)->orderBy(['updated' => SORT_DESC])->all();
        $brokersCount = Broker::find()->count();
        $pageContents = PageContent::find()->limit(self::NOTES_LIMIT)->with(['page','language'])->orderBy(['updated' => SORT_DESC])->all();
        $pageCount = Page::find()->count();

        return $this->render('index',[
            'lotteries' => $lotteries,
            'lotteriesCount' => $lotteriesCount,
            'brokers' => $brokers,
            'brokersCount' => $brokersCount,
            'pageContents' => $pageContents,
            'pageCount' => $pageCount,
        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
