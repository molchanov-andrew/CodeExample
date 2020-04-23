<?php

use common\models\records\Broker;
use common\models\records\BrokerStatus;
use yii\grid\SerialColumn;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\models\image\RelativeImageColumn;
use backend\models\grid\CustomCheckboxColumn;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\BrokerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $statuses common\models\records\BrokerStatus */

$this->title = 'Brokers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="broker-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(['id' => 'pjax']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Broker', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Change multiple',['change-multiple'],[
            'class' => 'btn btn-primary change-multiple-grid open-modal-link',
            'data-toggle'=>'modal',
            'data-target'=>'#modalGeneral',
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
            'name',
            [
                'attribute' => 'site',
                'format' => 'url',
                'headerOptions' => ['class' => 'column-200',],
                'filterOptions' => ['class' => 'column-200',],
                'contentOptions' => ['class' => 'column-200',],
            ],
            [
                'attribute' => 'statusId',
                'label' => 'Status',
                'filter' => Html::activeDropDownList($searchModel, 'statusId', array_column($statuses,'name','id'), ['class'=>'form-control', 'prompt'=>'']),
                'value' => function(Broker $broker){
                    return $broker->status->name;
                }
            ],
            [
                'class' => RelativeImageColumn::class,
                'imageField' => 'image',
            ],
            [
                'attribute' => 'Lottery Relations',
                'format' => 'raw',
                'filter' => Html::activeInput('text', $searchModel, 'lotteryName', ['class'=>'form-control']),
                'value' => function(Broker $model){
                    if(empty($model->brokerToLotteries)){
                        return 'Not set.';
                    }
                    $result = '';
                    $index = 0;
                    foreach ($model->brokerToLotteries as $brokerToLottery) {
                        $index++;
                        $class = ($index > 2) ? 'hidden-until-clicked' : '';
                        $result .= Html::beginTag('p',['class' => $class]);
                        $result .= Html::a($brokerToLottery->lottery->name,["broker/{$model->id}/broker-to-lottery/index",'BrokerToLotterySearch[lotteryName]' =>$brokerToLottery->lottery->name]);
                        $result .= Html::endTag('p');
                    }
                    if($index > 2){
                        $result .= Html::a('Show all','javascript:;',['class' => 'hidden-opener', 'data-next-text' => 'Hide all']);
                    }
                    return $result;
                },
            ],
            'published:boolean',
            [
                'class' => ActionColumn::class,
                'template' => '{lottery-relations} {broker-email} {broker-phone} {view} {update} {delete}',
                'buttons' => [
                    'lottery-relations' => function($url, Broker $model){
                        return Html::a('<i class="glyphicon glyphicon-star-empty"></i>',['/broker/' . $model->id . '/broker-to-lottery/index'],[
                            'class' => 'actions-button',
                            'title' => 'Lotteries relations list',
                            'data-pjax' => '0',
                        ]);
                    },
                    'broker-email' => function($url, Broker $model){
                        return Html::a('<i class="glyphicon glyphicon-envelope"></i>',['/broker/' . $model->id . '/broker-email/index'],[
                            'class' => 'actions-button',
                            'title' => 'Emails',
                            'data-pjax' => '0',
                        ]);
                    },
                    'broker-phone' => function($url, Broker $model){
                        return Html::a('<i class="glyphicon glyphicon-earphone"></i>',['/broker/' . $model->id . '/broker-phone/index'],[
                            'class' => 'actions-button',
                            'title' => 'Phones',
                            'data-pjax' => '0',
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
