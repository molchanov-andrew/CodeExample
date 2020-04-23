<?php

use yii\grid\SerialColumn;
use yii\grid\ActionColumn;
use backend\models\image\RelativeImageColumn;
use common\models\records\Lottery;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\models\grid\CustomCheckboxColumn;
use yii\helpers\ArrayHelper;
use common\models\records\Country;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\LotterySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model common\models\records\Lottery */

$this->title = 'Lotteries';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="lottery-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin([
            'id' => 'pjax',
            'timeout' => false,
            ]); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Lottery', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Change multiple', ['change-multiple'], [
            'class' => 'btn btn-primary change-multiple-grid open-modal-link',
            'data-toggle' => 'modal',
            'data-target' => '#modalGeneral',
            'data-pjax' => '0',
        ]); ?>
        <?= Html::a('Delete', ['delete-multiple'], ['class' => 'btn btn-danger ajax-solo-rows',
            'data-solo-confirm' => Yii::t('yii', 'Are you sure you want to delete this items?'),]) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => CustomCheckboxColumn::class, 'limitFilter' => true],
            ['class' => SerialColumn::class],
            [
                'attribute' => 'name',
                'headerOptions' => ['class' => 'column-200',],
                'filterOptions' => ['class' => 'column-200',],
                'contentOptions' => ['class' => 'column-200',],
            ],
            [
                'attribute' => 'countryId',
                'label' => 'Country',
                'format' => 'raw',
                'value' => 'country.name',
                'filter' => Html::activeDropDownList($searchModel, 'countryId', ArrayHelper::map(Country::find()->asArray()->all(), 'id', 'name'), ['class'=>'form-control', 'prompt' => 'Select country']),
            ],
            [
                'class' => RelativeImageColumn::class,
                'imageField' => 'logoImage',
            ],
            [
                'attribute' => 'Lottery Relations',
                'label' => 'Brokers',
                'format' => 'raw',
                'filter' => Html::activeInput('text', $searchModel, 'brokerName', ['class' => 'form-control']),
                'value' => function (Lottery $model) {
                    if (empty($model->brokerToLotteries)) {
                        return 'Not set.';
                    }
                    $result = '';
                    $index = 0;
                    foreach ($model->brokerToLotteries as $brokerToLottery) {
                        $index++;
                        $class = $index > 2 ? 'hidden-until-clicked' : '';
                        $result .= Html::beginTag('p', ['class' => $class]);
                        $result .= Html::a($brokerToLottery->broker->name, ["lottery/{$model->id}/broker-to-lottery/index", 'BrokerToLotterySearch[brokerName]' => $brokerToLottery->broker->name]);
                        $result .= Html::endTag('p');
                    }
                    if ($index > 2) {
                        $result .= Html::a('Show all', 'javascript:;', ['class' => 'hidden-opener', 'data-next-text' => 'Hide all']);
                    }
                    return $result;
                }
            ],
            'published:boolean',
            'updated',
            [
                'class' => ActionColumn::class,
                'template' => '{broker-relations} {timers} {results} {view} {update} {delete}',
                'buttons' => [
                    'broker-relations' => function ($url, Lottery $model) {
                        return Html::a('<i class="glyphicon glyphicon-star-empty"></i>', ['/lottery/' . $model->id . '/broker-to-lottery/index'], [
                            'class' => 'actions-button',
                            'title' => 'Brokers relations list',
                            'data-pjax' => '0',
                        ]);
                    },
                    'timers' => function ($url, Lottery $model) {
                        return Html::a('<i class="glyphicon glyphicon-time"></i>', ['/lottery/' . $model->id . '/lottery-timer/index'], [
                            'class' => 'actions-button',
                            'title' => 'Lottery timers list',
                            'data-pjax' => '0',
                        ]);
                    },
                    'results' => function ($url, Lottery $model) {
                        return Html::a('<i class="glyphicon glyphicon-list-alt"></i>', ['/lottery/' . $model->id . '/lottery-result/index'], [
                            'class' => 'actions-button',
                            'title' => 'Lottery results list',
                            'data-pjax' => '0',
                        ]);
                    },
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
