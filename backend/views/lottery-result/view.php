<?php

use common\models\records\LotteryResult;
use common\models\records\LotteryTimer;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\records\LotteryResult */
/* @var $lottery common\models\records\Lottery */

$this->title = $lottery->name . '|' . $model->date . ' ';
$this->title .= ($model->lotteryTimer !== null) ? $model->lotteryTimer->resultName : '';
$this->params['breadcrumbs'][] = ['label' => 'Lottery Results', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="lottery-result-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'uniqueResultId',
            'mainNumbers',
            'additionalNumbers',
            'bonusNumbers',
            [
                'label' => 'Status',
                'format' => 'html',
                'value' => function(LotteryResult $value){
                    return LotteryResult::STATUSES[$value->status];
                },
            ],
            'date',
            'jackpot',
            'created',
            'updated',
            [
                'label' => 'Lottery',
                'format' => 'html',
                'value' => function(LotteryResult $value){
                    if($value->lottery !== null){
                        return Html::tag('span', $value->lottery->name);
                    } else {
                        return 'Not set';
                    }
                },
            ],
            [
                'label' => 'Timer',
                'format' => 'html',
                'value' => function(LotteryResult $value){
                    if($value->lotteryTimer !== null){
                        return Html::a($value->lotteryTimer->time . ' ' . LotteryTimer::DAYS_OF_WEEK[$value->lotteryTimer->dayOfWeek],['lottery-timer/view','id' => $value->lotteryTimer->id]);
                    } else {
                        return 'Not set';
                    }
                },
            ],
        ],
    ]) ?>

</div>
