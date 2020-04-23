<?php

use common\models\records\LotteryTimer;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\records\LotteryTimer */
/* @var $lottery common\models\records\Lottery */


$this->title = $lottery->name . '|' . $this->title = $model->time . ' ' . LotteryTimer::DAYS_OF_WEEK[$model->dayOfWeek];
$this->params['breadcrumbs'][] = ['label' => 'Lottery Timers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lottery-timer-view">

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
            'time',
            'timeCorrection:datetime',
            'dayOfWeek',
            'resultName',
            'timezone',
            [
                'label' => 'Logo Image',
                'format' => ['image', ['width' => '100px']],
                'value' => Yii::$app->params['frontendUrl'] . $lottery->getLogoImage()->one()->getFilePath(),
            ],
            'remoteId',
            'created',
            'updated',
        ],
    ]) ?>

</div>
