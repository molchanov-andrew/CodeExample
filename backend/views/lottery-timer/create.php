<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\records\LotteryTimer */

$this->title = 'Create Lottery Timer for ' . $lottery->name;
$this->params['breadcrumbs'][] = ['label' => 'Lottery Timers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lottery-timer-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'lottery' => 'lottery',
    ]) ?>

</div>
