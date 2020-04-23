<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\records\LotteryResult */
/* @var $lottery common\models\records\Lottery */
/* @var $lotteryList array common\models\records\Lottery */

$this->title = 'Update Lottery Result: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Lottery Results', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Result ID ' . $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lottery-result-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'lottery' => $lottery,
        'lotteryList' => $lotteryList,
    ]) ?>

</div>
