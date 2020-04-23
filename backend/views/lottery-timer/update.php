<?php

use common\models\records\LotteryTimer;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\records\LotteryTimer */
/* @var $lottery common\models\records\Lottery */

$this->title = 'Update Lottery Timer: ' . $lottery->name . '|' . $this->title = $model->time . ' ' . LotteryTimer::DAYS_OF_WEEK[$model->dayOfWeek];
$this->params['breadcrumbs'][] = ['label' => 'Lottery Timers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Timer ID ' . $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lottery-timer-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'lottery' => 'lottery',
    ]) ?>

</div>
