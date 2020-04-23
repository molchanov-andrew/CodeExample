<?php

use common\models\records\BrokerToLottery;
use yii\grid\SerialColumn;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\models\grid\CustomCheckboxColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\BrokerToLotterySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$parentEntity = Yii::$app->request->get('parentEntity', null);
$parentId = Yii::$app->request->get('parentId', null);

$this->title = "{$parentModel->name} relations";
?>
<?php $parentEntity = Yii::$app->request->get('parentEntity', null) ?>
<div class="broker-to-lottery-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(['id' => 'pjax']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Broker To Lottery', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Change multiple', ['change-multiple'], [
            'class' => 'btn btn-primary change-multiple-grid open-modal-link',
            'data-toggle' => 'modal',
            'data-target' => '#modalGeneral',
            'data-pjax' => '0',
        ]); ?>
        <?= Html::a('Delete', ['delete-multiple'], ['class' => 'btn btn-danger ajax-solo-rows',
            'data-solo-confirm' => Yii::t('yii', 'Are you sure you want to delete this items?'),]) ?>
    </p>
    <?php
    $columns = [
        ['class' => CustomCheckboxColumn::class, 'limitFilter' => true],
        ['class' => SerialColumn::class],

        $parentEntity !== 'broker' ? [
            'attribute' => 'Broker',
            'format' => 'raw',
            'filter' => Html::activeInput('text', $searchModel, 'brokerName', ['class' => 'form-control']),
            'value' => function (BrokerToLottery $model) {
                return Html::a($model->broker->name, ['/broker/view', 'id' => $model->broker->id]);
            }
        ] : null,
        $parentEntity !== 'lottery' ? [
            'attribute' => 'Lottery',
            'format' => 'raw',
            'filter' => Html::activeInput('text', $searchModel, 'lotteryName', ['class' => 'form-control']),
            'value' => function (BrokerToLottery $model) {
                return Html::a($model->lottery->name, ['/lottery/view', 'id' => $model->lottery->id]);
            }
        ] : null,
        [
            'attribute' => 'url',
            'format' => 'url',
            'headerOptions' => ['class' => 'column-200',],
            'filterOptions' => ['class' => 'column-200',],
            'contentOptions' => ['class' => 'column-200',],
        ],

        [
            'attribute' => 'broker.syndicat',
            'format' => 'raw',
            'value' =>'broker.syndicat',
//            'filter' => Html::activeInput('text', $searchModel, 'lotteryName', ['class' => 'form-control']),
        ],
        [
            'attribute' => 'Systematics',
            'format' => 'raw',
            'value' => function (BrokerToLottery $model) use ($parentId, $parentEntity) {
                if (empty($model->systematics)) {
                    return 'Not set.';
                }
                $result = '';
                foreach ($model->systematics as $systematic) {
                    $result .= Html::beginTag('p');
                    $result .= Html::a("N: {$systematic->numbers}, L: {$systematic->lines}", ["/{$parentEntity}/{$parentId}/broker-to-lottery/{$model->id}/systematic/view", 'id' => $systematic->id]);
                    $result .= Html::endTag('p');
                }
                return $result;
            }
        ],
        [
            'attribute' => 'Discounts',
            'format' => 'raw',
            'value' => function (BrokerToLottery $model) use ($parentId, $parentEntity) {
                if (empty($model->discounts)) {
                    return 'Not set.';
                }
                $result = '';
                foreach ($model->discounts as $discount) {
                    $result .= Html::beginTag('p');
                    $result .= Html::a("D: {$discount->discount}, DD: {$discount->description}", ["/{$parentEntity}/{$parentId}/broker-to-lottery/{$model->id}/discount/view", 'id' => $discount->id]);
                    $result .= Html::endTag('p');
                }
                return $result;
            }
        ],
        [
            'class' => ActionColumn::class,
            'template' => '{systematics} {discounts} {view} {update} {delete}',
            'buttons' => [
                'discounts' => function ($url, BrokerToLottery $model) use ($parentEntity, $parentId) {
                    return Html::a('<i class="glyphicon glyphicon-tag"></i>', ["/{$parentEntity}/{$parentId}/broker-to-lottery/{$model->id}/discount/index"], [
                        'class' => 'actions-button',
                        'title' => 'Discounts',
                        'data-pjax' => '0',
                    ]);
                },
                'systematics' => function ($url, BrokerToLottery $model) use ($parentEntity, $parentId) {
                    return Html::a('<i class="glyphicon glyphicon-asterisk"></i>', ["/{$parentEntity}/{$parentId}/broker-to-lottery/{$model->id}/systematic/index"], [
                        'class' => 'actions-button',
                        'title' => 'Systematics',
                        'data-pjax' => '0',
                    ]);
                },
            ]
        ],
    ];
    $columns = array_filter($columns, function ($column) {
        return is_array($column) || is_string($column);
    });
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>
    <?php Pjax::end(); ?>
</div>
