<?php

use yii\grid\CheckboxColumn;
use yii\grid\SerialColumn;
use yii\grid\ActionColumn;
use common\models\records\LotteryTimer;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\LotteryTimerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lottery Timers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lottery-timer-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Lottery Timer', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Change multiple',['change-multiple'],[
            'class' => 'btn btn-primary change-multiple-grid open-modal-link',
            'data-toggle'=>'modal',
            'data-target'=>'#modalGeneral',
            'data-pjax' => '0',
        ]); ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => CheckboxColumn::class],
            ['class' => SerialColumn::class],

            'time',
            [
                'attribute' => 'Next draw time(UTC)',
                'label' => 'Next draw time(UTC+3)',
                'value' => function(LotteryTimer $model){
                    return $model->getHomeDatetimeWithTimeCorrection(LotteryTimer::DIRECTION_NEXT)->format('d-m-Y H:i:s');
                }
            ],
            [
                'attribute' => 'dayOfWeek',
                'value' => function(LotteryTimer $model){
                    return array_key_exists($model->dayOfWeek,LotteryTimer::DAYS_OF_WEEK) ? LotteryTimer::DAYS_OF_WEEK[$model->dayOfWeek] : 'Not set';
                }
            ],
            'resultName',
            //'timezone',
            //'lotteryId',
            //'created',
            //'updated',

            ['class' => ActionColumn::class],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
